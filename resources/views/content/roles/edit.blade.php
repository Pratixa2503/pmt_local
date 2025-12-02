@extends('layouts/layoutMaster')

@section('title', $title)

@section('vendor-style')
@endsection

@section('vendor-script')
@endsection

@section('content')
    {{-- <x-breadcrumb :items="['home' => 'Roles and Permissions', 'icon' => 'menu-icon tf-icons ti ti-user-check', 'name' => 'Roles', 'subname' => 'Edit Role']" /> --}}
    <!-- Basic Layout & Basic with Icons -->
    <div class="loader d-none" id="loader">
        <div class="loader-img"><img src="{{ asset('assets/img/branding/loader.svg') }}" alt="loader" /></div>
    </div>
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 text-dark bold">{{ __('Edit Roles') }}</h4>
                </div>
                <div class="card-body">
                    <form id="createForm" method="POST" action="{{ route('roles.update', Crypt::encryptString($role->id)) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="mb-3 custom-validation col-md-6 col-lg-6">
                                <label for="name" class="form-label">Role Name</label>
                                <input type="text" placeholder="Enter Role Name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name" name="name" value="{{ old('name', $role->name) }}" @if($role->name == 'super admin') disabled @endif>
                                <input type="hidden" name="name" value="{{ $role->name }}">
                                @error('name')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 custom-validation col-md-6 col-lg-6 create__section">
                                <label for="permissions" class="form-label">Assign Permissions</label>
                                <select multiple class="form-control" id="permissions" name="permissions[]">
                                    @foreach($permissions as $permission)
                                        <option value="{{ $permission->id }}" @if($role->permissions->contains($permission)) selected
                                        @endif>{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                                <span class="d-block" id="permissions-error">
                                    @error('permissions')
                                        <strong>{{ $message }}</strong>
                                    @enderror
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="justify-content-between text-end">
                                <a href="{{ route('roles.index') }}" class="btn btn-info mx-1"><i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>{{ __('Back') }}</a>
                                <button type="submit" class="btn btn-primary submit">{{ __('Update') }}<i class="ti ti-file-upload mb-1 ms-1"></i></button>
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
            }, "Role Name can contain only small letters and spaces");

            $('#name').on('keyup change', function() {
                $(this).valid();
                var data = $('.error-name').next().prevObject.remove();
            });

            $("#permissions").select2({
                placeholder: "Select Permissions",
                closeOnSelect: false,
                tags: false,
            }).on('change', function() {
                $(this).valid();
            });

            $("#createForm").validate({
                // Specify validation rules
                rules: {
                    name: {
                        required: true,
                        maxlength: 100,
                        regex: /^[a-z1-9\s]+$/, // Allows letters and spaces
                        normalizer: function(value) {
                            // Trim the value before validating
                            return $.trim(value);
                        }
                    },
                    'permissions[]': {
                        required: true,
                    },
                },
                // Specify validation error messages
                messages: {
                    name: {
                        required: "Please Enter Permission Name",
                        maxlength: "Permission Name cannot exceed 100 characters",
                        regex: "Permission Name can contain only small letters and spaces",
                    },
                    'permissions[]': {
                        required: "Please Select Permissions",
                    },
                },
                errorElement: 'span', // Wrap errors in 'span' element
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                    if ($(element).attr('name') === 'permissions[]') {
                        $('#permissions-error').text($(element).validationErrors).show();
                    }
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                    if ($(element).attr('name') === 'permissions[]') {
                        $('#permissions-error').hide();
                    }
                },
                errorPlacement: function(error, element) {
                    if (element.attr("name") == "permissions[]") {
                        error.insertAfter(element.next('.select2'));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        });
    </script>
@endsection
