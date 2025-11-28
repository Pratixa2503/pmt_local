@extends('layouts/layoutMaster')
@section('title', $title)

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<style>
   /* wrapper around the select2 to position the action buttons */
  .select2-with-actions {
    position: relative;
  }

  /* action buttons sit inside the right edge of the control, vertically centered */
  .select2-with-actions .select-action-bar {
    position: absolute;
    top: 50%;
    right: .5rem;
    transform: translateY(-50%);
    display: flex;
    gap: .25rem;
    z-index: 3; /* above the select2 selection box */
    pointer-events: auto;
  }

  /* keep the select text from sliding under the buttons */
  .select2-with-actions .select2-container--default .select2-selection--single,
  .select2-with-actions .select2.select2-container .select2-selection--single {
    padding-right: 3.6rem; /* space for the two small buttons */
  }

  /* ensure visual fit for small buttons */
  .select2-with-actions .btn {
    height: 30px;
    line-height: 1;
    padding: 0 .45rem;
  }

  /* make sure the select2 container stretches full width of the wrapper */
  .select2-with-actions .select2-container {
    width: 100% !important;
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
  $isEdit  = isset($type) && $type === 'edit';
  $action  = $isEdit ? route('projects.update', $project->id) : route('projects.store');
  $masters = Helper::getProjectMasterData();

  // POCs selected (for repeater seed) — null-safe
  $selectedPocIds = collect(old('pocs', isset($project) ? ($project->pocs?->pluck('id')->all() ?? []) : []))
    ->filter(fn($v)=>!empty($v))->values()->all();
  if (empty($selectedPocIds)) { $selectedPocIds = [null]; }

  // PMs (for edit/old) — null-safe
  $existingPms = old('pm_ids', isset($project) ? ($project->pms?->pluck('id')->toArray() ?? []) : []);
  if (empty($existingPms)) $existingPms = [null];

  // Members-by-PM mapping for edit/old repopulation
  $membersByPmOldOrEdit = old('members_by_pm', $membersByPm ?? []);

  // helper for POC display
  $pocLabel = function($p) {
    return trim(($p->first_name ?? $p->name ?? '').' '.($p->last_name ?? ''))
      . ($p->email ? " ({$p->email})" : '');
  };
@endphp

<script>
  // Seed POC cache so "+" works before customer change (edit case)
  window.__pocSeed = {!! json_encode(
    ($pocsForCustomer ?? collect())->map(function($p){
      return [
        'id'    => (string)$p->id,
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
        <form id="projectForm" method="POST" action="{{ $action }}" novalidate>
          @csrf
          @if($isEdit) @method('PUT') @endif

          <input type="hidden" name="parent_id" value="{{ old('parent_id', $parentId) }}">

          {{-- ===================== Project Basics ===================== --}}
          <div class="col-md-12 my-3">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Project Basics</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label d-block">Project Category <span class="text-danger">*</span></label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="general_project" name="project_category" value="1"
                  {{ (string)old('project_category', $project->project_category ?? 1) === '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="general_project">General Project</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="la_project" name="project_category" value="2"
                  {{ (string)old('project_category', $project->project_category ?? 1) === '2' ? 'checked' : '' }}>
                <label class="form-check-label" for="la_project">LA Project</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="la_project" name="project_category" value="3"
                  {{ (string)old('project_category', $project->project_category ?? 1) === '3' ? 'checked' : '' }}>
                <label class="form-check-label" for="la_project">API</label>
              </div>
               <select name="project_category" id="project_category" class="form-select select2" required>
                <option value="">Select</option>
                @foreach($masters['project_category'] as $item)
                  <option value="{{ $item->id }}" {{ (string)old('project_category', $project->project_category ?? '') === (string)$item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                  </option>
                @endforeach
              </select>
              @error('project_category') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
              <label for="project_name" class="form-label">Project Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="project_name" name="project_name" required
                value="{{ old('project_name', $project->project_name ?? '') }}" placeholder="Enter project name" autocomplete="off">
              @error('project_name') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="description" class="form-label">Description / Scope <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="description" name="description" required
                value="{{ old('description', $project->description ?? '') }}" placeholder="Short scope or description" autocomplete="off">
               <textarea
                  class="form-control"
                  id="description"
                  name="description"
                  rows="2"
                  maxlength="1000"
                  required
                  placeholder="Short scope or description"
                  autocomplete="off"
                >{{ old('description', $project->description ?? '') }}</textarea>
              @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
          </div>

          {{-- ===================== Timeline & Recurrence ===================== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Timeline & Recurrence</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label d-block">Ongoing / Recurring</label>
              <div class="form-check form-switch">
                <input type="hidden" name="is_recurring" value="0">
                <input class="form-check-input" type="checkbox" id="is_recurring" name="is_recurring" value="1"
                  {{ old('is_recurring', $project->is_recurring ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_recurring">Ongoing project</label>
              </div>
              @error('is_recurring') <div class="text-danger small">{{ $message }}</div> @enderror
              <label class="form-label d-block">Project Type</label>

              <div class="form-check form-switch">
                {{-- Unchecked fallback --}}
                <input type="hidden" name="is_recurring" value="0">

                @php
                  $isRecurring = (bool) old('is_recurring', $project->is_recurring ?? false);
                @endphp

                <input
                  class="form-check-input"
                  type="checkbox"
                  id="is_recurring"
                  name="is_recurring"
                  value="1"
                  {{ $isRecurring ? 'checked' : '' }}
                  aria-describedby="isRecurringHelp"
                >

                <label class="form-check-label" for="is_recurring">
                   <strong id="isRecurringText">{{ $isRecurring ? 'Ongoing' : 'One-time' }}</strong>
                </label>
              </div>

              <div id="isRecurringHelp" class="form-text">
                Turn ON if the work recurs each billing period; leave OFF for a one-time engagement.
              </div>

              @error('is_recurring')
                <div class="text-danger small">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6" id="recurrence_group" style="display:none;">
              <label class="form-label d-block">Repeat <span class="text-danger">*</span></label>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="recurring_type" id="weekly" value="weekly"
                  {{ old('recurring_type', $project->recurring_type ?? '') == 'weekly' ? 'checked' : '' }}>
                <label class="form-check-label" for="weekly">Weekly</label>
              </div>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="recurring_type" id="biweekly" value="biweekly"
                  {{ old('recurring_type', $project->recurring_type ?? '') == 'biweekly' ? 'checked' : '' }}>
                <label class="form-check-label" for="biweekly">Fortnightly</label>
              </div>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="recurring_type" id="monthly" value="monthly"
                  {{ old('recurring_type', $project->recurring_type ?? '') == 'monthly' ? 'checked' : '' }}>
                <label class="form-check-label" for="monthly">Monthly</label>
              </div>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="recurring_type" id="yearly" value="yearly"
                  {{ old('recurring_type', $project->recurring_type ?? '') == 'yearly' ? 'checked' : '' }}>
                <label class="form-check-label" for="yearly">Yearly</label>
              </div>
              @error('recurring_type') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

              @error('recurring_type') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
              <input type="text" class="form-control datepicker" id="start_date" name="start_date" required
                value="{{ old('start_date', isset($project->start_date) ? \Carbon\Carbon::parse($project->start_date)->format('m/d/Y') : '') }}"
                placeholder="MM/DD/YYYY" autocomplete="off">
                value="{{ old('start_date', isset($project->start_date) ? \Carbon\Carbon::parse($project->start_date)->format('m-d-Y') : '') }}"
                placeholder="MM-DD-YYYY" autocomplete="off">
              @error('start_date') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
              <input type="text" class="form-control datepicker" id="end_date" name="end_date" required
                value="{{ old('end_date', isset($project->end_date) ? \Carbon\Carbon::parse($project->end_date)->format('m/d/Y') : '') }}"
                placeholder="MM/DD/YYYY" autocomplete="off">
                value="{{ old('end_date', isset($project->end_date) ? \Carbon\Carbon::parse($project->end_date)->format('m-d-Y') : '') }}"
                placeholder="MM-DD-YYYY" autocomplete="off">
              @error('end_date') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
          </div>

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
              <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
              <select name="customer_id" id="customer_id" class="form-select select2" required>
                <option value="">Select Customer</option>
                @foreach($customers as $c)
                  <option value="{{ $c->id }}" {{ (string)old('customer_id', $project->customer_id ?? '') === (string)$c->id ? 'selected' : '' }}>
            <!-- <div class="col-md-4">
              <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
             
              <select name="customer_id" id="customer_id"
                      class="form-select select2"
                      @if(!empty($presetCustomerId)) data-readonly="1" @endif
                      required>
                <option value="">Select Customer</option>
                @foreach($customers as $c)
                  <option value="{{ $c->id }}"
                    {{ (string)old('customer_id', $presetCustomerId) === (string)$c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                  </option>
                @endforeach
              </select>
              @error('customer_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-8">
            </div> -->
            <input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id', $presetCustomerId) }}">
            <input type="hidden" name="project_id" id="project_id" value="{{ old('project_id', $id ?? '') }}">

            <div class="col-md-12">
              <label class="form-label">POCs <span class="text-danger">*</span></label>
              <div id="poc-repeater" class="d-flex flex-column gap-2">
                @php $alreadyChosen = []; @endphp
                @foreach($selectedPocIds as $i => $selId)
                <div class="poc-row d-flex gap-2 align-items-start" data-index="{{ $i }}">
                  <select name="pocs[]" class="form-select poc-select" required style="width:100%;">
                    <option value="">Select POC</option>
                    @foreach(($pocsForCustomer ?? collect()) as $p)
                      @php
                        $idStr = (string) $p->id;
                        $isSelected = (string)$selId === $idStr;
                        $isDisabled = !$isSelected && in_array($idStr, $alreadyChosen, true);
                      @endphp
                      <option value="{{ $p->id }}" {{ $isSelected ? 'selected' : '' }} {{ $isDisabled ? 'disabled' : '' }}>
                        {{ $pocLabel($p) }}
                      </option>
                      @if($isSelected) @php $alreadyChosen[] = $idStr; @endphp @endif
                    @endforeach
                  </select>
                  <button type="button" class="btn btn-outline-primary poc-addremove" data-action="add">+</button>
                </div>
                @endforeach
              </div>
              <div id="poc-repeater-error" class="text-danger small d-none">Please select at least one POC.</div>

              {{-- Hidden PM row template (includes per-PM Members block) --}}
              <div id="pm-row-template" class="d-none">
                <div class="pm-row d-flex flex-column gap-2 border rounded p-2">
                  <div class="d-flex gap-2 align-items-start">
                    <select class="form-select pm-select" required style="width:100%;" disabled>
                      <option value="">Select PM</option>
                      @foreach($users as $u)
                        <option value="{{ $u->id }}">
                          {{ $u->first_name }} {{ $u->last_name }}@if($u->email) ({{ $u->email }})@endif
                        </option>
                      @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-primary pm-addremove" data-action="add">+</button>
                  </div>

                  {{-- Members for THIS PM (AJAX-populated, not all $users) --}}
                  <div class="members-for-pm" style="display:none;">
                    <label class="form-label mb-1">Members for this PM</label>
                    <select
                      class="form-select members-select"
                      multiple
                      name="members_by_pm[TMP][]"
                      data-for-pm=""
                      data-preselected='[]'
                      style="width:100%;"
                    ></select>
                    <div class="form-text">Pick one or more team members under this PM.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- ===================== Ownership (PM Repeater + Members per PM) ===================== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Ownership</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label">Project Managers <span class="text-danger">*</span></label>
              <div id="pm-repeater" class="d-flex flex-column gap-2">
                @foreach($existingPms as $pmId)
                  @php $preMembers = $pmId ? (array)($membersByPmOldOrEdit[$pmId] ?? []) : []; @endphp
                  <div class="pm-row d-flex flex-column gap-2 border rounded p-2">
                    <div class="d-flex gap-2 align-items-start">
                      <select name="pm_ids[]" class="form-select pm-select" required style="width:100%;">
                        <option value="">Select PM</option>
                        @foreach($users as $u)
                          <option value="{{ $u->id }}" {{ (string)$pmId === (string)$u->id ? 'selected' : '' }}>
                            {{ $u->first_name }} {{ $u->last_name }}@if($u->email) ({{ $u->email }})@endif
                          </option>
                        @endforeach
                      </select>
                      <button type="button" class="btn btn-outline-primary pm-addremove" data-action="add">+</button>
                    </div>

                    <div class="members-for-pm"
                         @if(!$pmId || (string)old('project_category', $project->project_category ?? 1) !== '1') style="display:none;" @endif>
                      <label class="form-label mb-1">Members for this PM</label>
                      <select
                        name="members_by_pm[{{ $pmId ?? 'TMP' }}][]"
                        class="form-select members-select"
                        multiple
                        data-for-pm="{{ $pmId ?? '' }}"
                        data-preselected='@json((array)$preMembers)'
                        style="width:100%;"
                      ></select>
                      <div class="form-text">Pick one or more team members under this PM.</div>
                    </div>
                  </div>
                @endforeach
              </div>
              <div id="pm-repeater-error" class="text-danger small d-none">Please select at least one Project Manager.</div>
            </div>
          </div>

          {{-- ===================== Configuration (Masters) ===================== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Configuration</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-4">
            <!-- <div class="col-md-4">
              <label for="project_type_id" class="form-label">Project Type <span class="text-danger">*</span></label>
              <select name="project_type_id" id="project_type_id" class="form-select select2" required>
                <option value="">Select</option>
                @foreach($masters['project_types'] as $item)
                  <option value="{{ $item->id }}" {{ (string)old('project_type_id', $project->project_type_id ?? '') === (string)$item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                  </option>
                @endforeach
              </select>
              @error('project_type_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
            </div> -->

            <div class="col-md-4">
              <label for="industry_vertical_id" class="form-label">Industry Vertical <span class="text-danger">*</span></label>
              <select name="industry_vertical_id" id="industry_vertical_id" class="form-select select2" required>
                <option value="">Select</option>
                @foreach($masters['industry_vertical'] as $item)
                  <option value="{{ $item->id }}" {{ (string)old('industry_vertical_id', $project->industry_vertical_id ?? '') === (string)$item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                  </option>
                @endforeach
              </select>
              @error('industry_vertical_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
 
            <div class="col-md-4">
              <label for="department_id" class="form-label">Department / Business Unit <span class="text-danger">*</span></label>
              <select name="department_id" id="department_id" class="form-select select2" required>
                <option value="">Select</option>
                @foreach($masters['departments'] as $item)
                  <option value="{{ $item->id }}" {{ (string)old('department_id', $project->department_id ?? '') === (string)$item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                  </option>
                @endforeach
              </select>
              @error('department_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label for="pricing_id" class="form-label">Pricing <span class="text-danger">*</span></label>
              <label for="service_offering_id" class="form-label">Service Offering <span class="text-danger">*</span></label>
              <select name="service_offering_id" id="service_offering_id" class="form-select select2" required>
                <option value="">Select</option>
                @foreach($masters['service_offering'] as $item)
                  <option value="{{ $item->id }}" {{ (string)old('service_offering_id', $project->service_offering_id ?? '') === (string)$item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                  </option>
                @endforeach
              </select>
              @error('service_offering_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label for="pricing_type" class="form-label">Pricing Type <span class="text-danger">*</span></label>
              <select name="pricing_type" id="pricing_type" class="form-select select2" required>
                <option value="">Select</option>
                <option value="standard" {{ old('pricing_type', $project->pricing_type ?? '') === 'standard' ? 'selected' : '' }}>
                  Standard
                </option>
                <option value="fixed" {{ old('pricing_type', $project->pricing_type ?? '') === 'fixed' ? 'selected' : '' }}>
                  Custom
                </option>
              </select>
              @error('pricing_type') 
                <div class="text-danger small">{{ $message }}</div> 
              @enderror
            </div>
 
           @php
            // pick the best available customer id source
            $customerId = filled(old('customer_id'))
    ? old('customer_id')
    : ($project->customer_id ?? request()->input('customer_id') ?? $presetCustomerId ?? null);
            
          @endphp

          <div class="col-md-4">
            <label for="pricing_id" class="form-label">Pricing <span class="text-danger">*</span></label>

            <div class="select2-with-actions position-relative">
              {{-- the action buttons are visually inside the select, aligned right --}}
              <div class="select-action-bar">
                <a
                  href="{{ route('pricing-master.create', ['customer_id' => $customerId]) }}"
                  class="btn btn-sm btn-outline-primary"
                  target="_blank"
                  rel="noopener"
                  title="Add new Pricing"
                >
                  <i class="ti ti-plus"></i>
                </a>

                <button
                  type="button"
                  class="btn btn-sm btn-outline-secondary"
                  id="btn-refresh-pricing"
                  title="Refresh Pricing list"
                >
                  <i class="ti ti-refresh"></i>
                </button>
              </div>

              <select name="pricing_id" id="pricing_id" class="form-select select2" required>
                <option value="">Select</option>
                @foreach(($pricing ?? []) as $p)
                  <option value="{{ $p->id }}" {{ (string)old('pricing_id', $project->pricing_id ?? '') === (string)$p->id ? 'selected' : '' }}>
                    {{ $p->name }}
                  </option>
                @endforeach
              </select>
              @error('pricing_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            </div>

            @error('pricing_id') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>
          
            <div class="col-md-4">
              <label for="input_format_id" class="form-label">Input Format <span class="text-danger">*</span></label>
              <select name="input_format_id" id="input_format_id" class="form-select select2" required>
                <option value="">Select</option>
                @foreach($masters['input_output_formats'] as $f)
                  <option value="{{ $f->id }}" {{ (string)old('input_format_id', $project->input_format_id ?? '') === (string)$f->id ? 'selected' : '' }}>
                    {{ $f->name }}
                  </option>
                @endforeach
              </select>
              @error('input_format_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label for="output_format_id" class="form-label">Output Format <span class="text-danger">*</span></label>
              <select name="output_format_id" id="output_format_id" class="form-select select2" required>
                <option value="">Select</option>
                @foreach($masters['input_output_formats'] as $f)
                  <option value="{{ $f->id }}" {{ (string)old('output_format_id', $project->output_format_id ?? '') === (string)$f->id ? 'selected' : '' }}>
                    {{ $f->name }}
                  </option>
                @endforeach
              </select>
              @error('output_format_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label for="mode_of_delivery_id" class="form-label">Mode of Delivery <span class="text-danger">*</span></label>
              <select name="mode_of_delivery_id" id="mode_of_delivery_id" class="form-select select2" required>
                <option value="">Select</option>
                @foreach($masters['modes_of_delivery'] as $m)
                  <option value="{{ $m->id }}" {{ (string)old('mode_of_delivery_id', $project->mode_of_delivery_id ?? '') === (string)$m->id ? 'selected' : '' }}>
                    {{ $m->name }}
                  </option>
                @endforeach
              </select>
              @error('mode_of_delivery_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label for="frequency_of_delivery_id" class="form-label">Frequency of Delivery <span class="text-danger">*</span></label>
              <select name="frequency_of_delivery_id" id="frequency_of_delivery_id" class="form-select select2" required>
                <option value="">Select</option>
                @foreach($masters['frequencies_of_delivery'] as $f)
                  <option value="{{ $f->id }}" {{ (string)old('frequency_of_delivery_id', $project->frequency_of_delivery_id ?? '') === (string)$f->id ? 'selected' : '' }}>
                    {{ $f->name }}
                  </option>
                @endforeach
              </select>
              @error('frequency_of_delivery_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
          </div>


            <!-- <div class="col-md-4" id="suite_id_group" style="{{ (string)old('project_category', $project->project_category ?? 1) === '2' ? '' : 'display:none;' }}">
              <label for="suite_id" class="form-label">Suite ID</label>
              <input type="text"
                    class="form-control"
                    id="suite_id"
                    name="suite_id"
                    value="{{ old('suite_id', $project->suite_id ?? '') }}"
                    placeholder="Enter Suite ID">
              @error('suite_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div> -->
          </div>



          {{-- ===================== Status & Priority ===================== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Status & Priority</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label for="project_priority_id" class="form-label">Project Priority <span class="text-danger">*</span></label>
              <select name="project_priority_id" id="project_priority_id" class="form-select select2" required>
                <option value="">Select</option>
                @foreach($masters['project_priorities'] as $p)
                  <option value="{{ $p->id }}" {{ (string)old('project_priority_id', $project->project_priority_id ?? '') === (string)$p->id ? 'selected' : '' }}>
                    {{ $p->name }}
                  </option>
                @endforeach
              </select>
              @error('project_priority_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="project_status_id" class="form-label">Project Status <span class="text-danger">*</span></label>
              <select name="project_status_id" id="project_status_id" class="form-select select2" required>
                <option value="">Select</option>
                @foreach($masters['project_statuses'] as $s)
                  <option value="{{ $s->id }}" {{ (string)old('project_status_id', $project->project_status_id ?? '') === (string)$s->id ? 'selected' : '' }}>
                    {{ $s->name }}
                  </option>
                @endforeach
              </select>
              @error('project_status_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
          </div>

          {{-- ===================== Actions ===================== --}}
          <div class="col-md-12 my-4 d-flex justify-content-end gap-2">
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">
              <i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>Back
            </a>
            <button type="submit" class="btn btn-primary">
              {{ $isEdit ? 'Update' : 'Save' }}
              <i class="ti ti-file-upload ms-1 mb-1"></i>
            </button>
          </div>
        </form>
        {{-- ===================== Bulk Import ===================== --}}
<div class="col-md-12 my-4" style="{{ (string)old('project_category', $project->project_category ?? 1) === '2' ? '' : 'display:none;' }}">
  <div class="d-flex align-items-center">
    <div class="flex-grow-1 border-top border-grey"></div>
    <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Bulk Import</span>
    <div class="flex-grow-1 border-top border-grey"></div>
  </div>
</div>

<div class="row g-3" style="{{ (string)old('project_category', $project->project_category ?? 1) === '2' ? '' : 'display:none;' }}">
  <div class="col-md-12">
    <div class="alert alert-info d-flex justify-content-between align-items-center">
      <div>
        Import multiple projects at once via Excel/CSV.<br>
        <small>Accepted: .xlsx, .xls, .csv • Max 10MB</small>
      </div>
     <a href="{{ asset('assets/img/projects_import_template_new.csv') }}"
        class="btn btn-outline-dark btn-sm"
        download="projects_import_template_new.csv"
        type="text/csv">
        Download Template
      </a>

    </div>
    @php
    use Illuminate\Support\Facades\Crypt;

    $param = $project ?? request()->route('project') ?? null;

    $projectId = null;

    if ($param instanceof \App\Models\Project) {
        $projectId = $param->id;
    } elseif (is_numeric($param)) {
        $projectId = (int) $param;
    } elseif (is_string($param)) {
        // Try normal Crypt string first
        try {
            $projectId = (int) Crypt::decryptString($param);
        } catch (\Throwable $e) {
            // If it was URL-safe base64 wrapped, unwrap then decrypt
            try {
                $cipher = base64_decode(strtr($param, '-_', '+/'));
                $projectId = (int) Crypt::decryptString($cipher);
            } catch (\Throwable $e2) {
                $projectId = null; // fallback if not decryptable
            }
        }
    }

     $action = $projectId
        ? route('projects.import', ['project' => $projectId])
        : route('projects.import');
    @endphp
    <form id="importForm" method="POST" action="{{ $action }}" enctype="multipart/form-data" class="border rounded p-3" id="bulk_import_section"
     style="{{ (string)old('project_category', $project->project_category ?? 1) === '2' ? '' : 'display:none;' }}">
      @csrf
      <div class="row g-3 align-items-end">
        <div class="col-md-6">
          <label for="bulk_file" class="form-label">Upload File <span class="text-danger">*</span></label>
          <input type="file"
                 class="form-control"
                 id="bulk_file"
                 name="bulk_file"
                 accept=".xlsx,.xls,.csv"
                 required>
          @error('bulk_file') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
          <label class="form-label d-block">First Row Is Header?</label>
          <div class="form-check form-switch">
            <input type="hidden" name="heading_row" value="1">
            <input class="form-check-input" type="checkbox" id="heading_row" name="heading_row" value="1" checked>
            <label class="form-check-label" for="heading_row">Yes</label>
          </div>
        </div>

        <div class="col-md-3 text-end">
          <button type="submit" class="btn btn-primary">
            Import <i class="ti ti-upload ms-1"></i>
          </button>
        </div>
      </div>

      <!-- <div class="form-text mt-2">
        Required columns: <code>project_category, project_name, description, start_date, end_date, customer_id</code>.<br>
        Optional: <code>is_recurring, recurring_type, project_type_id, department_id, pricing_id, input_format_id, output_format_id, mode_of_delivery_id, frequency_of_delivery_id, project_priority_id, project_status_id, pocs</code>
      </div> -->
    </form>
  </div>
</div>

      </div>

    </div>
  </div>
</div>
@endsection

@section('extra-script')
<script>
$(function () {
  // ---------- helpers ----------
  function isGeneralProject() { return $('input[name="project_category"]:checked').val() === '1'; }

  // ---------- base UI ----------
  $('.select2').select2({ width: '100%' });
  const $form = $('#projectForm');
  const $isRecurring = $('#is_recurring');
  const $recurrenceGroup = $('#recurrence_group');
  const $customerSel = $('#customer_id');

  function toggleRecurrence() { $recurrenceGroup.toggle($isRecurring.is(':checked')); }
  $isRecurring.on('change', toggleRecurrence); toggleRecurrence();

  // ---------- datepickers ----------
  function parseUsDate(str){
    const m = /^(\d{2})\/(\d{2})\/(\d{4})$/.exec((str||'').trim());
    if(!m) return null; const mm=+m[1],dd=+m[2],yyyy=+m[3];
    const d=new Date(yyyy,mm-1,dd);
    return (d.getFullYear()===yyyy && d.getMonth()===mm-1 && d.getDate()===dd) ? d : null;
  }
  $('#start_date').datepicker({ minDate:'today', format:'mm/dd/yyyy', autoclose:true, todayHighlight:true, startDate:new Date() })
    .on('changeDate', function(e){
      $('#end_date').datepicker('setStartDate', e.date);
      const endD = $('#end_date').datepicker('getDate');
      if (endD && endD < e.date) $('#end_date').datepicker('setDate', null);
      autoSetEndDateFromStart();
      if ($.validator && $form.data('validator')) $('#end_date').valid();
    });
  $('#end_date').datepicker({ format:'mm/dd/yyyy', autoclose:true, todayHighlight:true });
  (function clampOnLoad(){
    const s = parseUsDate($('#start_date').val());
    if (s){
      $('#end_date').datepicker('setStartDate', s);
      const e = parseUsDate($('#end_date').val());
      if (e && e < s) $('#end_date').datepicker('setDate', null);
    }
  })();
  function addDays(d,n){ const x=new Date(d.getTime()); x.setDate(x.getDate()+n); return x; }
  function addMonthsClamped(d,m){ const tM=d.getMonth()+m,tY=d.getFullYear()+Math.floor(tM/12),mIdx=(tM%12+12)%12;
    const last=new Date(tY,mIdx+1,0).getDate(), day=Math.min(d.getDate(),last); return new Date(tY,mIdx,day); }
  function addYearsClamped(d,y){ if(d.getMonth()===1&&d.getDate()===29) return new Date(d.getFullYear()+y,1,28);
    return new Date(d.getFullYear()+y,d.getMonth(),d.getDate()); }
  function getRecurrence(){ return $('input[name="recurring_type"]:checked').val() || null; }
  function autoSetEndDateFromStart(){
    if (!$isRecurring.is(':checked')) return;
    const r=getRecurrence(); if(!r) return; const s=$('#start_date').datepicker('getDate'); if(!s) return;
    let e=null; if(r==='weekly') e=addDays(s,7); if(r==='monthly') e=addMonthsClamped(s,1); if(r==='yearly') e=addYearsClamped(s,1);
    if(e){ $('#end_date').datepicker('setStartDate', s); $('#end_date').datepicker('setDate', e);
      if ($.validator && $form.data('validator')) $('#end_date').valid(); }
  }
  $('input[name="recurring_type"]').on('change', autoSetEndDateFromStart);
  $isRecurring.on('change', function(){ if($(this).is(':checked')) autoSetEndDateFromStart(); });

  // ---------- validation ----------
 // ---------- validation ----------
if ($.validator) {
  // Group validator: at least one in a selector list must be filled
  $.validator.addMethod('atLeastOneSelected', function(value, element, selector) {
    return $(selector).toArray().some(el => $(el).value ? $(el).value : $(el).value === '' ? false : !!$(el).value) // fallback
           || $(selector).toArray().some(el => !!$(el).value)
           || $(selector).toArray().some(el => !!$(el).value);
  }, 'Required.');

  // Better: use jQuery way
  $.validator.addMethod('atLeastOne', function(value, element, selector) {
    return $(selector).toArray().some(el => $(el).val());
  }, 'Required.');

  $.validator.addMethod('usDate', function(v, el){ return this.optional(el) || !!parseUsDate(v); }, 'Use MM/DD/YYYY.');
  $.validator.addMethod('endAfterStart', function(v, el, startSel){
    const s=parseUsDate($(startSel).val()), e=parseUsDate(v); if(!s||!e) return true; return e>=s;
  }, 'End date must be on or after start date.');
  $.validator.addMethod('lettersNumbersSpaces', (v)=> (v||'').match(/^[A-Za-z0-9 ]+$/) || v==='', 'Only letters, numbers and spaces.');

  const validator = $('#projectForm').validate({
    ignore: ':hidden:not(.select2-hidden-accessible)',
    rules: {
      project_category: { required:true },
      project_name: { required:true, maxlength:255, lettersNumbersSpaces:true },
      description: { required:true, maxlength:1000 },
      start_date: { required:true, usDate:true },
      end_date:   { required:true, usDate:true, endAfterStart:'#start_date' },
      customer_id: { required:true },
      // IMPORTANT: remove 'pocs[]' and 'pm_ids[]' here.
      project_type_id: { required:true },
      department_id: { required:true },
      pricing_id: { required:true },
      input_format_id: { required:true },
      output_format_id: { required:true },
      mode_of_delivery_id: { required:true },
      frequency_of_delivery_id: { required:true },
      project_priority_id: { required:true },
      project_status_id: { required:true },
      recurring_type: { required: function(){ return $('#is_recurring').is(':checked'); } }
    },
    messages: {
      end_date: { endAfterStart: 'End date must not be before start date.' }
    },
    errorElement: 'div',
    errorPlacement: function(error, element){
      error.addClass('invalid-feedback');
      if (element.hasClass('select2-hidden-accessible'))
        error.insertAfter(element.next('.select2'));
      else if (element.parent('.input-group').length)
        error.insertAfter(element.parent());
      else error.insertAfter(element);
    },
    highlight: function(el){
      $(el).addClass('is-invalid');
      if ($(el).hasClass('select2-hidden-accessible'))
        $(el).next('.select2').find('.select2-selection').addClass('is-invalid');
    },
    unhighlight: function(el){
      $(el).removeClass('is-invalid');
      if ($(el).hasClass('select2-hidden-accessible'))
        $(el).next('.select2').find('.select2-selection').removeClass('is-invalid');
    }
  });

  // Apply group rule to the FIRST control of each group,
  // so only one visible error shows per group.
  function wireGroupValidation() {
    const $firstPoc = $('#poc-repeater .poc-select').first();
    const $firstPm  = $('#pm-repeater .pm-select').first();

    if ($firstPoc.length) {
      $firstPoc.rules('remove'); // avoid duplicates
      $firstPoc.rules('add', {
        atLeastOne: '#poc-repeater .poc-select',
        messages: { atLeastOne: 'Please select at least one POC.' }
      });
    }
    if ($firstPm.length) {
      $firstPm.rules('remove');
      $firstPm.rules('add', {
        atLeastOne: '#pm-repeater .pm-select',
        messages: { atLeastOne: 'Please select at least one Project Manager.' }
      });
    }
  }
  // initial
  wireGroupValidation();

  // re-wire after rows are added/removed
  $(document).on('click', '.poc-addremove, .pm-addremove', wireGroupValidation);
  // update when values change
  $(document).on('change', '#poc-repeater .poc-select, #pm-repeater .pm-select', function(){
    $('#projectForm').valid(); // refresh errors if any
  });
}


  // ---------------------- POC repeater ----------------------
function togglePricingActions() {
  const pType = $('#pricing_type').val(); // 'standard' or 'fixed'
  const $bar  = $('.select2-with-actions .select-action-bar');

  if (String(pType) === 'fixed') {
    $bar.removeClass('d-none');
  } else {
    $bar.addClass('d-none');
  }
}

  // Call once on page load
  togglePricingActions();
  $('#pricing_type').on('change', function () {
    togglePricingActions();
    togglePricingByCategory();
    // loadPricing() is already bound on change elsewhere; no need to duplicate
  });
  function togglePricingByCategory(){
    const isCat2   = getProjectCategoryVal() === '2';
    const isCustom = String($('#pricing_type').val()) === 'fixed'; // "Custom"

    const $pricingId = $('#pricing_id');
    const $pricingIdCol = $pricingId.closest('.col-md-4');

    // Show pricing only when:
    // - category != 2  OR
    // - category == 2 AND pricing type is "custom/fixed"
    const shouldShowPricing = !isCat2 || (isCat2 && isCustom);

    if (shouldShowPricing) {
      $pricingIdCol.show();
      $pricingId.prop('required', true);
    } else {
      $pricingIdCol.hide();
      $pricingId.prop('required', false).val('').trigger('change');
    }
  }

  // ==========================
  // Helpers (Select2 + placeholder management)
  // ==========================
  function setSelect2Disabled($sel, disabled, placeholderText){
    const wasInit = $sel.hasClass('select2-hidden-accessible');
    if (wasInit) $sel.select2('destroy');
    $sel.prop('disabled', !!disabled);
    $sel.select2({
      width: '100%',
      allowClear: true,
      placeholder: placeholderText || 'Select members',
    });
    $sel.trigger('change.select2');
  }
  function clearMembersPlaceholder($sel){
    if ($sel.data('placeholder-injected')) {
      $sel.removeData('placeholder-injected');
      if ($sel.find('option').length === 1 && $sel.find('option').first().val() === '') {
        $sel.empty();
      }
    }
  }

  // ==========================
  // Small utilities
  // ==========================
  function fmt2(n){ return String(n).padStart(2,'0'); }
  function parseDMY(str){
    const m = /^(\d{2})-(\d{2})-(\d{4})$/.exec((str||'').trim());
    if (!m) return null;
    const mm=+m[1], dd=+m[2], yyyy=+m[3];
    const d=new Date(yyyy, mm-1, dd);
    return (d.getFullYear()===yyyy && d.getMonth()===mm-1 && d.getDate()===dd) ? d : null;
  }
  function addDays(d,n){ const x=new Date(d.getTime()); x.setDate(x.getDate()+n); return x; }
  function addMonthsClamped(d,m){
    const tM=d.getMonth()+m, tY=d.getFullYear()+Math.floor(tM/12), mIdx=(tM%12+12)%12;
    const last=new Date(tY,mIdx+1,0).getDate(), day=Math.min(d.getDate(),last);
    return new Date(tY,mIdx,day);
  }
  function addYearsClamped(d,y){
    if(d.getMonth()===1&&d.getDate()===29) return new Date(d.getFullYear()+y,1,28);
    return new Date(d.getFullYear()+y,d.getMonth(),d.getDate());
  }
  function getProjectCategoryVal(){
    const $sel = $('#project_category');
    const vSel = $sel.length ? $sel.val() : null;
    if (vSel !== null && vSel !== '') return String(vSel);
    const vRadio = $('input[name="project_category"]:checked').val();
    return vRadio ? String(vRadio) : null;
  }

  // ==========================
  // Select2 init (skip hidden #customer_id)
  // ==========================
  function initSelect2(scope){
    const $ctx = scope ? $(scope) : $(document);
    $ctx.find('.select2').each(function(){
      const $el = $(this);
      if ($el.attr('id') === 'customer_id') return; // hidden input, not a select
      if ($el.hasClass('select2-hidden-accessible')) return;
      $el.select2({
        width:'100%',
        allowClear:true,
        placeholder:'Select',
        dropdownParent: $('.modal.show').length ? $('.modal.show') : $(document.body)
      });
    });
  }
  initSelect2();

  // ==========================
  // Datepickers + recurrence
  // ==========================
  const $isRecurring     = $('#is_recurring');
  const $recurrenceGroup = $('#recurrence_group');
  const $start = $('#start_date');
  const $end   = $('#end_date');

  function initDatepickers(){
    $start.datepicker('destroy').datepicker({
      format:'mm-dd-yyyy', autoclose:true, todayHighlight:true, endDate: new Date()
    }).on('changeDate', function(e){
      $end.datepicker('setStartDate', e.date).datepicker('setEndDate', null);
      const endD=$end.datepicker('getDate');
      if (endD && endD < e.date) $end.datepicker('setDate', null);
      autoSetEndDateFromStart(true);
    });

    $end.datepicker('destroy').datepicker({
      format:'mm-dd-yyyy', autoclose:true, todayHighlight:true, endDate: null
    });

    const s = parseDMY($start.val());
    if (s){
      $end.datepicker('setStartDate', s).datepicker('setEndDate', null);
      const e = parseDMY($end.val());
      if (e && e < s) $end.datepicker('setDate', null);
    } else {
      $end.datepicker('setStartDate', null).datepicker('setEndDate', null);
    }
  }
  initDatepickers();

  function getRecurringType(){ return $('input[name="recurring_type"]:checked').val() || null; }
  function autoSetEndDateFromStart(forceRun){
    const s = $start.datepicker('getDate');
    if (!s) return;
    if (!$isRecurring.is(':checked') && !forceRun) return;

    let r = getRecurringType();
    if (!r){
      $('input[name="recurring_type"][value="weekly"]').prop('checked', true);
      r = 'weekly';
    }

    let e = null;
    if (r === 'weekly')    e = addDays(s, 7);
    if (r === 'biweekly')  e = addDays(s, 14);
    if (r === 'monthly')   e = addMonthsClamped(s, 1);
    if (r === 'yearly')    e = addYearsClamped(s, 1);

    if (e){ $end.datepicker('setStartDate', s).datepicker('setEndDate', null).datepicker('setDate', e); }
  }

  function toggleRecurrence(){
    const on = $isRecurring.is(':checked');
    $recurrenceGroup.toggle(on);
    if (on){
      if (!getRecurringType()){
        $('input[name="recurring_type"][value="weekly"]').prop('checked', true);
      }
      autoSetEndDateFromStart(true);
    }
  }
  $isRecurring.on('change', toggleRecurrence);
  toggleRecurrence();
  $(document).on('change', 'input[name="recurring_type"]', function(){
    $('input[name="recurring_type"]').not(this).prop('checked', false);
    autoSetEndDateFromStart(true);
  });
  (function onLoadForEdit(){
    if ($isRecurring.is(':checked') && getRecurringType()){
      autoSetEndDateFromStart(true);
    }
  })();

  // ==========================
  // Repeater helpers
  // ==========================
  function ensureAddButton($container, id, label, onAdd){
    let $btn = $('#'+id);
    if (!$btn.length){
      $btn = $('<div class="mt-1"><button type="button" class="btn btn-outline-primary btn-sm" id="'+id+'">'+label+'</button></div>');
      $container.after($btn);
      $btn.on('click', onAdd);
    }
    return $btn;
  }
  function updateRepeaterButtons($container, rowSel, btnSel, addButtonId, addLabel){
    const $rows = $container.find(rowSel);
    const count = $rows.length;
    const $addBtn = ensureAddButton($container, addButtonId, addLabel, function(){
      $container.trigger('repeater:add');
    });

    if (count <= 1) {
      $rows.find(btnSel).each(function(){
        $(this).text('+')
               .removeClass('btn-outline-danger').addClass('btn-outline-primary')
               .attr('data-action','add');
      });
      $addBtn.hide();
    } else {
      $rows.find(btnSel).each(function(){
        $(this).text('−')
               .removeClass('btn-outline-primary').addClass('btn-outline-danger')
               .attr('data-action','remove');
      });
      $addBtn.show();
    }
  }

  // ==========================
  // POC Repeater (hidden customer_id aware)
  // ==========================
  let pocOptionsCache = Array.isArray(window.__pocSeed) ? window.__pocSeed : [];
  function pocLabel(p){ return p.email ? `${p.name} (${p.email})` : p.name; }
  function getAllSelectedPocIds(exclude){
    const ids=new Set(); $('#poc-repeater .poc-select').each(function(){
      if (exclude && this===exclude[0]) return; const v=$(this).val(); if(v) ids.add(String(v));
    }); return ids;
  }
  function populatePocSelect($select){
    const selected = getAllSelectedPocIds($select); const curVal = $select.val();
    const selected = getAllSelectedPocIds($select); const curVal=$select.val();
    $select.empty().append(new Option('Select POC','',false,false));
    pocOptionsCache.forEach(p=>{
      const opt = new Option(pocLabel(p), String(p.id), false, false);
      if (selected.has(String(p.id)) && String(p.id)!==String(curVal)) $(opt).prop('disabled', true);
      $select.append(opt);
    });
    if (curVal && $select.find(`option[value="${curVal}"]`).length) $select.val(curVal);
    else $select.val('');
    if ($select.hasClass('select2-hidden-accessible')) $select.trigger('change.select2');
  }
  function refreshAllPocSelects(){ $('#poc-repeater .poc-select').each(function(){ populatePocSelect($(this)); }); }
  function togglePocButtons(){
    const rows = $('#poc-repeater .poc-row');
    rows.each(function(i){
      const $btn=$(this).find('.poc-addremove');
      if (i===rows.length-1) $btn.text('+').removeClass('btn-outline-danger').addClass('btn-outline-primary').attr('data-action','add');
      else $btn.text('−').removeClass('btn-outline-primary').addClass('btn-outline-danger').attr('data-action','remove');
    });
    if (curVal && $select.find(`option[value="${curVal}"]`).length) $select.val(curVal); else $select.val('');
    if ($select.hasClass('select2-hidden-accessible')) $select.trigger('change.select2');
  }
  function refreshAllPocSelects(){ $('#poc-repeater .poc-select').each(function(){ populatePocSelect($(this)); }); }
  function resetPocRepeaterToSingleRow(){
    $('#poc-repeater').html(`
      <div class="poc-row d-flex gap-2 align-items-start" data-index="0">
        <select name="pocs[]" class="form-select poc-select" required style="width:100%;">
          <option value="">Select POC</option>
        </select>
        <button type="button" class="btn btn-outline-primary poc-addremove" data-action="add">+</button>
      </div>`);
    $('#poc-repeater .poc-select').select2({ width:'100%', placeholder:'Select POC', allowClear:true })
      .on('change', function(){ refreshAllPocSelects(); });
    updateRepeaterButtons($('#poc-repeater'), '.poc-row', '.poc-addremove', 'poc-add', '+ Add POC');
  }
  function loadPocsForCustomer(customerId, {resetRepeater=false} = {}){
    if (!customerId){
      pocOptionsCache = [];
      if (resetRepeater) resetPocRepeaterToSingleRow();
      refreshAllPocSelects();
      return;
    }
    // If server seeded them, use cache
    if (Array.isArray(window.__pocSeed) && window.__pocSeed.length){
      pocOptionsCache = window.__pocSeed;
      if (resetRepeater) resetPocRepeaterToSingleRow();
      refreshAllPocSelects();
      return;
    }
    if (resetRepeater) resetPocRepeaterToSingleRow();
    $.getJSON(`/customers/${customerId}/pocs`)
      .done(function(data){ pocOptionsCache = Array.isArray(data) ? data : []; refreshAllPocSelects(); })
      .fail(function(){ pocOptionsCache=[]; refreshAllPocSelects(); alert('Failed to load POCs.'); });
  }
  function addPocRow(){
    const idx=$('#poc-repeater .poc-row').length;
    const $row=$(`
      <div class="poc-row d-flex gap-2 align-items-start" data-index="${idx}">
        <select name="pocs[]" class="form-select poc-select" required style="width:100%;">
          <option value="">Select POC</option>
        </select>
        <button type="button" class="btn btn-outline-primary poc-addremove" data-action="add">+</button>
      </div>`);
    $('#poc-repeater').append($row);
    $row.find('.poc-select').select2({ width:'100%', placeholder:'Select POC', allowClear:true })
      .on('change', function(){ $(this).valid(); refreshAllPocSelects(); });
    refreshAllPocSelects(); togglePocButtons();
  }
  $('#poc-repeater .poc-select').select2({ width:'100%', placeholder:'Select POC', allowClear:true })
    .on('change', function(){ $(this).valid(); refreshAllPocSelects(); });
  refreshAllPocSelects(); togglePocButtons();
  $('#poc-repeater').on('click', '.poc-addremove', function(){
    const action=$(this).attr('data-action');
    if (action==='add'){
      if (!pocOptionsCache.length){
        const customerId=$('#customer_id').val();
        if (!customerId){ alert('Select a customer first.'); return; }
        $('#customer_id').trigger('change'); return;
      }
      const selected=getAllSelectedPocIds(); const available=pocOptionsCache.filter(p=>!selected.has(String(p.id)));
      if (!available.length){ alert('All POCs already selected.'); return; }
      addPocRow();
    } else {
      if ($('#poc-repeater .poc-row').length<=1) return;
      $(this).closest('.poc-row').remove(); refreshAllPocSelects(); togglePocButtons();
    }
  });
  $customerSel.on('change', function(){
    const customerId=$(this).val();
    $('#poc-repeater').html(`
      <div class="poc-row d-flex gap-2 align-items-start" data-index="0">
        <select name="pocs[]" class="form-select poc-select" required style="width:100%;">
          <option value="">Select POC</option>
        </select>
        <button type="button" class="btn btn-outline-primary poc-addremove" data-action="add">+</button>
      </div>`);
    $('#poc-repeater .poc-select').select2({ width:'100%', placeholder:'Select POC', allowClear:true })
      .on('change', function(){ $(this).valid(); refreshAllPocSelects(); });
    togglePocButtons();
    if (!customerId){ pocOptionsCache=[]; refreshAllPocSelects(); return; }
    $.getJSON(`/customers/${customerId}/pocs`)
      .done(function(data){ pocOptionsCache = Array.isArray(data) ? data : []; refreshAllPocSelects(); })
      .fail(function(){ pocOptionsCache=[]; refreshAllPocSelects(); alert('Failed to load POCs.'); });
  });

  // ---------------------- PM repeater (+ per-PM members via AJAX) ----------------------
  const $pmRepeater = $('#pm-repeater');
  const $pmTplWrap  = $('#pm-row-template');

  // de-dup helpers
      .on('change', function(){ refreshAllPocSelects(); });
    refreshAllPocSelects();
    updateRepeaterButtons($('#poc-repeater'), '.poc-row', '.poc-addremove', 'poc-add', '+ Add POC');
  }

  // init first select & cache
  $('#poc-repeater .poc-select').select2({ width:'100%', placeholder:'Select POC', allowClear:true })
    .on('change', function(){ refreshAllPocSelects(); });
  (function initPocsFromHiddenCustomer(){
    const cid = $('#customer_id').val(); // hidden input
    loadPocsForCustomer(cid, { resetRepeater: false });
  })();
  refreshAllPocSelects();
  updateRepeaterButtons($('#poc-repeater'), '.poc-row', '.poc-addremove', 'poc-add', '+ Add POC');

  // add/remove row
  $('#poc-repeater').on('click', '.poc-addremove', function(){
    const action=$(this).attr('data-action');
    if (action==='add'){
      $('#poc-repeater').trigger('repeater:add');
    } else {
      if ($('#poc-repeater .poc-row').length<=1) return;
      $(this).closest('.poc-row').remove();
      refreshAllPocSelects();
      updateRepeaterButtons($('#poc-repeater'), '.poc-row', '.poc-addremove', 'poc-add', '+ Add POC');
    }
  });
  $('#poc-repeater').on('repeater:add', function(){
    if (!pocOptionsCache.length){
      const customerId = $('#customer_id').val(); // hidden input
      if (!customerId){ alert('Customer is missing.'); return; }
      loadPocsForCustomer(customerId, { resetRepeater: false });
      return;
    }
    const selected = getAllSelectedPocIds();
    const available = pocOptionsCache.filter(p => !selected.has(String(p.id)));
    if (!available.length){ alert('All POCs already selected.'); return; }
    addPocRow();
  });

  // ==========================
  // PM Repeater + Members
  // ==========================
  const $pmRepeater = $('#pm-repeater');
  const $pmTplWrap  = $('#pm-row-template');

  function chosenPmIds(exclude){
    const ids=new Set();
    $pmRepeater.find('.pm-select').each(function(){
      if (exclude && this===exclude[0]) return;
      const v=$(this).val(); if (v) ids.add(String(v));
    });
    return ids;
  }
  function dedupePmSelect($select){
    const chosen=chosenPmIds($select); const curVal=$select.val();
    $select.find('option').each(function(){
      const val=$(this).attr('value'); if(!val) return;
      const disable = chosen.has(String(val)) && String(val)!==String(curVal);
      $(this).prop('disabled', disable);
    });
    if ($select.hasClass('select2-hidden-accessible')) $select.trigger('change.select2');
  }
  function refreshAllPmSelects(){
    $pmRepeater.find('.pm-select').each(function(){ dedupePmSelect($(this)); });
  }
  function togglePmButtons(){
    const rows=$pmRepeater.find('.pm-row');
    rows.each(function(i){
      const $btn=$(this).find('.pm-addremove');
      if (i===rows.length-1) $btn.text('+').removeClass('btn-outline-danger').addClass('btn-outline-primary').attr('data-action','add');
      else $btn.text('−').removeClass('btn-outline-primary').addClass('btn-outline-danger').attr('data-action','remove');
    });
  }

  // Build options & apply selection
      $(this).prop('disabled', chosen.has(String(val)) && String(val)!==String(curVal));
    });
    if ($select.hasClass('select2-hidden-accessible')) $select.trigger('change.select2');
  }
  function refreshAllPmSelects(){ $pmRepeater.find('.pm-select').each(function(){ dedupePmSelect($(this)); }); }

  function populateMembersSelect($select, list, preselectedIds = []) {
    const selectedSet = new Set((preselectedIds || []).map(String));
    $select.empty();
    list.forEach(u => {
      const text = u.email ? `${u.name} (${u.email})` : u.name;
      const opt = new Option(text, String(u.id), false, selectedSet.has(String(u.id)));
      $select.append(opt);
    });
    $select.trigger('change');
  }

  function fetchMembersForPM(pmId) {
    return $.post("{{ route('projects.pmMembers') }}", {
      pm_ids: [String(pmId)],
      @if($isEdit)
      project_id: {{ (int)$project->id }},
  function fetchMembersForPM(pmId) {
    return $.post("{{ route('projects.pmMembers') }}", {
      pm_ids: [String(pmId)],
      @if(isset($isEdit) && $isEdit)
      project_id: {{ (int)($project->id ?? 0) }},
      @endif
      _token: "{{ csrf_token() }}"
    });
  }

  function refreshMembersForRow($row){
    const pmId = $row.find('.pm-select').val();
    const show = $('input[name="project_category"]:checked').val() === '1' && !!pmId;

    const $wrap = $row.find('.members-for-pm');
    const $sel  = $row.find('.members-select');

    if (!show) {
      $wrap.hide();
      $sel.attr('name', 'members_by_pm[TMP][]').attr('data-for-pm', '');
      return;
    }

    // Show & set correct post name
    $wrap.show();
    $sel.attr('name', `members_by_pm[${pmId}][]`).attr('data-for-pm', pmId);

    // Prevent redundant loads per PM per row
    if ($sel.data('loaded-for') === String(pmId)) return;

    // Initial preselected (from server for edit/old), used only once
    const preselected = Array.isArray($sel.data('preselected')) ? $sel.data('preselected') : [];
    const catIs1 = getProjectCategoryVal() === '1';
    const $wrap = $row.find('.members-for-pm');
    const $sel  = $row.find('.members-select');

    if (!catIs1) {
      $wrap.hide();
      clearMembersPlaceholder($sel);
      if (!$sel.hasClass('select2-hidden-accessible')) {
        $sel.select2({ width:'100%', placeholder:'Select members', allowClear:true });
      }
      $sel.attr('name', 'members_by_pm[TMP][]').attr('data-for-pm', '').removeData('loaded-for');
      return;
    }

    $wrap.show();

    if (!$sel.hasClass('select2-hidden-accessible')) {
      $sel.select2({ width:'100%', placeholder:'Select members', allowClear:true });
    }

    if (!pmId) {
      $sel.attr('name', 'members_by_pm[TMP][]').attr('data-for-pm', '').removeData('loaded-for');
      if (!$sel.data('placeholder-injected')) {
        $sel.empty().append(new Option('Select a PM to load members', '', false, false));
        $sel.data('placeholder-injected', true);
      }
      setSelect2Disabled($sel, true, 'Select a PM to load members');
      return;
    }

    $sel.attr('name', `members_by_pm[${pmId}][]`).attr('data-for-pm', pmId);
    clearMembersPlaceholder($sel);
    setSelect2Disabled($sel, false, 'Select members');

    if ($sel.data('loaded-for') === String(pmId)) return;

    const preselected = Array.isArray($sel.data('preselected')) ? $sel.data('preselected') : [];

    fetchMembersForPM(pmId)
      .done(res => {
        const list = Array.isArray(res?.data) ? res.data : [];
        populateMembersSelect($sel, list, preselected);
        $sel.data('loaded-for', String(pmId));
        $sel.data('preselected', []); // clear cache
      })
      .fail(() => {
        console.error('Failed to load members for PM', pmId);
        populateMembersSelect($sel, [], []);
        $sel.data('loaded-for', String(pmId)).data('preselected', []);
        $sel.trigger('change.select2');
      })
      .fail(() => {
        $sel.empty().trigger('change.select2');
      });
  }

  function addPmRow(){
    const $row = $pmTplWrap.children('.pm-row').first().clone(true,true);

    // reset fields
    const $sel=$row.find('select.pm-select');
    $sel.prop('disabled', false).attr('name','pm_ids[]').val('');
    const $members=$row.find('.members-select');
    $members.attr('name','members_by_pm[TMP][]').attr('data-for-pm','').data('preselected', []);
    $row.find('.members-for-pm').hide();

    $pmRepeater.append($row);

    // init select2
    $sel.select2({ width:'100%', placeholder:'Select PM', allowClear:true })
      .on('change', function(){
        $(this).valid && $(this).valid();
        refreshAllPmSelects();
        refreshMembersForRow($row);
      });

    $members.select2({ width:'100%', placeholder:'Select members', allowClear:true });

    refreshAllPmSelects(); togglePmButtons();
  }

  // init existing PM selects & members
    $sel.select2({ width:'100%', placeholder:'Select PM', allowClear:true });
    $members.select2({ width:'100%', placeholder:'Select members', allowClear:true });

    refreshAllPmSelects();
    updateRepeaterButtons($pmRepeater, '.pm-row', '.pm-addremove', 'pm-add', '+ Add PM');
    refreshMembersForRow($row);
  }

  $pmRepeater.find('.pm-select').each(function(){
    $(this).select2({ width:'100%', placeholder:'Select PM', allowClear:true });
  });
  $pmRepeater.find('.members-select').each(function(){
    $(this).select2({ width:'100%', placeholder:'Select members', allowClear:true });
  });

  refreshAllPmSelects();
  $pmRepeater.find('.pm-row').each(function(){ refreshMembersForRow($(this)); });
  togglePmButtons();

  // events for PM repeater
  $pmRepeater.on('click', '.pm-addremove', function(){
    const action=$(this).attr('data-action');
    if (action==='add'){
      const totalUsers=$pmTplWrap.find('option[value!=""]').length;
      const chosenCount=chosenPmIds().size;
      if (chosenCount>=totalUsers){ alert('All PMs are already selected.'); return; }
      addPmRow();
    } else {
      if ($pmRepeater.find('.pm-row').length<=1) return;
      $(this).closest('.pm-row').remove(); refreshAllPmSelects(); togglePmButtons();
    }
  });

  $pmRepeater.on('change', 'select.pm-select', function(){
    $(this).valid && $(this).valid();
    refreshAllPmSelects();
    refreshMembersForRow($(this).closest('.pm-row'));
  });

  // Toggle per-PM members blocks on category change
  $('input[name="project_category"]').on('change', function(){
    $pmRepeater.find('.pm-row').each(function(){ refreshMembersForRow($(this)); });
  }).trigger('change');

  // ---------------------- Pricing by Department ----------------------
  $('#department_id').on('change', function(){
    var deptId = $(this).val(), $pricingSel = $('#pricing_id');
    $pricingSel.empty().append('<option value="">Loading...</option>').trigger('change');
    if (!deptId){ $pricingSel.html('<option value="">Select</option>').trigger('change'); return; }
    $.ajax({
      url: "{{ route('pricing.byDepartment', ':id') }}".replace(':id', deptId),
      type: "GET",
      dataType: "json",
      success: function(resp){
        $pricingSel.empty().append('<option value="">Select</option>');
        if (resp.data){
          resp.data.forEach(function(item){ $pricingSel.append('<option value="'+item.id+'">'+item.name+'</option>'); });
        }
        var oldVal = "{{ old('pricing_id', $project->pricing_id ?? '') }}";
        if (oldVal) $pricingSel.val(oldVal);
        $pricingSel.trigger('change');
      },
      error: function(){
        $pricingSel.empty().append('<option value="">Select</option>').trigger('change');
        alert('Could not load pricing for the selected department.');
      }
    });
  });
  if ($('#department_id').val()){ $('#department_id').trigger('change'); }

  // -------- final submit guard for POCs/PMs ----------
  refreshAllPmSelects();
  $pmRepeater.find('.pm-row').each(function(){ refreshMembersForRow($(this)); });

  updateRepeaterButtons($pmRepeater, '.pm-row', '.pm-addremove', 'pm-add', '+ Add PM');
  $pmRepeater.on('repeater:add', function(){ addPmRow(); });
  $(document).on('click', '#pm-add', function(){ $pmRepeater.trigger('repeater:add'); });
  $(document).on('click', '#poc-add', function(){ $('#poc-repeater').trigger('repeater:add'); });

  $pmRepeater.on('click', '.pm-addremove', function(){
    const action=$(this).attr('data-action');
    if (action==='add'){
      $pmRepeater.trigger('repeater:add');
    } else {
      if ($pmRepeater.find('.pm-row').length<=1) return;
      $(this).closest('.pm-row').remove();
      refreshAllPmSelects();
      updateRepeaterButtons($pmRepeater, '.pm-row', '.pm-addremove', 'pm-add', '+ Add PM');
    }
  });

  $pmRepeater.on('change', '.pm-select', function(){
    const $row = $(this).closest('.pm-row');
    refreshAllPmSelects();
    refreshMembersForRow($row);
  });

  // ==========================
  // Category -> members visibility sync
  // ==========================
  function onCategoryChange(){
    $pmRepeater.find('.pm-row').each(function(){ refreshMembersForRow($(this)); });
    applyBulkToggle();
    togglePricingByCategory();
  }
  $('#project_category').off('change.pmcat').on('change.pmcat', onCategoryChange);
  $('input[name="project_category"]').off('change.pmcat').on('change.pmcat', onCategoryChange);
  onCategoryChange();

  // ==========================
  // Pricing by Department (AJAX)
  // ==========================
// ========= Pricing loader (refresh on IV/Dept/SO/Pricing Type) =========
function loadPricing(opts) {
  const options = Object.assign({ autoSelectFirst: false }, opts || {});
  const $pricingSel = $('#pricing_id');
  const pType  = $('#pricing_type').val();
  // Only fetch when category != 2
  if (getProjectCategoryVal() === '2' && pType == "standard") {
    $pricingSel.empty().append('<option value="">Select</option>').trigger('change');
    return;
  }

  const deptId = $('#department_id').val();               // used in the route
  const indId  = $('#industry_vertical_id').val();
  const servId = $('#service_offering_id').val();
  
  const custId = $('#customer_id').val();
  const proId  = $('#project_id').val();
  $pricingSel.empty().append('<option value="">Loading...</option>').trigger('change');

  if (!deptId) {
    // Department is mandatory for the endpoint
    $pricingSel.html('<option value="">Select</option>').trigger('change');
    return;
  }

  $.ajax({
    url: "{{ route('pricing.byDepartment', ':id') }}".replace(':id', deptId),
    type: "GET",
    dataType: "json",
    data: {
      industry_vertical_id: indId || '',
      service_offering_id:  servId || '',
      pricing_type:         pType || '',
      customer_id:          custId || '',
      project_id: proId || '' 
    },
    success: function(resp){
      const list = (resp && resp.data) ? resp.data : [];
      $pricingSel.empty().append('<option value="">Select</option>');

      list.forEach(function(item){
        $pricingSel.append('<option value="'+item.id+'">'+item.name+'</option>');
      });

      // Keep old selection unless refresh requested an auto-pick
      var oldVal = "{{ old('pricing_id', $project->pricing_id ?? '') }}";

      if (options.autoSelectFirst) {
        if (list.length > 0) {
          $pricingSel.val(String(list[0].id));
        } else {
          $pricingSel.val('');
        }
      } else if (oldVal) {
        $pricingSel.val(String(oldVal));
      }

      $pricingSel.trigger('change');
    },
    error: function(){
      $pricingSel.empty().append('<option value="">Select</option>').trigger('change');
      alert('Could not load pricing for the selected criteria.');
    }
  });
}

// Existing bindings already call loadPricing on these:
$('#industry_vertical_id, #department_id, #service_offering_id, #pricing_type').off('change.loadPricing').on('change.loadPricing', loadPricing);

// Initial hydration (if anything already selected and category != 2)
if (getProjectCategoryVal() !== '2') {
  loadPricing();
}

// ---------- Refresh button = reload + auto-select first from response ----------
$(document).on('click', '#btn-refresh-pricing', function(){
  loadPricing({ autoSelectFirst: true });
});


  // ==========================
  // Submit guards
  // ==========================
  function validatePocRepeaterRequired(){
    const ok = $('#poc-repeater .poc-select').toArray().some(el => $(el).val());
    $('#poc-repeater-error').toggleClass('d-none', ok);
    return ok;
  }
  $(document).on('change', '#poc-repeater .poc-select', function(){
  validatePocRepeaterRequired();
});
  $('#projectForm').on('submit', function(e){
    const pocOK = validatePocRepeaterRequired();
    const pmOK  = $pmRepeater.find('.pm-select').toArray().some(el => el.value);
  $('#projectForm').on('submit', function(e){
    const pocOK = validatePocRepeaterRequired();
    const pmOK  = $('#pm-repeater .pm-select').toArray().some(el => el.value);
    $('#pm-repeater-error').toggleClass('d-none', pmOK);
    if (!pocOK || !pmOK){
      e.preventDefault();
      if (!pocOK) $('#poc-repeater .poc-select').first().select2('open');
      else        $pmRepeater.find('.pm-select').first().select2('open');
    }
  });
      else $('#pm-repeater .pm-select').first().select2('open');
    }
  });

  // ==========================
  // Recurring label + Bulk toggle
  // ==========================
  (function () {
    const input = document.getElementById('is_recurring');
    const text  = document.getElementById('isRecurringText');
    if (!input || !text) return;
    function updateLabel() { text.textContent = input.checked ? 'Ongoing' : 'One-time'; }
    input.addEventListener('change', updateLabel);
    updateLabel();
  })();

  function applyBulkToggle(){
    const bulk  = document.getElementById('bulk_import_section');
    if (!bulk) return;
    bulk.style.display = (getProjectCategoryVal() === '2') ? '' : 'none';
  }
  (function () {
    document.getElementById('project_category')?.addEventListener('change', applyBulkToggle);
    document.querySelectorAll('input[name="project_category"]').forEach(r => r.addEventListener('change', applyBulkToggle));
    applyBulkToggle();
  })();

  // ========= Helpers for cascading dropdowns =========
function resetSelect($sel, placeholder) {
  $sel.empty().append('<option value="">' + (placeholder || 'Select') + '</option>').trigger('change');
}
function populateSelect($sel, items, selectedVal) {
  resetSelect($sel);
  (items || []).forEach(function (it) {
    $sel.append('<option value="' + it.id + '">' + it.name + '</option>');
  });
  if (selectedVal) $sel.val(String(selectedVal));
  $sel.trigger('change');
}

// ========= AJAX loaders =========
function loadDepartments(industryId, opts) {
  const $dept = $('#department_id');
  const $so   = $('#service_offering_id');
  resetSelect($dept, 'Select Department');
  resetSelect($so, 'Select Service Offering');
  if (!industryId) return;

  $dept.append('<option value="">Loading...</option>').trigger('change');

  $.ajax({
    url: "{{ route('departments.byIndustry', ':id') }}".replace(':id', industryId),
    type: "GET",
    dataType: "json",
    success: function (resp) {
      populateSelect($dept, resp?.data || [], opts?.preselectDepartment);
      // If a department was preselected (edit/old), load its service offerings next.
      const nextDeptId = $dept.val();
      if (nextDeptId) loadServiceOfferings(nextDeptId, { preselectService: opts?.preselectService });
    },
    error: function () {
      resetSelect($dept, 'Select Department');
      alert('Could not load departments for the selected Industry Vertical.');
    }
  });
}

function loadServiceOfferings(departmentId, opts) {
  const $so = $('#service_offering_id');
  resetSelect($so, 'Select Service Offering');
  if (!departmentId) return;

  $so.append('<option value="">Loading...</option>').trigger('change');

 $.ajax({
  // ✅ Use the previously created route name (no dash)
  url: "{{ route('serviceOfferings.byDepartment', ':id') }}".replace(':id', departmentId),
  type: "GET",
  dataType: "json",
  success: function (resp) {
    populateSelect($so, resp?.data || [], opts?.preselectService);
  },
  error: function () {
    resetSelect($so, 'Select Service Offering');
    alert('Could not load service offerings for the selected Department.');
  }
});
}

// ========= Event bindings =========
$('#industry_vertical_id').on('change', function () {
  loadDepartments(this.value);
});

$('#department_id').on('change', function () {
  loadServiceOfferings(this.value);
});

// ========= Initial (edit/old) hydration =========
(function hydrateCascadesOnLoad() {
  const ivId     = $('#industry_vertical_id').val();
  const oldDept  = "{{ old('department_id', $project->department_id ?? '') }}";
  const oldSO    = "{{ old('service_offering_id', $project->service_offering_id ?? '') }}";

  if (ivId) {
    loadDepartments(ivId, { preselectDepartment: oldDept, preselectService: oldSO });
  }
})();

});
</script>
@endsection
