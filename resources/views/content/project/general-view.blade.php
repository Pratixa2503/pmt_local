@extends('layouts/layoutMaster')
@section('title', $title)

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
@endsection

@section('content')
<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="text-dark mb-0">Project: {{ $project->project_name }}</h4>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Back</a>
      </div>

      <div class="card-body">
        {{-- Save assignment dates --}}
        <form id="rangeForm" method="POST" action="{{ route('projects.storeAssignmentDates', $encryptedId) }}" class="row g-3 mb-4" novalidate>
          @csrf

          @if(!empty($isAdminOrManager) && $isAdminOrManager)
          <div class="col-md-4">
            <label class="form-label">Member <span class="text-danger">*</span></label>
            @php
            $memberGroups = isset($assignments) ? $assignments->groupBy('member_id') : collect();
            @endphp
            <select class="form-select" name="member_id" required>
              @foreach($memberGroups as $mid => $rows)
              @php
              $first = $rows->first();
              $memberName = $first->member
              ? trim($first->member->first_name . ' ' . $first->member->last_name)
              : 'Member #' . $mid;
              @endphp
              <option value="{{ $mid }}" {{ (isset($memberId) && (int)$memberId === (int)$mid) ? 'selected' : '' }}>
                {{ $memberName }}
              </option>
              @endforeach
            </select>
          </div>
          @else
          {{-- Regular member: lock to self --}}
          <input type="hidden" name="member_id" value="{{ $memberId ?? auth()->id() }}">
          @endif

          <div class="col-md-4">
            <label class="form-label">Start Date <span class="text-danger">*</span></label>
            <input type="text" class="form-control datepicker" name="start_date"
              value="{{ old('start_date', optional($startDate)->format('m/d/Y')) }}"
              placeholder="MM/DD/YYYY" autocomplete="off" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">End Date <span class="text-danger">*</span></label>
            <input type="text" class="form-control datepicker" name="end_date"
              value="{{ old('end_date', optional($endDate)->format('m/d/Y')) }}"
              placeholder="MM/DD/YYYY" autocomplete="off" required>
          </div>

          <div class="col-12 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Save</button>
          </div>
        </form>

        @if(!empty($isAdminOrManager) && $isAdminOrManager)
        <div class="border rounded p-3">
          <h6 class="mb-3">All Members</h6>
          @if(isset($assignments) && $assignments->count())
          <div class="table-responsive">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Member</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                </tr>
              </thead>
              <tbody>
                @foreach($assignments as $as)
                @php
                $memberName = $as->member
                ? trim($as->member->first_name . ' ' . $as->member->last_name)
                : 'Member #' . $as->member_id;
                @endphp
                <tr>
                  <td>{{ $memberName }}</td>
                  <td>{{ $as->startdate ? \Carbon\Carbon::parse($as->startdate)->format('M d, Y') : '—' }}</td>
                  <td>{{ $as->enddate ? \Carbon\Carbon::parse($as->enddate)->format('M d, Y') : '—' }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @else
          <p class="mb-0 text-muted">No assignments found for this project.</p>
          @endif
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@section('extra-script')
<script>
  $(function() {
    function parseUsDate(str) {
      const m = /^(\d{2})\/(\d{2})\/(\d{4})$/.exec((str || '').trim());
      if (!m) return null;
      const mm = +m[1],
        dd = +m[2],
        yyyy = +m[3];
      const d = new Date(yyyy, mm - 1, dd);
      return (d.getFullYear() === yyyy && d.getMonth() === mm - 1 && d.getDate() === dd) ? d : null;
    }

    // init datepickers
    $('input.datepicker').datepicker({
      format: 'mm/dd/yyyy',
      autoclose: true,
      todayHighlight: true
    });

    // simple validation
    if ($.validator) {
      $.validator.addMethod('usDate', function(v, el) {
        return this.optional(el) || !!parseUsDate(v);
      }, 'Use MM/DD/YYYY.');
      $.validator.addMethod('endAfterStart', function(v, el) {
        const s = parseUsDate($('input[name="start_date"]').val());
        const e = parseUsDate(v);
        if (!s || !e) return true;
        return e >= s;
      }, 'End date must be on or after Start date.');

      $('#rangeForm').validate({
        rules: {
          @if(!empty($isAdminOrManager) && $isAdminOrManager)
          member_id: {
            required: true
          },
          @endif
          start_date: {
            required: true,
            usDate: true
          },
          end_date: {
            required: true,
            usDate: true,
            endAfterStart: true
          }
        },
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        highlight: function(el) {
          $(el).addClass('is-invalid');
        },
        unhighlight: function(el) {
          $(el).removeClass('is-invalid');
        },
        errorPlacement: function(error, element) {
          if (element.parent('.input-group').length) error.insertAfter(element.parent());
          else error.insertAfter(element);
        }
      });
    }
  });
</script>
@endsection