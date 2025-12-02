{{-- resources/views/companies/form.blade.php --}}
@extends('layouts/layoutMaster')

@section('title', $title ?? 'Create Company')

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
  $(document).ready(function () {
    // Initialize Select2
    $('select.select2').each(function(){
      if (!$(this).hasClass('select2-hidden-accessible')) $(this).select2();
    });

    // --- Repeater: Team rows ---
    const $wrapper  = $('#team-repeater');
    const $template = $('#teamRowTemplate').html();

    // Ensure proper +/- buttons
    function updateRowButtons() {
      const count = $wrapper.find('.team-row').length;

      $wrapper.find('.team-row .btn-slot').each(function(){
        const $slot = $(this);
        $slot.empty();
        // Always add +
        $slot.append('<button type="button" class="btn btn-success add-row me-1">+</button>');
        // Add - for all rows if there are 2 or more rows
        if (count > 1) {
          $slot.append('<button type="button" class="btn btn-danger remove-row">-</button>');
        }
      });
    }

    // Wire select2 validation
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

    // Bind rules for a team row
    function bindTeamRowValidation($row) {
      const $first  = $row.find('input.first-name');
      const $last   = $row.find('input.last-name');
      const $phone  = $row.find('input.contact-no');
      const $email  = $row.find('input.email');
      const $status = $row.find('select.status-select');

      $first.rules('add',  { required: true, maxlength: 100, messages: { required: "Enter first name." }});
      $last.rules('add',   { required: true, maxlength: 100, messages: { required: "Enter last name." }});
      $phone.rules('add',  { optionalPhone: true, maxlength: 50 });
      $email.rules('add',  { required: true, email: true, maxlength: 255, messages: { required: "Enter email." }});
      $status.rules('add', { required: true });

      wireSelect2Validation($status);
    }

    // Add a row
    $wrapper.on('click', '.add-row', function () {
      let idx = Number($wrapper.data('idx') || 0);
      const html = $template.replaceAll('__IDX__', idx);
      $wrapper.append(html);
      $wrapper.data('idx', idx + 1);

      // init select2 for the newly added status field
      const $newRow     = $wrapper.find('.team-row').last();
      const $lastStatus = $newRow.find('.status-select');
      $lastStatus.select2();

      // notify validator binding
      $wrapper.trigger('row:added', [ $newRow ]);
      updateRowButtons();
    });

    // Remove a row (but keep at least one)
    $wrapper.on('click', '.remove-row', function () {
      const count = $wrapper.find('.team-row').length;
      if (count <= 1) return; // safety: never remove the last one
      $(this).closest('.team-row').remove();
      updateRowButtons();
    });

    // ---------- Validation ----------
    const $form = $("#companyForm");

    $.validator.addMethod("optionalPhone", function (value, element) {
      if (this.optional(element)) return true;
      return /^[0-9+\-() ]+$/.test(value);
    }, "Enter a valid phone.");

    const validator = $form.validate({
      ignore: ":hidden:not(.select2-hidden-accessible)",
      rules: {
        'name': { required: true, maxlength: 255 },
        'address': { maxlength: 255 },
        'location': { maxlength: 255 },
      },
      messages: {
        name: { required: "Enter customer name." }
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

    // Initial bind for existing rows
    $('#team-repeater .team-row').each(function(){ bindTeamRowValidation($(this)); });
    updateRowButtons();

    // Bind on new rows
    $wrapper.on('row:added', function (e, $row) {
      bindTeamRowValidation($row);
    });
  });
</script>

<script>
  // Show/Hide "Invoice Entity" when Company Type is Non-India
  $(function () {
    function currentCompanyType() {
      const $choice = $('[name="company_type"]');
      if ($choice.is('select')) return String($choice.val() || '');
      return String($('input[name="company_type"]:checked').val() || '');
    }

    function toggleInvoiceEntity() {
      const show = currentCompanyType() === '2'; // Non-India
      const $grp = $('#invoice_type_group');
      if (show) {
        $grp.removeClass('d-none');
      } else {
        $grp.addClass('d-none');
        $('input[name="invoice_type"][value="1"]').prop('checked', true);
      }
    }

    $(document).on('change', 'input[name="company_type"], select[name="company_type"]', toggleInvoiceEntity);
    toggleInvoiceEntity();
  });
</script>
@endsection


@section('content')
@php
  // Defaults for old input / edit mode
  $company = $company ?? null;

  $oldTeam = old('team', $team ?? [
    [
      'first_name' => '',
      'last_name' => '',
      'contact_no' => '',
      'email' => '',
      'status' => '1',
      'is_billing' => '0',
      'is_project' => '0'
    ]
  ]);
@endphp

<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header">
        <h4 class="text-dark">{{ $title ?? 'Create Customer' }}</h4>
      </div>

      <div class="card-body">
        <form id="companyForm" method="POST" action="{{ $type == 'edit'
              ? route('companies.update', Crypt::encryptString($company?->id)) 
              : route('companies.store') }}">
          @csrf
          @if(($type ?? 'create') === 'edit') @method('PUT') @endif

          {{-- Company Information --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Customer Information</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="row">
            <div class="mb-3 col-md-6 form-group">
              <label for="name" class="form-label">Customer Name <span class="text-danger">*</span></label>
              <input type="text" name="name" id="name" class="form-control"
                     value="{{ old('name', $company->name ?? '') }}" placeholder="Enter customer name" required>
              @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3 col-md-6 form-group">
              <label for="address" class="form-label">Address</label>
              <input type="text" name="address" id="address" class="form-control"
                     value="{{ old('address', $company->address ?? '') }}" placeholder="Enter address">
              @error('address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3 col-md-4 form-group">
              <label for="location" class="form-label">Location</label>
              <input type="text" name="location" id="location" class="form-control"
                     value="{{ old('location', $company->location ?? '') }}" placeholder="City / State / Country">
              @error('location')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3 col-md-4 form-group">
              <label for="contact_no" class="form-label">Contact No.</label>
              <input type="text" name="contact_no" id="contact_no" class="form-control"
                     value="{{ old('contact_no', $company->contact_no ?? '') }}" placeholder="1234567890">
              @error('contact_no')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3 col-md-4 form-group">
              <label for="website" class="form-label">Website</label>
              <input type="text" name="website" id="website" class="form-control"
                     value="{{ old('website', $company->website ?? '') }}" placeholder="https://example.com">
              @error('website')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3 col-md-4">
              <label for="zip_code" class="form-label">ZIP / Postal Code</label>
              <input type="text"
                    id="zip_code"
                    name="zip_code"
                    class="form-control @error('zip_code') is-invalid @enderror"
                    value="{{ old('zip_code', $company->zip_code ?? '') }}"
                    placeholder="e.g., 560001 or 94105">
              @error('zip_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Company Type (1 = India, 2 = Non-India) --}}
            <div class="mb-3">
              <label class="form-label d-block">Company Type <span class="text-danger">*</span></label>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="company_type" id="ct_india" value="1"
                  {{ (string)old('company_type', $company->company_type ?? 1) === '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="ct_india">India</label>
              </div>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="company_type" id="ct_non_india" value="2"
                  {{ (string)old('company_type', $company->company_type ?? 1) === '2' ? 'checked' : '' }}>
                <label class="form-check-label" for="ct_non_india">Global</label>
              </div>
            </div>

            {{-- Invoice Entity (only shown when company_type == 2) --}}
            <div id="invoice_type_group"
                class="{{ (string)old('company_type', $company->company_type ?? 1) === '2' ? '' : 'd-none' }}">
              <label class="form-label d-block">Invoice Entity <span class="text-danger">*</span></label>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="invoice_type" id="inv_india" value="1"
                  {{ (string)old('invoice_type', $company->invoice_type ?? 1) === '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="inv_india">Springbord Systems Pvt Ltd, India</label>
              </div>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="invoice_type" id="inv_us" value="2"
                  {{ (string)old('invoice_type', $company->invoice_type ?? 1) === '2' ? 'checked' : '' }}>
                <label class="form-check-label" for="inv_us">Springbord Smartshore LLC, US</label>
              </div>
            </div>
          </div>

          {{-- Team / Contact Information --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Customer Contact Information</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2"><label class="form-label">First Name <span class="text-danger">*</span></label></div>
            <div class="col-md-2"><label class="form-label">Last Name <span class="text-danger">*</span></label></div>
            <div class="col-md-2"><label class="form-label">Contact No.</label></div>
            <div class="col-md-2"><label class="form-label">Email <span class="text-danger">*</span></label></div>
            <div class="col-md-2"><label class="form-label">Status</label></div>
            <div class="col-md-1"><label class="form-label">Billing</label></div>
            <div class="col-md-1"><label class="form-label">Project</label></div>
          </div>

          @php
            $rowCount = max(count($oldTeam), 1);
          @endphp

          <div id="team-repeater" data-idx="{{ $rowCount }}">
            @for ($i = 0; $i < $rowCount; $i++)
              @php
                $row = $oldTeam[$i] ?? [
                  'first_name'=>'','last_name'=>'','contact_no'=>'','email'=>'',
                  'status'=>'1','is_billing'=>'0','is_project'=>'0'
                ];
              @endphp
              <div class="row team-row align-items-end mb-2">
                <div class="mb-3 col-md-2 form-group">
                  <input type="text" name="team[{{ $i }}][first_name]" class="form-control first-name"
                         value="{{ $row['first_name'] ?? '' }}" placeholder="First name" required>
                  @error('team.'.$i.'.first_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3 col-md-2 form-group">
                  <input type="text" name="team[{{ $i }}][last_name]" class="form-control last-name"
                         value="{{ $row['last_name'] ?? '' }}" placeholder="Last name" required>
                  @error('team.'.$i.'.last_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3 col-md-2 form-group">
                  <input type="text" name="team[{{ $i }}][contact_no]" class="form-control contact-no"
                         value="{{ $row['contact_no'] ?? '' }}" placeholder="Phone">
                  @error('team.'.$i.'.contact_no')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3 col-md-2 form-group">
                  <input type="email" name="team[{{ $i }}][email]" class="form-control email"
                         value="{{ $row['email'] ?? '' }}" placeholder="email@example.com" required>
                  @error('team.'.$i.'.email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3 col-md-2 form-group">
                  <select name="team[{{ $i }}][status]" class="form-select status-select select2">
                    <option value="1" {{ (string)($row['status'] ?? '1') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ (string)($row['status'] ?? '0') === '0' ? 'selected' : '' }}>Inactive</option>
                  </select>
                  @error('team.'.$i.'.status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3 col-md-1 d-flex align-items-center">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           name="team[{{ $i }}][is_billing]" value="1"
                           {{ !empty($row['is_billing_contact']) && (string)$row['is_billing_contact'] === '1' ? 'checked' : '' }}>
                  </div>
                </div>

                <div class="mb-3 col-md-1 d-flex align-items-center">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           name="team[{{ $i }}][is_project]" value="1"
                           {{ !empty($row['is_project_contact']) && (string)$row['is_project_contact'] === '1' ? 'checked' : '' }}>
                  </div>
                </div>

                <div class="col-md-12 d-flex justify-content-end btn-slot">
                  <!-- buttons will be injected by JS -->
                </div>
              </div>
            @endfor
          </div>

          {{-- Actions --}}
          <div class="text-end mt-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
              <i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>Back
            </a>
            <button type="submit" class="btn btn-primary">
              {{ ($type ?? 'create') === 'edit' ? 'Update' : 'Save' }}
              <i class="ti ti-file-upload ms-1 mb-1"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Hidden template for new rows --}}
<template id="teamRowTemplate">
  <div class="row team-row align-items-end mb-2">
    <div class="mb-3 col-md-2 form-group">
      <input type="text" name="team[__IDX__][first_name]" class="form-control first-name" placeholder="First name" required>
    </div>
    <div class="mb-3 col-md-2 form-group">
      <input type="text" name="team[__IDX__][last_name]" class="form-control last-name" placeholder="Last name" required>
    </div>
    <div class="mb-3 col-md-2 form-group">
      <input type="text" name="team[__IDX__][contact_no]" class="form-control contact-no" placeholder="Phone">
    </div>
    <div class="mb-3 col-md-2 form-group">
      <input type="email" name="team[__IDX__][email]" class="form-control email" placeholder="email@example.com" required>
    </div>
    <div class="mb-3 col-md-2 form-group">
      <select name="team[__IDX__][status]" class="form-select status-select select2">
        <option value="1" selected>Active</option>
        <option value="0">Inactive</option>
      </select>
    </div>
    <div class="mb-3 col-md-1 d-flex align-items-center">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="team[__IDX__][is_billing]" value="1">
      </div>
    </div>
    <div class="mb-3 col-md-1 d-flex align-items-center">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="team[__IDX__][is_project]" value="1">
      </div>
    </div>
    <div class="col-md-12 d-flex justify-content-end btn-slot"></div>
  </div>
</template>
@endsection
