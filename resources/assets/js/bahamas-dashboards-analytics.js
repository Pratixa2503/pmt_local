// public/assets/js/bahamas-dashboards-analytics.js
// Bahamas charts JS (standalone, improved reflow + exposed fetchers)

(function(){

  const safeNum = v => Number(v || 0);
  const pad2 = n => String(n).padStart(2, '0');

  // ---------- Company chart ----------
  function initCompanyChart(){
    const chartEl = document.querySelector("#company-chart");
    const yearSelect = document.getElementById("yearFilter_data");
    if (!chartEl) return console.error("company-chart element not found");
    const companyUrl = chartEl.dataset.companyUrl || window.appRoutes?.companyByYear || '/dashboard/company';

    const initialData = (typeof window.companyData === 'object' && window.companyData !== null)
        ? window.companyData
        : { 'Total Company': 0, 'Valid Company': 0, 'Invalid Company': 0 };

    const barColors = ['#E35205', '#97999B', '#00CFE8'];
    function buildFromObj(obj) {
        const labels = Object.keys(obj);
        const values = Object.values(obj).map(v => safeNum(v));
        return { labels, values };
    }
    const init = buildFromObj(initialData);

    function buildCustomLegend(labels, colors) {
        let legendEl = document.getElementById("company-legend");
        if (!legendEl) {
            legendEl = document.createElement("div");
            legendEl.id = "company-legend";
            legendEl.classList.add("d-flex", "justify-content-center", "mt-2", "flex-wrap");
            chartEl.insertAdjacentElement("afterend", legendEl);
        }
        legendEl.innerHTML = "";
        labels.forEach((label, i) => {
            const color = colors[i] || "#999";
            const item = document.createElement("div");
            item.style.display = "flex";
            item.style.alignItems = "center";
            item.style.margin = "0 12px";
            const dot = document.createElement("span");
            dot.style.width = "10px";
            dot.style.height = "10px";
            dot.style.borderRadius = "50%";
            dot.style.backgroundColor = color;
            dot.style.display = "inline-block";
            dot.style.marginRight = "6px";
            const text = document.createElement("span");
            text.style.fontSize = "13px";
            text.style.color = "#555";
            text.textContent = label;
            item.appendChild(dot);
            item.appendChild(text);
            legendEl.appendChild(item);
        });
    }

    const options = {
        chart: { type: 'bar', height: 350, offsetY: -10, toolbar: { show: false }, animations: { enabled: true } },
        series: [{ name: "Companies", data: init.values }],
        xaxis: { categories: init.labels, labels: { show: false }, axisTicks: { show: false }, axisBorder: { show: false } },
        plotOptions: { bar: { horizontal: false, columnWidth: '50%', endingShape: 'rounded', distributed: true } },
        dataLabels: { enabled: true, formatter: function(val) { return val; } },
        fill: { colors: barColors },
        tooltip: { y: { formatter: function(val) { return val + " Companies"; } } },
        legend: { show: false },
        yaxis: { min: 0 }
    };

    const chart = new ApexCharts(chartEl, options);
    window.companyChart = chart;
    chart.render().then(() => buildCustomLegend(init.labels, barColors)).catch(e => console.warn("companyChart render err", e));

    async function setChartData(obj) {
        const { labels, values } = buildFromObj(obj);
        try {
            await chart.updateOptions({
                xaxis: { categories: labels, labels: { show: false }, axisTicks: { show: false }, axisBorder: { show: false } },
                fill: { colors: barColors }
            }, false, true);
            await chart.updateSeries([{ name: 'Companies', data: values }], true);
            buildCustomLegend(labels, barColors);
        } catch (err) {
            console.error('company setChartData error', err);
        }
    }

    // Expose fetcher to window so external code (bahamas-shown) can call it
    async function fetchCompanyByYear(year){
        try {
            const url = new URL(companyUrl, window.location.origin);
            url.searchParams.set('year', year);
            const res = await fetch(url.toString(), { method: 'GET', credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error('Network error '+res.status);
            const json = await res.json();
            if (!json || !(json.success || json.status)) throw new Error('API failed');
            const payload = json.data || json;
            const obj = payload.data ? payload.data : payload;
            const out = {
                'Total Company': safeNum(obj['Total Company'] ?? obj.total_companies ?? 0),
                'Valid Company': safeNum(obj['Valid Company'] ?? obj.valid_companies ?? 0),
                'Invalid Company': safeNum(obj['Invalid Company'] ?? obj.invalid_companies ?? 0),
                'year': Number(obj.year || year)
            };
            // update chart after fetching
            await setChartData({
                'Total Company': out['Total Company'],
                'Valid Company': out['Valid Company'],
                'Invalid Company': out['Invalid Company']
            });
            return out;
        } catch (err) {
            console.error("Error fetching company data:", err);
            await setChartData({ 'Total Company':0, 'Valid Company':0, 'Invalid Company':0 });
            return { 'Total Company':0, 'Valid Company':0, 'Invalid Company':0, 'year': year };
        }
    }

    // Expose
    window.fetchCompanyByYear = fetchCompanyByYear;

    if (yearSelect) {
        yearSelect.addEventListener('change', function(e){
            // call exposed fetcher
            window.fetchCompanyByYear(e.target.value).catch(()=>{});
        });
    }

    // initial load: call fetch for selected year (if any)
    (async function(){
        try {
            const selected = yearSelect ? Number(yearSelect.value) : null;
            if (selected) await fetchCompanyByYear(selected);
        } catch(e){}
    })();
  } // initCompanyChart

  // ---------- Filetype chart ----------
  function initFiletypeChart(){
    const chartEl = document.querySelector("#filetype-chart");
    if (!chartEl) return console.error("filetype-chart element not found");
    const apiRoute = chartEl.dataset.companyUrl || (window.appRoutes && window.appRoutes.fileTypeByYear) || '/dashboard/filetype';
    const yearSelect = document.getElementById("yearFilter_filedata");

    function normalizePayload(payload) {
        payload = payload || {};
        const keysLower = Object.keys(payload).reduce((acc, k) => { acc[k.toLowerCase()] = payload[k]; return acc; }, {});
        const xbrl = safeNum(payload['xbrl'] ?? payload['XBRL Delivered'] ?? payload['XBRL'] ?? keysLower['xbrl'] ?? 0);
        const nonXbrl = safeNum(payload['non_xbrl'] ?? payload['non xbrl'] ?? payload['nonXbrl'] ?? keysLower['non xbrl'] ?? 0);
        return { xbrl, nonXbrl };
    }

    const initialRaw = window.fileDeliveryData || {};
    const initial = normalizePayload(initialRaw);
    let totalInitial = initial.xbrl + initial.nonXbrl;
    const labels = ['XBRL Delivered', 'Non XBRL Delivered'];
    const colors = ['#E35205', '#97999B'];

    const options = {
        chart: { type: 'donut', height: 350 },
        series: totalInitial === 0 ? [1, 1] : [initial.xbrl, initial.nonXbrl],
        labels: labels,
        colors: colors,
        legend: { position: 'bottom' },
        dataLabels: { enabled: true },
        tooltip: { y: { formatter: function (val) { return (totalInitial === 0) ? "0 Files" : (val + " Files"); } } }
    };

    const chart = new ApexCharts(chartEl, options);
    window.fileTypeChart = chart;
    chart.render();

    async function updateChartValues(xbrl, nonXbrl) {
        const total = safeNum(xbrl) + safeNum(nonXbrl);
        if (total === 0) {
            await chart.updateOptions({ labels: labels, colors: colors, tooltip: { y: { formatter: () => "0 Files" } } }, false, false);
            await chart.updateSeries([1, 1], true);
            return;
        }
        await chart.updateOptions({ labels: labels, colors: colors, tooltip: { y: { formatter: function (val) { return val + " Files"; } } } }, false, false);
        await chart.updateSeries([safeNum(xbrl), safeNum(nonXbrl)], true);
    }

    async function fetchAndUpdate(year) {
        try {
            const url = apiRoute + (apiRoute.includes('?') ? '&' : '?') + 'year=' + encodeURIComponent(year);
            if (yearSelect) yearSelect.disabled = true;
            const res = await fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
            if (!res.ok) { await updateChartValues(0,0); if (yearSelect) yearSelect.disabled = false; return; }
            const json = await res.json();
            if (!(json && (json.success === true || json.status === true))) { await updateChartValues(0,0); if (yearSelect) yearSelect.disabled = false; return; }
            const payloadCandidate = json.data ?? {};
            const inner = (payloadCandidate && payloadCandidate.data) ? payloadCandidate.data : payloadCandidate;
            const normalized = normalizePayload(inner);
            await updateChartValues(normalized.xbrl, normalized.nonXbrl);
            if (yearSelect) yearSelect.disabled = false;
        } catch (err) {
            console.error("Error fetching filetype data:", err);
            await updateChartValues(0,0);
            if (yearSelect) yearSelect.disabled = false;
        }
    }

    // Expose
    window.fetchFiletypeByYear = fetchAndUpdate;

    if (yearSelect) {
        yearSelect.addEventListener("change", function (e) {
            fetchAndUpdate(e.target.value).catch(()=>{});
        });
    }

    (async function(){
        const initialYear = (yearSelect && yearSelect.value) ? yearSelect.value : (new Date()).getFullYear();
        await fetchAndUpdate(initialYear);
    })();
  } // initFiletypeChart

  // ---------- SLA chart ----------
  function initSlaChart(){
    const chartEl = document.querySelector('#sla-chart');
    if (!chartEl) return console.error('SLA chart element not found');

    const initialObj = window.slaDeliveryData || {};
    const initLabels = Object.keys(initialObj);
    const initValues = Object.values(initialObj);
    const pretty = lbl => lbl.replace(/^Last\s*/i, '').replace(/\s*Day(s)?$/i, ' Days');

    const barColors = ['#E35205', '#97999B', '#00CFE8'];
    const lineColor = '#97999B';

    const options = {
        chart: { height: 360, type: 'line', toolbar: { show: false } },
        series: [
            { name: 'Days', type: 'column', data: initValues },
            { name: 'Trend', type: 'line', data: initValues }
        ],
        colors: [
            function ({ seriesIndex, dataPointIndex }) {
                return seriesIndex === 0 ? barColors[dataPointIndex] ?? barColors[0] : lineColor;
            }
        ],
        plotOptions: { bar: { distributed: true, columnWidth: '40%', borderRadius: 6 } },
        dataLabels: { enabled: true, enabledOnSeries: [0], formatter: val => Math.round(val), offsetY: -12, style: { fontSize: '12px', fontWeight: 700 } },
        stroke: { width: [0, 3], curve: 'smooth' },
        markers: { size: 4, strokeWidth: 2 },
        xaxis: { categories: initLabels.map(pretty) },
        yaxis: { labels: { formatter: v => Math.round(v) }, title: { text: 'Count' } },
        grid: { strokeDashArray: 4 },
        legend: { position: 'top', horizontalAlign: 'center' },
        tooltip: { shared: true, intersect: false },
        fill: { opacity: 1 }
    };

    window.slaChart = new ApexCharts(chartEl, options);
    window.slaChart.render().catch(e => console.error('Error rendering SLA chart:', e));

    async function updateSlaChart(obj) {
        const keys = Object.keys(obj || {});
        const vals = Object.values(obj || {});
        const prettyCats = keys.map(pretty);
        try {
            await window.slaChart.updateOptions({ xaxis: { categories: prettyCats } }, false, true);
            await window.slaChart.updateSeries([
                { name: 'Days', type: 'column', data: vals },
                { name: 'Trend', type: 'line', data: vals }
            ], true);
        } catch (err) {
            console.error('Error updating SLA chart:', err);
        }
    }

    async function fetchSlaForYear(year) {
        const resolvedYear = String(year || new Date().getFullYear());
        const routeFromWindow = window.appRoutes && window.appRoutes.sla;
        const routeFromData = chartEl.dataset && (chartEl.dataset.sla || chartEl.dataset.url || chartEl.dataset.slaUrl);
        const apiUrl = routeFromWindow || routeFromData || '/dashboard/sla';
        const yearSelect = document.getElementById('yearFilter');
        if (yearSelect) yearSelect.disabled = true;

        try {
            let payload = null;
            if (typeof axios !== 'undefined') {
                const resp = await axios.get(apiUrl, { params: { year: resolvedYear }, withCredentials: true });
                payload = resp && resp.data ? resp.data : null;
            } else {
                const qs = new URLSearchParams({ year: resolvedYear });
                const r = await fetch(apiUrl + '?' + qs.toString(), { credentials: 'same-origin', headers: { 'Accept': 'application/json' }});
                const j = await r.json().catch(()=>null);
                payload = j;
            }

            if (payload && (payload.success === true || payload.status === true) && payload.data) {
                const candidate = payload.data.data ? payload.data.data : payload.data;
                const toUse = { ...candidate };
                delete toUse.year;
                await updateSlaChart(toUse);
            } else {
                console.warn('SLA response invalid — updating with empty payload', payload);
                await updateSlaChart({});
            }
        } catch (err) {
            console.error('Error fetching SLA for year', resolvedYear, err);
            await updateSlaChart({});
        } finally {
            if (yearSelect) yearSelect.disabled = false;
        }
    }

    // expose
    window.fetchSlaForYear = fetchSlaForYear;

    const yearSelectEl = document.getElementById('yearFilter');
    if (yearSelectEl) {
        yearSelectEl.addEventListener('change', function (e) {
            fetchSlaForYear(e.target.value).catch(()=>{});
        });
    }

    (function initialLoad() {
        let startYear = null;
        if (yearSelectEl && yearSelectEl.value) startYear = yearSelectEl.value;
        else if (window.slaDeliveryData && window.slaDeliveryData.year) startYear = window.slaDeliveryData.year;
        else if (chartEl.dataset && chartEl.dataset.year) startYear = chartEl.dataset.year;
        startYear = startYear || (new Date()).getFullYear();
        fetchSlaForYear(startYear).catch(()=>{});
    })();
  } // initSlaChart

  // ---------- Monthly chart ----------
  function initMonthlyChart(){
    const allMonths = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const safeNumberArray = arr => Array.isArray(arr) ? arr.map(v => Number(v || 0)) : [];
    const baseUrl = window.appRoutes?.monthcount ?? '/dashboard/monthly-delivered';
    const monthEl = document.getElementById('monthFilter');
    const yearEl  = document.getElementById('yearFilter_month');
    const chartEl = document.querySelector("#monthly-chart");
    if (!chartEl) return console.warn("#monthly-chart missing");

    let monthChoices = null;
    try {
      if (monthEl && typeof Choices !== 'undefined' && !monthEl.dataset.choicesInitialized) {
        monthChoices = new Choices(monthEl, {
          removeItemButton: true,
          maxItemCount: 12,
          shouldSort: false,
          searchEnabled: false,
          placeholder: true,
          placeholderValue: 'Select months',
          itemSelectText: '',
          position: 'bottom'
        });
        monthEl.dataset.choicesInitialized = '1';
      }
    } catch (e) { console.warn("Choices init error:", e); }

    const initialPayload = (window.monthDeliveryData && typeof window.monthDeliveryData === 'object') ?
          { labels: window.monthDeliveryData.labels || [], values: window.monthDeliveryData.values || [] } :
          { labels: [], values: [] };

    const initLabels = initialPayload.labels.length ? initialPayload.labels : allMonths.slice();
    const initValues = initialPayload.values.length ? safeNumberArray(initialPayload.values) : initLabels.map(_=>0);

    const baseColors = [
      '#1f77b4','#ff7f0e','#2ca02c','#d62728','#9467bd','#8c564b',
      '#e377c2','#7f7f7f','#bcbd22','#17becf','#E35205','#97999B'
    ];
    const colorsFor = n => Array.from({length:n}, (_,i) => baseColors[i % baseColors.length]);

    let chart = null;
    if (typeof ApexCharts !== 'undefined' && chartEl) {
      const options = {
        chart: { type: 'bar', height: 350, toolbar: { show: false } },
        series: [{ name: 'Count', data: initValues }],
        plotOptions: { bar: { horizontal: false, columnWidth: '50%', endingShape: 'rounded', distributed: true } },
        dataLabels: { enabled: true, style: { fontSize: '12px', fontWeight: '700' }, formatter: val => val },
        xaxis: { categories: initLabels, labels: { rotate: -15 } },
        fill: { colors: colorsFor(initLabels.length) },
        tooltip: { y: { formatter: val => String(val) } },
        legend: { show: false },
        yaxis: { min: 0 }
      };
      chart = new ApexCharts(chartEl, options);
      window.monthlyChart = chart;
      chart.render().then(()=> console.log("monthly chart initial")).catch(e=>console.warn("monthly chart render err:", e));
    }

    let legendEl = document.getElementById('monthly-legend');
    if (!legendEl && chartEl) {
      legendEl = document.createElement('div');
      legendEl.id = 'monthly-legend';
      legendEl.style.marginTop = '10px';
      legendEl.style.display = 'flex';
      legendEl.style.flexWrap = 'wrap';
      legendEl.style.gap = '12px';
      chartEl.parentNode.appendChild(legendEl);
    }
    function renderLegend(labels, colors) {
      if (!legendEl) return;
      legendEl.innerHTML = '';
      labels.forEach((lab, idx) => {
        const color = colors[idx % colors.length] || '#ccc';
        const item = document.createElement('div');
        item.style.display = 'flex'; item.style.alignItems = 'center'; item.style.gap = '8px';
        item.style.fontSize = '13px'; item.style.color = '#374151';
        const dot = document.createElement('span');
        dot.style.width = '12px'; dot.style.height = '12px'; dot.style.borderRadius = '50%';
        dot.style.background = color; dot.style.display = 'inline-block';
        dot.style.boxShadow = '0 0 0 1px rgba(0,0,0,0.06) inset';
        const txt = document.createElement('span'); txt.textContent = lab;
        item.appendChild(dot); item.appendChild(txt); legendEl.appendChild(item);
      });
    }
    renderLegend(initLabels, colorsFor(initLabels.length));

    async function fetchMonthlyDelivered(year, monthsArray) {
      try {
        const params = new URLSearchParams();
        params.set('year', String(year));
        if (Array.isArray(monthsArray) && monthsArray.length) {
          const csv = monthsArray.map(m => pad2(Number(m))).join(',');
          params.set('month', csv);
        }
        const url = `${baseUrl}?${params.toString()}`;
        const res = await fetch(url, { method: 'GET', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
        if (!res.ok) { console.error('Fetch failed', res.status, res.statusText); return null; }
        const json = await res.json();
        if (!json || !json.success) { console.warn('monthlyDelivered API response not successful', json && json.message); return null; }
        const data = json.data || {};
        const labels = data.labels || data.monthly_labels || [];
        const values = data.values || data.monthly_values || [];
        return { labels, values: values.map(v => Number(v || 0)) };
      } catch (err) {
        console.error('fetchMonthlyDelivered error', err);
        return null;
      }
    }

    function getSelectedMonths() {
      try {
        let months = [];
        if (monthChoices && typeof monthChoices.getValue === 'function') {
          months = monthChoices.getValue(true) || [];
        } else if (monthEl) {
          months = Array.from(monthEl.selectedOptions).map(o => o.value);
        }
        months = months.map(m => parseInt(m, 10)).filter(n => !isNaN(n));
        return months;
      } catch (err) {
        console.error("getSelectedMonths error", err);
        return [];
      }
    }

    async function updateChartFromFilters() {
      if (!yearEl) return;
      const year = parseInt(yearEl.value, 10) || (new Date()).getFullYear();
      const months = getSelectedMonths();

      const payload = await fetchMonthlyDelivered(year, months);

      let labels, values;
      if (!payload) {
        if (months && months.length) {
          labels = months.map(m => allMonths[(m-1)] || String(m));
          values = labels.map(_=>0);
        } else {
          labels = allMonths.slice();
          values = labels.map(_=>0);
        }
      } else {
        labels = (payload.labels && payload.labels.length) ? payload.labels : (months.length ? months.map(m=> allMonths[m-1]) : allMonths.slice());
        values = (payload.values && payload.values.length) ? payload.values : labels.map(_=>0);
      }

      const cols = colorsFor(labels.length);
      try {
        if (chart && typeof chart.updateOptions === 'function') {
          chart.updateOptions({ xaxis: { categories: labels }, fill: { colors: cols } }, false, false);
          chart.updateSeries([{ name: 'Count', data: values }], true);
        }
      } catch (err) {
        console.error("Error updating monthly chart:", err);
      }

      renderLegend(labels, cols);
    }

    if (yearEl) yearEl.addEventListener('change', updateChartFromFilters);
    if (monthEl) {
      monthEl.addEventListener('change', updateChartFromFilters);
      if (monthChoices && monthChoices.passedElement && monthChoices.passedElement.element) {
        try { monthChoices.passedElement.element.addEventListener('change', updateChartFromFilters); } catch(e){}
      }
    }

    // initial call
    updateChartFromFilters();

    // expose helper for external usage
    window.__updateMonthlyChart = updateChartFromFilters;
  } // initMonthlyChart

  // bootstrap
  document.addEventListener("DOMContentLoaded", function(){
    try { initCompanyChart(); } catch(e){ console.warn('initCompanyChart error', e); }
    try { initFiletypeChart(); } catch(e){ console.warn('initFiletypeChart error', e); }
    try { initSlaChart(); } catch(e){ console.warn('initSlaChart error', e); }
    try { initMonthlyChart(); } catch(e){ console.warn('initMonthlyChart error', e); }
  });

  // When Bahamas block is shown -> wait a tick, force reflow and re-fetch data
  window.addEventListener('bahamas-shown', function (e) {
    try {
      // small delay to let the browser compute layout after show
      setTimeout(function(){
        try {
          if (window.companyChart && typeof window.companyChart.updateOptions === 'function') {
            // force reflow + redraw
            window.companyChart.updateOptions({}, true, true);
          }
          if (window.fileTypeChart && typeof window.fileTypeChart.updateOptions === 'function') {
            window.fileTypeChart.updateOptions({}, true, true);
          }
          if (window.slaChart && typeof window.slaChart.updateOptions === 'function') {
            window.slaChart.updateOptions({}, true, true);
          }
          if (window.monthlyChart && typeof window.monthlyChart.updateOptions === 'function') {
            window.monthlyChart.updateOptions({}, true, true);
          }

          // Re-run data fetchers (call exposed functions if present)
          const yd = document.getElementById('yearFilter_data')?.value;
          const yf = document.getElementById('yearFilter_filedata')?.value;
          const ys = document.getElementById('yearFilter')?.value;
          const ym = document.getElementById('yearFilter_month')?.value;

          if (typeof window.fetchCompanyByYear === 'function') {
            // prefer currently selected year, fallback to ym or current year
            window.fetchCompanyByYear(yd || ys || (new Date()).getFullYear()).catch(()=>{});
          }
          if (typeof window.fetchFiletypeByYear === 'function') {
            window.fetchFiletypeByYear(yf || ys || (new Date()).getFullYear()).catch(()=>{});
          }
          if (typeof window.fetchSlaForYear === 'function') {
            window.fetchSlaForYear(ys || (new Date()).getFullYear()).catch(()=>{});
          }
          if (typeof window.__updateMonthlyChart === 'function') {
            window.__updateMonthlyChart();
          }
        } catch (errInner) {
          console.warn('bahamas-shown inner error', errInner);
        }
      }, 80); // 80ms delay is usually enough
    } catch (err) {
      console.warn('Error refreshing Bahamas charts on show', err);
    }
  });

})(); // end file
