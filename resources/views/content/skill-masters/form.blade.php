@extends('layouts/layoutMaster')
@section('title', $title)

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
@endsection

@section('extra-script')
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: 'Select an option',
            allowClear: true
        });

        $('#skillMasterForm').validate({
            rules: {
                name: { required: true },
                skill_expertise_level: { required: true },
                ctc: { required: true, number: true, min: 0 },
                status: { required: true },
            },
            messages: {
                name: "Please enter Skill Name",
                skill_expertise_level: "Please enter Expertise Level",
                ctc: {
                    required: "Please enter CTC",
                    number: "Must be a number",
                    min: "Cannot be negative"
                },
                status: {
                    required: "Please select Status",
                }
            },
            errorElement: 'div',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="text-dark">{{ $title }}</h4>
            </div>
            <div class="card-body">
                <form id="skillMasterForm" method="POST" action="{{ 
                    $type == 'create' 
                        ? route('skill-masters.store') 
                        : route('skill-masters.update', Crypt::encryptString($data->id)) 
                }}">
                    @csrf
                    @if($type == 'edit') @method('PUT') @endif

                    <div class="row">
                        <div class="col-md-6 mb-3 form-group">
                            <label for="name" class="form-label">Skill Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Enter skill name" value="{{ old('name', $data->name ?? '') }}">
                        </div>

                        <div class="col-md-6 mb-3 form-group">
                            <label for="skill_expertise_level" class="form-label">Expertise Level <span class="text-danger">*</span></label>
                            <input type="text" name="skill_expertise_level" class="form-control" placeholder="e.g. Beginner, Intermediate, Expert" value="{{ old('skill_expertise_level', $data->skill_expertise_level ?? '') }}">
                        </div>

                        <div class="col-md-6 mb-3 form-group">
                            <label for="ctc" class="form-label">CTC <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="ctc" class="form-control" placeholder="Enter CTC" value="{{ old('ctc', $data->ctc ?? '') }}">
                        </div>

                        @php
                       
                        $statusVal = (string) old('status', $data->status ?? 1);
                        @endphp

                        <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select {{ $errors->has('status') ? 'is-invalid' : '' }}" required>
                            <option value="">Select</option>
                            <option value="1" {{ $statusVal === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $statusVal === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                    </div>

                    <div class="text-end">
                        <a href="{{ route('skill-masters.index') }}" class="btn btn-secondary">
                            <i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            {{ $type == 'create' ? 'Save' : 'Update' }}
                            <i class="ti ti-file-upload ms-1 mb-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
