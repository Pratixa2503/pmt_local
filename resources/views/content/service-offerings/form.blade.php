@extends('layouts/layoutMaster')
@section('title', $title)

@section('content')
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="text-dark">{{ $title }}</h4>
            </div>
            <div class="card-body">
                <form id="formatForm" method="POST" action="{{ 
                    $type == 'create' 
                        ? route('service-offerings.store') 
                        : route('service-offerings.update', Crypt::encryptString($data->id)) 
                }}">
                    @csrf
                    @if($type == 'edit') @method('PUT') @endif

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="name" class="form-label">Service Offering <span class="text-danger">*</span></label>
                            <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   id="name" name="name" value="{{ old('name', $data->name ?? '') }}" placeholder="Enter Service Offering" autofocus>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                            @php
                                $selectedDept = old('department_id', $data->department_id ?? '');
                            @endphp
                            <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                <option value="">Select Department</option>
                                @foreach(($department ?? collect()) as $dept)
                                    <option value="{{ $dept->id }}" {{ (string)$selectedDept === (string)$dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @php
                            $statusVal = (string) old('status', $data->status ?? 1);
                        @endphp

                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select {{ $errors->has('status') ? 'is-invalid' : '' }}" required>
                                <option value="">Select</option>
                                <option value="1" {{ $statusVal === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $statusVal === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- NEW: Department --}}
                    <div class="row">
                        
                    </div>

                    <div class="text-end">
                        <a href="{{ route('service-offerings.index') }}" class="btn btn-secondary">
                            <i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>{{ __('Back') }}
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
