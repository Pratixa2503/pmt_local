@extends('layouts/layoutMaster')

@section('title', $title)

@section('content')
    <div class="loader d-none" id="loader">
        <div class="loader-img"><img src="{{ asset('assets/img/branding/loader.svg') }}" alt="loader" /></div>
    </div>

    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 text-dark bold">{{ $title }}</h4>
                </div>
                <div class="card-body">
                    <form id="createForm" method="POST" action="{{ isset($department) ? route('departments.update', Crypt::encryptString($department->id)) : route('departments.store') }}">
                        @csrf
                        @if(isset($department))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label for="name" class="form-label">Department Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $department->name ?? '') }}" placeholder="Enter Department Name" autofocus>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3 col-md-4">
                                <label for="industry_verticals_id" class="form-label">Industry Vertical <span class="text-danger">*</span></label>
                                @php
                                $selectedIv = old('industry_verticals_id', $department->industry_verticals_id ?? '');
                                @endphp
                                <select name="industry_verticals_id"
                                        id="industry_verticals_id"
                                        class="form-select @error('industry_verticals_id') is-invalid @enderror"
                                        required>
                                <option value="">Select Industry Vertical</option>
                                @foreach($industry_verticals as $iv)
                                    <option value="{{ $iv->id }}" {{ (string)$selectedIv === (string)$iv->id ? 'selected' : '' }}>
                                    {{ $iv->name }}
                                    </option>
                                @endforeach
                                </select>
                                @error('industry_verticals_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3 col-md-4">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="">Select Status</option>
                                    <option value="1" {{ old('status', $department->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $department->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="justify-content-between text-end">
                                <a href="{{ route('departments.index') }}" class="btn btn-info mx-1">
                                    <i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>Back
                                </a>
                                <button type="submit" class="btn btn-primary submit">
                                    {{ isset($department) ? 'Update' : 'Submit' }} <i class="ti ti-file-upload mb-1 ms-1"></i>
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
        $(function() {
            $("#createForm").validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 100
                    },
                    status: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Please enter department name",
                        maxlength: "Department name must not exceed 100 characters"
                    },
                    status: {
                        required: "Please select status"
                    }
                },
                errorElement: 'span',
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection
