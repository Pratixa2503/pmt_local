@extends('layouts/layoutMaster')
@section('title', $title)

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
@endsection

@section('extra-script')
<script>
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
    var oldDept = "{{ old('department_id', $data->department_id ?? request('department_id')) }}";   // ✅ added request(...)
    var oldSO   = "{{ old('service_offering_id', $data->service_offering_id ?? request('service_offering_id')) }}"; // ✅

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
    const n0 = num(v);
    return (n0 > 1) ? (n0 / 100) : n0;
  }

  // ========================= Skills repeater =========================
  function updateCTC($row) {
    const ctc = $row.find('.skill-select option:selected').data('ctc');
    $row.find('.ctc-field').val(typeof ctc !== 'undefined' ? ctc : '');
  }

  function refreshSkillOptions() {
    const selected = $('.skill-select').map(function () {
      return $(this).val();
    }).get().filter(Boolean);

    $('.skill-select').each(function () {
      const currentVal = $(this).val();
      $(this).find('option').each(function () {
        const val = $(this).val();
        if (!val) return;
        $(this).prop('disabled', selected.includes(val) && val !== currentVal);
      });
      $(this).trigger('change.select2');
    });
  }

  function updateButtons() {
    const $rows = $('.skill-row');
    const many  = $rows.length > 1;

    $rows.each(function () {
      const $btnContainer = $(this)
        .find('.btn-container, .col-md-2, .col-md-3.d-flex.align-items-end.mb-3')
        .first();

      $btnContainer
        .empty()
        .append('<button type="button" class="btn btn-success add-row me-1">+</button>');

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

    $clone.find('input').val('');

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
    $('#rate').prop('readonly', custom);
  }

  function calcSkillSubtotal() {
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

  // Visually lock fields in Custom-Fixed while mirrors carry the values
function applyFixedVisualDisable() {
  const fixed = isCustomFixed();
  const toLock = [
    '#industry_vertical_id', '#department_id', '#service_offering_id',
    '#unit_of_measurement_id', '#description_id',
    // Skills + costs (already hidden in fixed, but lock anyway if visible)
    'select[name="skills[]"]',
    'input[name="average_handling_time[]"]',
    'input[name="ctc[]"]',
    'input[name="project_management_cost"]',
    'input[name="vendor_cost"]',
    'input[name="infrastructure_cost"]',
    'input[name="overhead_percentage"]',
    'input[name="margin_percentage"]',
    'input[name="volume"]',
    'input[name="volume_based_discount"]',
    'input[name="conversion_rate"]'
  ];

  toLock.forEach(sel => {
    const $el = $(sel);
    if (!$el.length) return;
    $el.prop('disabled', fixed);
    // keep Select2 UI in sync
    if ($el.hasClass('select2') || $el.hasClass('select2-hidden-accessible')) {
      $el.trigger('change.select2');
    }
  });

  // Rate remains editable in fixed (as per your current logic)
  $('#rate').prop('readonly', isPricingCustom() && !fixed);
}

  function computeRate() {
    if (!isPricingCustom() || isCustomFixed()) return;

    const {
      laborCTC, pmPct, vendor, infra,
      overheadPct, marginPct, volAdj, fx
    } = readFields();

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
    initSelect2($(document));
    wireSelect2DuplicateGuard();
    toggleSections();

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

        project_management_cost: {
          required: { depends: isPricingCustom },
          positiveNumber: { depends: isPricingCustom }
        },
        vendor_cost: { positiveNumber: { depends: isPricingCustom } },
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
        volume: { positiveNumber: { depends: isPricingCustom } },
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

    $('select.select2').each(function () { wireSelect2Validation($(this)); });

    $('#skills-repeater .skill-row').each(function () {
      bindSkillRowValidation($(this));
      updateCTC($(this));
    });
    refreshSkillOptions();
    updateButtons();

    $('#skills-repeater').on('click', '.add-row', addRow);
    $('#skills-repeater').on('click', '.remove-row', function () { removeRow(this); });

    $('#skills-repeater').on('change', '.skill-select', function () {
      updateCTC($(this).closest('.skill-row'));
      refreshSkillOptions();
      scheduleCompute();
    });

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

    $('input[name="pricing_type"]').on('change', function () {
      toggleSections();
      if (isPricingCustom()) scheduleCompute();
      // ✅ also update mirrors when mode flips
      updateMirrors();  // defined below
       applyFixedVisualDisable();
    });

    $('#custom_pricing_type').on('change', function () {
      toggleCustomPricingUI();
      if (isPricingCustom() && !isCustomFixed()) scheduleCompute();
      // ✅ also update mirrors when subtype flips
      updateMirrors();
       applyFixedVisualDisable();
    });

    // ✅ initial mirror sync
    updateMirrors();
    applyFixedVisualDisable();
  });


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

  // disable/enable (unchanged)
  function pmSetDisabled(namesOrIds, disabled) {
    namesOrIds.forEach(sel => {
      const $el = sel.startsWith('#') ? $(sel) : $('[name="'+sel+'"]');
      $el.prop('disabled', !!disabled);
      if ($el.hasClass('select2') || $el.hasClass('select2-hidden-accessible')) {
        $el.prop('disabled', !!disabled).trigger('change.select2');
      }
    });
  }

  // Show/hide (unchanged)
  function toggleCustomPricingUI() {
    toggleSections();
    const fixed = isCustomFixed();
    $('#rate').prop('readonly', isPricingCustom() && !fixed);

    const $keep = [
      $('#custom_pricing_type').closest('.form-group'),
      $('#currency_id').closest('.form-group'),
      $('#rate').closest('.form-group')
    ];

    if (fixed) {
      $('.custom').hide();
      $keep.forEach($g => $g.show());
      pmSetDisabled([
        'industry_vertical_id', 'department_id', 'service_offering_id',
        'unit_of_measurement_id', 'description_id',
        'skills[]', 'average_handling_time[]', 'ctc[]',
        'project_management_cost', 'vendor_cost', 'infrastructure_cost',
        'overhead_percentage', 'margin_percentage',
        'volume', 'volume_based_discount', 'conversion_rate'
      ], true);
      pmSetDisabled(['custom_pricing_type', 'currency_id', 'rate'], false);
    } else {
      if (isPricingCustom()) {
        $('.custom').show();
      } else {
        $('.custom').hide();
        $('.static').show();
      }
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

  // =========================
  // ✅ Minimal mirror logic (NEW) — keeps values submitted when selects are hidden/disabled
  // =========================
  function setMirrorPair(selectId, hiddenId, fieldName, activateMirror) {
    const $sel = $('#' + selectId);
    const $hid = $('#' + hiddenId);
    if (activateMirror) {
      if ($sel.attr('name')) {
        $hid.attr('name', fieldName);
        const val = $sel.val() || $hid.val() || '';
        $hid.val(val);
        $sel.removeAttr('name');       // prevent duplicate submit
      }
    } else {
      if (!$sel.attr('name')) {
        $sel.attr('name', fieldName);
        const val = $hid.val() || $sel.val() || '';
        $sel.val(val).trigger('change');
        $hid.removeAttr('name');
      }
    }
  }

  function updateMirrors() {
    const fixed = isCustomFixed();
    if (fixed) {
      setMirrorPair('industry_vertical_id', 'h_industry_vertical_id', 'industry_vertical_id', true);
      setMirrorPair('department_id',        'h_department_id',        'department_id',        true);
      setMirrorPair('service_offering_id',  'h_service_offering_id',  'service_offering_id',  true);
    } else {
      setMirrorPair('industry_vertical_id', 'h_industry_vertical_id', 'industry_vertical_id', false);
      setMirrorPair('department_id',        'h_department_id',        'department_id',        false);
      setMirrorPair('service_offering_id',  'h_service_offering_id',  'service_offering_id',  false);
    }
  }

  // keep hidden mirrors synced on any change of the real selects
  $(document).on('change', '#industry_vertical_id, #department_id, #service_offering_id', function(){
    $('#h_' + this.id).val($(this).val() || '');
  });

  // Disable all form fields if approved and user is not super admin AND no modification request
  @if(isset($data) && $data->approval_status === 'approved' && !auth()->user()->hasRole('super admin') && empty($data->modification_notes))
    $(document).ready(function() {
      $('#pricingMasterForm').find('input, select, textarea, button[type="submit"]').not('[type="hidden"]').prop('disabled', true);
      $('#pricingMasterForm').find('select.select2').each(function() {
        if ($(this).hasClass('select2-hidden-accessible')) {
          $(this).next('.select2').find('.select2-selection').css('pointer-events', 'none').css('opacity', '0.6');
        }
      });
    });
  @endif

})(jQuery, window, document);
</script>
@endsection

@section('content')
@php
  $isApproved = isset($data) && $data->approval_status === 'approved';
  $isRejected = isset($data) && $data->approval_status === 'rejected';
  $isSuperAdmin = auth()->user()->hasRole('super admin');
  $hasModificationRequest = isset($data) && !empty($data->modification_notes);
  // Can edit if: not approved, OR super admin, OR modification was requested
  $canEdit = !$isApproved || $isSuperAdmin || $hasModificationRequest;
@endphp
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

          @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Please fix the following errors:</strong>
              <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if($isApproved && !$isSuperAdmin && !$hasModificationRequest)
            <div class="alert alert-info">
              <i class="ti ti-info-circle me-2"></i>
              This pricing master is approved and cannot be edited. Only super admin can request modifications.
            </div>
          @endif

          @if(isset($data) && $data->modification_notes)
            <div class="alert alert-warning">
              <h6><i class="ti ti-alert-triangle me-2"></i>Modification Required</h6>
              <p><strong>Modification Notes:</strong> {{ $data->modification_notes }}</p>
              <p><strong>Modification Parameter:</strong> {{ $data->modification_parameter }}</p>
            </div>
          @endif

          <div class="row">
@php
  // Resolve customer_id from old/model/request
  $resolvedCustomerId = old('customer_id', $data->customer_id ?? request('customer_id'));

  // ✅ also resolve cascades from request() for URL-prefill
  $resolvedIV   = old('industry_vertical_id', $data->industry_vertical_id ?? request('industry_vertical_id'));   // ✅
  $resolvedDept = old('department_id',        $data->department_id        ?? request('department_id'));          // ✅
  $resolvedSO   = old('service_offering_id',  $data->service_offering_id  ?? request('service_offering_id'));    // ✅

  $forced = $resolvedCustomerId ? 'custom' : 'static';
  $pricingType = old('pricing_type', $data->pricing_type ?? $forced);
  if ($pricingType !== $forced) { $pricingType = $forced; }
@endphp

<div class="col-md-12 mb-4 form-group">
  <label class="form-label d-block">Pricing Type <span class="text-danger">*</span></label>

  @if($forced === 'custom')
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="pricing_type" id="pricing_custom" value="custom" checked>
      <label class="form-check-label" for="pricing_custom">Custom</label>
    </div>
    <div class="form-text">Customer selected — only Custom pricing is allowed.</div>
  @else
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="pricing_type" id="pricing_static" value="static" checked>
      <label class="form-check-label" for="pricing_static">Standard</label>
    </div>
    <div class="form-text">No customer selected — only Standard pricing is allowed.</div>
  @endif
</div>

            {{-- Basic Details --}}
            <div class="col-md-12 my-4">
              <div class="d-flex align-items-center">
                <div class="flex-grow-1 border-top border-grey"></div>
                <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Basic Details</span>
                <div class="flex-grow-1 border-top border-grey"></div>
              </div>
            </div>

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

            <div class="col-md-4 mb-3 form-group static custom">
              <label for="industry_vertical_id" class="form-label">Industry Vertical <span class="text-danger">*</span></label>
              <select name="industry_vertical_id" id="industry_vertical_id" class="form-select select2">
                <option value="">Select</option>
                @foreach($masters['industry_vertical'] as $item)
                  <option value="{{ $item->id }}" {{ (string)$resolvedIV === (string)$item->id ? 'selected' : '' }}>  {{-- ✅ request prefill --}}
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
                  <option value="{{ $item->id }}" {{ (string)$resolvedDept === (string)$item->id ? 'selected' : '' }}> {{-- ✅ --}}
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
                  <option value="{{ $item->id }}" {{ (string)$resolvedSO === (string)$item->id ? 'selected' : '' }}> {{-- ✅ --}}
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

            {{-- ✅ Hidden mirrors to submit values when selects are hidden/disabled --}}
            <input type="hidden" id="h_industry_vertical_id"  value="{{ $resolvedIV }}">   {{-- no name here --}}
            <input type="hidden" id="h_department_id"         value="{{ $resolvedDept }}">
            <input type="hidden" id="h_service_offering_id"   value="{{ $resolvedSO }}">

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
                $oldAHT    = old('average_handling_time', isset($data) ? $data->skillLines->pluck('average_handling_time')->toArray() : []);
                $oldCTC    = old('ctc', isset($data) ? $data->skillLines->pluck('ctc')->toArray() : []);
                $rowCount  = max(count($oldSkills), count($oldAHT), count($oldCTC), 1);
              @endphp

              @for ($i = 0; $i < $rowCount; $i++)
              <div class="row skill-row">
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

                <div class="mb-3 col-md-3 form-group">
                  <input type="text" name="average_handling_time[]" class="form-control"
                         placeholder="Enter Average Handling Time" value="{{ $oldAHT[$i] ?? '' }}">
                </div>

                <div class="mb-3 col-md-3 form-group">
                  <input type="text" name="ctc[]" class="form-control ctc-field"
                         placeholder="CTC" value="{{ $oldCTC[$i] ?? '' }}" readonly>
                </div>

                <div class="col-md-3 d-flex align-items-end mb-3"></div>
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
              <input type="text" name="project_management_cost" class="form-control" placeholder="Enter Project Management cost"
                     value="{{ old('project_management_cost', $data->project_management_cost ?? '') }}">
            </div>

            <div class="mb-3 col-md-4 form-group custom">
              <label for="vendor_cost" class="form-label">Vendor Cost </label>
              <input type="text" name="vendor_cost" class="form-control" placeholder="Enter Vendor Cost"
                     value="{{ old('vendor_cost', $data->vendor_cost ?? '') }}">
            </div>

            <div class="mb-3 col-md-4 form-group custom">
              <label for="infrastructure_cost" class="form-label">Infrastructure Cost <span class="text-danger">*</span></label>
              <input type="text" name="infrastructure_cost" class="form-control" placeholder="Enter Infrastructure Cost"
                     value="{{ old('infrastructure_cost', $data->infrastructure_cost ?? '') }}">
            </div>

            <div class="mb-3 col-md-4 form-group custom">
              <label for="overhead_percentage" class="form-label">Overhead (%) <span class="text-danger">*</span></label>
              <input type="text" name="overhead_percentage" class="form-control" placeholder="Enter Overhead Percentage"
                     value="{{ old('overhead_percentage', $data->overhead_percentage ?? '') }}">
            </div>

            <div class="mb-3 col-md-4 form-group custom">
              <label for="margin_percentage" class="form-label">Margin (%) <span class="text-danger">*</span></label>
              <input type="text" name="margin_percentage" class="form-control" placeholder="Enter Margin Percentage"
                     value="{{ old('margin_percentage', $data->margin_percentage ?? '') }}">
            </div>

            <div class="mb-3 col-md-4 form-group custom">
              <label for="volume" class="form-label">Volume </label>
              <input type="text" name="volume" class="form-control" placeholder="Enter Volume"
                     value="{{ old('volume', $data->volume ?? '') }}">
            </div>

            <div class="mb-3 col-md-4 form-group custom">
              <label for="volume_based_discount" class="form-label">Volume Based Addition/Discounts <span class="text-danger">*</span></label>
              <input type="text" name="volume_based_discount" class="form-control" placeholder="Enter Volume Based Addition/Discounts"
                     value="{{ old('volume_based_discount', $data->volume_based_discount ?? '') }}">
            </div>

            <div class="mb-3 col-md-4 form-group custom">
              <label for="conversion_rate" class="form-label">Conversion Rate <span class="text-danger">*</span></label>
              <input type="text" name="conversion_rate" class="form-control" placeholder="Enter Conversion Rate"
                     value="{{ old('conversion_rate', isset($data->conversion_rate) ? number_format($data->conversion_rate, 2, '.', '') : '') }}">
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
              <input type="text" class="form-control" id="rate" name="rate"
                     value="{{ old('rate', $data->rate ?? '') }}" placeholder="Enter Rate">
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
              <input type="text" name="name" id="name" class="form-control" placeholder="Enter pricing name"
                     value="{{ old('name', $data->name ?? '') }}">
            </div>

            <div class="mb-3 col-md-4 form-group">
              <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
              @php $statusVal = (string) old('status', $data->status ?? 1); @endphp
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
            <input type="file" class="form-control @error('document') is-invalid @enderror"
                   id="document" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
            @error('document')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if(isset($data) && $data->document_path)
             <div class="mt-2 d-flex align-items-center gap-3 flex-wrap">
                <a href="{{ Storage::url($data->document_path) }}" target="_blank" rel="noopener">
                  View current document
                </a>

                {{-- ✅ simple remove toggle (no JS dependency) --}}
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="remove_document" name="remove_document">
                  <label class="form-check-label" for="remove_document">
                    Remove this document on save
                  </label>
                </div>
                <small class="text-muted d-block" style="margin-left:1.9rem;">
                  (If you also upload a new file above, the new file will replace it.)
                </small>
              </div>
            @endif
          </div>

          <div class="text-end">
            <a href="{{ route('pricing-master.index') }}" class="btn btn-secondary">
              <i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>Back
            </a>
            @if($canEdit)
              <button type="submit" class="btn btn-primary">
                {{ $type == 'create' ? 'Save' : 'Update' }}
                <i class="ti ti-file-upload ms-1 mb-1"></i>
              </button>
            @endif
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
