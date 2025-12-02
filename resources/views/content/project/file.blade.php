@extends('layouts/layoutMaster')
@section('title', $title)

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('extra-script')
<script>
  // --------- Minimal helpers ----------
  function initSelect2(scope) {
    // (Re)initialize only the selects that are not yet select2-ified
    const $scope = scope ? window.jQuery(scope) : window.jQuery(document);
    $scope.find('select.select2').each(function () {
      const $sel = window.jQuery(this);
      if (!$sel.hasClass('select2-hidden-accessible')) {
        $sel.select2({ width: '100%' });
      }
    });
  }

  function addRow(containerId, templateId, afterAddCb) {
    const container = document.getElementById(containerId);
    const tpl = document.getElementById(templateId);
    const node = tpl.content.cloneNode(true);
    container.appendChild(node);
    // init Select2 for just-added row
    const lastRow = container.lastElementChild;
    if (window.jQuery && window.jQuery.fn.select2) {
      initSelect2(lastRow);
    }
    if (typeof afterAddCb === 'function') afterAddCb(lastRow);
    refreshButtons(containerId);
  }

  function removeRow(targetRow, containerId) {
    const container = document.getElementById(containerId);
    if (container.children.length > 1) {
      targetRow.remove();
      refreshButtons(containerId);
    }
  }

  function refreshButtons(containerId) {
    const container = document.getElementById(containerId);
    const rows = Array.from(container.children);
    rows.forEach((row, i) => {
      const btnHolder = row.querySelector('[data-btn-holder]');
      if (!btnHolder) return;
      btnHolder.innerHTML = '';
      // + button
      const addBtn = document.createElement('button');
      addBtn.type = 'button';
      addBtn.className = 'btn btn-success me-1';
      addBtn.textContent = '+';
      addBtn.dataset.action = 'add';
      btnHolder.appendChild(addBtn);
      // - button (not shown for first row only if you'd like)
      if (rows.length > 1) {
        const rmBtn = document.createElement('button');
        rmBtn.type = 'button';
        rmBtn.className = 'btn btn-danger';
        rmBtn.textContent = '-';
        rmBtn.dataset.action = 'remove';
        btnHolder.appendChild(rmBtn);
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Init Select2 for the page
    if (window.jQuery && window.jQuery.fn.select2) {
      initSelect2();
    }

    // ---------- PDF NAMES REPEATER ----------
    refreshButtons('pdf-repeater');
    document.getElementById('pdf-repeater').addEventListener('click', function(e){
      const row = e.target.closest('.pdf-row');
      if (!row) return;
      const holder = e.target.closest('[data-btn-holder]');
      if (!holder) return;
      if (e.target.dataset.action === 'add') {
        addRow('pdf-repeater', 'tpl-pdf');
      }
      if (e.target.dataset.action === 'remove') {
        removeRow(row, 'pdf-repeater');
      }
    });

    // ---------- QUERIES REPEATER ----------
    refreshButtons('queries-repeater');
    document.getElementById('queries-repeater').addEventListener('click', function(e){
      const row = e.target.closest('.q-row');
      if (!row) return;
      const holder = e.target.closest('[data-btn-holder]');
      if (!holder) return;
      if (e.target.dataset.action === 'add') {
        addRow('queries-repeater', 'tpl-query', function(newRow){
          // clear any inputs just in case
          newRow.querySelectorAll('input').forEach(i => i.value = '');
          const sel = newRow.querySelector('select');
          if (sel) sel.selectedIndex = 0;
        });
      }
      if (e.target.dataset.action === 'remove') {
        removeRow(row, 'queries-repeater');
      }
    });

    // ---------- FEEDBACK REPEATER ----------
    refreshButtons('feedback-repeater');
    document.getElementById('feedback-repeater').addEventListener('click', function(e){
      const row = e.target.closest('.fb-row');
      if (!row) return;
      const holder = e.target.closest('[data-btn-holder]');
      if (!holder) return;
      if (e.target.dataset.action === 'add') {
        addRow('feedback-repeater', 'tpl-feedback', function(newRow){
          newRow.querySelectorAll('input, textarea').forEach(el => el.value = '');
          newRow.querySelectorAll('select').forEach(sel => sel.selectedIndex = 0);
        });
      }
      if (e.target.dataset.action === 'remove') {
        removeRow(row, 'feedback-repeater');
      }
    });
  });
</script>
@endsection

@section('content')
<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header">
        <h4 class="text-dark">{{ $title }}</h4>
      </div>
      <div class="card-body">
        <form id="intakeForm" method="POST" action="#">
          @csrf
          @if($type == 'edit') @method('PUT') @endif

          {{-- ===== Basic Details (ClientId & ProjectId removed) ===== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Basic Details</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Name of the Project <span class="text-danger">*</span></label>
              <input type="text" name="project_name" class="form-control" value="{{ old('project_name', $data->project_name ?? '') }}" placeholder="Enter Project Name">
            </div>

            <div class="col-md-3 mb-3">
              <label class="form-label">PropertyManagerId <span class="text-danger">*</span></label>
              <input type="text" name="property_manager_id" class="form-control" value="{{ old('property_manager_id', $data->property_manager_id ?? '') }}" placeholder="Enter Property Manager Id">
            </div>

            <div class="col-md-3 mb-3">
              <label class="form-label">Request Received Date <span class="text-danger">*</span></label>
              <input type="text" name="request_received_date" class="form-control" value="{{ old('request_received_date', $data->request_received_date ?? '') }}" placeholder="YYYY-MM-DD">
            </div>

            <div class="col-md-3 mb-3">
              <label class="form-label">PriorityId <span class="text-danger">*</span></label>
              <select name="priority_id" class="form-select select2">
                <option value="">Select</option>
                @foreach(($masters['priority'] ?? []) as $item)
                  <option value="{{ $item->id }}" {{ old('priority_id', $data->priority_id ?? '') == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-3 mb-3">
              <label class="form-label">Status <span class="text-danger">*</span></label>
              <select name="status_master" class="form-select select2">
                <option value="">Select</option>
                @foreach(($masters['status'] ?? []) as $item)
                  <option value="{{ $item->id }}" {{ old('status_master', $data->status_master ?? '') == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- ===== Property & Tenant ===== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Property & Tenant</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 mb-3">
              <label class="form-label">Property ID <span class="text-danger">*</span></label>
              <input type="text" name="property_id" class="form-control" value="{{ old('property_id', $data->property_id ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Property Name <span class="text-danger">*</span></label>
              <input type="text" name="property_name" class="form-control" value="{{ old('property_name', $data->property_name ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Tenant Name</label>
              <input type="text" name="tenant_name" class="form-control" value="{{ old('tenant_name', $data->tenant_name ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Tenant ID / Lease ID</label>
              <input type="text" name="tenant_or_lease_id" class="form-control" value="{{ old('tenant_or_lease_id', $data->tenant_or_lease_id ?? '') }}">
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Premises Address</label>
              <input type="text" name="premises_address" class="form-control" value="{{ old('premises_address', $data->premises_address ?? '') }}" placeholder="Street, City, State, Zip">
            </div>
          </div>

          {{-- ===== Documents (PDF Names repeater) ===== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Documents</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <!-- <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">No. of Documents</label>
              <input type="text" name="no_of_documents" class="form-control" value="{{ old('no_of_documents', $data->no_of_documents ?? '') }}" placeholder="0">
            </div>
          </div> -->

          <div id="pdf-repeater">
            <div class="row pdf-row">
              <div class="col-md-10 mb-3">
                <label class="form-label">PDF Name</label>
                <input type="text" name="pdf_names[]" class="form-control" placeholder="e.g., Executed Lease.pdf" value="">
              </div>
              <div class="col-md-2 d-flex align-items-end mb-3" data-btn-holder></div>
            </div>
          </div>

          {{-- ===== Queries (repeater) ===== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Queries</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div id="queries-repeater">
            <div class="row q-row">
              <div class="col-md-3 mb-3">
                <label class="form-label">SB Queries</label>
                <input type="text" name="sb_queries[]" class="form-control" placeholder="e.g., count or ref">
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label">Type of Queries</label>
                <input type="text" name="type_of_queries[]" class="form-control" placeholder="Summary of issues">
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label">Client Response</label>
                <input type="text" name="client_response[]" class="form-control">
              </div>
              <div class="col-md-2 mb-3">
                <label class="form-label">Query Status</label>
                <select name="query_status[]" class="form-select select2">
                  <option value="">Select</option>
                  @foreach(($masters['query_status'] ?? []) as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-1 d-flex align-items-end mb-3" data-btn-holder></div>
            </div>
          </div>

          {{-- ===== Abstractor ===== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Abstractor</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 mb-3">
              <label class="form-label">Abstractor</label>
              <select name="abstractor" class="form-select select2">
                <option value="">Select</option>
                @foreach(($masters['users'] ?? []) as $u)
                  <option value="{{ $u->id }}" {{ old('abstractor', $data->abstractor ?? '') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Abstraction Start Date</label>
              <input type="text" name="abstraction_start_date" class="form-control" value="{{ old('abstraction_start_date', $data->abstraction_start_date ?? '') }}" placeholder="YYYY-MM-DD">
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Abstract Completion Date</label>
              <input type="text" name="abstract_completion_date" class="form-control" value="{{ old('abstract_completion_date', $data->abstract_completion_date ?? '') }}" placeholder="YYYY-MM-DD">
            </div>
          </div>

          {{-- ===== Reviewer ===== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Reviewer</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 mb-3">
              <label class="form-label">Reviewer</label>
              <select name="reviewer" class="form-select select2">
                <option value="">Select</option>
                @foreach(($masters['users'] ?? []) as $u)
                  <option value="{{ $u->id }}" {{ old('reviewer', $data->reviewer ?? '') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Review Completion Date</label>
              <input type="text" name="review_completion_date" class="form-control" value="{{ old('review_completion_date', $data->review_completion_date ?? '') }}" placeholder="YYYY-MM-DD">
            </div>
          </div>

          {{-- ===== Sense Check ===== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Sense Check</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 mb-3">
              <label class="form-label">Sense Check</label>
              <select name="sense_check_ddr" class="form-select select2">
                <option value="">Select</option>
                @foreach(($masters['sense_ddr'] ?? []) as $item)
                  <option value="{{ $item->id }}" {{ old('sense_check_ddr', $data->sense_check_ddr ?? '') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Sense Check</label>
              <input type="text" name="sense_check_completion_date" class="form-control" value="{{ old('sense_check_completion_date', $data->sense_check_completion_date ?? '') }}" placeholder="YYYY-MM-DD">
            </div>
          </div>

          {{-- ===== Delivery ===== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Delivery</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 mb-3">
              <label class="form-label">Proposed Delivery Date</label>
              <input type="text" name="proposed_delivery_date" class="form-control" value="{{ old('proposed_delivery_date', $data->proposed_delivery_date ?? '') }}" placeholder="YYYY-MM-DD">
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Actual Delivered Date</label>
              <input type="text" name="actual_delivered_date" class="form-control" value="{{ old('actual_delivered_date', $data->actual_delivered_date ?? '') }}" placeholder="YYYY-MM-DD">
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Feedback received Date</label>
              <input type="text" name="feedback_received_date" class="form-control" value="{{ old('feedback_received_date', $data->feedback_received_date ?? '') }}" placeholder="YYYY-MM-DD">
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Feedback completion date</label>
              <input type="text" name="feedback_completion_date" class="form-control" value="{{ old('feedback_completion_date', $data->feedback_completion_date ?? '') }}" placeholder="YYYY-MM-DD">
            </div>
          </div>

          {{-- ===== Feedback (repeater) ===== --}}
          <div class="col-md-12 my-4">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Feedback</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div id="feedback-repeater">
            <div class="row fb-row">
              <div class="col-md-3 mb-3">
                <label class="form-label">Date of Feedback Received</label>
                <input type="text" name="fb_date_received[]" class="form-control" placeholder="YYYY-MM-DD" value="">
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label">Customer Name</label>
                <input type="text" name="fb_customer_name[]" class="form-control" value="">
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label">Category of FB</label>
                <select name="fb_category_id[]" class="form-select select2">
                  <option value="">Select</option>
                  @foreach(($masters['feedback_categories'] ?? []) as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label">Feedback completion date</label>
                <input type="text" name="fb_completion_date[]" class="form-control" placeholder="YYYY-MM-DD" value="">
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Customer Feedback Comments</label>
                <textarea name="fb_customer_comments[]" class="form-control" rows="2" placeholder="Enter customer feedback..."></textarea>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">SB Response</label>
                <textarea name="fb_sb_response[]" class="form-control" rows="2" placeholder="Enter SB response..."></textarea>
              </div>

              <div class="col-md-10 mb-3">
                <label class="form-label">Feedback (extra)</label>
                <input type="text" name="fb_feedback[]" class="form-control" placeholder="Short feedback summary">
              </div>

              <div class="col-md-2 d-flex align-items-end mb-3" data-btn-holder></div>
            </div>
          </div>

          {{-- ===== Submit ===== --}}
          <div class="text-end mt-2">
            <a href="#" class="btn btn-secondary">
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

{{-- ===== Repeater templates (kept uninitialized for clean cloning) ===== --}}
<template id="tpl-pdf">
  <div class="row pdf-row">
    <div class="col-md-10 mb-3">
      <label class="form-label">PDF Name</label>
      <input type="text" name="pdf_names[]" class="form-control" placeholder="e.g., Executed Lease.pdf" value="">
    </div>
    <div class="col-md-2 d-flex align-items-end mb-3" data-btn-holder></div>
  </div>
</template>

<template id="tpl-query">
  <div class="row q-row">
    <div class="col-md-3 mb-3">
      <label class="form-label">SB Queries</label>
      <input type="text" name="sb_queries[]" class="form-control" placeholder="e.g., count or ref">
    </div>
    <div class="col-md-3 mb-3">
      <label class="form-label">Type of Queries</label>
      <input type="text" name="type_of_queries[]" class="form-control" placeholder="Summary of issues">
    </div>
    <div class="col-md-3 mb-3">
      <label class="form-label">Client Response</label>
      <input type="text" name="client_response[]" class="form-control">
    </div>
    <div class="col-md-2 mb-3">
      <label class="form-label">Query Status</label>
      <select name="query_status[]" class="form-select select2">
        <option value="">Select</option>
        @foreach(($masters['query_status'] ?? []) as $item)
          <option value="{{ $item->id }}">{{ $item->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-1 d-flex align-items-end mb-3" data-btn-holder></div>
  </div>
</template>

<template id="tpl-feedback">
  <div class="row fb-row">
    <div class="col-md-3 mb-3">
      <label class="form-label">Date of Feedback Received</label>
      <input type="text" name="fb_date_received[]" class="form-control" placeholder="YYYY-MM-DD" value="">
    </div>
    <div class="col-md-3 mb-3">
      <label class="form-label">Customer Name</label>
      <input type="text" name="fb_customer_name[]" class="form-control" value="">
    </div>
    <div class="col-md-3 mb-3">
      <label class="form-label">Category of FB</label>
      <select name="fb_category_id[]" class="form-select select2">
        <option value="">Select</option>
        @foreach(($masters['feedback_categories'] ?? []) as $item)
          <option value="{{ $item->id }}">{{ $item->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3 mb-3">
      <label class="form-label">Feedback completion date</label>
      <input type="text" name="fb_completion_date[]" class="form-control" placeholder="YYYY-MM-DD" value="">
    </div>

    <div class="col-md-6 mb-3">
      <label class="form-label">Customer Feedback Comments</label>
      <textarea name="fb_customer_comments[]" class="form-control" rows="2" placeholder="Enter customer feedback..."></textarea>
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">SB Response</label>
      <textarea name="fb_sb_response[]" class="form-control" rows="2" placeholder="Enter SB response..."></textarea>
    </div>

    <div class="col-md-10 mb-3">
      <label class="form-label">Feedback (extra)</label>
      <input type="text" name="fb_feedback[]" class="form-control" placeholder="Short feedback summary">
    </div>

    <div class="col-md-2 d-flex align-items-end mb-3" data-btn-holder></div>
  </div>
</template>
@endsection
