@extends('layouts/layoutMaster')
@section('title', $title)

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
<<<<<<< HEAD
=======

>>>>>>> 9d9ed85b (for cleaner setup)
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection
@section('page-script')
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
@endsection
@section('extra-script')
<script>
<<<<<<< HEAD
  $(document).ready(function() {

   function updateCTC($row) {
    const $opt = $row.find('.skill-select option:selected');
    const ctc = $opt.data('ctc') ?? '';
    $row.find('.ctc-field').val(ctc);
  }

  // ===== Disallow duplicates across rows & refresh Select2 view =====
  function refreshSkillOptions() {
    const selectedSkills = $('.skill-select').map(function() {
      return $(this).val();    // string or null
    }).get().filter(v => v);    // remove null/empty

    $('.skill-select').each(function() {
      const currentVal = $(this).val();
      $(this).find('option').each(function() {
        const optionVal = $(this).val();
        if (!optionVal) return; // keep placeholder enabled
        // Disable if selected elsewhere (but not this row's current choice)
        $(this).prop('disabled', selectedSkills.includes(optionVal) && optionVal !== currentVal);
      });

      // IMPORTANT: update Select2 UI after changing option disabled state
=======
(function ($, window, document) {
  'use strict';

  // ---- helpers shared across scripts ----
  window.isPricingCustom = function () {
    return $('input[name="pricing_type"]:checked').val() === 'custom';
  };
  // ---- Cascade: Industry -> Department -> Service Offering
$('#industry_vertical_id').off('change.pm').on('change.pm', function () {
  pmLoadDepartments(this.value);              // fills #department_id
});

$('#department_id').off('change.pm').on('change.pm', function () {
  pmLoadServiceOfferings(this.value);         // fills #service_offering_id
});

// Initial hydration for edit/validation-old states
(function pmHydrateOnLoad() {
  var ivId    = $('#industry_vertical_id').val();
  var oldDept = "{{ old('department_id', $data->department_id ?? '') }}";
  var oldSO   = "{{ old('service_offering_id', $data->service_offering_id ?? '') }}";

  if (ivId) {
    pmLoadDepartments(ivId, {                 // preselect dept + auto-load SO
      preselectDepartment: oldDept,
      preselectService: oldSO
    });
  } else {
    // ensure clean placeholders when nothing selected yet
    pmResetSelect($('#department_id'), 'Select Department');
    pmResetSelect($('#service_offering_id'), 'Select Service Offering');
  }
})();

  // number helpers
  function num(v) {
    const x = (typeof v === 'string') ? v.replace(/,/g, '').trim() : v;
    const n = parseFloat(x);
    return Number.isFinite(n) ? n : 0;
  }
  function pct(v) {
    // Accept 20 or 0.2 → both mean 20%
    const n0 = num(v);
    return (n0 > 1) ? (n0 / 100) : n0;
  }

  // ========================= Skills repeater =========================
  function updateCTC($row) {
    const ctc = $row.find('.skill-select option:selected').data('ctc');
    $row.find('.ctc-field').val(typeof ctc !== 'undefined' ? ctc : '');
  }

  // Disallow duplicates across all skill rows
  function refreshSkillOptions() {
    const selected = $('.skill-select').map(function () {
      return $(this).val();
    }).get().filter(Boolean);

    $('.skill-select').each(function () {
      const currentVal = $(this).val();
      $(this).find('option').each(function () {
        const val = $(this).val();
        if (!val) return; // keep placeholder enabled
        $(this).prop('disabled', selected.includes(val) && val !== currentVal);
      });
      // refresh Select2 UI
>>>>>>> 9d9ed85b (for cleaner setup)
      $(this).trigger('change.select2');
    });
  }

  function updateButtons() {
<<<<<<< HEAD
    $('.skill-row').each(function(index) {
      const $btnContainer = $(this).find('.btn-container, .col-md-2, .col-md-3.d-flex.align-items-end.mb-3').first();
      $btnContainer.html('');
      $btnContainer.append('<button type="button" class="btn btn-success add-row me-1">+</button>');
      if (index > 0) $btnContainer.append('<button type="button" class="btn btn-danger remove-row">-</button>');
    });
  }

  // Initialize ALL select2 fields
  $('select.select2').each(function() {
    if (!$(this).hasClass('select2-hidden-accessible')) {
      $(this).select2({ width: '100%' });
    }
  });

  // Add row
  $('#skills-repeater').on('click', '.add-row', function() {
    const $original = $('.skill-row:first');
    const $clone = $original.clone(false, false);

    // Clear inputs (AHT & CTC)
    $clone.find('input').val('');

    // Rebuild a fresh, unselected skill select
    const $freshSelect = $original.find('select.skill-select').first().clone(false, false);
    $freshSelect.find('option').prop('selected', false); // clear any selected attr
    $freshSelect.val(null);                              // ensure no value
    $freshSelect.removeClass('select2-hidden-accessible'); // safety when cloning
    $freshSelect.next('.select2').remove();                // remove any stale container

    // Inject fresh select into wrapper, init Select2
    $clone.find('.select-wrapper').empty().append($freshSelect);
    $('#skills-repeater').append($clone);

    const $newRow = $('#skills-repeater .skill-row:last');
    const $newSelect = $newRow.find('select.skill-select');
    $newSelect.select2({ width: '100%' });

    // Keep options unique + buttons + reset CTC
    refreshSkillOptions();
    updateButtons();
    updateCTC($newRow);

    // Let any validator bind
    $('#skills-repeater').trigger('row:added', [$newRow]);
  });

  // Remove row
  $('#skills-repeater').on('click', '.remove-row', function() {
    $(this).closest('.skill-row').remove();
    refreshSkillOptions();
    updateButtons();
  });

  // On skill change: enforce uniqueness, update CTC
  $('#skills-repeater').on('change', '.skill-select', function() {
    updateCTC($(this).closest('.skill-row'));
    refreshSkillOptions();
  });

  // Hard guard: prevent choosing a value already used elsewhere
  $('#skills-repeater').on('select2:selecting', '.skill-select', function(e) {
    const incoming = e.params.args.data.id; // the option value being selected
    const $this = $(this);
    const alreadyUsedSomewhere = $('.skill-select').not($this).toArray()
      .some(sel => $(sel).val() === incoming);

    if (alreadyUsedSomewhere) {
      e.preventDefault(); // block the selection
    }
  });

  // First init
  $('select.skill-select').select2({ width: '100%' });
  refreshSkillOptions();
  updateButtons();

  // Populate CTC for preselected rows
  $('.skill-row').each(function() { updateCTC($(this)); });


});
</script>
<script>
  $(function() {
    const $form = $("#pricingMasterForm");

    const isCustom = () => $('input[name="pricing_type"]:checked').val() === 'custom';

    function toggleSections() {
      if (isCustom()) {
        $('.static, .custom').hide();
        $('.custom').show();
      } else {
        $('.static, .custom').hide();
        $('.static').show();
      }
    }

    // Custom methods
    $.validator.addMethod("positiveNumber", function(value, element) {
      return this.optional(element) || ($.isNumeric(value) && parseFloat(value) > 0);
    }, "Enter a positive number.");

    $.validator.addMethod("percentage", function(value, element) {
      return this.optional(element) || ($.isNumeric(value) && value >= 0 && value <= 100);
    }, "Enter a value between 0 and 100.");

    // Validator init
    const validator = $form.validate({
      // Ignore hidden fields EXCEPT Select2 originals
      ignore: ":hidden:not(.select2-hidden-accessible)",
      rules: {
        pricing_type: {
          required: true
        },
        industry_vertical_id: {
          required: true
        },
        department_id: {
          required: true
        },
        service_offering_id: {
          required: true
        },
        unit_of_measurement_id: {
          required: true
        },
        description_id: {
          required: true
        },
        currency_id: {
          required: true
        },
        rate: {
          required: true,
          positiveNumber: true
        },
        name: {
          required: true,
          maxlength: 255
        },
        status: {
          required: true
        },

        // Custom-only via depends
        project_management_cost: {
          required: {
            depends: isCustom
          },
          positiveNumber: {
            depends: isCustom
          }
        },
        vendor_cost: {
          // required: {
          //   depends: isCustom
          // },
          positiveNumber: {
            depends: isCustom
          }
        },
        infrastructure_cost: {
          required: {
            depends: isCustom
          },
          positiveNumber: {
            depends: isCustom
          }
        },
        overhead_percentage: {
          required: {
            depends: isCustom
          },
          percentage: {
            depends: isCustom
          }
        },
        margin_percentage: {
          required: {
            depends: isCustom
          },
          percentage: {
            depends: isCustom
          }
        },
        volume: {
          // required: {
          //   depends: isCustom
          // },
          positiveNumber: {
            depends: isCustom
          }
        },
        volume_based_discount: {
          required: {
            depends: isCustom
          },
          number: {
            depends: isCustom
          }
        },
        conversion_rate: {
          required: {
            depends: isCustom
          },
          positiveNumber: {
            depends: isCustom
          }
        }
      },
      messages: {
        industry_vertical_id: "Select industry vertical.",
        department_id: "Select department.",
        service_offering_id: "Select service offering.",
        unit_of_measurement_id: "Select unit of measurement.",
        description_id: "Select description.",
        currency_id: "Select currency.",
        rate: {
          required: "Enter rate."
        },
        name: {
          required: "Enter pricing name.",
          maxlength: "Max 255 characters."
        },
        status: "Select status."
      },
      errorElement: 'div',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');

        // Select2 -> place error under visible widget
        if (element.hasClass('select2-hidden-accessible')) {
          error.insertAfter(element.next('.select2'));
        } else if (element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } else {
          error.insertAfter(element);
        }
      },
      highlight: function(element) {
        $(element).addClass('is-invalid');
        if ($(element).hasClass('select2-hidden-accessible')) {
          $(element).next('.select2').find('.select2-selection').addClass('is-invalid');
        }
      },
      unhighlight: function(element) {
        $(element).removeClass('is-invalid');
        if ($(element).hasClass('select2-hidden-accessible')) {
          $(element).next('.select2').find('.select2-selection').removeClass('is-invalid');
        }
      }
    });

    // Select2 revalidation (works whether already init or not)
    function wireSelect2Validation($select) {
      $select.on('change', function() {
        $(this).valid();
        if ($(this).val()) {
          $(this).removeClass('is-invalid');
          $(this).next('.select2').find('.select2-selection').removeClass('is-invalid');
          $(this).next('.select2').next('.invalid-feedback').remove();
        }
      });
    }

    // Wire all select2-enabled selects
    $('select.select2').each(function() {
      wireSelect2Validation($(this));
    });

    // Bind rules for a single skill row (called on load and when new rows are added)
    function bindSkillRowValidation($row) {
      const $skill = $row.find('select[name="skills[]"]');
      const $aht = $row.find('input[name="average_handling_time[]"]');

      // rules with depends so they’re only required in "custom"
      $skill.rules('add', {
        required: {
          depends: isCustom
        },
        messages: {
          required: "Select a skill."
        }
      });

      $aht.rules('add', {
        required: {
          depends: isCustom
        },
        positiveNumber: {
          depends: isCustom
        },
        messages: {
          required: "Enter handling time."
        }
      });

      wireSelect2Validation($skill);
    }

    // initial bind for existing row(s)
    $('#skills-repeater .skill-row').each(function() {
      bindSkillRowValidation($(this));
    });

    // When the repeater adds a row, we get the new row and bind rules
    $('#skills-repeater').on('row:added', function(e, $newRow) {
      bindSkillRowValidation($newRow);
    });

    // If you prefer to catch the click directly (delegated):
    $('#skills-repeater').on('click', '.add-row', function() {
      // nothing here; creation is handled in the other script
      // rules binding is done via the row:added event
    });

    // Toggle sections on type change
    $('input[name="pricing_type"]').on('change', function() {
      toggleSections();
      if (isCustom()) {
        $('.custom :input').each(function() {
          $(this).valid();
        });
      }
    });

    toggleSections();
  });

  //rate calculation
 const MARGIN_MODE = 'divide';  // 'divide' => price = cost / (1 - margin)
                                 // 'markup' => price = cost + (baseUsedForMargin * margin)
  const VOLUME_ON   = 'with_infra'; // 'with_infra' | 'without_infra'
  const OVERHEAD_MODE = 'ignore';   // 'ignore' | 'markup' | 'divide'
  const ROUND_STEPS = true;      // mimic Excel-like step rounding (2 decimals) between steps
  // ===========================================================
  function calcSkillSubtotal() {
    // E16 = sum over skills: (AHT/9600) * CTC
    let E16 = 0;
    document.querySelectorAll('#skills-repeater .skill-row').forEach(row => {
      const aht = num(row.querySelector('input[name="average_handling_time[]"]')?.value);
      const ctc = num(row.querySelector('input[name="ctc[]"]')?.value);
      E16 += (aht / 9600) * ctc;
    });
    return E16;
  }
  const $doc = $(document);

  function n(v) {
    const x = (typeof v === 'string') ? v.replace(/,/g,'').trim() : v;
    const y = parseFloat(x);
    return Number.isFinite(y) ? y : 0;
  }
  function pct(v) { let p = n(v); return p > 1 ? p/100 : p; }
  function r2(x) { return Math.round((+x) * 100) / 100; }

  function readInputs() {
    const laborCTC = calcSkillSubtotal();  // <-- Excel E16

    return {
      laborCTC,
      pmPct:  pct($('input[name="project_management_cost"]').val()),
      vendor: n($('input[name="vendor_cost"]').val()),
      infra:  n($('input[name="infrastructure_cost"]').val()),
      overhead: pct($('input[name="overhead_percentage"]').val()),
      margin:   pct($('input[name="margin_percentage"]').val()),
      volume:   n($('input[name="volume"]').val()),
      volAdj:   pct($('input[name="volume_based_discount"]').val()),
      fx:       n($('input[name="conversion_rate"]').val()) || 1
    };
  }

  // Central calculator with configurable application order
function num(v) {
    const x = (typeof v === 'string') ? v.replace(/,/g,'').trim() : v;
    const n = parseFloat(x);
    return isFinite(n) ? n : 0;
  }
  function pct(v) {
    // Accept 20 or 0.2 → both mean 20%
    let n = num(v);
    return (n > 0) ? n / 100 : n;
  }

  // Grab all form inputs into one object
  function readFields() {
    const laborCTC = calcSkillSubtotal();

=======
    const $rows = $('.skill-row');
    const many  = $rows.length > 1;

    $rows.each(function () {
      // find the existing button container in the row
      const $btnContainer = $(this)
        .find('.btn-container, .col-md-2, .col-md-3.d-flex.align-items-end.mb-3')
        .first();

      // reset and add "+"
      $btnContainer
        .empty()
        .append('<button type="button" class="btn btn-success add-row me-1">+</button>');

      // if there are 2+ rows, show "-" on EVERY row
      if (many) {
        $btnContainer.append('<button type="button" class="btn btn-danger remove-row">-</button>');
      }
    });
}


  function initSelect2($ctx) {
    $ctx.find('select.select2, select.skill-select').each(function () {
      if (!$(this).hasClass('select2-hidden-accessible')) {
        $(this).select2({ width: '100%' });
      }
    });
  }

  function addRow() {
    const $original = $('.skill-row:first');
    const $clone = $original.clone(false, false);

    // clear inputs in cloned row
    $clone.find('input').val('');

    // rebuild a fresh skill select (no selection, no stale select2 DOM)
    const $fresh = $original.find('select.skill-select').first().clone(false, false);
    $fresh.find('option').prop('selected', false);
    $fresh.val(null);
    $fresh.removeClass('select2-hidden-accessible');
    $fresh.next('.select2').remove();

    $clone.find('.select-wrapper').empty().append($fresh);
    $('#skills-repeater').append($clone);

    const $newRow = $('#skills-repeater .skill-row:last');
    initSelect2($newRow);
    updateCTC($newRow);
    refreshSkillOptions();
    updateButtons();

    // notify validator binder
    $('#skills-repeater').trigger('row:added', [$newRow]);
  }

  function removeRow(btn) {
    const $rows = $('#skills-repeater .skill-row');
    if ($rows.length > 1) {
      $(btn).closest('.skill-row').remove();
      refreshSkillOptions();
      updateButtons();
      scheduleCompute();
    }
  }

  // Hard guard at selection time (block selecting an already-used skill)
  function wireSelect2DuplicateGuard() {
    $('#skills-repeater').on('select2:selecting', '.skill-select', function (e) {
      const incoming = e.params.args.data.id;
      const $self = $(this);
      const used = $('.skill-select').not($self).toArray()
        .some(sel => $(sel).val() === incoming);
      if (used) e.preventDefault();
    });
  }

  // ========================= Validation =========================
  function wireSelect2Validation($select) {
    $select.on('change', function () {
      $(this).valid();
      if ($(this).val()) {
        $(this).removeClass('is-invalid');
        $(this).next('.select2').find('.select2-selection').removeClass('is-invalid');
        $(this).next('.select2').next('.invalid-feedback').remove();
      }
    });
  }

  function bindSkillRowValidation($row) {
    const $skill = $row.find('select[name="skills[]"]');
    const $aht = $row.find('input[name="average_handling_time[]"]');

    $skill.rules('add', {
      required: { depends: isPricingCustom },
      messages: { required: "Select a skill." }
    });

    $aht.rules('add', {
      required: { depends: isPricingCustom },
      positiveNumber: { depends: isPricingCustom },
      messages: { required: "Enter handling time." }
    });

    wireSelect2Validation($skill);
  }

  // ========================= Sections & Rate calc =========================
  function toggleSections() {
    const custom = isPricingCustom();
    $('.static, .custom').hide();
    $(custom ? '.custom' : '.static').show();

    // Rate: editable in Static, readonly in Custom
    $('#rate').prop('readonly', custom);
  }

  function calcSkillSubtotal() {
    // Excel-like: sum (AHT / 9600) * CTC per row
    let sum = 0;
    document.querySelectorAll('#skills-repeater .skill-row').forEach(row => {
      const aht = num(row.querySelector('input[name="average_handling_time[]"]')?.value);
      const ctc = num(row.querySelector('input[name="ctc[]"]')?.value);
      sum += (aht / 9600) * ctc;
    });
    return sum;
  }

  function readFields() {
    const laborCTC = calcSkillSubtotal();
>>>>>>> 9d9ed85b (for cleaner setup)
    return {
      laborCTC,
      pmPct:       pct(document.querySelector('input[name="project_management_cost"]')?.value),
      vendor:      num(document.querySelector('input[name="vendor_cost"]')?.value),
      infra:       num(document.querySelector('input[name="infrastructure_cost"]')?.value),
      overheadPct: pct(document.querySelector('input[name="overhead_percentage"]')?.value),
      marginPct:   pct(document.querySelector('input[name="margin_percentage"]')?.value),
      volAdj:      pct(document.querySelector('input[name="volume_based_discount"]')?.value),
      fx:          num(document.querySelector('input[name="conversion_rate"]')?.value) || 1
    };
  }

<<<<<<< HEAD
  // ---------- main calculation ----------
  function computeRate() {
=======
  function computeRate() {
    // Never touch Rate in Static mode
    if (!isPricingCustom() || isCustomFixed()) return;

>>>>>>> 9d9ed85b (for cleaner setup)
    const {
      laborCTC, pmPct, vendor, infra,
      overheadPct, marginPct, volAdj, fx
    } = readFields();

<<<<<<< HEAD
  //   // Step 1: Base cost
  //   const pmCost = laborCTC * pmPct;
  //   const base   = laborCTC + pmCost + vendor + infra;

  //   // Step 2: Overhead
  //   const withOverhead = base * (1 + overheadPct);

  //   // Step 3: Margin (Excel style: divide by (1 - margin))
  //   const withMargin = withOverhead / (1 - marginPct || 1);

  //   // Step 4: Volume adjustment (add base * volAdj)
  //   const afterVolume = withMargin + (base * volAdj);

  //   // Step 5: Conversion
  //   const finalRate = afterVolume * fx;
  //  console.log("laborCTC",laborCTC,"pmPct",pmPct,"vendor",vendor,"infra",infra,"overheadPct",overheadPct,'marginPct',marginPct,'volAdj',volAdj,'fx',fx);
    const finalRate1 =
      (((laborCTC + (laborCTC * pmPct)) + vendor + infra) *
      (1 + (overheadPct/1) * (1 + marginPct/1))) *
      (1 + (volAdj/1)) / fx;
        document.getElementById('rate').value = finalRate1.toFixed(3);
      }

  // ---------- wiring ----------
  const watchSelectors = [
    '.skill-row .ctc-field',
    '.skill-row .skill-select',
    '.skill-row input[name="average_handling_time[]"]',
    'input[name="project_management_cost"]',
    'input[name="vendor_cost"]',
    'input[name="infrastructure_cost"]',
    'input[name="overhead_percentage"]',
    'input[name="margin_percentage"]',
    'input[name="volume_based_discount"]',
    'input[name="conversion_rate"]',
    '#currency_id'
  ].join(',');

  let t = null;
  function schedule() { clearTimeout(t); t = setTimeout(computeRate, 120); }

  document.addEventListener('input', e => {
    if (e.target.matches(watchSelectors)) schedule();
  });
  document.addEventListener('change', e => {
    if (e.target.matches(watchSelectors)) schedule();
  });

  // Initial compute once DOM is ready
  window.addEventListener('DOMContentLoaded', () => setTimeout(computeRate, 150));
</script>

=======
    // Your original logic:
    // finalRate1 =
    //   (((laborCTC + (laborCTC * pmPct)) + vendor + infra) *
    //    (1 + overheadPct * (1 + marginPct))) *
    //   (1 + volAdj) / fx;
    const base = (laborCTC + (laborCTC * pmPct)) + vendor + infra;
    const finalRate1 = (base * (1 + (overheadPct * (1 + marginPct)))) * (1 + volAdj) / (fx || 1);

    if (Number.isFinite(finalRate1)) {
      document.getElementById('rate').value = finalRate1.toFixed(3);
    }
  }

  function scheduleCompute() {
    clearTimeout(scheduleCompute.t);
    scheduleCompute.t = setTimeout(computeRate, 120);
  }

  // ========================= Boot =========================
  $(function () {
    // Init select2 everywhere
    initSelect2($(document));
    wireSelect2DuplicateGuard();

    // Initial section state & rate field behavior
    toggleSections();

    // jQuery Validate
    const $form = $("#pricingMasterForm");

    $.validator.addMethod("positiveNumber", function (value, element) {
      return this.optional(element) || ($.isNumeric(value) && parseFloat(value) > 0);
    }, "Enter a positive number.");

    $.validator.addMethod("percentage", function (value, element) {
      return this.optional(element) || ($.isNumeric(value) && value >= 0 && value <= 100);
    }, "Enter a value between 0 and 100.");

    $form.validate({
      ignore: ":hidden:not(.select2-hidden-accessible)",
      rules: {
        pricing_type: { required: true },
        industry_vertical_id: { required: true },
        department_id: { required: true },
        service_offering_id: { required: true },
        unit_of_measurement_id: { required: true },
        description_id: { required: true },
        currency_id: { required: true },
        rate: { required: true, positiveNumber: true },
        name: { required: true, maxlength: 255 },
        status: { required: true },

        // Custom-only fields
        project_management_cost: {
          required: { depends: isPricingCustom },
          positiveNumber: { depends: isPricingCustom }
        },
        vendor_cost: {
          positiveNumber: { depends: isPricingCustom }
        },
        infrastructure_cost: {
          required: { depends: isPricingCustom },
          positiveNumber: { depends: isPricingCustom }
        },
        overhead_percentage: {
          required: { depends: isPricingCustom },
          percentage: { depends: isPricingCustom }
        },
        margin_percentage: {
          required: { depends: isPricingCustom },
          percentage: { depends: isPricingCustom }
        },
        volume: {
          positiveNumber: { depends: isPricingCustom }
        },
        volume_based_discount: {
          required: { depends: isPricingCustom },
          number: { depends: isPricingCustom }
        },
        conversion_rate: {
          required: { depends: isPricingCustom },
          positiveNumber: { depends: isPricingCustom }
        }
      },
      messages: {
        industry_vertical_id: "Select industry vertical.",
        department_id: "Select department.",
        service_offering_id: "Select service offering.",
        unit_of_measurement_id: "Select unit of measurement.",
        description_id: "Select description.",
        currency_id: "Select currency.",
        rate: { required: "Enter rate." },
        name: { required: "Enter pricing name.", maxlength: "Max 255 characters." },
        status: "Select status."
      },
      errorElement: 'div',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        if (element.hasClass('select2-hidden-accessible')) {
          error.insertAfter(element.next('.select2'));
        } else if (element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } else {
          error.insertAfter(element);
        }
      },
      highlight: function (element) {
        $(element).addClass('is-invalid');
        if ($(element).hasClass('select2-hidden-accessible')) {
          $(element).next('.select2').find('.select2-selection').addClass('is-invalid');
        }
      },
      unhighlight: function (element) {
        $(element).removeClass('is-invalid');
        if ($(element).hasClass('select2-hidden-accessible')) {
          $(element).next('.select2').find('.select2-selection').removeClass('is-invalid');
        }
      }
    });

    // Wire select2 validation for all selects
    $('select.select2').each(function () { wireSelect2Validation($(this)); });

    // Bind existing rows for skills + populate CTC for any preselected
    $('#skills-repeater .skill-row').each(function () {
      bindSkillRowValidation($(this));
      updateCTC($(this));
    });
    refreshSkillOptions();
    updateButtons();

    // Add/remove row events
    $('#skills-repeater').on('click', '.add-row', addRow);
    $('#skills-repeater').on('click', '.remove-row', function () { removeRow(this); });

    // On skill change
    $('#skills-repeater').on('change', '.skill-select', function () {
      updateCTC($(this).closest('.skill-row'));
      refreshSkillOptions();
      scheduleCompute();
    });

    // Watch inputs that affect rate
    const watchSelectors = [
      '.skill-row .ctc-field',
      '.skill-row .skill-select',
      '.skill-row input[name="average_handling_time[]"]',
      'input[name="project_management_cost"]',
      'input[name="vendor_cost"]',
      'input[name="infrastructure_cost"]',
      'input[name="overhead_percentage"]',
      'input[name="margin_percentage"]',
      'input[name="volume_based_discount"]',
      'input[name="conversion_rate"]',
      '#currency_id'
    ].join(',');

    $(document).on('input change', watchSelectors, scheduleCompute);

    // Switch Static <-> Custom
    $('input[name="pricing_type"]').on('change', function () {
      toggleSections();
      if (isPricingCustom()) scheduleCompute();
    });

    // Initial compute ONLY in Custom mode
    if (isPricingCustom()) setTimeout(computeRate, 150);

    // When a new row is added by repeater, bind its rules
    $('#skills-repeater').on('row:added', function (e, $newRow) {
      bindSkillRowValidation($newRow);
      scheduleCompute();
    });
  });


  // changes for Industrial vertical logic

  // ========= Helpers for cascading dropdowns (Pricing Master) =========
function pmResetSelect($sel, placeholder) {
  $sel.empty().append('<option value="">' + (placeholder || 'Select') + '</option>').trigger('change');
}
function pmPopulateSelect($sel, items, selectedVal) {
  pmResetSelect($sel);
  (items || []).forEach(function (it) {
    $sel.append('<option value="' + it.id + '">' + it.name + '</option>');
  });
  if (selectedVal) $sel.val(String(selectedVal));
  $sel.trigger('change');
}
function pmLoadDepartments(industryId, opts) {
  const $dept = $('#department_id');
  const $so   = $('#service_offering_id');
  pmResetSelect($dept, 'Select Department');
  pmResetSelect($so, 'Select Service Offering');
  if (!industryId) return;

  $dept.append('<option value="">Loading...</option>').trigger('change');

  $.ajax({
    url: "{{ route('departments.byIndustry', ['industry' => '__IND__']) }}"
           .replace('__IND__', encodeURIComponent(industryId)),
    type: "GET",
    dataType: "json",
    success: function (resp) {
      pmPopulateSelect($dept, resp?.data || [], opts?.preselectDepartment);
      const nextDeptId = $dept.val();
      if (nextDeptId) pmLoadServiceOfferings(nextDeptId, { preselectService: opts?.preselectService });
    },
    error: function () {
      pmResetSelect($dept, 'Select Department');
      alert('Could not load departments for the selected Industry Vertical.');
    }
  });
}

function pmLoadServiceOfferings(departmentId, opts) {
  const $so = $('#service_offering_id');
  pmResetSelect($so, 'Select Service Offering');
  if (!departmentId) return;

  $so.append('<option value="">Loading...</option>').trigger('change');

  $.ajax({
    url: "{{ route('serviceOfferings.byDepartment', ['department' => '__DEP__']) }}"
           .replace('__DEP__', encodeURIComponent(departmentId)),
    type: "GET",
    dataType: "json",
    success: function (resp) {
      pmPopulateSelect($so, resp?.data || [], opts?.preselectService);
    },
    error: function () {
      pmResetSelect($so, 'Select Service Offering');
      alert('Could not load service offerings for the selected Department.');
    }
  });
}

// Is custom + fixed?
function isCustomFixed() {
  return isPricingCustom() && ($('#custom_pricing_type').val() === 'fixed');
}

// disable/enable a bunch of fields so jQuery Validate ignores them
function pmSetDisabled(namesOrIds, disabled) {
  namesOrIds.forEach(sel => {
    const $el = sel.startsWith('#') ? $(sel) : $('[name="'+sel+'"]');
    $el.prop('disabled', !!disabled);
    // also disable the Select2 proxy so user can't interact
    if ($el.hasClass('select2') || $el.hasClass('select2-hidden-accessible')) {
      $el.prop('disabled', !!disabled).trigger('change.select2');
    }
  });
}

// Show/hide sections based on pricing type + custom pricing type
function toggleCustomPricingUI() {
  // First apply your existing split: custom vs static
  toggleSections();

  // Always keep rate editable in "fixed" mode, and stop auto-compute
  const fixed = isCustomFixed();
  $('#rate').prop('readonly', isPricingCustom() && !fixed);

  // Fields to KEEP visible in "fixed" mode:
  const $keep = [
    $('#custom_pricing_type').closest('.form-group'),
    $('#currency_id').closest('.form-group'),
    $('#rate').closest('.form-group')
  ];

  if (fixed) {
    // Hide every ".custom" block, then re-show only keepers
    $('.custom').hide();
    $keep.forEach($g => $g.show());

    // Disable everything except currency, rate and custom_pricing_type
    pmSetDisabled([
      // Basic Details (custom)
      'industry_vertical_id', 'department_id', 'service_offering_id',
      'unit_of_measurement_id', 'description_id',
      // Skills repeater
      'skills[]', 'average_handling_time[]', 'ctc[]',
      // Custom cost fields
      'project_management_cost', 'vendor_cost', 'infrastructure_cost',
      'overhead_percentage', 'margin_percentage',
      'volume', 'volume_based_discount', 'conversion_rate'
    ], true);

    // Ensure the 3 allowed fields remain enabled
    pmSetDisabled(['custom_pricing_type', 'currency_id', 'rate'], false);

  } else {
    // Back to "custom variable" or "static" → show the normal groups
    if (isPricingCustom()) {
      $('.custom').show();
    } else {
      $('.custom').hide();
      $('.static').show();
    }

    // Re-enable everything (normal validation applies)
    pmSetDisabled([
      'industry_vertical_id', 'department_id', 'service_offering_id',
      'unit_of_measurement_id', 'description_id',
      'skills[]', 'average_handling_time[]', 'ctc[]',
      'project_management_cost', 'vendor_cost', 'infrastructure_cost',
      'overhead_percentage', 'margin_percentage',
      'volume', 'volume_based_discount', 'conversion_rate',
      'custom_pricing_type', 'currency_id', 'rate'
    ], false);
  }
}

// When pricing type or custom pricing type changes, re-toggle UI
$('input[name="pricing_type"]').on('change', function () {
  toggleCustomPricingUI();
  if (isPricingCustom() && !isCustomFixed()) scheduleCompute();
});

$('#custom_pricing_type').on('change', function () {
  toggleCustomPricingUI();
  if (isPricingCustom() && !isCustomFixed()) scheduleCompute();
});

// Initial state on page load
toggleCustomPricingUI();

})(jQuery, window, document);
</script>


>>>>>>> 9d9ed85b (for cleaner setup)
@endsection

@section('content')
@php
$masters = Helper::getPricingMasterData();
$pricingType = old('pricing_type', $data->pricing_type ?? 'static');
@endphp

<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header">
        <h4 class="text-dark">{{ $title }}</h4>
      </div>
      <div class="card-body">
        <form id="pricingMasterForm" method="POST" action="{{
                    $type == 'create'
                        ? route('pricing-master.store')
                        : route('pricing-master.update', Crypt::encryptString($data->id))
                }}" enctype="multipart/form-data">
          @csrf
          @if($type == 'edit') @method('PUT') @endif

          <div class="row">
<<<<<<< HEAD
            {{-- Pricing Type --}}
            <div class="col-md-12 mb-4 form-group">
              <label class="form-label d-block">Pricing Type <span class="text-danger">*</span></label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pricing_type" id="pricing_static" value="static" {{ $pricingType == 'static' ? 'checked' : '' }}>
                <label class="form-check-label" for="pricing_static">Static</label>
=======
           @php
  // Resolve customer_id from old/model/request
  $resolvedCustomerId = old('customer_id', $data->customer_id ?? request('customer_id'));

  // Force the allowed option based on presence of customer_id
  $forced = $resolvedCustomerId ? 'custom' : 'static';

  // Preserve old/model but clamp to forced option
  $pricingType = old('pricing_type', $data->pricing_type ?? $forced);
  if ($pricingType !== $forced) {
      $pricingType = $forced;
  }
@endphp

<div class="col-md-12 mb-4 form-group">
  <label class="form-label d-block">Pricing Type <span class="text-danger">*</span></label>

  @if($forced === 'custom')
    <div class="form-check form-check-inline">
      <input class="form-check-input"
             type="radio"
             name="pricing_type"
             id="pricing_custom"
             value="custom"
             checked>
      <label class="form-check-label" for="pricing_custom">Custom</label>
    </div>
    <div class="form-text">Customer selected — only Custom pricing is allowed.</div>
  @else
    <div class="form-check form-check-inline">
      <input class="form-check-input"
             type="radio"
             name="pricing_type"
             id="pricing_static"
             value="static"
             checked>
      <label class="form-check-label" for="pricing_static">Standard</label>
    </div>
    <div class="form-text">No customer selected — only Standard pricing is allowed.</div>
  @endif
</div>


            {{-- Pricing Type --}}
            <!-- <div class="col-md-12 mb-4 form-group">
              <label class="form-label d-block">Pricing Type <span class="text-danger">*</span></label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pricing_type" id="pricing_static" value="static" {{ $pricingType == 'static' ? 'checked' : '' }}>
                <label class="form-check-label" for="pricing_static">Standard</label>
>>>>>>> 9d9ed85b (for cleaner setup)
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pricing_type" id="pricing_custom" value="custom" {{ $pricingType == 'custom' ? 'checked' : '' }}>
                <label class="form-check-label" for="pricing_custom">Custom</label>
              </div>
<<<<<<< HEAD
            </div>
=======
            </div> -->
            {{-- Pricing Type --}}
            <!-- <div class="col-md-12 mb-4 form-group">
              <label class="form-label d-block">Pricing Type <span class="text-danger">*</span></label>

              @php
                $hasCustomer = request()->filled('customer_id');
              @endphp

              @if($type === 'create' && $hasCustomer)
                {{-- ✅ Create + customer present → force Custom --}}
                <input type="hidden" name="pricing_type" value="custom">
                <span class="badge bg-info">Custom</span>
                <div class="form-text">Linked to a specific customer — only <strong>Custom</strong> pricing is allowed.</div>

              @elseif($type === 'create' && !$hasCustomer)
                {{-- ✅ Create + no customer → force Standard --}}
                <input type="hidden" name="pricing_type" value="static">
                <span class="badge bg-primary">Standard</span>
                <div class="form-text">Generic pricing — only <strong>Standard</strong> pricing is allowed.</div>

              @else
                {{-- ✅ Edit mode → both visible --}}
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pricing_type" id="pricing_static"
                        value="static" {{ $pricingType == 'static' ? 'checked' : '' }}>
                  <label class="form-check-label" for="pricing_static">Standard</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pricing_type" id="pricing_custom"
                        value="custom" {{ $pricingType == 'custom' ? 'checked' : '' }}>
                  <label class="form-check-label" for="pricing_custom">Custom</label>
                </div>
              @endif
            </div> -->
>>>>>>> 9d9ed85b (for cleaner setup)

            {{-- Basic Details --}}
            <div class="col-md-12 my-4">
              <div class="d-flex align-items-center">
                <div class="flex-grow-1 border-top border-grey"></div>
                <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Basic Details</span>
                <div class="flex-grow-1 border-top border-grey"></div>
              </div>
            </div>
<<<<<<< HEAD
=======
            {{-- Custom Pricing Type (visible only for Custom) --}}
            <div class="col-md-4 mb-3 form-group custom">
              <label for="custom_pricing_type" class="form-label">
                Custom Pricing Type <span class="text-danger">*</span>
              </label>
              <select name="custom_pricing_type" id="custom_pricing_type" class="form-select select2">
                <option value="">Select</option>
                <option value="fixed"    {{ old('custom_pricing_type', $data->custom_pricing_type ?? '') === 'fixed' ? 'selected' : '' }}>Fixed</option>
                <option value="variable" {{ old('custom_pricing_type', $data->custom_pricing_type ?? '') === 'variable' ? 'selected' : '' }}>Variable</option>
              </select>
            </div>
>>>>>>> 9d9ed85b (for cleaner setup)

            <div class="col-md-4 mb-3 form-group static custom">
              <label for="industry_vertical_id" class="form-label">Industry Vertical <span class="text-danger">*</span></label>
              <select name="industry_vertical_id" id="industry_vertical_id" class="form-select select2">
                <option value="">Select</option>
                @foreach($masters['industry_vertical'] as $item)
                <option value="{{ $item->id }}" {{ old('industry_vertical_id', $data->industry_vertical_id ?? '') == $item->id ? 'selected' : '' }}>
                  {{ $item->name }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-3 form-group static custom">
              <label for="department_id" class="form-label">Department / Business Unit <span class="text-danger">*</span></label>
              <select name="department_id" id="department_id" class="form-select select2">
                <option value="">Select</option>
                @foreach($masters['departments'] as $item)
                <option value="{{ $item->id }}" {{ old('department_id', $data->department_id ?? '') == $item->id ? 'selected' : '' }}>
                  {{ $item->name }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-3 form-group static custom">
              <label for="service_offering_id" class="form-label">Service Offering <span class="text-danger">*</span></label>
              <select name="service_offering_id" id="service_offering_id" class="form-select select2">
                <option value="">Select</option>
                @foreach($masters['service_offering'] as $item)
                <option value="{{ $item->id }}" {{ old('service_offering_id', $data->service_offering_id ?? '') == $item->id ? 'selected' : '' }}>
                  {{ $item->name }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-3 form-group static custom">
              <label for="unit_of_measurement_id" class="form-label">Unit of Measurement <span class="text-danger">*</span></label>
              <select name="unit_of_measurement_id" id="unit_of_measurement_id" class="form-select select2">
                <option value="">Select</option>
                @foreach($masters['unit_of_measurement'] as $item)
                <option value="{{ $item->id }}" {{ old('unit_of_measurement_id', $data->unit_of_measurement_id ?? '') == $item->id ? 'selected' : '' }}>
                  {{ $item->name }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-3 form-group custom static">
              <label for="description_id" class="form-label">Description <span class="text-danger">*</span></label>
              <select name="description_id" id="description_id" class="form-select select2">
                <option value="">Select</option>
                @foreach($masters['description'] as $item)
                <option value="{{ $item->id }}" {{ old('description_id', $data->description_id ?? '') == $item->id ? 'selected' : '' }}>
                  {{ $item->name }}
                </option>
                @endforeach
              </select>
            </div>
            {{-- Skills Section --}}
            <div class="col-md-12 my-4 custom">
              <div class="d-flex align-items-center">
                <div class="flex-grow-1 border-top border-grey"></div>
                <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Skills</span>
                <div class="flex-grow-1 border-top border-grey"></div>
              </div>
            </div>

            <div class="row custom">
              <div class="col-md-3 form-group"><label class="form-label">Skills <span class="text-danger">*</span></label></div>
              <div class="col-md-3 form-group"><label class="form-label">Average Handling Time (Min) <span class="text-danger">*</span></label></div>
              <div class="col-md-3 form-group"><label class="form-label">CTC</label></div>
              <div class="col-md-3"></div>
            </div>

            <div id="skills-repeater" class="custom">
              @php
              $oldSkills = old('skills', isset($data) ? $data->skillLines->pluck('skill_id')->toArray() : []);
              $oldAHT = old('average_handling_time', isset($data) ? $data->skillLines->pluck('average_handling_time')->toArray() : []);
              $oldCTC = old('ctc', isset($data) ? $data->skillLines->pluck('ctc')->toArray() : []);

              // Make sure arrays are of same length
              $rowCount = max(count($oldSkills), count($oldAHT), count($oldCTC), 1);
              @endphp

              @for ($i = 0; $i < $rowCount; $i++)
                <div class="row skill-row">
                {{-- Skill Select --}}
                <div class="col-md-3 mb-3 form-group select-wrapper">
                  <select name="skills[]" class="form-select skill-select select2">
                    <option value="">Select</option>
                    @foreach($masters['skills'] as $item)
                    <option value="{{ $item->id }}" data-ctc="{{ $item->ctc }}"
                      {{ (isset($oldSkills[$i]) && $oldSkills[$i] == $item->id) ? 'selected' : '' }}>
                      {{ $item->name }}
                    </option>
                    @endforeach
                  </select>
                </div>

                {{-- Average Handling Time --}}
                <div class="mb-3 col-md-3 form-group">
                  <input type="text"
                    name="average_handling_time[]"
                    class="form-control"
                    placeholder="Enter Average Handling Time"
                    value="{{ $oldAHT[$i] ?? '' }}">
                </div>

                {{-- Readonly CTC --}}
                <div class="mb-3 col-md-3 form-group">
                  <input type="text"
                    name="ctc[]"
                    class="form-control ctc-field"
                    placeholder="CTC"
                    value="{{ $oldCTC[$i] ?? '' }}"
                    readonly>
                </div>

                {{-- Add/Remove Buttons --}}
                <div class="col-md-3 d-flex align-items-end mb-3">
                  <!-- JS add/remove buttons -->
                </div>
            </div>
            @endfor
          </div>
          {{-- Pricing Details --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Pricing Details</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="col-md-4 mb-3 form-group static custom">
            <label for="currency_id" class="form-label">Currency <span class="text-danger">*</span></label>
            <select name="currency_id" id="currency_id" class="form-select select2">
              <option value="">Select</option>
              @foreach($masters['currency'] as $item)
              <option value="{{ $item->id }}" {{ old('currency_id', $data->currency_id ?? '') == $item->id ? 'selected' : '' }}>
                {{ $item->name }}
              </option>
              @endforeach
            </select>
          </div>



          {{-- Custom Cost Fields --}}
          <div class="mb-3 col-md-4 form-group custom">
            <label for="project_management_cost" class="form-label">Project Management Cost <span class="text-danger">*</span></label>
            <input type="text" name="project_management_cost" class="form-control" placeholder="Enter Project Management cost" value="{{ old('project_management_cost', $data->project_management_cost ?? '') }}">
          </div>

          <div class="mb-3 col-md-4 form-group custom">
            <label for="vendor_cost" class="form-label">Vendor Cost </label>
            <input type="text" name="vendor_cost" class="form-control" placeholder="Enter Vendor Cost" value="{{ old('vendor_cost', $data->vendor_cost ?? '') }}">
          </div>

          <div class="mb-3 col-md-4 form-group custom">
            <label for="infrastructure_cost" class="form-label">Infrastructure Cost <span class="text-danger">*</span></label>
            <input type="text" name="infrastructure_cost" class="form-control" placeholder="Enter Infrastructure Cost" value="{{ old('infrastructure_cost', $data->infrastructure_cost ?? '') }}">
          </div>

          <div class="mb-3 col-md-4 form-group custom">
            <label for="overhead_percentage" class="form-label">Overhead (%) <span class="text-danger">*</span></label>
            <input type="text" name="overhead_percentage" class="form-control" placeholder="Enter Overhead Percentage" value="{{ old('overhead_percentage', $data->overhead_percentage ?? '') }}">
          </div>

          <div class="mb-3 col-md-4 form-group custom">
            <label for="margin_percentage" class="form-label">Margin (%) <span class="text-danger">*</span></label>
            <input type="text" name="margin_percentage" class="form-control" placeholder="Enter Margin Percentage" value="{{ old('margin_percentage', $data->margin_percentage ?? '') }}">
          </div>

          <div class="mb-3 col-md-4 form-group custom">
            <label for="volume" class="form-label">Volume </label>
            <input type="text" name="volume" class="form-control" placeholder="Enter Volume" value="{{ old('volume', $data->volume ?? '') }}">
          </div>

          <div class="mb-3 col-md-4 form-group custom">
            <label for="volume_based_discount" class="form-label">Volume Based Addition/Discounts <span class="text-danger">*</span></label>
            <input type="text" name="volume_based_discount" class="form-control" placeholder="Enter Volume Based Addition/Discounts" value="{{ old('volume_based_discount', $data->volume_based_discount ?? '') }}">
          </div>

          <div class="mb-3 col-md-4 form-group custom">
            <label for="conversion_rate" class="form-label">Conversion Rate <span class="text-danger">*</span></label>
            <input type="text" name="conversion_rate" class="form-control" placeholder="Enter Conversion Rate" value="{{ old('conversion_rate', isset($data->conversion_rate) ? number_format($data->conversion_rate, 2, '.', '') : '') }}">
          </div>

          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Rate</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>
          <div class="col-md-4 mb-3 form-group custom static">
            <label for="rate" class="form-label">Rate <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="rate" name="rate" value="{{ old('rate', $data->rate ?? '') }}" placeholder="Enter Rate">
          </div>

          {{-- Status and Name --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Name and Status</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="mb-3 col-md-4 form-group">
            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter pricing name" value="{{ old('name', $data->name ?? '') }}">
          </div>

          <div class="mb-3 col-md-4 form-group">
            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
<<<<<<< HEAD
            <select name="status" id="status" class="form-control select2">
              <option value="1" {{ old('status', $data->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
              <option value="0" {{ old('status', $data->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
          </div>
=======

            @php
              // Default to 1 (Active) if nothing set yet; keep old()/model value on edit/validation error
              $statusVal = (string) old('status', $data->status ?? 1);
            @endphp

            <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror" required>
              <option value="1" {{ $statusVal === '1' ? 'selected' : '' }}>Active</option>
              <option value="0" {{ $statusVal === '0' ? 'selected' : '' }}>Inactive</option>
            </select>

            @error('status')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
        @php
          $customerId = old('customer_id', $data->customer_id ?? request('customer_id'));
        @endphp

        <input type="hidden" name="customer_id" value="{{ $customerId }}">

>>>>>>> 9d9ed85b (for cleaner setup)
      </div>
      <div class="col-md-12 my-4">
        <div class="d-flex align-items-center">
          <div class="flex-grow-1 border-top border-grey"></div>
          <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Document</span>
          <div class="flex-grow-1 border-top border-grey"></div>
        </div>
      </div>
      <div class="mb-3">
        <label for="document" class="form-label">Document</label>
        <input type="file"
          class="form-control @error('document') is-invalid @enderror"
          id="document"
          name="document"
          accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">

        @error('document')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if(isset($data) && $data->document_path)
        <div class="mt-2">
          <a href="{{ Storage::url($data->document_path) }}" target="_blank" rel="noopener">
            View current document
          </a>
        </div>
        @endif
      </div>
      {{-- Buttons --}}
      <div class="text-end">
        <a href="{{ route('pricing-master.index') }}" class="btn btn-secondary">
          <i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>Back
        </a>
        <button type="submit" class="btn btn-primary">
          {{ $type == 'create' ? 'Save' : 'Update' }}
          <i class="ti ti-file-upload ms-1 mb-1"></i>
        </button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>

@endsection
