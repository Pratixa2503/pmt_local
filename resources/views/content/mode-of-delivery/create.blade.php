@extends('layouts/layoutMaster')

@section('title', 'Add Mode of Delivery')

@section('content')
<div class="loader d-none" id="loader">
    <div class="loader-img"><img src="{{ asset('assets/img/branding/loader.svg') }}" alt="loader" /></div>
</div>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="mb-0 text-dark bold">{{ __('Add Mode of Delivery') }}</h4>
            </div>
            <div class="card-body">
                <form id="createForm" method="POST" action="{{ route('mode-of-delivery.store') }}">
                    @csrf
                    <div class="row">
                        <div class="mb-3 custom-validation col-md-6">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" placeholder="Enter Mode of Delivery" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" autofocus>
                            @error('name')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 custom-validation col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>

                            @php
                                // Default to Active (1) when creating; keep old()/model value on edit/validation error
                                $statusVal = old('status', $data->status ?? 1);
                            @endphp

                            <select name="status" id="status" class="form-control form-select @error('status') is-invalid @enderror">
                                <option value="">Select Status</option>
                                <option value="1" {{ (string)$statusVal === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (string)$statusVal === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>

                            @error('status')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            </div>

                    </div>

                    <div class="text-end">
                        <a href="{{ route('mode-of-delivery.index') }}" class="btn btn-info mx-1"><i class="ti ti-chevron-left me-1"></i>Back</a>
                        <button type="submit" class="btn btn-primary submit">Submit<i class="ti ti-file-upload ms-1"></i></button>
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
    $(document).ready(function () {
        $("#createForm").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 100,
                    regex: /^[a-zA-Z0-9\s]+$/,
                    normalizer: function (value) {
                        return $.trim(value);
                    }
                },
                status: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Please enter Mode of Delivery",
                    maxlength: "Mode of Delivery can't exceed 100 characters",
                    regex: "Only letters, numbers, and spaces allowed"
                },
                status: {
                    required: "Please select status"
                }
            },
            errorElement: 'span',
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            }
        });
    });
</script>
@endsection
