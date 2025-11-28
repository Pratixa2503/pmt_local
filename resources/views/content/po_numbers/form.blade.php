@extends('layouts/layoutMaster')

@section('title', $title ?? ($edit ? 'Edit PO' : 'Add PO'))

@section('content')
@php
  $edit = $edit ?? false;
@endphp

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">{{ $edit ? 'Edit PO Number' : 'Add PO Number' }}</h5>
    <a href="{{ route('po-numbers.index') }}" class="btn btn-outline-secondary">
      <i class="ti ti-chevron-left me-1"></i> Back
    </a>
  </div>

  <div class="card-body">
    <form method="POST" action="{{ $edit ? route('po-numbers.update', $encryptedId) : route('po-numbers.store') }}">
      @csrf
      @if($edit) @method('PUT') @endif

      <div class="row g-3">

        {{-- Customer --}}
        <div class="col-md-6">
          <label class="form-label">Customer <span class="text-danger">*</span></label>
          <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
            <option value="">Select customer</option>
            @foreach($customers as $c)
              <option value="{{ $c->id }}" {{ (int)old('customer_id', $row->customer_id ?? 0) === (int)$c->id ? 'selected' : '' }}>
                {{ $c->name }}
              </option>
            @endforeach
          </select>
          @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Project --}}
        <div class="col-md-6">
          <label class="form-label">Project <span class="text-danger">*</span></label>
          <select name="project_id" id="projectSelect" class="form-select @error('project_id') is-invalid @enderror" required>
            <option value="">Select project</option>
            @foreach($projects as $p)
              <option value="{{ $p->id }}" {{ (int)old('project_id', $row->project_id ?? 0) === (int)$p->id ? 'selected' : '' }}>
                {{ $p->project_name }}
              </option>
            @endforeach
          </select>
          @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Sub Project (optional) --}}
        <div class="col-md-6">
          <label class="form-label">Sub Project</label>
          <select name="sub_project_id" id="subProjectSelect" class="form-select @error('sub_project_id') is-invalid @enderror">
            <option value="">Select sub project (optional)</option>
            @foreach($subProjects as $sp)
              <option
                value="{{ $sp->id }}"
                data-parent="{{ $sp->parent_id }}"
                {{ (int)old('sub_project_id', $row->sub_project_id ?? 0) === (int)$sp->id ? 'selected' : '' }}
              >
                {{ $sp->project_name }}
              </option>
            @endforeach
          </select>
          <div class="form-text">Shown list is all child projects; you can filter it by project via the script below.</div>
          @error('sub_project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Start / End --}}
        <div class="col-md-3">
          <label class="form-label">Start Date</label>
          <input type="date" name="start_date" value="{{ old('start_date', optional($row->start_date ?? null)->format('Y-m-d')) }}" class="form-control @error('start_date') is-invalid @enderror">
          @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
          <label class="form-label">End Date</label>
          <input type="date" name="end_date" value="{{ old('end_date', optional($row->end_date ?? null)->format('Y-m-d')) }}" class="form-control @error('end_date') is-invalid @enderror">
          @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- PO Number --}}
        <div class="col-md-6">
          <label class="form-label">PO Number <span class="text-danger">*</span></label>
          <input type="text" name="po_number" value="{{ old('po_number', $row->po_number ?? '') }}" class="form-control @error('po_number') is-invalid @enderror" placeholder="Enter PO Number" required>
          @error('po_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Status --}}
        <div class="col-md-3">
          <label class="form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="1" {{ old('status', $row->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', $row->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
          </select>
          @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
       <div class="col-md-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>

        @php
          // Default to 1 (Active) when creating; keep old()/model value on edit/validation error
          $statusVal = old('status', isset($row) ? $row->status : 1);
        @endphp

        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
          <option value="1" {{ (string)$statusVal === '1' ? 'selected' : '' }}>Active</option>
          <option value="0" {{ (string)$statusVal === '0' ? 'selected' : '' }}>Inactive</option>
        </select>

        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      </div>

      <div class="text-end mt-3">
        <button class="btn btn-primary">{{ $edit ? 'Update' : 'Save' }}</button>
      </div>
    </form>
  </div>
</div>

{{-- Optional filter: show only sub-projects belonging to selected project --}}
<script>
document.addEventListener('DOMContentLoaded', function(){
  const project = document.getElementById('projectSelect');
  const subSel  = document.getElementById('subProjectSelect');
  const original = Array.from(subSel.options);

  function filterSubs() {
    const pid = project.value;
    subSel.innerHTML = '';
    subSel.appendChild(new Option('Select sub project (optional)', ''));
    original.forEach(opt => {
      const parent = opt.getAttribute('data-parent');
      if (!opt.value || (parent && parent === pid)) {
        subSel.appendChild(opt.cloneNode(true));
      }
    });
  }
  if (project && subSel) {
    project.addEventListener('change', filterSubs);
    filterSubs(); // initial
  }
});

document.addEventListener('DOMContentLoaded', function () {
  const projectSel = document.getElementById('projectSelect');
  const subSel     = document.getElementById('subProjectSelect');

  async function loadSubs(pid, selectedId = '{{ old('sub_project_id', $row->sub_project_id ?? '') }}') {
    // Reset dropdown
    subSel.innerHTML = '';
    subSel.appendChild(new Option('Select sub project (optional)', ''));

    if (!pid) return;

    try {
      const url = "{{ route('projects.sub-projects', ':id') }}".replace(':id', pid);
      const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
      const list = await res.json();

      if (!Array.isArray(list) || list.length === 0) {
        // No sub projects; keep only the placeholder
        return;
      }

      list.forEach(sp => {
        const opt = new Option(sp.name, sp.id);
        if (String(selectedId) === String(sp.id)) opt.selected = true;
        subSel.appendChild(opt);
      });
    } catch (e) {
      console.error('Failed to load sub-projects', e);
    }
  }

  if (projectSel && subSel) {
    // On change -> load subs
    projectSel.addEventListener('change', () => loadSubs(projectSel.value, ''));
    // Initial load (for edit)
    if (projectSel.value) loadSubs(projectSel.value);
  }
});
</script>


@endsection
