<<<<<<< HEAD
{{-- resources/views/project-intake/form.blade.php (fixed header & sequence) --}}
=======
{{-- resources/views/project-intake/form.blade.php --}}
>>>>>>> 9d9ed85b (for cleaner setup)
@extends('layouts/layoutMaster')

@section('title', $title)

@section('vendor-style')
<<<<<<< HEAD
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<style>
  :root {
    --theme-primary: #E35205;
    --theme-on-primary: #fff;
    --theme-primary-600: #c94805;
    --theme-primary-700: #b63f04;
    --theme-primary-050: #fff3ec;
  }

  .excel-table thead th {
    background: var(--theme-primary) !important;
    color: var(--theme-on-primary) !important;
    border-color: var(--theme-primary) !important;
    vertical-align: middle
  }

  .excel-table thead th.sticky {
    position: sticky;
    top: 0;
    z-index: 5;
    box-shadow: 0 2px 0 rgba(0, 0, 0, .04)
  }

  .excel-table tbody tr:nth-child(odd) td {
    background: #fff
  }

  .excel-table tbody tr:nth-child(even) td {
    background: var(--theme-primary-050)
  }

  .excel-table td,
  .excel-table th {
    border-color: #e8e8e8 !important
  }

  .excel-table td>.form-control,
  .excel-table td>.form-select,
  .excel-table td>.select2-container {
    min-width: 240px;
    height: 38px
  }

  .excel-table td textarea.form-control {
    min-width: 340px;
    height: 38px
  }

  .form-control:focus,
  .form-select:focus {
    border-color: var(--theme-primary);
    box-shadow: 0 0 0 .2rem rgba(227, 82, 5, .15)
  }

  .btn-primary {
    background: var(--theme-primary);
    border-color: var(--theme-primary);
    color: var(--theme-on-primary)
  }

  .btn-primary:hover,
  .btn-primary:focus {
    background: var(--theme-primary-600);
    border-color: var(--theme-primary-600);
    color: var(--theme-on-primary)
  }

  .excel-actions .btn-danger {
    background: transparent;
    color: #dc3545;
    border-color: #dc3545
  }

  .excel-actions .btn-danger:hover {
    background: #dc3545;
    color: #fff
  }

  .select2-container--default .select2-selection--single {
    border-color: #ced4da;
    height: 38px
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 38px
  }

  .select2-container--default.select2-container--focus .select2-selection--single {
    border-color: var(--theme-primary);
    box-shadow: 0 0 0 .2rem rgba(227, 82, 5, .15)
  }

  .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
    background: var(--theme-primary);
    color: var(--theme-on-primary)
  }

  .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: var(--theme-primary);
    border-color: var(--theme-primary);
    color: var(--theme-on-primary)
  }

  .card-header .btn.btn-primary {
    background: var(--theme-primary);
    border-color: var(--theme-primary)
  }

  .card-header .btn.btn-primary:hover {
    background: var(--theme-primary-600);
    border-color: var(--theme-primary-600)
  }

  .is-invalid {
    border-color: #dc3545 !important
  }

  .invalid-feedback {
    display: block
  }

  .layout-page .card .orange-header {
    background: #fde6dc !important;
  }
  .layout-page .card .orange-header .bg-secondary-subtle {
    padding: 0;
    border: 0 !important;
    color: #e85115;
    border-left: 1px solid #5d596c !important;
    border-radius: 0;
    padding-left: 12px;
  }
  .orange-header .more-filters, .orange-header .more-filters:hover {
        background: #5d596c !important;
      color: #fff !important;
      border-color: #5d596c !important;
  }
  .orange-header .reset-btn {
        background: #fff;
      color: #000;
  }
  .container-xxl.flex-grow-1.container-p-y {
    overflow: hidden;
}
</style>
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('extra-script')
<script>
  /* ================== Utilities ================== */
  function todayYMD() {
    const d = new Date();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${d.getFullYear()}-${m}-${day}`;
  }

  function parseYMD(s) {
    if (!s || !/^\d{4}-\d{2}-\d{2}$/.test(s)) return null;
    const [y, m, d] = s.split('-').map(Number);
    const dt = new Date(y, m - 1, d);
    return isNaN(dt.getTime()) ? null : dt;
  }

  function minDate(a, b) {
    return (a && b) ? (a < b ? a : b) : (a || b);
  }

  function maxDate(a, b) {
    return (a && b) ? (a > b ? a : b) : (a || b);
  }

  // Row-level date pairs to enforce end >= start
  const DATE_PAIRS = [{
      start: 'query_raised_date',
      end: 'query_resolved_date',
      labelStart: 'Query Raised',
      labelEnd: 'Query Resolved'
    },
    {
      start: 'abstraction_start_date',
      end: 'abstract_completion_date',
      labelStart: 'Abstraction Start',
      labelEnd: 'Abstraction Complete'
    },
    {
      start: 'feedback_received_date',
      end: 'feedback_completion_date',
      labelStart: 'Feedback Received',
      labelEnd: 'Feedback Complete'
    },
    {
      start: 'proposed_delivery_date',
      end: 'actual_delivered_date',
      labelStart: 'Proposed Delivery',
      labelEnd: 'Actual Delivered'
    }
  ];

  /* ================== Select2 & Datepicker ================== */
  function initSelect2(scope) {
    const $scope = scope ? jQuery(scope) : jQuery(document);
    if (!$.fn.select2) {
      console.warn('Select2 not loaded');
      return;
    }
    $scope.find('select.select2').each(function() {
      const $el = jQuery(this);
      if (!$el.hasClass('select2-hidden-accessible')) $el.select2({
        width: '100%'
      });
    });
  }

  function initDatePickers(scope) {
    if (!$.fn.datepicker) {
      console.error('Bootstrap Datepicker not loaded');
      return;
    }
    const $scope = scope ? jQuery(scope) : jQuery(document);

    const max = new Date(); // block future dates for all .js-date

    // Day-level pickers
    $scope.find('.js-date').each(function() {
      const $el = jQuery(this);
      $el.datepicker('destroy').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        orientation: 'bottom',
        clearBtn: true,
        endDate: max // <= today
      }).on('changeDate change', function() {
        $el.valid();
        const $row = $el.closest('tr');
        updateRowDatepickerBounds($row);
      });
    });

    // Month pickers (cap at current month)
    $scope.find('.js-month').each(function() {
      const $el = jQuery(this);
      $el.datepicker('destroy').datepicker({
        format: 'yyyy-mm',
        autoclose: true,
        minViewMode: 1,
        orientation: 'bottom',
        clearBtn: true,
        endDate: new Date()
      }).on('changeDate change', function() {
        $el.valid();
      });
    });
  }

  function updateRowDatepickerBounds($row) {
    const today = parseYMD(todayYMD());
    DATE_PAIRS.forEach(pair => {
      const $start = $row.find(`input[name="${pair.start}[]"]`);
      const $end = $row.find(`input[name="${pair.end}[]"]`);
      if (!$end.length) return;

      const startVal = parseYMD($start.val());
      const minBound = startVal || null;
      const maxBound = today;

      $end.datepicker('setStartDate', minBound);
      $end.datepicker('setEndDate', maxBound);

      const endVal = parseYMD($end.val());
      if (endVal && ((minBound && endVal < minBound) || (maxBound && endVal > maxBound))) {
        $end.val('');
        $end.valid();
      }

      $start.datepicker && $start.datepicker('setEndDate', maxBound);
      const sVal = parseYMD($start.val());
      if (sVal && maxBound && sVal > maxBound) {
        $start.val('');
        $start.valid();
      }
    });
  }

  /* ================== Validation helpers ================== */
  if (!$.validator.methods.pattern) {
    $.validator.addMethod("pattern", function(value, element, param) {
      if (this.optional(element)) return true;
      const re = (typeof param === 'string') ? new RegExp(param) : param;
      return re.test(value);
    }, "Invalid format.");
  }

  $.validator.addMethod("notFuture", function(value, element) {
    if (this.optional(element)) return true;
    const v = parseYMD(value);
    const t = parseYMD(todayYMD());
    return v && t && v <= t;
  }, "Date cannot be in the future.");

  $.validator.addMethod("onOrAfter", function(value, element, startSelector) {
    if (this.optional(element)) return true;
    const end = parseYMD(value);
    const startVal = $(startSelector).val();
    const start = parseYMD(startVal);
    if (!end || !start) return true;
    return end >= start;
  }, "End date must be on or after start date.");

  function baseArrayName(name) {
    return name.replace(/\[\d*\]$/, '[]');
  }

  function reindexNamesAndRules() {
    const $rows = $('#excel-tbody tr');

    $rows.each(function(rowIdx) {
      const $row = $(this);

      $row.find('input[name], select[name], textarea[name]').each(function() {
        const $el = $(this);
        const oldName = $el.attr('name');
        if (!oldName) return;

        const base = baseArrayName(oldName);
        const plain = base.slice(0, -2);
        const newName = `${plain}[${rowIdx}]`;

        if (oldName !== newName) {
          try {
            $el.rules('remove');
          } catch (e) {}
          $el.attr('name', newName);
          $el.removeData('previousValue');
        }
      });

      attachRowRulesForIndexedRow($row, rowIdx);
      updateRowDatepickerBounds($row);
    });
  }

  function attachRowRulesForIndexedRow($row, rowIdx) {
    const $proj = $row.find(`input[name="project_name[${rowIdx}]"]`);
    if ($proj.length) {
      $proj.rules('add', {
        required: true,
        messages: {
          required: 'Project Name is required.'
        }
      });
    }
    $row.find('.js-date').each(function() {
      $(this).rules('add', {
        notFuture: true
      });
    });

    DATE_PAIRS.forEach(pair => {
      const startSel = `input[name="${pair.start}[${rowIdx}]"]`;
      const endSel = `input[name="${pair.end}[${rowIdx}]"]`;
      const $end = $row.find(endSel);
      if ($end.length) {
        $end.rules('add', {
          onOrAfter: startSel,
          messages: {
            onOrAfter: `${pair.labelEnd} must be on or after ${pair.labelStart}.`
          }
        });
      }
    });
  }

  function addRow() {
    const tbody = document.getElementById('excel-tbody');
    const tpl = document.getElementById('row-template');
    const node = tpl.content.cloneNode(true);
    tbody.appendChild(node);
    const last = tbody.lastElementChild;
    initSelect2(last);
    initDatePickers(last);
    renumberRowIndex();
    reindexNamesAndRules();
  }

  function removeRow(btn) {
    const row = btn.closest('tr');
    const tbody = document.getElementById('excel-tbody');
    if (tbody.children.length > 1) {
      row.remove();
      renumberRowIndex();
      reindexNamesAndRules();
    }
  }

  function renumberRowIndex() {
    document.querySelectorAll('#excel-tbody tr').forEach((tr, idx) => {
      const cell = tr.querySelector('[data-row-idx]');
      if (cell) cell.textContent = idx + 1;
    });
  }

  $(function() {
    initSelect2();
    initDatePickers();
    renumberRowIndex();

    $('#btn-add-row').on('click', addRow);
    $('#excel-tbody').on('click', '[data-remove-row]', function() {
      removeRow(this);
    });

    $.validator.setDefaults({
      ignore: function(index, el) {
        if ($(el).hasClass('select2-hidden-accessible')) return false;
        return $(el).is(':hidden');
      },
      errorElement: 'div',
      errorClass: 'invalid-feedback',
      highlight: function(el) {
        const $el = $(el).addClass('is-invalid');
        if ($el.hasClass('select2-hidden-accessible')) {
          $el.next('.select2').find('.select2-selection').addClass('is-invalid');
        }
      },
      unhighlight: function(el) {
        const $el = $(el).removeClass('is-invalid');
        if ($el.hasClass('select2-hidden-accessible')) {
          $el.next('.select2').find('.select2-selection').removeClass('is-invalid');
        }
      },
      errorPlacement: function(error, element) {
        if (element.hasClass('select2-hidden-accessible')) {
          error.insertAfter(element.next('.select2'));
        } else {
          error.insertAfter(element);
        }
      }
    });

    $.validator.addClassRules({
      'int-id': {
        digits: true
      },
      'int-nonneg': {
        digits: true,
        min: 0
      },
      'num-nonneg': {
        number: true,
        min: 0
      },
      ymd: {
        pattern: /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/
      },
      ym: {
        pattern: /^\d{4}-(0[1-9]|1[0-2])$/
      }
    });

    const $form = $('#excelForm').attr('novalidate', 'novalidate');
    const validator = $form.validate({
      rules: {
        'project_name[]': {
          required: true
        }
      },
      messages: {
        'project_name[]': {
          required: 'Project Name is required.'
        },
        'billing_month[]': {
          pattern: 'Billing Month must be in YYYY-MM format.'
        },
        'request_received_date[]': {
          pattern: 'Dates must be in YYYY-MM-DD format.'
        }
      },
      invalidHandler: function(event, v) {
        if (v.errorList.length) {
          const $first = $(v.errorList[0].element);
          $('html, body').animate({
            scrollTop: $first.offset().top - 120
          }, 200);
        }
      }
    });

    reindexNamesAndRules();

    $(document).on('change input', '#excel-tbody input, #excel-tbody textarea, #excel-tbody select', function() {
      $(this).valid();
      if ($(this).hasClass('js-date')) updateRowDatepickerBounds($(this).closest('tr'));
    });
    $(document).on('change', 'select.select2', function() {
      $(this).valid();
    });

    $('#excelForm').on('submit', function(e) {
      if (!$(this).valid()) e.preventDefault();
    });
  });
   (function() {
    const form = document.getElementById('filtersForm');
    if (!form) return;

    const qs = new URLSearchParams(window.location.search);
    const container = document.getElementById('activeFiltersBadges');
    if (!container) return;

    const labelMap = {
      'filter[property_manager_id]': 'Property Manager',
      'filter[status_id]': 'Status',
      'filter[type_of_work_id]': 'Type of Work',
      'filter[customer_id]': 'Client',
      'filter[request_received_date_from]': 'Received From',
      'filter[request_received_date_to]': 'Received To',
      'filter[delivered_date_from]': 'Delivery From',
      'filter[delivered_date_to]': 'Delivery To',
      'filter[abstractor_id]': 'Abstractor',
      'filter[reviewer_id]': 'Reviewer',
      'filter[sense_check_ddr_id]': 'Sense Check',
      'filter[member_role]': 'Role',
      'filter[member_user_id]': 'Member',
      'filter[actual_delivered_date_from]': 'Actual From',
      'filter[actual_delivered_date_to]': 'Actual To',
      'filter[billing_month]': 'Billing Month',
      'filter[invoice_format_id]': 'Invoice',
      'filter[fb_category_id]': 'FB Category',
      'filter[has_feedback]': 'Has FB',
      'filter[feedback_received_date_from]': 'FB From',
      'filter[feedback_received_date_to]': 'FB To',
    };

    const humanizeSelect = (name) => {
      const el = form.querySelector(`[name="${name}"]`);
      if (!el) return null;
      if (el.tagName === 'SELECT') {
        const opt = el.options[el.selectedIndex];
        return (opt && opt.text && opt.value) ? opt.text : el.value;
      }
      return el.value;
    };

    Object.keys(labelMap).forEach(name => {
      const val = qs.get(name);
      if (val && val.trim() !== '') {
        const text = humanizeSelect(name) || val;
        const badge = document.createElement('span');
        badge.className = 'badge bg-secondary-subtle text-secondary-emphasis border';
        badge.textContent = `${labelMap[name]}: ${text}`;
        container.appendChild(badge);
        container.parentElement.classList.remove('d-none');
      }
    });

    // Reset filters: clear all inputs and submit
    document.getElementById('btnResetFilters')?.addEventListener('click', function(e) {
      e.preventDefault();
      // Clear URL query and reload
      const base = window.location.pathname + window.location.hash;
      window.location.href = base;
    });
  })();
</script>
@endsection


@section('content')
{{-- resources/views/project/intake-excel.blade.php --}}

@php
// Centralized permission flags
$canAdd = auth()->user()->can('intake form add');
$canRemove = auth()->user()->can('intake form remove');

$canPrimary = auth()->user()->can('view intake form primary information');
$canQueries = auth()->user()->can('view intake form queries');
$canProduction = auth()->user()->can('view intake form production details');
$canBilling = auth()->user()->can('view intake form billing details');
$canCustomerFB = auth()->user()->can('view intake form customer feedback');

// Compute counts for existing/old rows
$existingCount = isset($rows) ? $rows->count() : 0;
$oldCount = max(
count(old('project_name', [])),
count(old('property_manager_id', [])),
$existingCount
);
$rowCount = max($oldCount, 1);

// Helper to resolve row value
if (!function_exists('rowVal')) {
function rowVal($name, $i, $model, $modelKey) {
$ov = old($name.'.'.$i);
if(!is_null($ov)) return $ov;
return $model ? data_get($model, $modelKey) : '';
}
}

@endphp

=======
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
  <style>
    :root{ --theme-primary:#E35205; --theme-on-primary:#fff; --theme-primary-600:#c94805; --theme-primary-050:#fff3ec; }
    .excel-table thead th{background:var(--theme-primary)!important;color:var(--theme-on-primary)!important;border-color:var(--theme-primary)!important;vertical-align:middle}
    .excel-table thead th.sticky{position:sticky;top:0;z-index:5;box-shadow:0 2px 0 rgba(0,0,0,.04)}
    .excel-table tbody tr:nth-child(odd) td{background:#fff}
    .excel-table tbody tr:nth-child(even) td{background:var(--theme-primary-050)}
    .excel-table td,.excel-table th{border-color:#e8e8e8!important}
    .excel-table td>.form-control,.excel-table td>.form-select,.excel-table td>.select2-container{min-width:240px;height:38px; z-index: 0 !important;}
    .excel-table td textarea.form-control{min-width:340px;height:38px}
    .form-control:focus,.form-select:focus{border-color:var(--theme-primary);box-shadow:0 0 0 .2rem rgba(227,82,5,.15)}
    .btn-primary{background:var(--theme-primary);border-color:var(--theme-primary);color:var(--theme-on-primary)}
    .btn-primary:hover,.btn-primary:focus{background:var(--theme-primary-600);border-color:var(--theme-primary-600)}
    .excel-actions .btn-danger{background:transparent;color:#dc3545;border-color:#dc3545}
    .excel-actions .btn-danger:hover{background:#dc3545;color:#fff}
    .select2-container--default .select2-selection--single{border-color:#ced4da;height:38px}
    .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:38px}
    .select2-container--default .select2-selection--single .select2-selection__arrow{height:38px}
    .select2-container--default.select2-container--focus .select2-selection--single{border-color:var(--theme-primary);box-shadow:0 0 0 .2rem rgba(227,82,5,.15)}
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable{background:var(--theme-primary);color:var(--theme-on-primary)}
    .select2-container--default .select2-selection--multiple .select2-selection__choice{background:var(--theme-primary);border-color:var(--theme-primary);color:var(--theme-on-primary)}
    .card-header .btn.btn-primary{background:var(--theme-primary);border-color:var(--theme-primary)}
    .card-header .btn.btn-primary:hover{background:var(--theme-primary-600);border-color:var(--theme-primary-600)}
    .is-invalid{border-color:#dc3545!important}
    .invalid-feedback{display:block}
    .layout-page .card .orange-header{background:#fde6dc!important}
    .layout-page .card .orange-header .bg-secondary-subtle{padding:0;border:0!important;color:#e85115;border-left:1px solid #5d596c!important;border-radius:0;padding-left:12px}
    .orange-header .more-filters,.orange-header .more-filters:hover{background:#5d596c!important;color:#fff!important;border-color:#5d596c!important}
    .orange-header .reset-btn{background:#fff;color:#000}
    .container-xxl.flex-grow-1.container-p-y{overflow:hidden}
    script[type="text/template"]{display:none!important}

    /* Read-only look */
    input[readonly], textarea[readonly]{ background-color:#f8f9fa!important; color:#6c757d!important; cursor:not-allowed!important; }
    select[data-readonly]{ background-color:#f8f9fa!important; color:#6c757d!important; pointer-events:none; cursor:not-allowed!important; appearance:none; -webkit-appearance:none; }
    .select2-container.select2-ro .select2-selection{ background-color:#f8f9fa!important; color:#6c757d!important; pointer-events:none; cursor:not-allowed!important; }
    .select2-container.select2-ro .select2-selection__arrow{ opacity:.35; pointer-events:none; }

    /* datepicker above sticky headers & modal */
    .datepicker-dropdown{ z-index: 1065 !important; }

    /* Select2 inside Bootstrap modal */
    .modal .select2-container{ width:100% !important; }

    /* Validation messages inside modal */
    #queriesForm .invalid-feedback { display:block; }
    .scroll-y{max-height: 300px; overflow-y: auto; padding: 10px 10px;}

    form.property-table .table-responsive .table { overflow: auto; }
    form.property-table .table-responsive { max-height: 400px; overflow-x: auto; position: relative; }
    form.property-table .excel-table thead th.sticky { border-width: 1px 0; height: 60px !important; }
    input[readonly].js-date,
    input[readonly].js-month { pointer-events: none; }

    /* Queries button */
    .btn-queries{
      --q-bg:#f7f7fb; --q-text:#373a49; --q-br:#e6e6ef;
      --q-bg-hover:#f1f0ff; --q-br-hover:#d7d6f5;
      display:inline-flex; align-items:center; gap:.4rem;
      border:1px solid var(--q-br); background:var(--q-bg); color:var(--q-text);
      padding:.38rem .7rem; line-height:1; border-radius:999px; font-weight:600;
      transition:all .18s ease; box-shadow:0 1px 0 rgba(0,0,0,.03);
    }
    .btn-queries:hover{ background:var(--q-bg-hover); border-color:var(--q-br-hover); transform:translateY(-1px); }
    .btn-queries:disabled{ opacity:.6; cursor:not-allowed; transform:none; }
    .q-badge{
      display:inline-flex; align-items:center; justify-content:center;
      min-width:1.35rem; height:1.35rem; padding:0 .35rem;
      border-radius:999px; font-size:.75rem; font-weight:700;
      border:1px solid transparent; margin-left:.25rem;
    }
    .q-badge-muted{ color:#6b7280; background:#f1f5f9; border-color:#e2e8f0; }
    .q-badge-danger{
      color:#fff; background:linear-gradient(135deg,#ef4444,#dc2626);
      border-color:#b91c1c; box-shadow:0 0 0 2px rgba(239,68,68,.12) inset;
      position:relative; isolation:isolate;
    }
    .q-badge-danger::after{
      content:""; position:absolute; inset:-2px; border-radius:inherit;
      box-shadow:0 0 0 0 rgba(239,68,68,.35); animation:qPulse 1.8s ease-out infinite;
      z-index:-1;
    }
    @keyframes qPulse{
      0%{ box-shadow:0 0 0 0 rgba(239,68,68,.35); }
      70%{ box-shadow:0 0 0 10px rgba(239,68,68,0); }
      100%{ box-shadow:0 0 0 0 rgba(239,68,68,0); }
    }
  </style>
@endsection

@section('vendor-script')
  <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
@endsection

@section('content')
@php
  $canAdd      = auth()->user()->can('intake form add');
  $canRemove   = auth()->user()->can('intake form remove');

  $canViewPrimary    = auth()->user()->can('view intake form primary information');
  $canEditPrimary    = auth()->user()->can('edit intake form primary information');

  $canViewQueries    = auth()->user()->can('view intake form queries');
  $canEditQueries    = auth()->user()->can('edit intake form queries');

  $canViewProduction = auth()->user()->can('view intake form production details');
  $canEditProduction = auth()->user()->can('edit intake form production details');

  $canViewBilling    = auth()->user()->can('view intake form billing details');
  $canEditBilling    = auth()->user()->can('edit intake form billing details');

  $canViewCustomerFB = auth()->user()->can('view intake form customer feedback');
  $canEditCustomerFB = auth()->user()->can('edit intake form customer feedback'); // not used to gate fields below

  $user = auth()->user();
  $isAbs   = method_exists($user,'hasAnyRole') ? $user->hasAnyRole(['abstractor','Abstractor']) : false;
  $isRev   = method_exists($user,'hasAnyRole') ? $user->hasAnyRole(['reviewer','Reviewer']) : false;
  $isSense = method_exists($user,'hasAnyRole') ? $user->hasAnyRole(['sense check','sense_check','Sense Check / DDR']) : false;

  // Role flags
  $isPM       = method_exists($user,'hasAnyRole') ? $user->hasAnyRole(['project manager']) : false;
  $isAdmin    = method_exists($user,'hasAnyRole') ? $user->hasAnyRole(['admin','super admin']) : false;
  $hasElevated = method_exists($user,'hasAnyRole') ? $user->hasAnyRole(['super admin','admin','project manager']) : false;

  $isCustomer = method_exists($user,'hasAnyRole') ? $user->hasAnyRole(['customer','Customer']) : false;

  // Whether a non-elevated role should see only their own set
  $roleOwnOnly = ($isAbs || $isRev || $isSense) && !$hasElevated;

  // Visibility groups (unchanged)
  $showProd_Abstractor        = !$roleOwnOnly || $isAbs;
  $showProd_AbsStart          = !$roleOwnOnly || $isAbs;
  $showProd_AbsComplete       = !$roleOwnOnly || $isAbs;

  $showProd_Reviewer          = !$roleOwnOnly || $isRev;
  $showProd_ReviewComplete    = !$roleOwnOnly || $isRev;

  $showProd_Sense             = !$roleOwnOnly || $isSense;
  $showProd_SenseComplete     = !$roleOwnOnly || $isSense;

  // Always show these four date columns to all team users
  $showProd_Proposed          = true;
  $showProd_Actual            = true;
  $showProd_FeedbackReceived  = true;

  // ===== Edit rights for the 4 columns (Admin/Super Admin/PM can edit everything) =====
  $canEdit_Proposed     = $hasElevated || $isPM;              // PM/Admin/Super Admin
  $canEdit_Actual       = $hasElevated || $isPM || $isSense;  // PM/Admin/SA + Sense
  $canEdit_FbReceived   = $hasElevated || $isPM || $isSense;  // PM/Admin/SA + Sense
  $canEdit_FbCompletion = $hasElevated || $isPM || $isSense;  // PM/Admin/SA + Sense

  // ===== Customer Feedback section (ONLY change requested) =====
  // PM/Admin/Super Admin can edit all CF fields EXCEPT "Customer Feedback Comments".
  // "Customer Feedback Comments" is editable only by customers.
  $canEditCF_DateReceived   = ($isPM || $isAdmin);
  $canEditCF_CustomerName   = ($isPM || $isAdmin);
  $canEditCF_Category       = ($isPM || $isAdmin);
  $canEditCF_Comments       = $isCustomer ? true : false; // customers only
  $canEditCF_SBResponse     = ($isPM || $isAdmin);
  $canEditCF_CompletionDate = ($isPM || $isAdmin);

  // Self-locks for role user selects (Abstractor/Reviewer/Sense) unless elevated
  $lockAbsSelectForSelf   = $isAbs   && !$hasElevated;
  $lockRevSelectForSelf   = $isRev   && !$hasElevated;
  $lockSenseSelectForSelf = $isSense && !$hasElevated;

  $canSubmit = $canAdd || $canRemove || $canEditPrimary || $canEditQueries || $canEditProduction || $canEditBilling || $canEditCustomerFB;

  $existingCount = isset($rows) ? $rows->count() : 0;
  $oldCount = max(
    count(old('project_name', [])),
    count(old('property_manager_id', [])),
    $existingCount
  );
  $rowCount = max($oldCount, 1);

  if (!function_exists('rowVal')) {
    function rowVal($name, $i, $model, $modelKey) {
      $ov = old($name.'.'.$i);
      if (!is_null($ov)) return $ov;
      return $model ? data_get($model, $modelKey) : '';
    }
  }
  if (!function_exists('roInput')) {
    function roInput(bool $canEdit): string { return $canEdit ? '' : 'readonly'; }
  }
  if (!function_exists('roSelect')) {
    function roSelect(bool $canEdit): string { return $canEdit ? '' : 'data-readonly="1"'; }
  }
  if (!function_exists('fmtMDY')) {
    function fmtMDY($v) {
      if (empty($v)) return '';
      try { return \Carbon\Carbon::parse($v)->format('m-d-Y'); } catch (\Exception $e) { return $v; }
    }
  }
  if (!function_exists('fmtMY')) {
    function fmtMY($v) {
      if (empty($v)) return '';
      try {
        if (preg_match('/^\d{4}-\d{2}$/', $v)) { $v .= '-01'; }
        return \Carbon\Carbon::parse($v)->format('m-Y');
      } catch (\Exception $e) { return $v; }
    }
  }
@endphp



{{-- expose if client response should be editable --}}
<script>window.__canEditClientResponse = @json($isCustomer);</script>
{{-- expose if queries editable --}}
<script>window.__canEditQueries = @json($canEditQueries);</script>

{{-- Flash messages --}}
@if (session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
@if (session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
@if ($errors->any())
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Please fix the following:</strong>
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

>>>>>>> 9d9ed85b (for cleaner setup)
<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h4 class="text-dark mb-0">
          {{ $title }} ({{ $project_info->project_name }}) ({{ $project_info->customer_name }})
        </h4>
        @if($canAdd)
<<<<<<< HEAD
        <div><button type="button" id="btn-add-row" class="btn btn-primary">+ Add Row</button></div>
        @endif
      </div>

      <div class="card-body">
=======
          <div><button type="button" id="btn-add-row" class="btn btn-primary">+ Add Row</button></div>
        @endif
      </div>

      <div class="card-body ">
        {{-- Filters --}}
>>>>>>> 9d9ed85b (for cleaner setup)
        <form id="filtersForm" method="GET" action="{{ url()->current() }}" class="mb-3">
          <div class="card border-0 shadow-sm">
            <div class="card-header orange-header bg-light d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-2">
                <h5 class="mb-0">Filters</h5>
<<<<<<< HEAD
                {{-- Active filters badges (auto-render if present) --}}
=======
>>>>>>> 9d9ed85b (for cleaner setup)
                <div class="d-none d-md-flex flex-wrap gap-2 ms-2" id="activeFiltersBadges"></div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn more-filters btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#moreFilters" aria-expanded="false">
                  More filters
                </button>
                <button type="reset" id="btnResetFilters" class="btn reset-btn btn-link btn-sm text-decoration-none">Reset</button>
                <button type="submit" class="btn Apply-btn btn-primary btn-sm">Apply</button>
              </div>
            </div>

            <div class="card-body">
<<<<<<< HEAD
              {{-- Top row: the most used filters --}}
              <div class="row g-3">
                {{-- Property Manager --}}
=======
              <div class="row g-3">
>>>>>>> 9d9ed85b (for cleaner setup)
                <div class="col-12 col-md-3">
                  <label class="form-label mb-1">Property Manager</label>
                  <select name="filter[property_manager_id]" class="form-select select2">
                    <option value="">All</option>
                    @foreach(($project_managers ?? []) as $u)
<<<<<<< HEAD
                    @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                    <option value="{{ $u->id }}" @selected(request('filter.property_manager_id')==$u->id)>{{ $name }}</option>
                    @endforeach
                  </select>
                </div>

                {{-- Status --}}
=======
                      @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                      <option value="{{ $u->id }}" @selected(request('filter.property_manager_id')==$u->id)>{{ $name }}</option>
                    @endforeach
                  </select>
                </div>
>>>>>>> 9d9ed85b (for cleaner setup)
                <div class="col-12 col-md-3">
                  <label class="form-label mb-1">Status</label>
                  <select name="filter[status_id]" class="form-select select2">
                    <option value="">All</option>
                    @foreach(($masters['status'] ?? []) as $item)
<<<<<<< HEAD
                    <option value="{{ $item->id }}" @selected(request('filter.status_id')==$item->id)>{{ $item->name }}</option>
                    @endforeach
                  </select>
                </div>

                {{-- Type of Work --}}
=======
                      <option value="{{ $item->id }}" @selected(request('filter.status_id')==$item->id)>{{ $item->name }}</option>
                    @endforeach
                  </select>
                </div>
>>>>>>> 9d9ed85b (for cleaner setup)
                <div class="col-12 col-md-3">
                  <label class="form-label mb-1">Type of Work</label>
                  <select name="filter[type_of_work_id]" class="form-select select2">
                    <option value="">All</option>
                    @foreach(($masters['work_types'] ?? []) as $item)
<<<<<<< HEAD
                    <option value="{{ $item->id }}" @selected(request('filter.type_of_work_id')==$item->id)>{{ $item->name }}</option>
                    @endforeach
                  </select>
                </div>

                {{-- Client-wise (Customer) --}}
=======
                      <option value="{{ $item->id }}" @selected(request('filter.type_of_work_id')==$item->id)>{{ $item->name }}</option>
                    @endforeach
                  </select>
                </div>
>>>>>>> 9d9ed85b (for cleaner setup)
                <div class="col-12 col-md-3">
                  <label class="form-label mb-1">Client</label>
                  <select name="filter[customer_id]" class="form-select select2">
                    <option value="">All</option>
                    @foreach(($customers ?? []) as $c)
<<<<<<< HEAD
                    <option value="{{ $c->id }}" @selected(request('filter.customer_id')==$c->id)>{{ $c->name }}</option>
=======
                      <option value="{{ $c->id }}" @selected(request('filter.customer_id')==$c->id)>{{ $c->name }}</option>
>>>>>>> 9d9ed85b (for cleaner setup)
                    @endforeach
                  </select>
                </div>

<<<<<<< HEAD
                {{-- Received Date (range) --}}
                <div class="col-12 col-md-3">
                  <label class="form-label mb-1">Received Date (from)</label>
                  <input type="text" name="filter[request_received_date_from]" class="form-control js-date ymd"
                    placeholder="YYYY-MM-DD" value="{{ request('filter.request_received_date_from') }}">
                </div>
                <div class="col-12 col-md-3">
                  <label class="form-label mb-1">Received Date (to)</label>
                  <input type="text" name="filter[request_received_date_to]" class="form-control js-date ymd"
                    placeholder="YYYY-MM-DD" value="{{ request('filter.request_received_date_to') }}">
                </div>

                {{-- Delivery Date (range) --}}
                <div class="col-12 col-md-3">
                  <label class="form-label mb-1">Delivery Date (from)</label>
                  <input type="text" name="filter[delivered_date_from]" class="form-control js-date ymd"
                    placeholder="YYYY-MM-DD" value="{{ request('filter.delivered_date_from') }}">
                </div>
                <div class="col-12 col-md-3">
                  <label class="form-label mb-1">Delivery Date (to)</label>
                  <input type="text" name="filter[delivered_date_to]" class="form-control js-date ymd"
                    placeholder="YYYY-MM-DD" value="{{ request('filter.delivered_date_to') }}">
                </div>
              </div>

              {{-- Collapsible: More filters --}}
              <div class="collapse mt-3" id="moreFilters">
                <div class="border-top pt-3">
                  <div class="row g-3">
                    {{-- Production Details: Abstractor / Reviewer / Sense Check --}}
=======
                {{-- Dates in MM-DD-YYYY --}}
                <div class="col-12 col-md-3">
                  <label class="form-label mb-1">Received Date (from)</label>
                  <input type="text" name="filter[request_received_date_from]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(request('filter.request_received_date_from')) }}">
                </div>
                <div class="col-12 col-md-3">
                  <label class="form-label mb-1">Received Date (to)</label>
                  <input type="text" name="filter[request_received_date_to]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(request('filter.request_received_date_to')) }}">
                </div>
                <div class="col-12 col-md-3">
                  <label class="form-label mb-1">Delivery Date (from)</label>
                  <input type="text" name="filter[delivered_date_from]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(request('filter.delivered_date_from')) }}">
                </div>
                <div class="col-12 col-md-3">
                  <label class="form-label mb-1">Delivery Date (to)</label>
                  <input type="text" name="filter[delivered_date_to]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(request('filter.delivered_date_to')) }}">
                </div>
              </div>

              <div class="collapse mt-3" id="moreFilters">
                <div class="border-top pt-3">
                  <div class="row g-3">
>>>>>>> 9d9ed85b (for cleaner setup)
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Abstractor</label>
                      <select name="filter[abstractor_id]" class="form-select select2">
                        <option value="">All</option>
                        @foreach(($abstractor_users ?? []) as $u)
<<<<<<< HEAD
                        @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                        <option value="{{ $u->id }}" @selected(request('filter.abstractor_id')==$u->id)>{{ $name }}</option>
=======
                          @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                          <option value="{{ $u->id }}" @selected(request('filter.abstractor_id')==$u->id)>{{ $name }}</option>
>>>>>>> 9d9ed85b (for cleaner setup)
                        @endforeach
                      </select>
                    </div>
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Reviewer</label>
                      <select name="filter[reviewer_id]" class="form-select select2">
                        <option value="">All</option>
                        @foreach(($reviewer ?? []) as $u)
<<<<<<< HEAD
                        @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                        <option value="{{ $u->id }}" @selected(request('filter.reviewer_id')==$u->id)>{{ $name }}</option>
=======
                          @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                          <option value="{{ $u->id }}" @selected(request('filter.reviewer_id')==$u->id)>{{ $name }}</option>
>>>>>>> 9d9ed85b (for cleaner setup)
                        @endforeach
                      </select>
                    </div>
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Sense Check / DDR</label>
                      <select name="filter[sense_check_ddr_id]" class="form-select select2">
                        <option value="">All</option>
                        @foreach(($sense_check ?? []) as $u)
<<<<<<< HEAD
                        @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                        <option value="{{ $u->id }}" @selected(request('filter.sense_check_ddr_id')==$u->id)>{{ $name }}</option>
=======
                          @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                          <option value="{{ $u->id }}" @selected(request('filter.sense_check_ddr_id')==$u->id)>{{ $name }}</option>
>>>>>>> 9d9ed85b (for cleaner setup)
                        @endforeach
                      </select>
                    </div>

<<<<<<< HEAD
                    {{-- Member-wise: Role + Member --}}
=======
>>>>>>> 9d9ed85b (for cleaner setup)
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Member-wise (Role)</label>
                      <select name="filter[member_role]" class="form-select">
                        <option value="">All</option>
<<<<<<< HEAD
                        <option value="abstractor" @selected(request('filter.member_role')==='abstractor' )>Abstractor</option>
                        <option value="reviewer" @selected(request('filter.member_role')==='reviewer' )>Reviewer</option>
                        <option value="sense_check" @selected(request('filter.member_role')==='sense_check' )>Sense Check / DDR</option>
                        <option value="property_manager" @selected(request('filter.member_role')==='property_manager' )>Property Manager</option>
=======
                        <option value="abstractor" @selected(request('filter.member_role')==='abstractor')>Abstractor</option>
                        <option value="reviewer" @selected(request('filter.member_role')==='reviewer')>Reviewer</option>
                        <option value="sense_check" @selected(request('filter.member_role')==='sense_check')>Sense Check / DDR</option>
                        <option value="property_manager" @selected(request('filter.member_role')==='property_manager')>Property Manager</option>
>>>>>>> 9d9ed85b (for cleaner setup)
                      </select>
                    </div>
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Member (User)</label>
                      <select name="filter[member_user_id]" class="form-select select2">
                        <option value="">All</option>
                        @foreach(($all_users ?? []) as $u)
<<<<<<< HEAD
                        @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                        <option value="{{ $u->id }}" @selected(request('filter.member_user_id')==$u->id)>{{ $name }}</option>
=======
                          @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                          <option value="{{ $u->id }}" @selected(request('filter.member_user_id')==$u->id)>{{ $name }}</option>
>>>>>>> 9d9ed85b (for cleaner setup)
                        @endforeach
                      </select>
                    </div>

<<<<<<< HEAD
                    {{-- Actual Delivery Date (range) --}}
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Actual Delivery Date (from)</label>
                      <input type="text" name="filter[actual_delivered_date_from]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                        value="{{ request('filter.actual_delivered_date_from') }}">
                    </div>
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Actual Delivery Date (to)</label>
                      <input type="text" name="filter[actual_delivered_date_to]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                        value="{{ request('filter.actual_delivered_date_to') }}">
                    </div>

                    {{-- Billing Details --}}
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Billing Month</label>
                      <input type="text" name="filter[billing_month]" class="form-control js-month ym" placeholder="YYYY-MM"
                        value="{{ request('filter.billing_month') }}">
=======
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Actual Delivery Date (from)</label>
                      <input type="text" name="filter[actual_delivered_date_from]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(request('filter.actual_delivered_date_from')) }}">
                    </div>
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Actual Delivery Date (to)</label>
                      <input type="text" name="filter[actual_delivered_date_to]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(request('filter.actual_delivered_date_to')) }}">
                    </div>

                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Billing Month</label>
                      <input type="text" name="filter[billing_month]" class="form-control js-month my" placeholder="MM-YYYY" value="{{ fmtMY(request('filter.billing_month')) }}">
>>>>>>> 9d9ed85b (for cleaner setup)
                    </div>
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Invoice Method</label>
                      <select name="filter[invoice_format_id]" class="form-select select2">
                        <option value="">All</option>
                        @foreach(($masters['invoice_formats'] ?? []) as $item)
<<<<<<< HEAD
                        <option value="{{ $item->id }}" @selected(request('filter.invoice_format_id')==$item->id)>{{ $item->name }}</option>
=======
                          <option value="{{ $item->id }}" @selected(request('filter.invoice_format_id')==$item->id)>{{ $item->name }}</option>
>>>>>>> 9d9ed85b (for cleaner setup)
                        @endforeach
                      </select>
                    </div>

<<<<<<< HEAD
                    {{-- Customer Feedback --}}
=======
>>>>>>> 9d9ed85b (for cleaner setup)
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Customer Feedback (Category)</label>
                      <select name="filter[fb_category_id]" class="form-select select2">
                        <option value="">All</option>
                        @foreach(($masters['feedback_categories'] ?? []) as $item)
<<<<<<< HEAD
                        <option value="{{ $item->id }}" @selected(request('filter.fb_category_id')==$item->id)>{{ $item->name }}</option>
=======
                          <option value="{{ $item->id }}" @selected(request('filter.fb_category_id')==$item->id)>{{ $item->name }}</option>
>>>>>>> 9d9ed85b (for cleaner setup)
                        @endforeach
                      </select>
                    </div>
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Has Feedback?</label>
                      <select name="filter[has_feedback]" class="form-select">
                        <option value="">All</option>
<<<<<<< HEAD
                        <option value="1" @selected(request('filter.has_feedback')==='1' )>Yes</option>
                        <option value="0" @selected(request('filter.has_feedback')==='0' )>No</option>
                      </select>
                    </div>

                    {{-- Feedback Received Date (range) --}}
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Feedback received date (from)</label>
                      <input type="text" name="filter[feedback_received_date_from]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                        value="{{ request('filter.feedback_received_date_from') }}">
                    </div>
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Feedback received date (to)</label>
                      <input type="text" name="filter[feedback_received_date_to]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                        value="{{ request('filter.feedback_received_date_to') }}">
=======
                        <option value="1" @selected(request('filter.has_feedback')==='1')>Yes</option>
                        <option value="0" @selected(request('filter.has_feedback')==='0')>No</option>
                      </select>
                    </div>

                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Feedback received date (from)</label>
                      <input type="text" name="filter[feedback_received_date_from]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(request('filter.feedback_received_date_from')) }}">
                    </div>
                    <div class="col-12 col-md-4">
                      <label class="form-label mb-1">Feedback received date (to)</label>
                      <input type="text" name="filter[feedback_received_date_to]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(request('filter.feedback_received_date_to')) }}">
>>>>>>> 9d9ed85b (for cleaner setup)
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
<<<<<<< HEAD
        <form id="excelForm" method="POST"
          action="{{ $type == 'edit' ? route('project.file.update') : route('project.file.store') }}">
          @csrf
          @if($type == 'edit') @method('PUT') @endif
          @isset($parentId) <input type="hidden" name="parent_id" value="{{ $parentId }}"> @endisset
=======

        {{-- Excel-like form --}}
        <form id="excelForm" method="POST" action="{{ $type == 'edit' ? route('project.file.update') : route('project.file.store') }}" class="property-table">
          @csrf
          @if($type == 'edit') @method('PUT') @endif
          @isset($parentId) <input type="hidden" name="parent_id" value="{{ $parentId }}"> @endisset
          @php
            $filtersActive = collect(request('filter', []))
              ->flatten()
              ->filter(fn($v) => !is_null($v) && $v !== '')
              ->isNotEmpty();
          @endphp

          <input type="hidden" name="has_active_filters" value="{{ $filtersActive ? 1 : 0 }}">
>>>>>>> 9d9ed85b (for cleaner setup)

          <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle excel-table">
              <thead class="table-light">
                <tr>
<<<<<<< HEAD
                  @if($canPrimary)
                  <th class="sticky">#</th>
                  {{-- <th class="sticky">Client Name</th>
                    <th class="sticky">Project Name</th> --}}
                  {{-- <th class="sticky">Project Manager</th> --}}
                  <th class="sticky">Property ID</th>
                  <th class="sticky">Property Name</th>
                  <th class="sticky">Tenant/Lease ID</th>
                  <th class="sticky">Tenant Name</th>
                  <th class="sticky">Priority</th>
                  <th class="sticky">Premises Address</th>
                  <th class="sticky">PDF Names</th>
                  <th class="sticky">No. of Documents</th>
                  <th class="sticky">Status</th>
                  <th class="sticky">Type of Lease</th>
                  <th class="sticky">Type of Work</th>
                  <th class="sticky">Language</th>
                  <th class="sticky">Request Received Date</th>
                  <th class="sticky">Delivered Date</th>
                  <th class="sticky">Property Manager</th>
                  @endif

                  @if($canQueries)
                  <th class="sticky">Type of Queries</th>
                  <th class="sticky">SB Queries</th>
                  <th class="sticky">Client Response</th>
                  <th class="sticky">Query Status</th>
                  <th class="sticky">Query Raised Date</th>
                  <th class="sticky">Query Resolved Date</th>
                  @endif

                  @if($canProduction)
                  <th class="sticky">Abstractor</th>
                  <th class="sticky">Abstraction Start Date</th>
                  <th class="sticky">Abstract Completion Date</th>
                  <th class="sticky">Reviewer</th>
                  <th class="sticky">Review Completion Date</th>
                  <th class="sticky">Sense Check / DDR</th>
                  <th class="sticky">Sense Check / DDR Completion Date</th>
                  <th class="sticky">Proposed Delivery Date</th>
                  <th class="sticky">Actual Delivered Date</th>
                  <th class="sticky">Feedback received Date</th>
                  @endif

                  @if($canBilling)
                  <th class="sticky">Feedback completion date</th>
                  <th class="sticky">Billing Month</th>
                  {{-- <th class="sticky">No. of Pages (If non-English)</th> --}}
                  <th class="sticky">Invoice Method</th>
                  <th class="sticky">No. of Pages (If non-English)</th>
                  @endif

                  @if($canCustomerFB)
                  <th class="sticky">Date of Feedback Received</th>
                  <th class="sticky">Feedback - Customer Name</th>
                  <th class="sticky">Category of FB</th>
                  <th class="sticky">Customer Feedback Comments</th>
                  <th class="sticky">SB Response</th>
                  <th class="sticky">Feedback Completion date</th>
                  @endif

                  @if($canRemove)
                  <th class="sticky excel-actions">Actions</th>
=======
                  @if($canViewPrimary)
                    <th class="sticky">#</th>
                    <th class="sticky">Property ID</th>
                    <th class="sticky">Property Name</th>
                    <th class="sticky">Tenant/Lease ID</th>
                    <th class="sticky">Suite Id</th>
                    <th class="sticky">Tenant Name</th>
                    <th class="sticky">Priority</th>
                    <th class="sticky">Premises Address</th>
                    <th class="sticky">PDF Names</th>
                    <th class="sticky">No. of Documents</th>
                   
                    <th class="sticky">Status</th>
                    <th class="sticky">Type of Lease</th>
                    <th class="sticky">Type of Work</th>
                    <th class="sticky">Language</th>
                    <th class="sticky">Request Received Date</th>
                    <th class="sticky">Delivered Date</th>
                    <th class="sticky">Property Manager</th>
                  @endif

                  @if($canViewQueries)
                    <th class="sticky">Queries</th>
                  @endif

                  @if($canViewProduction)
                    @if($showProd_Abstractor)       <th class="sticky">Abstractor</th> @endif
                    @if($showProd_AbsStart)         <th class="sticky">Abstraction Start Date</th> @endif
                    @if($showProd_AbsComplete)      <th class="sticky">Abstract Completion Date</th> @endif
                    @if($showProd_Reviewer)         <th class="sticky">Reviewer</th> @endif
                    @if($showProd_ReviewComplete)   <th class="sticky">Review Start Date</th> @endif
                    @if($showProd_ReviewComplete)   <th class="sticky">Review Completion Date</th> @endif
                    @if($showProd_Sense)            <th class="sticky">Sense Check / DDR</th> @endif
                    @if($showProd_SenseComplete)    <th class="sticky">Sense Check / DDR Start Date</th> @endif
                    @if($showProd_SenseComplete)    <th class="sticky">Sense Check / DDR Completion Date</th> @endif
                    @if($showProd_Proposed)         <th class="sticky">Proposed Delivery Date</th> @endif
                    @if($showProd_Actual)           <th class="sticky">Actual Delivered Date</th> @endif
                    @if($showProd_FeedbackReceived) <th class="sticky">Feedback received Date</th> @endif
                  @endif

                  @if($canViewBilling)
                    <th class="sticky">Feedback completion date</th>
                    <th class="sticky">Billing Month</th>
                    <th class="sticky">No. of Pages (If non-English)</th>
                    <th class="sticky">Invoice Method</th>
                   
                  @endif

                  @if($canViewCustomerFB)
                    <th class="sticky">Date of Feedback Received</th>
                    <th class="sticky">Feedback - Customer Name</th>
                    <th class="sticky">Category of FB</th>
                    <th class="sticky">Customer Feedback Comments</th>
                    <th class="sticky">SB Response</th>
                    <th class="sticky">Feedback Completion date</th>
                  @endif

                  @if($canRemove)
                    <th class="sticky excel-actions">Actions</th>
>>>>>>> 9d9ed85b (for cleaner setup)
                  @endif
                </tr>
              </thead>

              <tbody id="excel-tbody">
                @for($i=0; $i < $rowCount; $i++)
<<<<<<< HEAD
                  @php $model=($rows[$i] ?? null); @endphp
                  <tr>
                  {{-- Always include the hidden id for update semantics --}}
                  <input type="hidden" name="intake_id[]" value="{{ $model->id ?? '' }}" />

                  @if($canPrimary)
                  <td data-row-idx>{{ $i+1 }}</td>

                  <td><input type="text" name="property_id[]" class="form-control"
                      value="{{ rowVal('property_id',$i,$model,'property_id') }}"></td>

                  <td><input type="text" name="property_name[]" class="form-control"
                      value="{{ rowVal('property_name',$i,$model,'property_name') }}"></td>

                  <td><input type="text" name="tenant_or_lease_id[]" class="form-control"
                      value="{{ rowVal('tenant_or_lease_id',$i,$model,'tenant_or_lease_id') }}"></td>

                  <td><input type="text" name="tenant_name[]" class="form-control"
                      value="{{ rowVal('tenant_name',$i,$model,'tenant_name') }}"></td>


                  @php $currentPriority = rowVal('priority_id',$i,$model,'priority_id'); @endphp
                  <td>
                    <select name="priority_id[]" class="form-select select2">
                      <option value="">Select</option>
                      @for($n = 1; $n <= 6; $n++)
                        <option value="{{ $n }}" {{ (string)$currentPriority === (string)$n ? 'selected' : '' }}>
                        {{ $n }}
                        </option>
                        @endfor
                    </select>
                  </td>

                  <td><input type="text" name="premises_address[]" class="form-control"
                      value="{{ rowVal('premises_address',$i,$model,'premises_address') }}"></td>

                  <td><input type="text" name="pdf_names[]" class="form-control"
                      value="{{ rowVal('pdf_names',$i,$model,'pdf_names') }}" placeholder="Executed Lease.pdf"></td>

                  <td><input type="text" name="no_of_documents[]" class="form-control"
                      value="{{ rowVal('no_of_documents',$i,$model,'no_of_documents') }}" placeholder="0"></td>

                  <td>
                    <select name="status_master[]" class="form-select select2">
                      <option value="">Select</option>
                      @foreach(($masters['status'] ?? []) as $item)
                      <option value="{{ $item->id }}"
                        {{ (string)rowVal('status_master',$i,$model,'status_master_id')===(string)$item->id ? 'selected':'' }}>
                        {{ $item->name }}
                      </option>
                      @endforeach
                    </select>
                  </td>

                  <td>
                    <select name="type_of_lease[]" class="form-select select2">
                      <option value="">Select</option>
                      @foreach(($masters['lease_types'] ?? []) as $item)
                      <option value="{{ $item->id }}"
                        {{ (string)rowVal('type_of_lease',$i,$model,'type_of_lease_id')===(string)$item->id ? 'selected':'' }}>
                        {{ $item->name }}
                      </option>
                      @endforeach
                    </select>
                  </td>

                  <td>
                    <select name="type_of_work[]" class="form-select select2">
                      <option value="">Select</option>
                      @foreach(($masters['work_types'] ?? []) as $item)
                      <option value="{{ $item->id }}"
                        {{ (string)rowVal('type_of_work',$i,$model,'type_of_work_id')===(string)$item->id ? 'selected':'' }}>
                        {{ $item->name }}
                      </option>
                      @endforeach
                    </select>
                  </td>

                  <td>
                    <select name="language[]" class="form-select select2">
                      <option value="">Select</option>
                      
                      @foreach(($masters['languages'] ?? []) as $item)
                        <option value="{{ $item->id }}"
                          {{ (string)rowVal('language',$i,$model,'language_code')===(string)$item->id ? 'selected':'' }}>
                          {{ $item->name }}
                        </option>
                      @endforeach
                    </select>
                  </td>

                  <td><input type="text" name="request_received_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                      value="{{ rowVal('request_received_date',$i,$model,'request_received_date') }}" autocomplete="off"></td>

                  <td><input type="text" name="delivered_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                      value="{{ rowVal('delivered_date',$i,$model,'delivered_date') }}" autocomplete="off"></td>

                  <td>
                    <select name="property_manager_id[]" class="form-select select2">
                      <option value="">Select</option>
                      @foreach(($project_managers ?? collect()) as $u)
                      @php
                      $fullName = trim(($u->first_name ?? '').' '.($u->last_name ?? ''));
                      $sel = (string)rowVal('property_manager_id', $i, $model, 'property_manager_id') === (string)$u->id ? 'selected' : '';
                      @endphp
                      <option value="{{ $u->id }}" {{ $sel }}>{{ $fullName }}</option>
                      @endforeach
                    </select>
                  </td>
                  @endif

                  @if($canQueries)
                  @php $currentQueryType = rowVal('type_of_queries',$i,$model,'type_of_queries'); @endphp
                  <td>
                    <select name="type_of_queries[]" class="form-select select2">
                      <option value="">Select</option>
                      @foreach(($masters['intake_query'] ?? []) as $item)
                      <option value="{{ $item->id }}" {{ (string)$currentQueryType === (string)$item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                      </option>
                      @endforeach
                    </select>
                  </td>

                  <td><input type="text" name="sb_queries[]" class="form-control"
                      value="{{ rowVal('sb_queries',$i,$model,'sb_queries') }}"></td>

                  <td><input type="text" name="client_response[]" class="form-control"
                      value="{{ rowVal('client_response',$i,$model,'client_response') }}"></td>

                  <td>
                    <select name="query_status[]" class="form-select select2">
                      <option value="">Select</option>
                      @foreach(($masters['query_status'] ?? []) as $item)
                      <option value="{{ $item->id }}"
                        {{ (string)rowVal('query_status',$i,$model,'query_status_id')===(string)$item->id ? 'selected':'' }}>
                        {{ $item->name }}
                      </option>
                      @endforeach
                    </select>
                  </td>

                  <td><input type="text" name="query_raised_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                      value="{{ rowVal('query_raised_date',$i,$model,'query_raised_date') }}" autocomplete="off"></td>

                  <td><input type="text" name="query_resolved_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                      value="{{ rowVal('query_resolved_date',$i,$model,'query_resolved_date') }}" autocomplete="off"></td>
                  @endif

                  @if($canProduction)
                  <td>
                    <select name="abstractor[]" class="form-select select2">
                      <option value="">Select</option>
                      @foreach(($abstractor_users ?? []) as $u)
                      @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                      <option value="{{ $u->id }}"
                        {{ (string)rowVal('abstractor',$i,$model,'abstractor_id')===(string)$u->id ? 'selected':'' }}>
                        {{ $name }}
                      </option>
                      @endforeach
                    </select>
                  </td>

                  <td><input type="text" name="abstraction_start_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                      value="{{ rowVal('abstraction_start_date',$i,$model,'abstraction_start_date') }}" autocomplete="off"></td>

                  <td><input type="text" name="abstract_completion_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                      value="{{ rowVal('abstract_completion_date',$i,$model,'abstract_completion_date') }}" autocomplete="off"></td>

                  <td>
                    <select name="reviewer[]" class="form-select select2">
                      <option value="">Select</option>
                      @foreach(($reviewer ?? []) as $u)
                      @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                      <option value="{{ $u->id }}"
                        {{ (string)rowVal('reviewer',$i,$model,'reviewer_id')===(string)$u->id ? 'selected':'' }}>
                        {{ $name }}
                      </option>
                      @endforeach
                    </select>
                  </td>

                  <td><input type="text" name="review_completion_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                      value="{{ rowVal('review_completion_date',$i,$model,'review_completion_date') }}" autocomplete="off"></td>

                  <td>
                    <select name="sense_check_ddr[]" class="form-select select2">
                      <option value="">Select</option>
                      @foreach(($sense_check ?? []) as $u)
                      @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                      <option value="{{ $u->id }}"
                        {{ (string)rowVal('sense_check_ddr',$i,$model,'sense_check_ddr_id')===(string)$u->id ? 'selected':'' }}>
                        {{ $name }}
                      </option>
                      @endforeach
                    </select>
                  </td>

                  <td><input type="text" name="sense_check_completion_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                      value="{{ rowVal('sense_check_completion_date',$i,$model,'sense_check_completion_date') }}" autocomplete="off"></td>

                  <td><input type="text" name="proposed_delivery_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                      value="{{ rowVal('proposed_delivery_date',$i,$model,'proposed_delivery_date') }}" autocomplete="off"></td>

                  <td><input type="text" name="actual_delivered_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                      value="{{ rowVal('actual_delivered_date',$i,$model,'actual_delivered_date') }}" autocomplete="off"></td>

                  <td><input type="text" name="feedback_received_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                      value="{{ rowVal('feedback_received_date',$i,$model,'feedback_received_date') }}" autocomplete="off"></td>
                  @endif

                  @if($canBilling)
                  <td><input type="text" name="feedback_completion_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                      value="{{ rowVal('feedback_completion_date',$i,$model,'feedback_completion_date') }}" autocomplete="off"></td>

                  <td><input type="text" name="billing_month[]" class="form-control js-month ym" placeholder="YYYY-MM"
                      value="{{ rowVal('billing_month',$i,$model,'billing_month') }}" autocomplete="off"></td>

                  <td>
                    <select name="invoice_format[]" class="form-select select2">
                      <option value="">Select</option>
                      @foreach(($masters['invoice_formats'] ?? []) as $item)
                      <option value="{{ $item->id }}"
                        {{ (string)rowVal('invoice_format',$i,$model,'invoice_format_id')===(string)$item->id ? 'selected':'' }}>
                        {{ $item->name }}
                      </option>
                      @endforeach
                    </select>
                  </td>

                  <td><input type="text" name="non_english_pages[]" class="form-control"
                      value="{{ rowVal('non_english_pages',$i,$model,'non_english_pages') }}" placeholder="0"></td>
                  @endif

                  @if($canCustomerFB)
                  <td><input type="text" name="fb_date_received[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                      value="{{ rowVal('fb_date_received',$i,$model,'fb_date_received') }}" autocomplete="off"></td>

                  <td><input type="text" name="fb_customer_name[]" class="form-control"
                      value="{{ rowVal('fb_customer_name',$i,$model,'fb_customer_name') }}"></td>

                  <td>
                    <select name="fb_category_id[]" class="form-select select2">
                      <option value="">Select</option>
                      @foreach(($masters['feedback_categories'] ?? []) as $item)
                      <option value="{{ $item->id }}"
                        {{ (string)rowVal('fb_category_id',$i,$model,'fb_category_id')===(string)$item->id ? 'selected':'' }}>
                        {{ $item->name }}
                      </option>
                      @endforeach
                    </select>
                  </td>

                  <td><textarea name="fb_customer_comments[]" class="form-control" rows="1">{{ rowVal('fb_customer_comments',$i,$model,'fb_customer_comments') }}</textarea></td>

                  <td><textarea name="fb_sb_response[]" class="form-control" rows="1">{{ rowVal('fb_sb_response',$i,$model,'fb_sb_response') }}</textarea></td>

                  <td><input type="text" name="fb_feedback_completion_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD"
                      value="{{ rowVal('fb_feedback_completion_date',$i,$model,'fb_feedback_completion_date') }}" autocomplete="off"></td>
                  @endif

                  @if($canRemove)
                  <td class="excel-actions">
                    <button type="button" class="btn btn-danger" data-remove-row>-</button>
                  </td>
                  @endif
                  </tr>
                  @endfor
=======
                  @php $model = ($rows[$i] ?? null); @endphp
                  <tr>
                    <input type="hidden" name="intake_id[]" value="{{ $model->id ?? '' }}" />

                    @if($canViewPrimary)
                      <td data-row-idx>{{ $i+1 }}</td>
                      <td><input type="text" name="property_id[]" class="form-control" value="{{ rowVal('property_id',$i,$model,'property_id') }}" {{ roInput($canEditPrimary) }}></td>
                      <td><input type="text" name="property_name[]" class="form-control" value="{{ rowVal('property_name',$i,$model,'property_name') }}" {{ roInput($canEditPrimary) }}></td>
                      <td><input type="text" name="tenant_or_lease_id[]" class="form-control" value="{{ rowVal('tenant_or_lease_id',$i,$model,'tenant_or_lease_id') }}" {{ roInput($canEditPrimary) }}></td>
                      <td><input type="text" name="suite_id[]" class="form-control" value="{{ rowVal('suite_id',$i,$model,'suite_id') }}" placeholder="Enter Suite Id" {{ roInput($canEditPrimary) }}></td>
                      <td><input type="text" name="tenant_name[]" class="form-control" value="{{ rowVal('tenant_name',$i,$model,'tenant_name') }}" {{ roInput($canEditPrimary) }}></td>

                      @php $currentPriority = rowVal('priority_id',$i,$model,'priority_id'); @endphp
                      <td>
                        <select name="priority_id[]" class="form-select select2" {!! roSelect($canEditPrimary) !!}>
                          <option value="">Select</option>
                          @for($n=1;$n<=6;$n++)
                            <option value="{{ $n }}" {{ (string)$currentPriority===(string)$n ? 'selected':'' }}>{{ $n }}</option>
                          @endfor
                        </select>
                      </td>

                      <td><input type="text" name="premises_address[]" class="form-control" value="{{ rowVal('premises_address',$i,$model,'premises_address') }}" {{ roInput($canEditPrimary) }}></td>
                      <td><input type="text" name="pdf_names[]" class="form-control" value="{{ rowVal('pdf_names',$i,$model,'pdf_names') }}" placeholder="Executed Lease.pdf" {{ roInput($canEditPrimary) }}></td>
                      <td><input type="text" name="no_of_documents[]" class="form-control" value="{{ rowVal('no_of_documents',$i,$model,'no_of_documents') }}" placeholder="0" {{ roInput($canEditPrimary) }}></td>

                      

                      <td>
                        <select name="status_master[]" class="form-select select2" {!! roSelect($canEditPrimary) !!}>
                          <option value="">Select</option>
                          @foreach(($masters['status'] ?? []) as $item)
                            <option value="{{ $item->id }}" {{ (string)rowVal('status_master',$i,$model,'status_master_id')===(string)$item->id ? 'selected':'' }}>
                              {{ $item->name }}
                            </option>
                          @endforeach
                        </select>
                      </td>

                      <td>
                        <select name="type_of_lease[]" class="form-select select2" {!! roSelect($canEditPrimary) !!}>
                          <option value="">Select</option>
                          @foreach(($masters['lease_types'] ?? []) as $item)
                            <option value="{{ $item->id }}" {{ (string)rowVal('type_of_lease',$i,$model,'type_of_lease_id')===(string)$item->id ? 'selected':'' }}>
                              {{ $item->name }}
                            </option>
                          @endforeach
                        </select>
                      </td>

                      <td>
                        <select name="type_of_work[]" class="form-select select2" {!! roSelect($canEditPrimary) !!}>
                          <option value="">Select</option>
                          @foreach(($masters['work_types'] ?? []) as $item)
                            <option value="{{ $item->id }}" {{ (string)rowVal('type_of_work',$i,$model,'type_of_work_id')===(string)$item->id ? 'selected':'' }}>
                              {{ $item->name }}
                            </option>
                          @endforeach
                        </select>
                      </td>

                      <td>
                        <select name="language[]" class="form-select select2" {!! roSelect($canEditPrimary) !!}>
                          <option value="">Select</option>
                          @foreach(($masters['languages'] ?? []) as $item)
                            <option value="{{ $item->id }}" {{ (string)rowVal('language',$i,$model,'language_code')===(string)$item->id ? 'selected':'' }}>
                              {{ $item->name }}
                            </option>
                          @endforeach
                        </select>
                      </td>

                      {{-- MM-DD-YYYY display --}}
                      <td><input type="text" name="request_received_date[]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(rowVal('request_received_date',$i,$model,'request_received_date')) }}" autocomplete="off" {{ roInput($canEditPrimary) }}></td>
                      <td><input type="text" name="delivered_date[]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(rowVal('delivered_date',$i,$model,'delivered_date')) }}" autocomplete="off" {{ roInput($canEditPrimary) }}></td>

                      <td>
                        <select name="property_manager_id[]" class="form-select select2" {!! roSelect($canEditPrimary) !!}>
                          <option value="">Select</option>
                          @foreach(($project_managers ?? collect()) as $u)
                            @php $fullName = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                            <option value="{{ $u->id }}" {{ (string)rowVal('property_manager_id',$i,$model,'property_manager_id')===(string)$u->id ? 'selected':'' }}>
                              {{ $fullName }}
                            </option>
                          @endforeach
                        </select>
                      </td>
                    @endif

                    @if($canViewQueries)
                      <td class="text-center">
                        @php 
                        $open = (int)($model->open_queries_count ?? 0); 
                        $has = $open > 0; 
                        $intakeId = $model->id ?? ''; @endphp
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm btn-open-queries"
                                data-intake-id="{{ $intakeId }}"
                                {{ $intakeId ? '' : 'disabled' }}>
                          <i class="ti ti-message-question me-1"></i>
                          Queries
                          <span class="q-badge {{ $open ? 'q-badge-danger' : 'q-badge-muted' }}">
                            {{ $open }}
                          </span>
                        </button>
                      </td>
                    @endif

                    @if($canViewProduction)
                      @if($showProd_Abstractor)
                        <td>
                          <select name="abstractor[]" class="form-select select2" {!! roSelect($canEditProduction && !$lockAbsSelectForSelf) !!} {{ $lockAbsSelectForSelf ? 'data-readonly="1"' : '' }}>
                            <option value="">Select</option>
                            @foreach(($abstractor_users ?? []) as $u)
                              @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                              <option value="{{ $u->id }}" {{ (string)rowVal('abstractor',$i,$model,'abstractor_id')===(string)$u->id ? 'selected':'' }}>{{ $name }}</option>
                            @endforeach
                          </select>
                        </td>
                      @endif

                      @if($showProd_AbsStart)
                        <td><input type="text" name="abstraction_start_date[]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(rowVal('abstraction_start_date',$i,$model,'abstraction_start_date')) }}" autocomplete="off" {{ roInput($canEditProduction) }}></td>
                      @endif

                      @if($showProd_AbsComplete)
                        <td><input type="text" name="abstract_completion_date[]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(rowVal('abstract_completion_date',$i,$model,'abstract_completion_date')) }}" autocomplete="off" {{ roInput($canEditProduction) }}></td>
                      @endif

                      @if($showProd_Reviewer)
                        <td>
                          <select name="reviewer[]" class="form-select select2" {!! roSelect($canEditProduction && !$lockRevSelectForSelf) !!} {{ $lockRevSelectForSelf ? 'data-readonly="1"' : '' }}>
                            <option value="">Select</option>
                            @foreach(($reviewer ?? []) as $u)
                              @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                              <option value="{{ $u->id }}" {{ (string)rowVal('reviewer',$i,$model,'reviewer_id')===(string)$u->id ? 'selected':'' }}>{{ $name }}</option>
                            @endforeach
                          </select>
                        </td>
                      @endif

                      @if($showProd_ReviewComplete)
                        <td><input type="text" name="review_start_date[]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(rowVal('review_start_date',$i,$model,'review_start_date')) }}" autocomplete="off" {{ roInput($canEditProduction) }}></td>
                      @endif

                      @if($showProd_ReviewComplete)
                        <td><input type="text" name="review_completion_date[]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(rowVal('review_completion_date',$i,$model,'review_completion_date')) }}" autocomplete="off" {{ roInput($canEditProduction) }}></td>
                      @endif

                      @if($showProd_Sense)
                        <td>
                          <select name="sense_check_ddr[]" class="form-select select2" {!! roSelect($canEditProduction && !$lockSenseSelectForSelf) !!} {{ $lockSenseSelectForSelf ? 'data-readonly="1"' : '' }}>
                            <option value="">Select</option>
                            @foreach(($sense_check ?? []) as $u)
                              @php $name = trim(($u->first_name ?? '').' '.($u->last_name ?? '')); @endphp
                              <option value="{{ $u->id }}" {{ (string)rowVal('sense_check_ddr',$i,$model,'sense_check_ddr_id')===(string)$u->id ? 'selected':'' }}>{{ $name }}</option>
                            @endforeach
                          </select>
                        </td>
                      @endif

                      @if($showProd_SenseComplete)
                        <td><input type="text" name="sense_check_start_date[]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(rowVal('sense_check_start_date',$i,$model,'sense_check_start_date')) }}" autocomplete="off" {{ roInput($canEditProduction) }}></td>
                      @endif

                      @if($showProd_SenseComplete)
                        <td><input type="text" name="sense_check_completion_date[]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(rowVal('sense_check_completion_date',$i,$model,'sense_check_completion_date')) }}" autocomplete="off" {{ roInput($canEditProduction) }}></td>
                      @endif

                      @if($showProd_Proposed)
                        <td><input type="text" name="proposed_delivery_date[]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(rowVal('proposed_delivery_date',$i,$model,'proposed_delivery_date')) }}" autocomplete="off" {{ roInput($canEdit_Proposed) }}></td>
                      @endif

                      @if($showProd_Actual)
                        <td><input type="text" name="actual_delivered_date[]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(rowVal('actual_delivered_date',$i,$model,'actual_delivered_date')) }}" autocomplete="off" {{ roInput($canEdit_Actual) }}></td>
                      @endif

                      @if($showProd_FeedbackReceived)
                        <td><input type="text" name="feedback_received_date[]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(rowVal('feedback_received_date',$i,$model,'feedback_received_date')) }}" autocomplete="off" {{ roInput($canEdit_FbReceived) }}></td>
                      @endif
                    @endif

                    @if($canViewBilling)
                      <td><input type="text" name="feedback_completion_date[]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(rowVal('feedback_completion_date',$i,$model,'feedback_completion_date')) }}" autocomplete="off" {{ roInput($canEdit_FbCompletion) }}></td>
                      <td><input type="text" name="billing_month[]" class="form-control js-month ym" placeholder="MM-YYYY" value="{{ fmtMY(rowVal('billing_month',$i,$model,'billing_month')) }}" autocomplete="off" {{ roInput($canEditBilling) }}></td>
                      <td><input type="text" name="non_english_pages[]" class="form-control" value="{{ rowVal('non_english_pages',$i,$model,'non_english_pages') }}" placeholder="0" {{ roInput($canEditBilling) }}></td>
                      <td>
                        <select name="invoice_format[]" class="form-select select2" {!! roSelect($canEditBilling) !!}>
                          <option value="">Select</option>
                          @foreach(($masters['invoice_formats'] ?? []) as $item)
                            <option value="{{ $item->id }}" {{ (string)rowVal('invoice_format',$i,$model,'invoice_format_id')===(string)$item->id ? 'selected':'' }}>
                              {{ $item->name }}
                            </option>
                          @endforeach
                        </select>
                      </td>
                     
                    @endif

                    @if($canViewCustomerFB)
                      {{-- ======= Customer Feedback block with new per-field permissions ======= --}}
                      <td><input type="text" name="fb_date_received[]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(rowVal('fb_date_received',$i,$model,'fb_date_received')) }}" autocomplete="off" {{ roInput($canEditCF_DateReceived) }}></td>
                      <td><input type="text" name="fb_customer_name[]" class="form-control" value="{{ rowVal('fb_customer_name',$i,$model,'fb_customer_name') }}" {{ roInput($canEditCF_CustomerName) }}></td>
                      <td>
                        <select name="fb_category_id[]" class="form-select select2" {!! roSelect($canEditCF_Category) !!}>
                          <option value="">Select</option>
                          @foreach(($masters['feedback_categories'] ?? []) as $item)
                            <option value="{{ $item->id }}" {{ (string)rowVal('fb_category_id',$i,$model,'fb_category_id')===(string)$item->id ? 'selected':'' }}>
                              {{ $item->name }}
                            </option>
                          @endforeach
                        </select>
                      </td>
                      <td><textarea name="fb_customer_comments[]" class="form-control" rows="1" {{ roInput($canEditCF_Comments) }}>{{ rowVal('fb_customer_comments',$i,$model,'fb_customer_comments') }}</textarea></td>
                      <td><textarea name="fb_sb_response[]" class="form-control" rows="1" {{ roInput($canEditCF_SBResponse) }}>{{ rowVal('fb_sb_response',$i,$model,'fb_sb_response') }}</textarea></td>
                      <td><input type="text" name="fb_feedback_completion_date[]" class="form-control js-date ymd" placeholder="MM-DD-YYYY" value="{{ fmtMDY(rowVal('fb_feedback_completion_date',$i,$model,'fb_feedback_completion_date')) }}" autocomplete="off" {{ roInput($canEditCF_CompletionDate) }}></td>
                      {{-- ======= /Customer Feedback block ======= --}}
                    @endif

                    @if($canRemove)
                      <td class="excel-actions">
                        <button type="button" class="btn btn-danger" data-remove-row {{ $canSubmit ? '' : 'disabled' }}>-</button>
                      </td>
                    @endif
                  </tr>
                @endfor
>>>>>>> 9d9ed85b (for cleaner setup)
              </tbody>
            </table>
          </div>

          <div class="text-end mt-3">
<<<<<<< HEAD
            <a href="{{ $backUrl ?? '#' }}" class="btn btn-secondary">
              <i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>Back
            </a>


            <button type="submit" class="btn btn-primary">
              {{ $type == 'create' ? 'Save' : 'Update' }}
              <i class="ti ti-file-upload ms-1 mb-1"></i>
            </button>

=======
            <a href="/projects" class="btn btn-secondary">
              <i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>Back
            </a>
            <button type="submit" class="btn btn-primary" {{ $canSubmit ? '' : 'disabled' }}>
              {{ $type == 'create' ? 'Save' : 'Update' }}
              <i class="ti ti-file-upload ms-1 mb-1"></i>
            </button>
>>>>>>> 9d9ed85b (for cleaner setup)
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<<<<<<< HEAD
{{-- ========= Template for new rows ========= --}}
<template id="row-template">
  <tr>
    {{-- Keep id hidden for newly added rows (blank) --}}
    <input type="hidden" name="intake_id[]" value="" />

    @if($canPrimary)
    <td data-row-idx></td>
    {{-- <td><input type="text" name="client_name[]" class="form-control"></td>
      <td><input type="text" name="project_name[]" class="form-control"></td> --}}
    {{-- Project Manager (old commented block kept as reference) --}}
    {{-- <td>
        <select name="project_manager[]" class="form-select select2 int-id">
          <option value="">Select</option>
          @foreach(($project_managers ?? []) as $u)
            <option value="{{ $u->id }}" @if(!empty($defaultManagerIds) && in_array($u->id, $defaultManagerIds)) selected @endif>
    {{ $u->first_name . ' ' . $u->last_name }}
    </option>
    @endforeach
    </select>
    </td> --}}

    <td><input type="text" name="property_id[]" class="form-control"></td>
    <td><input type="text" name="property_name[]" class="form-control"></td>
    <td><input type="text" name="tenant_or_lease_id[]" class="form-control"></td>
    <td><input type="text" name="tenant_name[]" class="form-control"></td>

    <td>
      <select name="priority_id[]" class="form-select select2">
        <option value="">Select</option>
        @for($n = 1; $n <= 6; $n++)
          <option value="{{ $n }}">{{ $n }}</option>
          @endfor
      </select>
    </td>

    <td><input type="text" name="premises_address[]" class="form-control"></td>
    <td><input type="text" name="pdf_names[]" class="form-control" placeholder="Executed Lease.pdf"></td>
    <td><input type="text" name="no_of_documents[]" class="form-control int-nonneg" placeholder="0"></td>

    <td>
      <select name="status_master[]" class="form-select select2">
        <option value="">Select</option>
        @foreach(($masters['status'] ?? []) as $item)
        <option value="{{ $item->id }}">{{ $item->name }}</option>
        @endforeach
      </select>
    </td>

    <td>
      <select name="type_of_lease[]" class="form-select select2">
        <option value="">Select</option>
        @foreach(($masters['lease_types'] ?? []) as $item)
        <option value="{{ $item->id }}">{{ $item->name }}</option>
        @endforeach
      </select>
    </td>

    <td>
      <select name="type_of_work[]" class="form-select select2">
        <option value="">Select</option>
        @foreach(($masters['work_types'] ?? []) as $item)
        <option value="{{ $item->id }}">{{ $item->name }}</option>
        @endforeach
      </select>
    </td>

    <td>
      <select name="language[]" class="form-select select2">
        <option value="">Select</option>
        @foreach(($masters['languages'] ?? []) as $item)
        <option value="{{ $item->code }}">{{ $item->name }}</option>
        @endforeach
      </select>
    </td>

    <td><input type="text" name="request_received_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD" autocomplete="off"></td>
    <td><input type="text" name="delivered_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD" autocomplete="off"></td>

    <td>
      <select name="property_manager_id[]" class="form-select select2 int-id">
        <option value="">Select</option>
        @foreach(($project_managers ?? []) as $u)
        <option value="{{ $u->id }}" @if(!empty($defaultManagerIds) && in_array($u->id, $defaultManagerIds)) selected @endif>
          {{ $u->first_name . ' ' . $u->last_name }}
        </option>
        @endforeach
      </select>
    </td>
    @endif

    @if($canQueries)
    <td>
      <select name="type_of_queries[]" class="form-select select2 int-id">
        <option value="">Select</option>
        @foreach(($masters['intake_query'] ?? []) as $item)
        <option value="{{ $item->id }}">{{ $item->name }}</option>
        @endforeach
      </select>
    </td>
    <td><input type="text" name="sb_queries[]" class="form-control"></td>
    <td><input type="text" name="client_response[]" class="form-control"></td>

    <td>
      <select name="query_status[]" class="form-select select2 int-id">
        <option value="">Select</option>
        @foreach(($masters['query_status'] ?? []) as $item)
        <option value="{{ $item->id }}">{{ $item->name }}</option>
        @endforeach
      </select>
    </td>

    <td><input type="text" name="query_raised_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD" autocomplete="off"></td>
    <td><input type="text" name="query_resolved_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD" autocomplete="off"></td>
    @endif

    @if($canProduction)
    <td>
      <select name="abstractor[]" class="form-select select2 int-id">
        <option value="">Select</option>
        @foreach(($abstractor_users ?? []) as $u)
        <option value="{{ $u->id }}">{{ $u->first_name . ' ' . $u->last_name }}</option>
        @endforeach
      </select>
    </td>

    <td><input type="text" name="abstraction_start_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD" autocomplete="off"></td>
    <td><input type="text" name="abstract_completion_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD" autocomplete="off"></td>

    <td>
      <select name="reviewer[]" class="form-select select2 int-id">
        <option value="">Select</option>
        @foreach(($reviewer ?? []) as $u)
        <option value="{{ $u->id }}">{{ $u->first_name . ' ' . $u->last_name }}</option>
        @endforeach
      </select>
    </td>

    <td><input type="text" name="review_completion_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD" autocomplete="off"></td>

    <td>
      <select name="sense_check_ddr[]" class="form-select select2 int-id">
        <option value="">Select</option>
        @foreach(($sense_check ?? []) as $u)
        <option value="{{ $u->id }}">{{ $u->first_name . ' ' . $u->last_name }}</option>
        @endforeach
      </select>
    </td>

    <td><input type="text" name="sense_check_completion_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD" autocomplete="off"></td>

    <td><input type="text" name="proposed_delivery_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD" autocomplete="off"></td>
    <td><input type="text" name="actual_delivered_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD" autocomplete="off"></td>
    <td><input type="text" name="feedback_received_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD" autocomplete="off"></td>
    @endif

    @if($canBilling)
    <td><input type="text" name="feedback_completion_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD" autocomplete="off"></td>
    <td><input type="text" name="billing_month[]" class="form-control js-month ym" placeholder="YYYY-MM" autocomplete="off"></td>

    <td>
      <select name="invoice_format[]" class="form-select select2 int-id">
        <option value="">Select</option>
        @foreach(($masters['invoice_formats'] ?? []) as $item)
        <option value="{{ $item->id }}">{{ $item->name }}</option>
        @endforeach
      </select>
    </td>

    <td><input type="text" name="non_english_pages[]" class="form-control int-nonneg" placeholder="0"></td>
    @endif

    @if($canCustomerFB)
    <td><input type="text" name="fb_date_received[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD" autocomplete="off"></td>
    <td><input type="text" name="fb_customer_name[]" class="form-control"></td>

    <td>
      <select name="fb_category_id[]" class="form-select select2 int-id">
        <option value="">Select</option>
        @foreach(($masters['feedback_categories'] ?? []) as $item)
        <option value="{{ $item->id }}">{{ $item->name }}</option>
        @endforeach
      </select>
    </td>

    <td><textarea name="fb_customer_comments[]" class="form-control" rows="1"></textarea></td>
    <td><textarea name="fb_sb_response[]" class="form-control" rows="1"></textarea></td>
    <td><input type="text" name="fb_feedback_completion_date[]" class="form-control js-date ymd" placeholder="YYYY-MM-DD" autocomplete="off"></td>
    @endif

    @if($canRemove)
    <td class="excel-actions"><button type="button" class="btn btn-danger" data-remove-row>-</button></td>
    @endif
  </tr>
</template>

@endsection
=======
{{-- ===== Modal: Queries Repeater (separate table) ===== --}}
<div class="modal fade" id="queriesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <form id="queriesForm" method="POST" action="{{ route('intake-queries.store') }}">
        @csrf
        <input type="hidden" name="intake_id" id="q_intake_id">

        <div class="modal-header">
          <h5 class="modal-title" id="queriesModalLabel">Queries</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div id="queriesFormErrors" class="alert alert-danger d-none"></div>
          <div id="queriesFormSuccess" class="alert alert-success d-none"></div>
          @can('create query')
          <div class="d-flex justify-content-end mb-2">
            <button type="button" class="btn btn-sm btn-primary" id="btnAddQueryRow">+ Add Query</button>
          </div>
          @endcan
          <div class="scroll-y">
            <div id="queriesRows"></div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btnSaveQueries">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<template id="queryRowTpl">
  <div class="card mb-2 query-row">
    <div class="card-body">
      <div class="row g-2 align-items-end">
        <div class="col-md-3">
          <label class="form-label">Type of Query <span class="text-danger">*</span></label>
          <select name="queries[__IDX__][type_id]" class="form-select select2 q-type">
            <option value="">Select</option>
            @foreach(($masters['intake_query'] ?? []) as $it)
              <option value="{{ $it->id }}">{{ $it->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Status <span class="text-danger">*</span></label>
          <select name="queries[__IDX__][status_id]" class="form-select select2 q-status">
            <option value="">Select</option>
            @foreach(($masters['query_status'] ?? []) as $st)
              <option value="{{ $st->id }}">{{ $st->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Raised Date</label>
          <input type="text" name="queries[__IDX__][raised_date]" class="form-control js-date ymd q-raised" placeholder="MM-DD-YYYY" autocomplete="off">
        </div>

        <div class="col-md-6">
          <label class="form-label">SB Queries <span class="text-danger">*</span></label>
          <textarea name="queries[__IDX__][sb_query]" class="form-control q-text" rows="1" placeholder="Enter query"></textarea>
        </div>

        <div class="col-md-5">
          <label class="form-label">Client Response</label>
          {{-- editable only for customer --}}
          <textarea name="queries[__IDX__][client_response]" class="form-control q-client-response" rows="1" placeholder="Enter client response (optional)" {{ roInput($isCustomer) }}></textarea>
        </div>

        <div class="col-md-1 d-flex justify-content-end">
          <button type="button" class="btn btn-outline-danger btn-sm btnRemoveQueryRow" title="Remove">-</button>
        </div>
        <input type="hidden" class="q-id" name="queries[__IDX__][id]" value="">
      </div>
    </div>
  </div>
</template>
@endsection

@section('extra-script')
<script>
$(function () {
  if (typeof $ === 'undefined') { console.error('jQuery not loaded'); return; }

  // ---------- feature flags ----------
  const hasSelect2    = !!$.fn.select2;
  const hasDatepicker = !!$.fn.datepicker;
  const hasValidate   = !!$.fn.validate;
  const TODAY = new Date();

  // ---------- CSRF ----------
  const $csrf = $('meta[name="csrf-token"]');
  if ($csrf.length) $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $csrf.attr('content') }});

  // ---------- helpers (dates) ----------
  function parseMDY(str){
    const m = /^(\d{2})-(\d{2})-(\d{4})$/.exec((str||'').trim());
    if(!m) return null;
    const mm=+m[1], dd=+m[2], yyyy=+m[3];
    const d = new Date(yyyy, mm-1, dd);
    return (d.getFullYear()===yyyy && d.getMonth()===mm-1 && d.getDate()===dd) ? d : null;
  }
  function fmt2(n){ return String(n).padStart(2,'0'); }
  function fmtMDY(date){ return `${fmt2(date.getMonth()+1)}-${fmt2(date.getDate())}-${date.getFullYear()}`; }
  function mdyToYmd(v){ const d=parseMDY(v); return d?`${d.getFullYear()}-${fmt2(d.getMonth()+1)}-${fmt2(d.getDate())}`:v; }
  function mmYYYYToYM(v){ const m=/^(\d{2})-(\d{4})$/.exec((v||'').trim()); return m ? (m[2]+'-'+m[1]) : v; }

  // ---------- NEW: date-pair validation + datepicker link helpers ----------
  function ensureId($el){
    if (!$el.attr('id')) $el.attr('id', 'fld_' + Math.random().toString(36).slice(2));
    return '#' + $el.attr('id');
  }
  // Add validator rules: end >= start (both optional)
  function addDatePairRules($row, startSelector, endSelector){
    const $start = $row.find(startSelector);
    const $end   = $row.find(endSelector);
    if (!$start.length || !$end.length) return;

    try { $start.rules('add', { mdyDate: true }); } catch(e){}
    try {
      const startIdSel = ensureId($start);
      $end.rules('add', { mdyDate: true, endAfterStartMDY: startIdSel });
    } catch(e){}

    $start.on('change input', function(){ if ($end.closest('form').data('validator')) $end.valid(); });
    $end.on('change input',   function(){ if ($end.closest('form').data('validator')) $end.valid(); });
  }
  // Link datepickers so "end" cannot select earlier than "start"
  function wireDatepickerMin($row, startSelector, endSelector){
    if (!hasDatepicker) return;
    const $start = $row.find(startSelector);
    const $end   = $row.find(endSelector);
    if (!$start.length || !$end.length) return;

    function setEndStartDate(){
      const d = parseMDY($start.val());
      if (d) { $end.datepicker('setStartDate', d); }
      else   { $end.datepicker('setStartDate', null); }

      const cur = parseMDY($end.val());
      if (d && cur && cur < d) {
        $end.val(fmtMDY(d));
        try { $end.datepicker('update', fmtMDY(d)); } catch(e){}
        $end.trigger('change');
      }
    }

    // initialize constraint once both have datepickers
    setTimeout(setEndStartDate, 0);

    // when start changes, update end's min selectable date
    $start.off('.pairMin').on('change.pairMin input.pairMin', setEndStartDate);
    // protect manual typing in end
    $end.off('.pairMin').on('change.pairMin', setEndStartDate);
  }
  // Bind all three pairs (per-row)
  // function bindAllRowDatePairs($scope){
  //   const $rows = ($scope && $scope.length ? $scope : $('#excel-tbody')).find('tr');
  //   $rows.each(function(){
  //     const $r = $(this);
  //     // 1) Request Received Date -> Delivered Date
  //     addDatePairRules($r, 'input[name="request_received_date[]"]', 'input[name="delivered_date[]"]');
  //     wireDatepickerMin($r, 'input[name="request_received_date[]"]', 'input[name="delivered_date[]"]');
  //     // 2) Abstraction Start Date -> Abstract Completion Date
  //     addDatePairRules($r, 'input[name="abstraction_start_date[]"]', 'input[name="abstract_completion_date[]"]');
  //     wireDatepickerMin($r, 'input[name="abstraction_start_date[]"]', 'input[name="abstract_completion_date[]"]');
  //     // 3) Date of Feedback Received -> Feedback Completion date
  //     addDatePairRules($r, 'input[name="fb_date_received[]"]', 'input[name="fb_feedback_completion_date[]"]');
  //     wireDatepickerMin($r, 'input[name="fb_date_received[]"]', 'input[name="fb_feedback_completion_date[]"]');
  //   });
  // }
  function bindAllRowDatePairs($scope){
    const $rows = ($scope && $scope.length ? $scope : $('#excel-tbody')).find('tr');
    $rows.each(function(){
      const $r = $(this);
      // 1) Request Received Date -> Delivered Date
      addDatePairRules($r, 'input[name="request_received_date[]"]', 'input[name="delivered_date[]"]');
      wireDatepickerMin($r, 'input[name="request_received_date[]"]', 'input[name="delivered_date[]"]');

      // 2) Abstraction Start Date -> Abstract Completion Date
      addDatePairRules($r, 'input[name="abstraction_start_date[]"]', 'input[name="abstract_completion_date[]"]');
      wireDatepickerMin($r, 'input[name="abstraction_start_date[]"]', 'input[name="abstract_completion_date[]"]');

      // 3) Date of Feedback Received -> Feedback Completion date
      addDatePairRules($r, 'input[name="fb_date_received[]"]', 'input[name="fb_feedback_completion_date[]"]');
      wireDatepickerMin($r, 'input[name="fb_date_received[]"]', 'input[name="fb_feedback_completion_date[]"]');

      // NEW 4) Review Start Date -> Review Completion Date
      addDatePairRules($r, 'input[name="review_start_date[]"]', 'input[name="review_completion_date[]"]');
      wireDatepickerMin($r, 'input[name="review_start_date[]"]', 'input[name="review_completion_date[]"]');

      // NEW 5) Sense Check Start Date -> Sense Check Completion Date
      addDatePairRules($r, 'input[name="sense_check_start_date[]"]', 'input[name="sense_check_completion_date[]"]');
      wireDatepickerMin($r, 'input[name="sense_check_start_date[]"]', 'input[name="sense_check_completion_date[]"]');
    });
  }


  // ---------- Select2 cleanup ----------
  function cleanSelect2ArtifactsSafe($scope){
    $scope.find('select.select2').each(function(){
      const $s = $(this);
      $s.next('.select2').remove();
      $s.removeClass('select2-hidden-accessible')
        .removeAttr('data-select2-id')
        .off('.select2');
      try { $s.removeData('select2'); } catch(e){}
      $s.find('option').removeAttr('data-select2-id');
    });
  }
  function cleanSelect2ArtifactsDeep($scope){
    $scope.find('select.select2').each(function(){
      const $s = $(this);
      $s.next('.select2').remove();
      $s.removeClass('select2-hidden-accessible')
        .removeAttr('data-select2-id')
        .off('.select2');
      try { $s.removeData('select2'); } catch(e){}
      $s.val(null);
      $s.find('option').each(function(){
        $(this).removeAttr('data-select2-id').prop('selected', false);
      });
    });
  }

  // ---------- Select2 init ----------
  function initSelect2Scoped($scope){
    if (!hasSelect2) return;
    const $ctx = ($scope || $(document));
    cleanSelect2ArtifactsSafe($ctx);

    $ctx.find('select.select2').each(function(){
      const $sel = $(this);
      const isReadOnly = $sel.is('[data-readonly="1"]');
      const wasDisabled = $sel.prop('disabled');
      if (isReadOnly || wasDisabled) $sel.prop('disabled', false);

      $sel.select2({
        width:'100%',
        allowClear:true,
        placeholder:'Select',
        dropdownParent: $(document.body),
        minimumResultsForSearch: 0
      });

      $sel.trigger('change.select2', { noValidate: true });

      if (isReadOnly || wasDisabled) $sel.prop('disabled', wasDisabled);
    });
  }

  // ---------- Datepicker init ----------
  function initDatepickerScoped($scope){
    if (!hasDatepicker) return;
    const $ctx = ($scope || $(document));
    $ctx.find('.js-date').each(function(){
      const $el = $(this);
      try{$el.datepicker('destroy');}catch(e){}
      $el.datepicker({
        format: 'mm-dd-yyyy',
        autoclose: true,
        todayHighlight: true,
        endDate: TODAY
      });
    });
    $ctx.find('.js-month').each(function(){
      const $el = $(this);
      try{$el.datepicker('destroy');}catch(e){}
      $el.datepicker({
        format: 'mm-yyyy',
        minViewMode: 1,
        autoclose: true,
        endDate: TODAY
      });
    });
  }

  // ---------- row clear ----------
  function clearExcelRowValues($row){
    $row.find('input[type="text"], input[type="number"], input[type="hidden"], textarea').each(function(){
      this.value = '';
    });
    $row.find('.js-date, .js-month').each(function(){
      const $el = $(this);
      $el.val('');
      if (hasDatepicker) { try { $el.datepicker('update', ''); } catch(e){} }
    });
    $row.find('select').each(function(){
      const $sel = $(this);
      const wasDisabled = $sel.prop('disabled');
      const isReadOnly  = $sel.is('[data-readonly="1"]');

      if (wasDisabled) $sel.prop('disabled', false);

      $sel.val(null);
      $sel.find('option').prop('selected', false).removeAttr('data-select2-id');
      $sel.removeAttr('data-select2-id');
      if ($sel.hasClass('select2-hidden-accessible')) {
        $sel.trigger('change.select2');
      } else {
        $sel.trigger('change');
      }

      if (isReadOnly){
        const name = $sel.attr('name');
        const $proxy = $sel.nextAll(`input[type="hidden"][data-ro-proxy="1"][name="${name}"]`).first();
        if ($proxy.length) $proxy.val('');
      }
      if (wasDisabled) $sel.prop('disabled', true);
      if ($sel.hasClass('select2-hidden-accessible') && isReadOnly){
        $sel.next('.select2').addClass('select2-ro');
      }
    });

    $row.find('.is-invalid').removeClass('is-invalid');
    $row.find('.invalid-feedback').remove();

    $row.find('input[name="intake_id[]"]').val('');

    // Reset Queries button in this cleared row
    (function(){
      const $qBtn = $row.find('.btn-open-queries');
      if ($qBtn.length){
        $qBtn.attr('data-intake-id','').prop('disabled', true);
        const $badge = $qBtn.find('.q-badge');
        $badge.text('0').removeClass('q-badge-danger').addClass('q-badge-muted');
      }
    })();
  }

  // ---------- enforce read-only selects ----------
  function enforceReadonlySelects(scope){
    var $scope = scope ? $(scope) : $(document);
    $scope.find('select[data-readonly="1"]').each(function(){
      var $sel  = $(this);
      var name  = $sel.attr('name');
      var $proxy = $sel.nextAll('input[type="hidden"][data-ro-proxy="1"][name="'+name+'"]').first();
      if (!$proxy.length) { $proxy = $('<input type="hidden" data-ro-proxy="1">').attr('name', name); $sel.after($proxy); }
      $proxy.val($sel.val());
      $sel.prop('disabled', true);
      if ($sel.hasClass('select2-hidden-accessible')) { $sel.next('.select2').addClass('select2-ro'); }
      $sel.off('.roSync').on('change.roSync', function(){ $proxy.val($sel.val()); });
    });
    $scope.closest('form').off('submit.roSync').on('submit.roSync', function(){
      $(this).find('select[data-readonly="1"]').each(function(){
        var $sel = $(this);
        var $proxy = $sel.nextAll('input[type="hidden"][data-ro-proxy="1"][name="'+$sel.attr('name')+'"]').first();
        if ($proxy.length) $proxy.val($sel.val());
      });
    });
  }

  // ---------- initial init ----------
  initSelect2Scoped();
  initDatepickerScoped();
  enforceReadonlySelects();

  // NEW: bind validation + min-date constraints on load
  bindAllRowDatePairs();

  const $form  = $('#excelForm');
  const $tbody = $('#excel-tbody');

  // ---------- Add Row ----------
  $('#btn-add-row').off('click.excelAdd').on('click.excelAdd', function(){
    const $last = $tbody.find('tr').last();
    const $new  = $last.clone(false, false);

    cleanSelect2ArtifactsDeep($new);

    $new.find('.js-date, .js-month').each(function(){
      if (hasDatepicker) { try { $(this).datepicker('destroy'); } catch(e){} }
    });

    $new.find('input[type="hidden"][data-ro-proxy="1"]').remove();

    $new.find('input[type="text"], textarea').val('');
    $new.find('input[name="intake_id[]"]').val('');

    $tbody.append($new);

    $tbody.find('tr').each(function(i){
      $(this).find('[data-row-idx], td:first').first().text(i+1);
    });

    initSelect2Scoped($new);
    initDatepickerScoped($new);

    $new.find('select[name="abstractor[]"], select[name="reviewer[]"], select[name="sense_check_ddr[]"]')
      .prop('disabled', false)
      .val(null)
      .trigger('change.select2');

    enforceReadonlySelects($new);

    // NEW: reset Queries button in the newly added row
    (function(){
      const $qBtn = $new.find('.btn-open-queries');
      if ($qBtn.length){
        $qBtn.attr('data-intake-id','').prop('disabled', true);
        const $badge = $qBtn.find('.q-badge');
        $badge.text('0').removeClass('q-badge-danger').addClass('q-badge-muted');
      }
    })();

    // NEW: bind validation + min-date constraints for the newly added row
    bindAllRowDatePairs($new);
  });

  // ---------- Remove / Clear Row ----------
  $(document).off('click.excelRemove','[data-remove-row]').on('click.excelRemove','[data-remove-row]', function(){
    const $tr   = $(this).closest('tr');
    const count = $tbody.find('tr').length;

    if (count <= 1) {
      if (window.confirm('Are you sure you want to delete this record? The row will be cleared and kept so you can still save one empty row.')) {
        clearExcelRowValues($tr);
        initSelect2Scoped($tr);
        enforceReadonlySelects($tr);
        // rebind constraints for the cleared row (no harm)
        bindAllRowDatePairs($tr);
      }
      return;
    }

    $tr.remove();

    $tbody.find('tr').each(function(i){
      $(this).find('[data-row-idx], td:first').first().text(i+1);
    });
  });

  // ---------- jQuery Validate ----------
  if (hasValidate && $form.length) {
    $.validator.addMethod('mdyDate', function(v, el){
      return this.optional(el) || !!parseMDY(v);
    }, 'Use MM-DD-YYYY.');
    $.validator.addMethod('endAfterStartMDY', function(v, el, startSel){
      const s=parseMDY($(startSel).val()), e=parseMDY(v); if(!s||!e) return true; return e>=s;
    }, 'End date must be on or after start date.');
    $.validator.addMethod('lettersNumbersSpaces', v => (v||'').match(/^[A-Za-z0-9 ]+$/) || v==='', 'Only letters, numbers and spaces.');

    $form.validate({
      ignore: ':hidden:not(.select2-hidden-accessible)',
      rules:{
        project_category:{required:true},
        project_name:{required:true, maxlength:255, lettersNumbersSpaces:true},
        description:{required:true, maxlength:1000},
        start_date:{mdyDate:true},
        end_date:{mdyDate:true, endAfterStartMDY:'#start_date'},
        customer_id:{required:true},
        project_type_id:{required:true},
        department_id:{required:true},
        pricing_id:{required:true},
        input_format_id:{required:true},
        output_format_id:{required:true},
        mode_of_delivery_id:{required:true},
        frequency_of_delivery_id:{required:true},
        project_priority_id:{required:true},
        project_status_id:{required:true},
        recurring_type:{ required:function(){ return $('#is_recurring').is(':checked'); } }
      },
      errorElement:'div',
      errorPlacement:function(error, element){
        error.addClass('invalid-feedback');
        if (element.hasClass('select2-hidden-accessible')) error.insertAfter(element.next('.select2'));
        else if (element.parent('.input-group').length) error.insertAfter(element.parent());
        else error.insertAfter(element);
      },
      highlight:function(el){
        $(el).addClass('is-invalid');
        if($(el).hasClass('select2-hidden-accessible'))
          $(el).next('.select2').find('.select2-selection').addClass('is-invalid');
      },
      unhighlight:function(el){
        $(el).removeClass('is-invalid');
        if($(el).hasClass('select2-hidden-accessible'))
          $(el).next('.select2').find('.select2-selection').removeClass('is-invalid');
      }
    });

    $form
      .off('change.excelValid input.excelValid select2:select.excelValid select2:unselect.excelValid select2:clear.excelValid')
      .on('change.excelValid input.excelValid', 'input:not(.poc-select):not(.pm-select), textarea, select:not(.poc-select):not(.pm-select)', function(){
        if ($form.data('validator')) $(this).valid();
      })
      .on('select2:select.excelValid select2:unselect.excelValid select2:clear.excelValid', 'select:not(.poc-select):not(.pm-select)', function(){
        if ($form.data('validator')) $(this).valid();
      });
  }

  // ===================== QUERIES MODAL =====================
  (function(){
    $.ajaxSetup({ headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });

    const CAN_EDIT_Q       = !!window.__canEditQueries;          // full query edit rights (non-customer staff)
    const CAN_EDIT_CLIENT  = !!window.__canEditClientResponse;   // customer can edit client response only
    const CAN_SAVE         = CAN_EDIT_Q || CAN_EDIT_CLIENT;      // enable Save if either is true

    function two(n){ return String(n).padStart(2,'0'); }
    function todayMDY(){ const d=new Date(); return `${two(d.getMonth()+1)}-${two(d.getDate())}-${d.getFullYear()}`; }

    const $modal   = $('#queriesModal');
    const $rowsBox = $('#queriesRows');
    const $qForm   = $('#queriesForm');
    const $saveBtn = $('#btnSaveQueries');
    const $errors  = $('#queriesFormErrors');
    const $okBox   = $('#queriesFormSuccess');
    const $tpl     = $('#queryRowTpl');

    if (!$modal.length || !$qForm.length || !$tpl.length) return;

    let nextIdx = 0;
    function tplRow(idx){ return $tpl.html().replaceAll('__IDX__', idx); }

    function showErrors(list){
      if (!list || !list.length){ $errors.addClass('d-none').empty(); return; }
      $errors.removeClass('d-none').html('<ul class="mb-0">' + list.map(e=>`<li>${e}</li>`).join('') + '</ul>');
    }
    function showSuccess(msg){
      if (!msg){ $okBox.addClass('d-none').empty(); return; }
      $okBox.removeClass('d-none').text(msg);
      $modal.find('.modal-body').animate({ scrollTop: 0 }, 150);
    }
    function setSaving(s){
      if (s){
        $saveBtn.prop('disabled',true).data('txt',$saveBtn.html())
                .html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
      } else {
        $saveBtn.prop('disabled',false).html($saveBtn.data('txt')||'Save');
      }
    }

    // local Select2 cleanup to avoid nuking selected values
    function cleanSelect2ArtifactsSafe($scope){
      $scope.find('select.select2').each(function(){
        const $s = $(this);
        $s.next('.select2').remove();
        $s.removeClass('select2-hidden-accessible').removeAttr('data-select2-id').off('.select2');
        try { $s.removeData('select2'); } catch(e){}
        $s.find('option').removeAttr('data-select2-id');
      });
    }
    function initSelect2In(scope){
      const $scope = scope ? $(scope) : $modal;
      cleanSelect2ArtifactsSafe($scope);
      $scope.find('select.select2').each(function(){
        const $el=$(this);
        $el.select2({ width:'100%', dropdownParent:$modal, allowClear:true, placeholder:'Select', minimumResultsForSearch:0 })
           .trigger('change.select2');
      });
    }
    function initDatesIn(scope){
      if (!$.fn.datepicker) return;
      const $scope = scope ? $(scope) : $modal;
      $scope.find('.js-date').each(function(){ try{$(this).datepicker('destroy');}catch(e){} });
      $scope.find('.q-raised').each(function(){
        const $el=$(this);
        $el.prop('readonly', true);
        $el.datepicker({
          format:'mm-dd-yyyy',
          autoclose:true,
          todayHighlight:true,
          clearBtn:true,
          endDate: new Date(),
          container:'body',
          enableOnReadonly:false
        }).on('show', function(){ $(this).datepicker('hide'); });
      });
    }

    // Validator: add strict rules only when staff can edit queries.
    if ($.validator && !$.validator.methods.pattern) {
      $.validator.addMethod('pattern', function(value, element, rx){
        if (this.optional(element)) return true;
        const re = rx instanceof RegExp ? rx : new RegExp(rx);
        return re.test(value);
      }, 'Invalid format.');
    }

    const qValidator = $qForm.validate({
      ignore: ":hidden:not(.select2-hidden-accessible)",
      errorElement: 'div',
      errorClass: 'invalid-feedback',
      highlight: function(el){
        const $el=$(el).addClass('is-invalid');
        if ($el.hasClass('select2-hidden-accessible')){
          $el.next('.select2').find('.select2-selection').addClass('is-invalid');
        }
      },
      unhighlight: function(el){
        const $el=$(el).removeClass('is-invalid');
        if ($el.hasClass('select2-hidden-accessible')){
          $el.next('.select2').find('.select2-selection').removeClass('is-invalid');
        }
      },
      errorPlacement: function(error, element){
        if (element.hasClass('select2-hidden-accessible')){
          error.insertAfter(element.next('.select2'));
        } else {
          error.insertAfter(element);
        }
      },
      submitHandler: function(form){
        if (!CAN_SAVE) return false;

        setSaving(true); showErrors([]); showSuccess('');

        // Convert MM-DD-YYYY to YYYY-MM-DD for any date fields present
        const changed=[];
        $qForm.find('.ymd').each(function(){
          const m = /^(\d{2})-(\d{2})-(\d{4})$/.exec((this.value||'').trim());
          if (m) {
            const ymd = `${m[3]}-${m[1]}-${m[2]}`;
            changed.push([this,this.value]);
            this.value = ymd;
          }
        });

        const payload = $qForm.serialize();
        changed.forEach(([el,old])=>{ el.value=old; });

        $.ajax({
          url: $qForm.attr('action'),
          method: $qForm.attr('method')||'POST',
          data: payload,
          dataType:'json'
        }).done(function(){
          showSuccess('Queries saved successfully.');
          const intakeId = $('#q_intake_id').val();
          reloadRows(intakeId);
        }).fail(function(xhr){
          let resp={};
          try{ resp=xhr.responseJSON||JSON.parse(xhr.responseText||'{}'); }catch(e){}
          if (xhr.status===422) applyServerErrors(resp);
          else showErrors([resp.message || 'Something went wrong. Please try again.']);
        }).always(function(){ setSaving(false); });

        return false;
      }
    });

    function bindValidationForRow($row){
      if (CAN_EDIT_Q) {
        $row.find('.q-type').rules('add', { required:true });
        $row.find('.q-status').rules('add', { required:true });
        $row.find('.q-text').rules('add',  { required:true, maxlength:5000 });
        $row.find('.ymd').each(function(){
          $(this).rules('add',{
            pattern:/^(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])-\d{4}$/,
            messages:{ pattern:'Use MM-DD-YYYY.' }
          });
        });
      }
    }

    function addQueryRow(prefill, opts){
      const options = opts || {};
      const prepend = !!options.prepend;

      const idx = nextIdx++;
      const $row = $(tplRow(idx));
      if (prepend) { $rowsBox.prepend($row); } else { $rowsBox.append($row); }

      const q = prefill || {};
      const id       = q.id ?? q.query_id ?? '';
      const typeId   = q.type_id ?? q.type_of_queries_id ?? '';
      const statusId = q.status_id ?? q.query_status_id ?? '';
      const raised   = q.raised_date ?? q.query_raised_date ?? '';

      $row.find('.q-type').val(typeId || '');

      if (statusId) $row.find('.q-status').val(statusId);
      else {
        const $opt = $row.find('.q-status option').filter(function(){return ($(this).text()||'').trim().toLowerCase()==='open';});
        if ($opt.length) $row.find('.q-status').val($opt.attr('value'));
      }

      const toMDY = v => /^\d{4}-\d{2}-\d{2}$/.test(v) ? (v.split('-')[1]+'-'+v.split('-')[2]+'-'+v.split('-')[0]) : v;
      $row.find('.q-raised').val(toMDY(raised) || todayMDY()).prop('readonly', true);

      $row.find('.q-text').val(q.sb_query ?? q.sb_queries ?? '');

      // Client Response: editable only for customers
      const clientResp = q.client_response ?? q.clientResponse ?? q.client_response_text ?? q.client_resp ?? q.clientresponse ?? '';
      const $clientTA  = $row.find(`textarea[name="queries[${idx}][client_response]"]`);
      $clientTA.val(clientResp);
      $clientTA.prop('readonly', !CAN_EDIT_CLIENT).toggleClass('bg-light', !CAN_EDIT_CLIENT);

      // Existing rows cannot be removed
      if (id) {
        $row.attr('data-existing','1');
        $row.find('.q-id').val(id);
        $row.find('.btnRemoveQueryRow').remove();
      }

      // If user cannot edit full queries, lock the other fields
      if (!CAN_EDIT_Q) {
        $row.find('.q-type, .q-status, .q-raised, .q-text').prop('readonly', true).addClass('bg-light');
        $row.find('.q-type, .q-status').prop('disabled', true);
        $row.find('.select2').addClass('select2-ro');
      }

      initSelect2In($row);
      initDatesIn($row);
      bindValidationForRow($row);

      $row.on('click','.btnRemoveQueryRow', function(){
        if (!CAN_EDIT_Q) return;
        if ($row.attr('data-existing')==='1' || $row.find('.q-id').val()) return;
        if ($('#queriesRows .query-row').length <= 1) return;
        $row.find('input,select,textarea').each(function(){ try{$(this).rules('remove');}catch(e){} });
        $row.remove();
      });
    }

    function clearRows(){ $rowsBox.empty(); nextIdx = 0; }

    function dotKeyToName(dotKey){
      const parts=String(dotKey).split('.'); const first=parts.shift();
      return first + parts.map(p=>`[${p}]`).join('');
    }
    function applyServerErrors(resp){
      const fieldErrors={}; let general=[];
      if (resp?.errors) {
        Object.keys(resp.errors).forEach(function(k){
          const msgs = resp.errors[k]||[]; if (!msgs.length) return;
          const name = dotKeyToName(k);
          if ($qForm.find(`[name="${name}"]`).length) fieldErrors[name]=msgs[0];
          else general = general.concat(msgs);
        });
      } else if (resp?.message) {
        general.push(resp.message);
      } else {
        general.push('Validation failed. Please review your inputs.');
      }
      qValidator.showErrors(fieldErrors);
      showErrors(general);
    }
    function normalizeQueryList(payload){
      if (Array.isArray(payload)) return payload;
      if (Array.isArray(payload?.data)) return payload.data;
      if (Array.isArray(payload?.items)) return payload.items;
      if (Array.isArray(payload?.data?.queries)) return payload.data.queries;
      if (Array.isArray(payload?.queries)) return payload.queries;
      return [];
    }
    function reloadRows(intakeId){
      clearRows();
      $.getJSON("{{ route('intake-queries.index') }}", { intake_id:intakeId })
        .done(function(payload){
          const items = normalizeQueryList(payload);
          if (items.length) items.forEach(function(it){ addQueryRow(it, { prepend:false }); });
          else addQueryRow(undefined, { prepend:true });
        })
        .fail(function(){ addQueryRow(undefined, { prepend:true }); });

      // Button states: allow Save if customer OR staff editor. Only show "Add Query" for staff editor
      if (!CAN_SAVE) {
        $('#btnAddQueryRow').prop('disabled', true).hide();
        $saveBtn.prop('disabled', true);
      } else {
        $('#btnAddQueryRow').prop('disabled', !CAN_EDIT_Q).toggle(CAN_EDIT_Q);
        $saveBtn.prop('disabled', false);
      }
    }

    $(document).off('click.queriesOpen','.btn-open-queries').on('click.queriesOpen','.btn-open-queries', function(){
      const intakeId = $(this).data('intake-id'); if(!intakeId) return;
      $('#q_intake_id').val(intakeId);
      $('#queriesModalLabel').text('Queries — Intake #'+intakeId);
      showErrors([]); showSuccess(''); clearRows();

      $.getJSON("{{ route('intake-queries.index') }}", { intake_id:intakeId })
        .done(function(payload){
          const items = normalizeQueryList(payload);
          if (items.length) items.forEach(function(it){ addQueryRow(it, { prepend:false }); });
          else addQueryRow(undefined, { prepend:true });

          if (window.bootstrap && bootstrap.Modal) {
            (new bootstrap.Modal($modal.get(0), { backdrop:'static', keyboard:false })).show();
          } else if ($.fn.modal) {
            $modal.modal({ backdrop:'static', keyboard:false }).modal('show');
          } else {
            $modal.addClass('show').css('display','block').attr('aria-modal','true').removeAttr('aria-hidden');
            $('body').addClass('modal-open').append('<div class="modal-backdrop fade show"></div>');
          }

          if (!CAN_SAVE) {
            $('#btnAddQueryRow').prop('disabled', true).hide();
            $saveBtn.prop('disabled', true);
          } else {
            $('#btnAddQueryRow').prop('disabled', !CAN_EDIT_Q).toggle(CAN_EDIT_Q);
            $saveBtn.prop('disabled', false);
          }
        })
        .fail(function(){
          addQueryRow(undefined, { prepend:true });
          if (window.bootstrap && bootstrap.Modal) {
            (new bootstrap.Modal($modal.get(0), { backdrop:'static', keyboard:false })).show();
          } else if ($.fn.modal) {
            $modal.modal({ backdrop:'static', keyboard:false }).modal('show');
          } else {
            $modal.addClass('show').css('display','block').attr('aria-modal','true').removeAttr('aria-hidden');
            $('body').addClass('modal-open').append('<div class="modal-backdrop fade show"></div>');
          }

          if (!CAN_SAVE) {
            $('#btnAddQueryRow').prop('disabled', true).hide();
            $saveBtn.prop('disabled', true);
          } else {
            $('#btnAddQueryRow').prop('disabled', !CAN_EDIT_Q).toggle(CAN_EDIT_Q);
            $saveBtn.prop('disabled', false);
          }
        });
    });

    $modal.on('shown.bs.modal', function(){
      initSelect2In($modal);
      initDatesIn($modal);
      if (CAN_EDIT_CLIENT && !CAN_EDIT_Q) {
        $modal.find('textarea.q-client-response:first').trigger('focus');
      } else {
        $modal.find('.q-type:first').trigger('focus');
      }
    });
    $modal.on('hidden.bs.modal', function(){
      $rowsBox.find('.is-invalid').removeClass('is-invalid');
      showErrors([]); showSuccess('');
    });

    $('#btnAddQueryRow').off('click.queriesAdd').on('click.queriesAdd', function(e){
      e.preventDefault();
      if (!CAN_EDIT_Q) return;
      addQueryRow(undefined, { prepend:true });
    });

    $('#queriesForm').off('input.queries change.queries').on('input.queries change.queries', 'input,select,textarea', function(){ showSuccess(''); });
  })();

  // === Filters: badges + reset ===
  (function () {
    var $filtersForm = $('#filtersForm');
    var $badgesBox   = $('#activeFiltersBadges');
    if (!$filtersForm.length) return;

    function isEmpty(v){ return v == null || String(v).trim() === ''; }
    function labelFor($el){
      var $col = $el.closest('[class*="col-"]');
      var txt  = ($col.find('label.form-label').first().text() || '').trim();
      return txt || $el.attr('name') || 'Filter';
    }
    function displayValue($el){
      var v = $el.val();
      if (isEmpty(v)) return '';
      if ($el.is('select') && $el.prop('multiple')) {
        var texts = [];
        ($el.val() || []).forEach(function(val){
          var t = $el.find('option[value="'+val+'"]').text();
          if (t) texts.push(t.trim());
        });
        return texts.join(', ');
      }
      if ($el.is('select') && !isEmpty(v)) {
        var t = $el.find('option:selected').text();
        return (t || v).trim();
      }
      return String(v).trim();
    }
    function renderBadges(){
      if (!$badgesBox.length) return;
      var badges = [];
      $filtersForm.find('input, select, textarea').each(function(){
        var $el = $(this);
        if ($el.is(':hidden') && !$el.hasClass('select2-hidden-accessible')) return;
        if (['submit','button'].includes(($el.attr('type')||'').toLowerCase())) return;
        var valText = displayValue($el);
        if (isEmpty(valText)) return;
        var lab = labelFor($el);
        badges.push('<span class="badge bg-secondary-subtle text-dark fw-normal me-1 mb-1">'+
                    $('<div>').text(lab+': '+valText).html()+'</span>');
      });
      $badgesBox.html(badges.join(''));
    }
    renderBadges();
    $filtersForm.on('change input', 'input,select,textarea', renderBadges)
                .on('select2:select select2:unselect select2:clear', 'select', renderBadges);

    // Toggle "More filters" / "Less filters" label
    (function(){
      const $mf = $('#moreFilters');
      const $mfBtn = $('.more-filters');

      function syncMoreFiltersBtn(){
        const expanded = $mf.hasClass('show') || $mf.attr('aria-expanded') === 'true';
        $mfBtn.text(expanded ? 'Less filters' : 'More filters');
      }
      // Initial label
      syncMoreFiltersBtn();

      // Update on collapse events
      $mf.on('shown.bs.collapse hidden.bs.collapse', syncMoreFiltersBtn);

      // Fallback: after click, let collapse update then sync
      $mfBtn.on('click', function(){ setTimeout(syncMoreFiltersBtn, 10); });

      // Also sync after Reset
      $('#btnResetFilters').on('click', function(){ setTimeout(syncMoreFiltersBtn, 10); });
    })();

    $('#btnResetFilters').off('click.filtersReset').on('click.filtersReset', function(e){
      e.preventDefault();
      $filtersForm[0].reset();
      $filtersForm.find('select').each(function(){
        var $s = $(this);
        $s.val(null).trigger('change.select2');
      });
      $filtersForm.find('.js-date, .js-month').each(function(){
        var $d = $(this);
        $d.val('');
        if ($.fn.datepicker) { try { $d.datepicker('update',''); } catch(e){} }
      });
      var $more = $('#moreFilters');
      if ($more.length && $more.hasClass('show')) {
        if (window.bootstrap && bootstrap.Collapse) bootstrap.Collapse.getOrCreateInstance($more[0]).hide();
        else if ($.fn.collapse) $more.collapse('hide');
      }
      renderBadges();
      $filtersForm.trigger('submit');
    });
  })();

});
</script>
@endsection
>>>>>>> 9d9ed85b (for cleaner setup)
