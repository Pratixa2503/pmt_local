@extends('layouts/layoutMaster')

@section('title', $title)

@section('vendor-style')
@endsection

@section('vendor-script')
@endsection

@section('content')
<div class="loader d-none" id="loader">
    <div class="loader-img">
        <img src="{{ asset('assets/img/branding/loader.svg') }}" alt="loader" />
    </div>
</div>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="mb-0 text-dark bold">{{ __('Edit User') }}</h4>
            </div>
            <div class="card-body">
                <form id="createForm" method="POST" action="{{ route('users.update', Crypt::encryptString($user->id)) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') 
                    <div class="row">
                        @php
                        $fields = [
                        'first_name' => 'First Name',
                        'last_name' => 'Last Name',
                        'email' => 'Email',
                        'contact_no' => 'Contact No',
                       
                        ];

                        $requiredFields = ['first_name','last_name','email', 'contact_no']; // Add required fields here
                        $focusField = 'first_name'; // Set the field to focus
                        @endphp
                        @foreach ($fields as $field => $label)
                        <div class="mb-3 custom-validation col-md-6 col-lg-6">
                            <label for="{{ $field }}" class="form-label">
                                {{ $label }}
                                @if(in_array($field, $requiredFields))
                                <span class="text-danger">*</span>
                                @endif
                            </label>
                            <input type="{{ $field === 'email' ? 'email' : 'text' }}"
                                placeholder="Enter {{ $label }}"
                                class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}"
                                id="{{ $field }}"
                                name="{{ $field }}"
                                value="{{ old($field, $user->$field) }}"
                                @if(in_array($field, $requiredFields)) required @endif
                                @if($field===$focusField) autofocus @endif>
                            @error($field)
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        @endforeach
                        <div class="mb-3 custom-validation col-md-6 col-lg-6">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role[]" id="role" class="form-control form-select {{ $errors->has('role') ? 'is-invalid' : '' }}" multiple>
                                @foreach ($roles as $role)
                                <option value="{{ $role->name }}"
                                    {{ in_array($role->name, old('role', $user->roles->pluck('name')->toArray() ?? [])) ? 'selected' : '' }}>
                                    {{ ucwords($role->name) }}
                                </option>
                                @endforeach
                            </select>
                            @error('role')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                       @php
  // If you're using spatie/laravel-permission:
  $userRoleNames = isset($user) && method_exists($user, 'roles')
      ? $user->roles->pluck('name')->map(fn($n)=>strtolower(trim($n)))->toArray()
      : [];

  // Use old('role') first so failed validation preserves the selection
  $hasPMRole = collect(old('role', $userRoleNames))
      ->contains(fn($r) => strtolower(trim($r)) === 'project manager');
@endphp

{{-- ... your Roles multi-select should exist elsewhere with id="role" ... --}}

<div id="pm-field"
     class="mb-3 col-md-6 col-lg-6 {{ $hasPMRole ? 'd-none' : '' }}">
  <label for="project_manager" class="form-label">Project Manager</label>

  <select
    name="project_manager"
    id="project_manager"
    class="form-control form-select @error('project_manager') is-invalid @enderror"
    {{ $hasPMRole ? 'disabled' : '' }}
  >
    <option value=""
      @selected(old('project_manager', $user->project_manager) == '')>
      -- Select Project Manager --
    </option>

    @foreach ($pm_users as $pm_u)
      <option value="{{ $pm_u->id }}"
        @selected(old('project_manager', $user->project_manager) == $pm_u->id)>
        {{ ucwords($pm_u->first_name . ' ' . $pm_u->last_name) }}
      </option>
    @endforeach
  </select>

  @error('project_manager')
    <span class="invalid-feedback d-block" role="alert">
      <strong>{{ $message }}</strong>
    </span>
  @enderror
</div>

<div class="mb-3 custom-validation col-md-6 col-lg-6">
  <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
  <select name="status" id="status"
          class="form-control form-select {{ $errors->has('status') ? 'is-invalid' : '' }}">
    <option value="">Select Status</option>
    <option value="1" {{ old('status', $user->status) == '1' ? 'selected' : '' }}>Active</option>
    <option value="0" {{ old('status', $user->status) == '0' ? 'selected' : '' }}>Deactive</option>
  </select>
  @error('status')
    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
  @enderror
</div>


                    <div class="row">
                        <div class="text-end">
                            <a href="{{ route('users.index') }}" class="btn btn-info mx-1">
                                <i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>{{ __('Back') }}
                            </a>
                            <button type="submit" class="btn btn-primary submit">
                                {{ __('Save') }}<i class="ti ti-file-upload ms-1 mb-1"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
@endsection

@section('extra-script')
<script>
    $(document).ready(function() {
        $.validator.addMethod("regex", function(value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        }, "Only letters, numbers, and spaces are allowed.");

        $('#status').select2();

        $('#role').select2({
            placeholder: "Select a role"
        });

        $("#createForm").validate({
            rules: {
                first_name: {
                    required: true,
                    maxlength: 100,
                    regex: /^[a-zA-Z0-9\s]+$/,
                    normalizer: function(value) {
                        return $.trim(value);
                    }
                },
                last_name: {
                    required: true,
                    maxlength: 100,
                    regex: /^[a-zA-Z0-9\s]+$/,
                    normalizer: function(value) {
                        return $.trim(value);
                    }
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 255,
                    normalizer: function(value) {
                        return $.trim(value);
                    }
                },
                contact_no: {
                    required: true,
                    maxlength: 20,
                    regex: /^[0-9+\-\s]+$/,
                    normalizer: function(value) {
                        return $.trim(value);
                    }
                },
                // company_name: {
                //     required: true,
                //     maxlength: 100,
                //     regex: /^[a-zA-Z0-9\s]*$/,
                //     normalizer: function(value) {
                //         return $.trim(value);
                //     }
                // },
                status: {
                    required: true
                },
                "role[]":{
                    required: true
                },
            },
            messages: {
                first_name: {
                    required: "First Name is required",
                    maxlength: "Max 100 characters",
                    regex: "Only letters and numbers allowed"
                },
                last_name: {
                    required: "Last Name is required",
                    maxlength: "Max 100 characters",
                    regex: "Only letters and numbers allowed"
                },
                email: {
                    required: "Email is required",
                    email: "Enter a valid email",
                    maxlength: "Max 255 characters"
                },
                contact_no: {
                    required: "Contact No is required",
                    maxlength: "Max 20 digits",
                    regex: "Enter a valid Contact No"
                },
                company_name: {
                    required: "Company Name is required",
                    maxlength: "Max 100 characters",
                    regex: "Only letters and numbers allowed"
                },
                "role[]":{
                    required: "Please select a Role",
                },
                status: {
                    required: "Please select a Status"
                }
            },
            errorElement: 'span',
            errorClass: 'invalid-feedback d-block',
            highlight: function (element) {
            $(element).addClass('is-invalid');
                if ($(element).hasClass("select2-hidden-accessible")) {
                    $(element).next('.select2-container').addClass('is-invalid');
                }
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
                if ($(element).hasClass("select2-hidden-accessible")) {
                    $(element).next('.select2-container').removeClass('is-invalid');
                }
            },
            errorPlacement: function (error, element) {
                if (element.hasClass("select2-hidden-accessible")) {
                    // Place error after select2 container
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });
    (function () {
  function hasPMRole() {
    const sel = document.getElementById('role'); // your Roles multi-select
    if (!sel) return false;
    return Array.from(sel.selectedOptions || [])
      .some(o => (o.value || o.text || '').toLowerCase().trim() === 'project manager');
  }

  function togglePM() {
    const wrap = document.getElementById('pm-field');
    const pm   = document.getElementById('project_manager');
    if (!wrap || !pm) return;
    if (hasPMRole()) {
      wrap.classList.add('d-none');
      pm.disabled = true;
      pm.value = '';
    } else {
      wrap.classList.remove('d-none');
      pm.disabled = false;
    }
  }

  document.addEventListener('change', function (e) {
    if (e.target && e.target.id === 'role') togglePM();
  });

  if (window.jQuery) {
    jQuery(function ($) {
      if ($('#role').hasClass('select2-hidden-accessible')) {
        $('#role').on('change.select2', togglePM);
      }
      togglePM(); // initial
    });
  } else {
    document.addEventListener('DOMContentLoaded', togglePM);
    togglePM();
  }
})();
</script>
@endsection