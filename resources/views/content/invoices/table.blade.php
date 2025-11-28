{{-- Partial: table of project_intakes rows with multi-select --}}
<div class="d-flex justify-content-between align-items-center mb-2">
  <div class="fw-medium">Project ID: {{ $projectId }} | Month: {{ $month }}</div>
  <div class="text-muted"><span id="selectedCount">0</span> selected</div>
</div>

@if($intakes->isEmpty())
  <div class="alert alert-warning">No intake records found for this project and month.</div>
@else
  <div class="table-responsive">
    <table class="table table-bordered table-sm align-middle">
      <thead>
        <tr>
          <th style="width:40px"><input type="checkbox" id="checkAll"></th>
          <th>ID</th>
          <th>Property</th>
          <th>Tenant</th>
          <th>Delivered</th>
          <th>Billing Month</th>
          <th class="text-end">Cost (USD)</th>
        </tr>
      </thead>
      <tbody>
        @foreach($intakes as $row)
          <tr>
            <td>
              <input type="checkbox" class="row-check" name="intake_ids[]" value="{{ $row->id }}">
            </td>
            <td>{{ $row->id }}</td>
            <td>
              <div class="fw-medium">{{ $row->property_name ?? '-' }}</div>
              <div class="text-muted small">ID: {{ $row->property_id ?? '-' }}</div>
            </td>
            <td>{{ $row->tenant_name ?? '-' }}</td>
            <td>{{ $row->delivered_date ?? '-' }}</td>
            <td>{{ $row->billing_month }}</td>
            <td class="text-end">{{ number_format((float)($row->cost_usd ?? 0), 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="d-flex justify-content-end gap-2 mt-2">
    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnClear">Clear</button>
    {{-- Wire this later to your generate route --}}
    <button type="button" class="btn btn-primary btn-sm" id="btnProceed" disabled>Proceed</button>
  </div>
@endif
