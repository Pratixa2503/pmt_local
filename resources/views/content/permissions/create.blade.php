@extends('layouts/layoutMaster')

@section('title', $title)

@section('vendor-style')
@endsection

@section('vendor-script')
@endsection

@section('content')
    {{-- <x-breadcrumb :items="['home' => 'Roles and Permissions', 'icon' => 'menu-icon tf-icons ti ti-user-check', 'name' => 'Permissions', 'subname' => 'Add Permission']" /> --}}
    <!-- Basic Layout & Basic with Icons -->
    <div class="loader d-none" id="loader">
        <div class="loader-img"><img src="{{ asset('assets/img/branding/loader.svg') }}" alt="loader" /></div>
    </div>
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 text-dark bold">{{ __('Add Permission') }}</h4>
                </div>
                <div class="card-body">
                    <form id="createForm" method="POST" action="{{ route('permissions.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 custom-validation col-md-12 col-lg-12">
                                <label for="name" class="form-label">Permission Name  <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Enter Permission Name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name" name="name" value="{{ old('name') }}">
                                @error('name')
                                    <span class="invalid-feedback error-name d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="justify-content-between text-end">
                                <a href="{{ route('permissions.index') }}" class="btn btn-info mx-1"><i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>{{ __('Back') }}</a>
                                <button type="submit" class="btn btn-primary submit">{{ __('Save') }}<i class="ti ti-file-upload ms-1 mb-1"></i></button>
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
            }, "Permission Name can contain only letters and spaces");

            $('#name').on('keyup change', function() {
                $(this).valid();
                var data = $('.error-name').next().prevObject.remove();
            });

            $("#createForm").validate({
                // Specify validation rules
                rules: {
                    name: {
                        required: true,
                        maxlength: 100,
                        regex: /^[a-zA-Z1-9\s]+$/,
                        normalizer: function(value) {
                            // Trim the value before validating
                            return $.trim(value);
                        }
                    },
                },
                // Specify validation error messages
                messages: {
                    name: {
                        required: "Please Enter Permission Name",
                        maxlength: "Permission Name cannot exceed 100 characters",
                        regex: "Permission Name can contain only letters and spaces",
                    },
                },
                errorElement: 'span', // Wrap errors in 'span' element
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                }
            });
        });
    </script>
@endsection
