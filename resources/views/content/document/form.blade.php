@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@extends('layouts/layoutMaster')
@section('title', $title)

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
@endsection

@section('vendor-script')
{{-- Required libs (assumes jQuery is already loaded by layout) --}}
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>

{{-- jQuery Validation MUST be loaded before page-specific code --}}
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
@endsection

@section('content')
@php
/** @var \App\Models\Document|null $document */
$isEdit = isset($type) && $type === 'edit';
$action = $isEdit ? route('document.update', $document->id) : route('document.store');
$masters = Helper::getDocumentMasterData();
// dd($masters);
@endphp
@php
// Build selected list (use old() first)
$selectedPocIds = collect(old('pocs', isset($project) ? $project->pocs->pluck('id')->all() : []))
->filter(fn($v) => !empty($v))
->values()
->all();

if (empty($selectedPocIds)) {
  $selectedPocIds = [null]; // one empty row
}

// Helper for POC label (adjust to your model fields)
$pocLabel = function($p) {
  return trim(($p->first_name ?? $p->name ?? '') . ' ' . ($p->last_name ?? ''))
    . ($p->email ? " ({$p->email})" : '');
};
@endphp

{{-- Seed POC cache for the initially selected customer (important for edit mode) --}}
<script>
  window.__pocSeed = {!! json_encode(
    ($pocsForCustomer ?? collect())->map(function($p){
      return [
        'id'    => $p->id,
        'name'  => trim(($p->first_name ?? $p->name ?? '').' '.($p->last_name ?? '')),
        'email' => $p->email,
      ];
    })->values()
  ) !!};
</script>

<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header">
        <h4 class="text-dark">{{ $title }}</h4>

      </div>

      <div class="card-body">
        <form id="documentForm" method="POST" action="{{ $action }}" enctype="multipart/form-data" novalidate>
          @csrf
          @if($isEdit) @method('PUT') @endif

          {{-- ===================== Customer & POCs ===================== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Customer & Contacts</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="row g-3">
           <div class="col-md-4">
              <label for="customer_id" class="form-label">
                Customer <span class="text-danger">*</span>
              </label>

              @php
                $selectedCustomerId = (string) old('customer_id', $document->customer_id ?? ($presetCustomerId ?? ''));
              @endphp
              <input type="hidden" name="customer_id" id="customer_id" value="{{ $selectedCustomerId }}">
              @if(!empty($presetCustomerId))
                {{-- Submit value via hidden input, show disabled select for clarity --}}
                <!-- <input type="hidden" name="customer_id" id="customer_id" value="{{ $selectedCustomerId }}"> -->

                <select class="form-select select2" disabled>
                  <option value="">Select Customer</option>
                  @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ $selectedCustomerId === (string) $c->id ? 'selected' : '' }}>
                      {{ $c->name }}
                    </option>
                  @endforeach
                </select>
                <div class="form-text">Customer is preset from the link.</div>
              @else
                <select name="customer_id" id="customer_id" class="form-select select2" disabled>
                  <option value="">Select Customer</option>
                  @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ $selectedCustomerId === (string) $c->id ? 'selected' : '' }}>
                      {{ $c->name }}
                    </option>
                  @endforeach
                </select>
              @endif

              @error('customer_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>


            <div class="col-md-6">
              <label for="description" class="form-label">Description<span class="text-danger">*</span></label>
               <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $document->description ?? '') }}</textarea>

            </div>

          </div>

          {{-- ===================== Timeline ===================== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Timeline</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>


          {{-- Unified Contract Dates and Alerts Repeater --}}
          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label">Contract Dates & Alerts <span class="text-danger">*</span></label>
              <div id="contract-alert-repeater" class="d-flex flex-column gap-3">
                @php
                  // Get existing contracts and alerts for edit mode
                  $existingRows = [];
                  if (old('contracts')) {
                    // Use old input if validation failed
                    $contracts = old('contracts', []);
                    $alerts = old('alerts', []);
                    foreach ($contracts as $index => $contract) {
                      $alertDays = $alerts[$index]['alert_days'] ?? [];
                      if (!is_array($alertDays)) {
                        $alertDays = $alertDays ? [$alertDays] : [];
                      }
                      $existingRows[] = [
                        'contract_start_date' => $contract['contract_start_date'] ?? '',
                        'contract_end_date' => $contract['contract_end_date'] ?? '',
                        'is_active' => isset($contract['is_active']) ? (bool)$contract['is_active'] : true,
                        'alert_days' => $alertDays,
                        'alert_file' => $alerts[$index]['alert_file'] ?? ($alerts[$index]['existing_file'] ?? ''),
                      ];
                    }
                  } elseif (isset($document)) {
                    // Get contracts with their alerts
                    $contracts = $document->contracts ?? collect();
                    foreach ($contracts as $contract) {
                      $contractAlerts = $contract->alerts ?? collect();
                      $alertDays = [];
                      $alertFile = '';
                      foreach ($contractAlerts as $alert) {
                        $days = is_array($alert->alert_days) ? $alert->alert_days : ($alert->alert_days ? [$alert->alert_days] : []);
                        $alertDays = array_merge($alertDays, $days);
                        if (!$alertFile && $alert->alert_file) {
                          $alertFile = $alert->alert_file;
                        }
                      }
                      $existingRows[] = [
                        'contract_start_date' => $contract ? \Carbon\Carbon::parse($contract->contract_start_date)->format('m/d/Y') : '',
                        'contract_end_date' => $contract ? \Carbon\Carbon::parse($contract->contract_end_date)->format('m/d/Y') : '',
                        'is_active' => $contract->is_active ?? true,
                        'alert_days' => array_unique($alertDays),
                        'alert_file' => $alertFile,
                      ];
                    }
                  }
                  // If no rows, show one empty row
                  if (empty($existingRows)) {
                    $existingRows = [['contract_start_date' => '', 'contract_end_date' => '', 'is_active' => true, 'alert_days' => [], 'alert_file' => '']];
                  }
                @endphp
                
                @foreach($existingRows as $index => $row)
                  <div class="contract-alert-row border rounded p-3 mb-3" data-index="{{ $index }}">
                    <div class="row g-3 align-items-end">
                      <div class="col-md-3">
                        <label class="form-label">Contract Start Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker contract-start-date" 
                          name="contracts[{{ $index }}][contract_start_date]" 
                          value="{{ old("contracts.{$index}.contract_start_date", $row['contract_start_date'] ?? '') }}"
                          placeholder="MM/DD/YYYY" autocomplete="off" required>
                        @error("contracts.{$index}.contract_start_date") <div class="text-danger small">{{ $message }}</div> @enderror
                      </div>
                      <div class="col-md-3">
                        <label class="form-label">Contract End Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker contract-end-date" 
                          name="contracts[{{ $index }}][contract_end_date]" 
                          value="{{ old("contracts.{$index}.contract_end_date", $row['contract_end_date'] ?? '') }}"
                          placeholder="MM/DD/YYYY" autocomplete="off" required>
                        @error("contracts.{$index}.contract_end_date") <div class="text-danger small">{{ $message }}</div> @enderror
                      </div>
                      <div class="col-md-2">
                        <label class="form-label">Active</label>
                        <div class="form-check form-switch">
                          <input class="form-check-input timeline-active-switch" type="checkbox" 
                            name="contracts[{{ $index }}][is_active]" 
                            id="is_active_{{ $index }}" 
                            value="1"
                            {{ old("contracts.{$index}.is_active", $row['is_active'] ?? true) ? 'checked' : '' }}>
                          <label class="form-check-label" for="is_active_{{ $index }}"></label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <label class="form-label">Upload File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control alert-file-input @error("alerts.{$index}.alert_file") is-invalid @enderror" 
                          name="alerts[{{ $index }}][alert_file]"
                          accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,image/png,image/jpeg"
                          {{ !isset($row['alert_file']) || !$row['alert_file'] ? 'required' : '' }}>
                        @error("alerts.{$index}.alert_file") 
                          <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @if(isset($row['alert_file']) && $row['alert_file'])
                          <div class="mt-1">
                            <input type="hidden" name="alerts[{{ $index }}][existing_file]" value="{{ $row['alert_file'] }}">
                            <a href="{{ Storage::url($row['alert_file']) }}" target="_blank" rel="noopener" class="small">
                              View current file
                            </a>
                          </div>
                        @endif
                      </div>
                      <div class="col-md-1">
                        <button type="button" class="btn btn-outline-danger contract-alert-remove-btn" {{ count($existingRows) <= 1 ? 'disabled' : '' }}>
                          <i class="ti ti-trash"></i>
                        </button>
                      </div>
                    </div>
                    <div class="row g-3 mt-2 alert-days-row" style="display: {{ old("contracts.{$index}.is_active", $row['is_active'] ?? true) ? 'block' : 'none' }};">
                      <div class="col-md-12">
                        @php
                          $selectedDays = old("alerts.{$index}.alert_days", $row['alert_days'] ?? []);
                          if (!is_array($selectedDays)) {
                            $selectedDays = $selectedDays ? [$selectedDays] : [];
                          }
                          $hasAlertDays = !empty($selectedDays);
                        @endphp
                        <div class="form-check mb-2">
                          <input class="form-check-input alert-before-master-checkbox" type="checkbox" 
                            id="alert_before_master_{{ $index }}" 
                            {{ $hasAlertDays ? 'checked' : '' }}>
                          <label class="form-check-label fw-semibold" for="alert_before_master_{{ $index }}">
                            Alert Before (Days)
                          </label>
                        </div>
                        <div class="alert-days-checkboxes-container" style="display: {{ $hasAlertDays ? 'block' : 'none' }};">
                          <div class="d-flex gap-3">
                            <div class="form-check">
                              <input class="form-check-input alert-day-checkbox" type="checkbox" 
                                name="alerts[{{ $index }}][alert_days][]" 
                                id="alert_days_{{ $index }}_7" 
                                value="7"
                                {{ in_array(7, $selectedDays) ? 'checked' : '' }}>
                              <label class="form-check-label" for="alert_days_{{ $index }}_7">7 Days</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input alert-day-checkbox" type="checkbox" 
                                name="alerts[{{ $index }}][alert_days][]" 
                                id="alert_days_{{ $index }}_15" 
                                value="15"
                                {{ in_array(15, $selectedDays) ? 'checked' : '' }}>
                              <label class="form-check-label" for="alert_days_{{ $index }}_15">15 Days</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input alert-day-checkbox" type="checkbox" 
                                name="alerts[{{ $index }}][alert_days][]" 
                                id="alert_days_{{ $index }}_30" 
                                value="30"
                                {{ in_array(30, $selectedDays) ? 'checked' : '' }}>
                              <label class="form-check-label" for="alert_days_{{ $index }}_30">30 Days</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input alert-day-checkbox" type="checkbox" 
                                name="alerts[{{ $index }}][alert_days][]" 
                                id="alert_days_{{ $index }}_60" 
                                value="60"
                                {{ in_array(60, $selectedDays) ? 'checked' : '' }}>
                              <label class="form-check-label" for="alert_days_{{ $index }}_60">60 Days</label>
                            </div>
                          </div>
                        </div>
                        @error("alerts.{$index}.alert_days") <div class="text-danger small">{{ $message }}</div> @enderror
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="mt-2">
                <button type="button" class="btn btn-outline-primary" id="add-contract-alert-btn">
                  <i class="ti ti-plus"></i> Add Contract Date Range
                </button>
              </div>
            </div>
          </div>



          {{-- ===================== Ownership (PM Repeater) ===================== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Ownership</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Project Managers </label>

              <div id="pm-repeater" class="d-flex flex-column gap-2">
               @php
                $existingPms = old(
                    'project_manager_id',
                    isset($document) && $document ? [$document->project_manager_id] : [null]
                );
            @endphp

                @foreach($existingPms as $pmId)
                    <select name="project_manager_id[]" class="form-select pm-select" style="width:100%;">
                      <option value="">Select PM</option>
                      @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ (string)$pmId === (string)$u->id ? 'selected' : '' }}>
                            {{ $u->first_name }} {{ $u->last_name }}
                        </option>
                      @endforeach
                    </select>
                @endforeach
              </div>


            </div>
          </div>



          {{-- ===================== Configuration ===================== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Configuration</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>
          <div class="row g-3">
            <div class="mb-3 custom-validation col-md-4 col-lg-4">
              <label for="status" class="form-label">Status <span class="text-danger">*</span></label>

              @php
                // Default to 1 (Active) for create; keep old()/model value for edit/validation errors
                $statusVal = old('status', isset($document) ? $document->status : 1);
              @endphp

              <select name="status" id="status" class="form-control form-select {{ $errors->has('status') ? 'is-invalid' : '' }}">
                <option value="">Select Status</option>
                <option value="1" {{ (string)$statusVal === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ (string)$statusVal === '0' ? 'selected' : '' }}>Inactive</option>
              </select>

              @error('status')
                <span class="invalid-feedback d-block" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>


            <div class="col-md-4 mb-3 form-group static custom">
              <label for="industry_vertical_id" class="form-label">Industry Vertical <span class="text-danger">*</span></label>
              <select name="industry_vertical_id" id="industry_vertical_id" class="form-select select2">
                <option value="">Select</option>
                @foreach($masters['industry_vertical'] as $item)
                <option value="{{ $item->id }}" {{ old('industry_vertical_id', $document->industry_vertical_id ?? '') == $item->id ? 'selected' : '' }}>
                  {{ $item->name }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-3 form-group static custom">
              <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
              <select name="department_id" id="department_id" class="form-select select2">
                <option value="">Select</option>
                @foreach($masters['departments'] as $item)
                <option value="{{ $item->id }}" {{ old('department_id', $document->department_id ?? '') == $item->id ? 'selected' : '' }}>
                  {{ $item->name }}
                </option>
                @endforeach
              </select>
            </div>
                </div>

          {{-- ===================== Actions ===================== --}}
          <div class="col-md-12 my-4 d-flex justify-content-end gap-2">
            <a href="{{ route('document.index') }}" class="btn btn-secondary">
              <i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>Back
            </a>
            <button type="submit" class="btn btn-primary">
              {{ $isEdit ? 'Update' : 'Save' }}
              <i class="ti ti-file-upload ms-1 mb-1"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('extra-script')
<script>
  $(function() {
    // ---------- UI ----------
    $('.select2').select2({ width: '100%' });

    const $form = $('#documentForm');
    const $isRecurring = $('#is_recurring');
    const $recurrenceGroup = $('#recurrence_group');
    const $customerSel = $('#customer_id');
    const $contractAlertRepeater = $('#contract-alert-repeater');

    function toggleRecurrence() {
      $recurrenceGroup.toggle($isRecurring.is(':checked'));
    }
    $isRecurring.on('change', toggleRecurrence);
    toggleRecurrence();

    // Unified Contract & Alert Repeater Functions
    function getNextContractAlertIndex() {
      const rows = $contractAlertRepeater.find('.contract-alert-row');
      if (rows.length === 0) return 0;
      const maxIndex = Math.max(...Array.from(rows).map(r => parseInt($(r).attr('data-index')) || 0));
      return maxIndex + 1;
    }

    function updateContractAlertRemoveButtons() {
      const rows = $contractAlertRepeater.find('.contract-alert-row');
      rows.each(function() {
        const $btn = $(this).find('.contract-alert-remove-btn');
        $btn.prop('disabled', rows.length <= 1);
      });
    }

    function addContractAlertRow(startDate = '', endDate = '', isActive = true, alertDays = [], existingFile = '') {
      const index = getNextContractAlertIndex();
      const alertDaysArray = Array.isArray(alertDays) ? alertDays : (alertDays ? [alertDays] : []);
      const checked7 = alertDaysArray.includes(7) ? 'checked' : '';
      const checked15 = alertDaysArray.includes(15) ? 'checked' : '';
      const checked30 = alertDaysArray.includes(30) ? 'checked' : '';
      const checked60 = alertDaysArray.includes(60) ? 'checked' : '';
      const $row = $(`
        <div class="contract-alert-row border rounded p-3 mb-3" data-index="${index}">
          <div class="row g-3 align-items-end">
            <div class="col-md-3">
              <label class="form-label">Contract Start Date <span class="text-danger">*</span></label>
              <input type="text" class="form-control datepicker contract-start-date" 
                name="contracts[${index}][contract_start_date]" 
                value="${startDate}"
                placeholder="MM/DD/YYYY" autocomplete="off" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Contract End Date <span class="text-danger">*</span></label>
              <input type="text" class="form-control datepicker contract-end-date" 
                name="contracts[${index}][contract_end_date]" 
                value="${endDate}"
                placeholder="MM/DD/YYYY" autocomplete="off" required>
            </div>
            <div class="col-md-2">
              <label class="form-label">Active</label>
              <div class="form-check form-switch">
                <input class="form-check-input timeline-active-switch" type="checkbox" 
                  name="contracts[${index}][is_active]" 
                  id="is_active_${index}" 
                  value="1"
                  ${isActive ? 'checked' : ''}>
                <label class="form-check-label" for="is_active_${index}"></label>
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label">Upload File <span class="text-danger">*</span></label>
              <input type="file" class="form-control alert-file-input" 
                name="alerts[${index}][alert_file]"
                accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,image/png,image/jpeg"
                ${existingFile ? '' : 'required'}>
              ${existingFile ? `
                <div class="mt-1">
                  <input type="hidden" name="alerts[${index}][existing_file]" value="${existingFile}">
                  <a href="/storage/${existingFile}" target="_blank" rel="noopener" class="small">
                    View current file
                  </a>
                </div>
              ` : ''}
            </div>
            <div class="col-md-1">
              <button type="button" class="btn btn-outline-danger contract-alert-remove-btn">
                <i class="ti ti-trash"></i>
              </button>
            </div>
          </div>
          <div class="row g-3 mt-2 alert-days-row" style="display: ${isActive ? 'block' : 'none'};">
            <div class="col-md-12">
              <div class="form-check mb-2">
                <input class="form-check-input alert-before-master-checkbox" type="checkbox" 
                  id="alert_before_master_${index}" 
                  ${alertDaysArray.length > 0 ? 'checked' : ''}>
                <label class="form-check-label fw-semibold" for="alert_before_master_${index}">
                  Alert Before (Days)
                </label>
              </div>
              <div class="alert-days-checkboxes-container" style="display: ${alertDaysArray.length > 0 ? 'block' : 'none'};">
                <div class="d-flex gap-3">
                  <div class="form-check">
                    <input class="form-check-input alert-day-checkbox" type="checkbox" 
                      name="alerts[${index}][alert_days][]" 
                      id="alert_days_${index}_7" 
                      value="7"
                      ${checked7}>
                    <label class="form-check-label" for="alert_days_${index}_7">7 Days</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input alert-day-checkbox" type="checkbox" 
                      name="alerts[${index}][alert_days][]" 
                      id="alert_days_${index}_15" 
                      value="15"
                      ${checked15}>
                    <label class="form-check-label" for="alert_days_${index}_15">15 Days</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input alert-day-checkbox" type="checkbox" 
                      name="alerts[${index}][alert_days][]" 
                      id="alert_days_${index}_30" 
                      value="30"
                      ${checked30}>
                    <label class="form-check-label" for="alert_days_${index}_30">30 Days</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input alert-day-checkbox" type="checkbox" 
                      name="alerts[${index}][alert_days][]" 
                      id="alert_days_${index}_60" 
                      value="60"
                      ${checked60}>
                    <label class="form-check-label" for="alert_days_${index}_60">60 Days</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `);
      $contractAlertRepeater.append($row);
      
      // Initialize datepicker for new row
      $row.find('.contract-start-date').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        todayHighlight: true
      }).on('changeDate', function(e) {
        const $endDate = $row.find('.contract-end-date');
        $endDate.datepicker('setStartDate', e.date);
        const endD = $endDate.datepicker('getDate');
        if (endD && endD < e.date) $endDate.datepicker('setDate', null);
      });

      $row.find('.contract-end-date').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        todayHighlight: true
      });

      // Bind timeline active switch toggle
      $row.find('.timeline-active-switch').on('change', function() {
        $row.find('.alert-days-row').toggle($(this).is(':checked'));
      });

      // Bind alert before master checkbox toggle
      $row.find('.alert-before-master-checkbox').on('change', function() {
        $row.find('.alert-days-checkboxes-container').toggle($(this).is(':checked'));
      });

      updateContractAlertRemoveButtons();
    }

    // Add contract-alert button
    $('#add-contract-alert-btn').on('click', function() {
      addContractAlertRow();
    });

    // Remove contract-alert button
    $contractAlertRepeater.on('click', '.contract-alert-remove-btn', function() {
      const rows = $contractAlertRepeater.find('.contract-alert-row');
      if (rows.length > 1) {
        $(this).closest('.contract-alert-row').remove();
        updateContractAlertRemoveButtons();
      }
    });

    // Timeline active switch toggle
    $contractAlertRepeater.on('change', '.timeline-active-switch', function() {
      $(this).closest('.contract-alert-row').find('.alert-days-row').toggle($(this).is(':checked'));
    });

    // Alert before master checkbox toggle
    $contractAlertRepeater.on('change', '.alert-before-master-checkbox', function() {
      $(this).closest('.alert-days-row').find('.alert-days-checkboxes-container').toggle($(this).is(':checked'));
    });

    // Initialize remove buttons state
    updateContractAlertRemoveButtons();

    // ---------- Datepicker ----------
    function parseUsDate(str) {
      const m = /^(\d{2})\/(\d{2})\/(\d{4})$/.exec((str || '').trim());
      if (!m) return null;
      const mm = +m[1], dd = +m[2], yyyy = +m[3];
      const d = new Date(yyyy, mm - 1, dd);
      return (d.getFullYear() === yyyy && d.getMonth() === mm - 1 && d.getDate() === dd) ? d : null;
    }

    // Initialize datepickers for existing contract-alert rows
    $contractAlertRepeater.find('.contract-alert-row').each(function() {
      const $row = $(this);
      const $startDate = $row.find('.contract-start-date');
      const $endDate = $row.find('.contract-end-date');
      
      $startDate.datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        todayHighlight: true
      }).on('changeDate', function(e) {
        $endDate.datepicker('setStartDate', e.date);
        const endD = $endDate.datepicker('getDate');
        if (endD && endD < e.date) $endDate.datepicker('setDate', null);
      });

      $endDate.datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        todayHighlight: true
      });

      // Set start date constraint on load
      const s = parseUsDate($startDate.val());
      if (s) {
        $endDate.datepicker('setStartDate', s);
        const e = parseUsDate($endDate.val());
        if (e && e < s) $endDate.datepicker('setDate', null);
      }
    });

    function addDays(d, n) { const x = new Date(d.getTime()); x.setDate(x.getDate() + n); return x; }
    function addMonthsClamped(d, m) {
      const tM = d.getMonth() + m, tY = d.getFullYear() + Math.floor(tM / 12), mIdx = (tM % 12 + 12) % 12;
      const last = new Date(tY, mIdx + 1, 0).getDate(), day = Math.min(d.getDate(), last);
      return new Date(tY, mIdx, day);
    }
    function addYearsClamped(d, y) {
      if (d.getMonth() === 1 && d.getDate() === 29) return new Date(d.getFullYear() + y, 1, 28);
      return new Date(d.getFullYear() + y, d.getMonth(), d.getDate());
    }
    function getRecurrence() { return $('input[name="recurring_type"]:checked').val() || null; }

    function autoSetEndDateFromStart() {
      if (!$isRecurring.is(':checked')) return;
      const r = getRecurrence(); if (!r) return;
      const s = $('#contract_start_date').datepicker('getDate'); if (!s) return;
      let e = null;
      if (r === 'weekly') e = addDays(s, 7);
      if (r === 'monthly') e = addMonthsClamped(s, 1);
      if (r === 'yearly') e = addYearsClamped(s, 1);
      if (e) {
        $('#contract_end_date').datepicker('setStartDate', s);
        $('#contract_end_date').datepicker('setDate', e);
        if ($.validator && $form.data('validator')) $('#contract_end_date').valid();
      }
    }
    $('input[name="recurring_type"]').on('change', autoSetEndDateFromStart);
    $isRecurring.on('change', function() { if ($(this).is(':checked')) autoSetEndDateFromStart(); });

    // ---------- Validation ----------
    if (!$.validator) return;

    $.validator.addMethod('usDate', function(v, el) { return this.optional(el) || !!parseUsDate(v); }, 'Use MM/DD/YYYY.');
    $.validator.addMethod('endAfterStart', function(v, el, startSel) {
      const s = parseUsDate($(startSel).val()), e = parseUsDate(v);
      if (!s || !e) return true; return e >= s;
    }, 'End date must be on or after start date.');
    $.validator.addMethod('lettersNumbersSpaces', (v, el) => (v || '').match(/^[A-Za-z0-9 ]+$/) || v === '', 'Only letters, numbers and spaces.');

    const validator = $form.validate({
      ignore: ':hidden:not(.select2-hidden-accessible)',
      rules: {
        //project_name: { required: true, maxlength: 255, lettersNumbersSpaces: true },
        customer_id: { required: true },
        description: { required: true, maxlength: 1000 },
        contract_start_date: { required: true, usDate: true },
        contract_end_date: { required: true, usDate: true, endAfterStart: '#contract_start_date' },
        status: { required: true },
        industry_vertical_id: { required: true },
        department_id: { required: true },
        pricing_id: { required: true },
        recurring_type: {
          required: function() { return $('#is_recurring').is(':checked'); }
        }
      },
      messages: {
        contract_end_date: { endAfterStart: 'End date must not be before start date.' },
        'pocs[]': 'Please select at least one POC.',
        //'pm_ids[]': 'Please select at least one Project Manager.'
      },
      errorElement: 'div',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        if (element.hasClass('select2-hidden-accessible')) {
          error.insertAfter(element.next('.select2'));
        } else if (element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } else { error.insertAfter(element); }
      },
      highlight: function(el) {
        $(el).addClass('is-invalid');
        if ($(el).hasClass('select2-hidden-accessible')) {
          $(el).next('.select2').find('.select2-selection').addClass('is-invalid');
        }
      },
      unhighlight: function(el) {
        $(el).removeClass('is-invalid');
        if ($(el).hasClass('select2-hidden-accessible')) {
          $(el).next('.select2').find('.select2-selection').removeClass('is-invalid');
        }
      }
    });

    $('select.select2').on('change', function() { $(this).valid(); });





    // Fetch POCs when customer changes
    $customerSel.on('change', function() {
      const customerId = $(this).val();

      // Reset to a single clean row
      $pocRepeater.html(`
        <div class="poc-row d-flex gap-2 align-items-start" data-index="0">
          <select name="pocs[]" class="form-select poc-select" required style="width:100%;">
            <option value="">Select POC</option>
          </select>
          <button type="button" class="btn btn-outline-primary poc-addremove" data-action="add">+</button>
        </div>`);
      $pocRepeater.find('.poc-select').select2({
        width: '100%', placeholder: 'Select POC', allowClear: true
      }).on('change', function() { $(this).valid(); refreshAllPocSelects(); });
      togglePocButtons();

      if (!customerId) {
        pocOptionsCache = [];
        refreshAllPocSelects();
        return;
      }

      // CSRF for Laravel (needs <meta name="csrf-token" ...> in layout)
      $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
      });

      $.getJSON(`/customers/${customerId}/pocs`)
        .done(function(data) {
          pocOptionsCache = Array.isArray(data) ? data : [];
          refreshAllPocSelects();
        })
        .fail(function() {
          pocOptionsCache = [];
          refreshAllPocSelects();
          alert('Failed to load POCs.');
        });
    });

    // Ensure at least one POC selected on submit
    function validatePocRepeaterRequired() {
      const ok = $pocRepeater.find('.poc-select').toArray().some(el => $(el).val());
      $('#poc-repeater-error').toggleClass('d-none', ok);
      return ok;
    }

    // ---------------------- PM REPEATER (FIXED) ----------------------
    const $pmRepeater = $('#pm-repeater');
    const $pmTplWrap = $('#pm-row-template'); // hidden div with one .pm-row

    // Init first PM select
    $pmRepeater.find('.pm-select').select2({
      width: '100%', placeholder: 'Select PM', allowClear: true
    });

    function chosenPmIds(exclude) {
      const ids = new Set();
      $pmRepeater.find('.pm-select').each(function() {
        if (exclude && this === exclude[0]) return;
        const v = $(this).val(); if (v) ids.add(String(v));
      });
      return ids;
    }

    function dedupePmSelect($select) {
      const chosen = chosenPmIds($select);
      const curVal = $select.val();
      $select.find('option').each(function() {
        const val = $(this).attr('value');
        if (!val) return;
        const disable = chosen.has(String(val)) && String(val) !== String(curVal);
        $(this).prop('disabled', disable);
      });
      if ($select.hasClass('select2-hidden-accessible')) $select.trigger('change.select2');
    }

    function refreshAllPmSelects() {
      $pmRepeater.find('.pm-select').each(function() { dedupePmSelect($(this)); });
    }

    function togglePmButtons() {
      const rows = $pmRepeater.find('.pm-row');
      rows.each(function(i) {
        const $btn = $(this).find('.pm-addremove');
        if (i === rows.length - 1) {
          $btn.text('+').removeClass('btn-outline-danger').addClass('btn-outline-primary').attr('data-action', 'add');
        } else {
          $btn.text('−').removeClass('btn-outline-primary').addClass('btn-outline-danger').attr('data-action', 'remove');
        }
      });
    }

    function addPmRow(selectedId) {
      const $row = $pmTplWrap.children('.pm-row').first().clone(true, true);
      $row.find('select.pm-select').val('');
      $pmRepeater.append($row);
      const $sel = $row.find('select.pm-select');
      $sel.prop('disabled', false).attr('name', 'pm_ids[]');
      $sel.select2({
        width: '100%', placeholder: 'Select PM', allowClear: true
      }).on('change', function() { $(this).valid && $(this).valid(); refreshAllPmSelects(); });
      refreshAllPmSelects();
      if (selectedId) { $sel.val(String(selectedId)).trigger('change'); }
      togglePmButtons();
    }

    $pmRepeater.on('click', '.pm-addremove', function() {
      const action = $(this).attr('data-action');
      if (action === 'add') {
        const totalUsers = $pmTplWrap.find('option[value!=""]').length;
        const chosenCount = chosenPmIds().size;
        if (chosenCount >= totalUsers) { alert('All PMs are already selected.'); return; }
        addPmRow();
      } else {
        if ($pmRepeater.find('.pm-row').length <= 1) return;
        $(this).closest('.pm-row').remove();
        refreshAllPmSelects();
        togglePmButtons();
      }
    });
    $pmRepeater.on('change', 'select.pm-select', function() {
      $(this).valid && $(this).valid();
      refreshAllPmSelects();
    });

    // First paint
    refreshAllPmSelects();
    togglePmButtons();

    // ---------------------- FINAL SUBMIT GUARDS ----------------------
    $form.on('submit', function(e) {
      const pocOK = validatePocRepeaterRequired();
      const pmOK = $pmRepeater.find('.pm-select').toArray().some(el => $(el).val());
      $('#pm-repeater-error').toggleClass('d-none', pmOK);

      if (!pocOK || !pmOK) {
        e.preventDefault();
        if (!pocOK) $pocRepeater.find('.poc-select').first().select2('open');
        else $pmRepeater.find('.pm-select').first().select2('open');
      }
    });
  });

  // ========= Load Departments by Industry Vertical =========
  function loadDepartmentsByIndustry(industryId, preselectDepartmentId) {
    const $deptSel = $('#department_id');
    
    // Reset department dropdown
    $deptSel.empty().append('<option value="">Select</option>');

    if (!industryId) {
      $deptSel.trigger('change.select2');
        return;
      }

    // Show loading state
    $deptSel.append('<option value="">Loading...</option>').trigger('change.select2');

      $.ajax({
      url: "{{ route('departments.byIndustry', ':id') }}".replace(':id', industryId),
        type: "GET",
        dataType: "json",
        success: function(response) {
        $deptSel.empty().append('<option value="">Select</option>');
        
        if (response.data && response.data.length > 0) {
            $.each(response.data, function(i, item) {
            $deptSel.append('<option value="' + item.id + '">' + item.name + '</option>');
            });
          }

        // Restore old/prior selection if still valid
        if (preselectDepartmentId) {
          const $option = $deptSel.find('option[value="' + preselectDepartmentId + '"]');
          if ($option.length) {
            $deptSel.val(preselectDepartmentId);
          }
        } else {
          // Try to restore from old input or document
          const oldVal = "{{ old('department_id', $document->department_id ?? '') }}";
          if (oldVal) {
            const $option = $deptSel.find('option[value="' + oldVal + '"]');
            if ($option.length) {
              $deptSel.val(oldVal);
            }
          }
        }

        $deptSel.trigger('change.select2');
        },
        error: function() {
        $deptSel.empty().append('<option value="">Select</option>').trigger('change.select2');
        alert('Could not load departments for the selected Industry Vertical.');
        }
      });
  }

  // ========= Event Handler for Industry Vertical Change =========
  $('#industry_vertical_id').on('change', function() {
    const industryId = $(this).val();
    loadDepartmentsByIndustry(industryId);
  });

  // ========= Initial Load (for edit mode) =========
  $(document).ready(function() {
    const $industrySel = $('#industry_vertical_id');
    const $deptSel = $('#department_id');
    const industryId = $industrySel.val();
    const departmentId = $deptSel.val();

    // If industry vertical is already selected (edit mode), load departments
    if (industryId) {
      loadDepartmentsByIndustry(industryId, departmentId);
    }
  });
</script>
@endsection

