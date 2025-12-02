@extends('layouts/layoutMaster')

@section('title', $title ?? ($edit ? 'Edit PO' : 'Add PO'))

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('content')
@php
  $edit = $edit ?? false;
  
  // Helper function to format date as MM-DD-YYYY
  if (!function_exists('fmtMDY')) {
    function fmtMDY($v) {
      if (empty($v)) return '';
      try { 
        if ($v instanceof \Carbon\Carbon || $v instanceof \DateTime) {
          return $v->format('m-d-Y');
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $v)) {
          return \Carbon\Carbon::parse($v)->format('m-d-Y');
        }
        return $v;
      } catch (\Exception $e) { 
        return $v; 
      }
    }
  }
@endphp

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">{{ $edit ? 'Edit PO Number' : 'Add PO Number' }}</h5>
    <a href="{{ route('po-numbers.index') }}" class="btn btn-outline-secondary">
      <i class="ti ti-chevron-left me-1"></i> Back
    </a>
  </div>

  <div class="card-body">
    <form method="POST" action="{{ $edit ? route('po-numbers.update', $encryptedId) : route('po-numbers.store') }}" id="poNumberForm">
      @csrf
      @if($edit) @method('PUT') @endif

      <div class="row g-3">

        {{-- Customer --}}
        <div class="col-md-6">
          <label class="form-label">Customer <span class="text-danger">*</span></label>
          <select name="customer_id" id="customerSelect" class="form-select @error('customer_id') is-invalid @enderror" required>
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
              <option value="{{ $p->id }}" data-customer-id="{{ $p->customer_id ?? '' }}" {{ (int)old('project_id', $row->project_id ?? 0) === (int)$p->id ? 'selected' : '' }}>
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
          <label class="form-label">Start Date <span class="text-danger">*</span></label>
          <input type="text" name="start_date" id="startDate" value="{{ old('start_date', fmtMDY($row->start_date ?? null)) }}" class="form-control js-date ymd @error('start_date') is-invalid @enderror" placeholder="MM-DD-YYYY" autocomplete="off" required>
          @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
          <label class="form-label">End Date <span class="text-danger">*</span></label>
          <input type="text" name="end_date" id="endDate" value="{{ old('end_date', fmtMDY($row->end_date ?? null)) }}" class="form-control js-date ymd @error('end_date') is-invalid @enderror" placeholder="MM-DD-YYYY" autocomplete="off" required>
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

{{-- Load projects by customer and filter sub-projects by project --}}
<script>
document.addEventListener('DOMContentLoaded', function(){
  const customerSel = document.getElementById('customerSelect');
  const projectSel  = document.getElementById('projectSelect');
  const subSel      = document.getElementById('subProjectSelect');
  const startDateInput = document.getElementById('startDate');
  const endDateInput = document.getElementById('endDate');
  const originalSubProjects = Array.from(subSel.options);
  const originalProjects = Array.from(projectSel.options);
  const selectedProjectId = '{{ old('project_id', $row->project_id ?? '') }}';
  const selectedCustomerId = '{{ old('customer_id', $row->customer_id ?? '') }}';

  // Helper functions for MM-DD-YYYY format
  function parseMDY(str) {
    if (!str) return null;
    var s = (str || '').trim();
    var m = /^(\d{2})-(\d{2})-(\d{4})$/.exec(s);
    if (!m) return null;
    var d = new Date(+m[3], +m[1]-1, +m[2]);
    if (!d || isNaN(d.getTime())) return null;
    if (d.getFullYear() !== +m[3] || d.getMonth() !== (+m[1]-1) || d.getDate() !== +m[2]) return null;
    return d;
  }

  function toYMD(mdyStr) {
    var d = parseMDY(mdyStr);
    if (!d) return '';
    var y = d.getFullYear();
    var m = String(d.getMonth() + 1).padStart(2, '0');
    var day = String(d.getDate()).padStart(2, '0');
    return y + '-' + m + '-' + day;
  }

  // Initialize datepicker for date fields (wait for jQuery)
  function initDatepickers() {
    if (typeof jQuery === 'undefined' || !jQuery.fn.datepicker) {
      // Retry after a short delay if jQuery isn't loaded yet
      setTimeout(initDatepickers, 100);
      return;
    }
    
    var $ = jQuery;
    const TODAY = new Date();
    
    // Start date: can be past dates
    if (startDateInput) {
      $(startDateInput).datepicker({
        format: 'mm-dd-yyyy',
        autoclose: true,
        todayHighlight: true,
        enableOnReadonly: false
      }).on('changeDate', function() {
        $(this).trigger('change');
        validateDates();
      });
    }

    // End date: must be >= start date (allow past dates for editing)
    if (endDateInput) {
      // Set initial startDate based on start_date value
      var initialStartDate = null;
      if (startDateInput && startDateInput.value) {
        initialStartDate = parseMDY(startDateInput.value);
      }
      
      $(endDateInput).datepicker({
        format: 'mm-dd-yyyy',
        autoclose: true,
        todayHighlight: true,
        startDate: initialStartDate || null, // Set to start date if available, otherwise no restriction
        enableOnReadonly: false
      }).on('changeDate', function() {
        $(this).trigger('change');
        validateDates();
      });
    }

    // Update end_date min when start_date changes
    if (startDateInput && endDateInput) {
      $(startDateInput).on('changeDate', function() {
        var startVal = $(this).val();
        if (startVal) {
          var startDateObj = parseMDY(startVal);
          
          // Set end_date min to start_date (allow past dates for editing)
          try {
            $(endDateInput).datepicker('setStartDate', startDateObj);
          } catch(e) {}
          
          // If end_date is before start_date, clear it
          var endVal = $(endDateInput).val();
          if (endVal) {
            var endDateObj = parseMDY(endVal);
            if (endDateObj && endDateObj < startDateObj) {
              $(endDateInput).val('');
            }
          }
        } else {
          // If start date is cleared, remove restriction on end date
          try {
            $(endDateInput).datepicker('setStartDate', null);
          } catch(e) {}
        }
        validateDates();
      });
      
      // Set initial restriction if start date is already set
      if (startDateInput.value) {
        var startVal = startDateInput.value;
        var startDateObj = parseMDY(startVal);
        if (startDateObj) {
          try {
            $(endDateInput).datepicker('setStartDate', startDateObj);
          } catch(e) {}
        }
      }
    }
  }
  
  // Initialize datepickers
  initDatepickers();

  // Date validation: end_date must be greater than start_date
  function validateDates() {
    if (!startDateInput || !endDateInput) return true;
    
    const startDate = startDateInput.value;
    const endDate = endDateInput.value;

    if (startDate && endDate) {
      var start = parseMDY(startDate);
      var end = parseMDY(endDate);
      
      if (start && end && end <= start) {
        endDateInput.setCustomValidity('End date must be greater than start date');
        endDateInput.classList.add('is-invalid');
        return false;
      } else {
        endDateInput.setCustomValidity('');
        endDateInput.classList.remove('is-invalid');
      }
    }
    return true;
  }

  // Validate on input change
  if (startDateInput && endDateInput) {
    startDateInput.addEventListener('change', validateDates);
    endDateInput.addEventListener('change', validateDates);
    
    // Initial validation
    validateDates();
  }

  // Load projects by customer
  async function loadProjectsByCustomer(customerId, selectedProjectId = '') {
    projectSel.innerHTML = '';
    projectSel.appendChild(new Option('Select project', ''));
    subSel.innerHTML = '';
    subSel.appendChild(new Option('Select sub project (optional)', ''));

    if (!customerId) {
      // If no customer selected, show all original projects
      originalProjects.forEach(opt => {
        if (opt.value) {
          projectSel.appendChild(opt.cloneNode(true));
        }
      });
      return Promise.resolve();
    }

    try {
      const url = "{{ route('po-numbers.projects-by-customer', ':id') }}".replace(':id', customerId);
      const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
      const projects = await res.json();

      if (Array.isArray(projects) && projects.length > 0) {
        projects.forEach(p => {
          const opt = new Option(p.project_name, p.id);
          if (String(selectedProjectId) === String(p.id)) {
            opt.selected = true;
          }
          projectSel.appendChild(opt);
        });
      }
      return Promise.resolve();
    } catch (e) {
      console.error('Failed to load projects', e);
      return Promise.reject(e);
    }
  }

  // Load sub-projects via AJAX when project is selected
  async function loadSubProjects(pid, selectedId = '') {
    subSel.innerHTML = '';
    subSel.appendChild(new Option('Select sub project (optional)', ''));

    if (!pid) return;

    try {
      const url = "{{ route('projects.sub-projects', ':id') }}".replace(':id', pid);
      const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
      const list = await res.json();

      if (Array.isArray(list) && list.length > 0) {
        list.forEach(sp => {
          const opt = new Option(sp.name, sp.id);
          if (String(selectedId) === String(sp.id)) opt.selected = true;
          subSel.appendChild(opt);
        });
      }
    } catch (e) {
      console.error('Failed to load sub-projects', e);
    }
  }

  // Customer change handler
  if (customerSel && projectSel) {
    customerSel.addEventListener('change', function() {
      loadProjectsByCustomer(this.value, '');
      // Reset sub projects when customer changes
      subSel.innerHTML = '';
      subSel.appendChild(new Option('Select sub project (optional)', ''));
    });

    // Initial load: if customer is pre-selected, load its projects
    if (selectedCustomerId) {
      loadProjectsByCustomer(selectedCustomerId, selectedProjectId).then(() => {
        // After projects are loaded, load sub projects if project is selected
        if (selectedProjectId) {
          const selectedSubProjectId = '{{ old('sub_project_id', $row->sub_project_id ?? '') }}';
          loadSubProjects(selectedProjectId, selectedSubProjectId);
        }
      });
    }
  }

  // Project change handler for sub-projects
  if (projectSel && subSel) {
    projectSel.addEventListener('change', function() {
      loadSubProjects(this.value, '');
    });
    
    // Initial load if project is pre-selected (even if no sub project selected)
    const selectedSubProjectId = '{{ old('sub_project_id', $row->sub_project_id ?? '') }}';
    if (selectedProjectId && !selectedCustomerId) {
      // If no customer is selected but project is, load sub projects directly
      loadSubProjects(selectedProjectId, selectedSubProjectId);
    } else if (selectedProjectId && selectedCustomerId) {
      // If both customer and project are selected, wait a bit for projects to load, then load sub projects
      setTimeout(() => {
        if (projectSel.value === selectedProjectId) {
          loadSubProjects(selectedProjectId, selectedSubProjectId);
        }
      }, 500);
    }
  }

  // Form submit validation and date conversion
  const form = document.getElementById('poNumberForm');
  if (form) {
    form.addEventListener('submit', function(e) {
      if (!validateDates()) {
        e.preventDefault();
        e.stopPropagation();
        
        // Show error message
        if (endDateInput && !endDateInput.value) {
          endDateInput.focus();
        } else if (endDateInput) {
          endDateInput.focus();
          // Create or update error message
          let errorDiv = endDateInput.parentElement.querySelector('.date-error-message');
          if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback date-error-message';
            endDateInput.parentElement.appendChild(errorDiv);
          }
          errorDiv.textContent = 'End date must be greater than start date';
          errorDiv.style.display = 'block';
        }
        return false;
      }

      // Convert MM-DD-YYYY to YYYY-MM-DD for server before submission
      var changedDates = [];
      if (startDateInput && startDateInput.value) {
        var ymd = toYMD(startDateInput.value);
        if (ymd) {
          changedDates.push([startDateInput, startDateInput.value, ymd]);
          startDateInput.value = ymd;
        }
      }

      if (endDateInput && endDateInput.value) {
        var ymd = toYMD(endDateInput.value);
        if (ymd) {
          changedDates.push([endDateInput, endDateInput.value, ymd]);
          endDateInput.value = ymd;
        }
      }

      // Restore MM-DD-YYYY format after form submission (in case of validation error)
      setTimeout(function() {
        changedDates.forEach(function(item) {
          item[0].value = item[1]; // Restore original MM-DD-YYYY value
        });
      }, 100);
    });
  }
});

</script>


@endsection
