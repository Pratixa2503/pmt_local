@extends('layouts/layoutMaster')

@section('title', $title)

@section('vendor-style')
@endsection

@section('vendor-script')
@endsection

@section('content')
   
    <div class="loader d-none" id="loader">
        <div class="loader-img"><img src="{{ asset('assets/img/branding/loader.svg') }}" alt="loader" /></div>
    </div>
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 text-dark bold">{{ __('Create Project Type') }}</h4>
                </div>
                <div class="card-body">
                    <form id="createForm" method="POST" action="{{ route('project-types.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="mb-3 custom-validation col-md-6 col-lg-6">
                                <label for="name" class="form-label">Name  <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Enter Project Type" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name" name="name" value="{{ old('name') }}" autofocus>
                                @error('name')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 custom-validation col-md-6 col-lg-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                @php $currentStatus = old('status', isset($model) ? (string)$model->status : '1'); @endphp
                                    <select name="status" id="status" class="form-control form-select {{ $errors->has('status') ? 'is-invalid' : '' }}">
                                        <option value="">Select Status</option>
                                        <option value="1" {{ $currentStatus === '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $currentStatus === '0' ? 'selected' : '' }}>Deactive</option>
                                    </select>
                                @error('status')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="justify-content-between text-end">
                                <a href="{{ route('project-types.index') }}" class="btn btn-info mx-1"><i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>{{ __('Back') }}</a>
                                <button type="submit" class="btn btn-primary submit">{{ __('Save') }}<i class="ti ti-file-upload mb-1 ms-1"></i></button>
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
            }, "Project Type Name can contain only letters and spaces");

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
                        regex: /^[a-zA-Z1-9\s]+$/, // Allows letters and spaces
                        normalizer: function(value) {
                            // Trim the value before validating
                            return $.trim(value);
                        }
                    },
                    status: {
                        required: true,
                    },
                },
                // Specify validation error messages
                messages: {
                    name: {
                        required: "Please Enter Project Type",
                        maxlength: "Project Type cannot exceed 100 characters",
                        regex: "Project Type can contain only letters and spaces",
                    },
                    status:{
                        required: "Please Select Status"
                    }
                        
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
