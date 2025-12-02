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
                        ? route('input-output-formats.store') 
                        : route('input-output-formats.update', Crypt::encryptString($data->id)) 
                }}">
                    @csrf
                    @if($type == 'edit') @method('PUT') @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   id="name" name="name" value="{{ old('name', $data->name ?? '') }}" placeholder="Enter Input/Output Format" autofocus>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>

                            @php
                                // Default to Active (1) on create; keep old()/model value on edit/validation error
                                $statusVal = old('status', isset($data) ? $data->status : 1);
                            @endphp

                            <select name="status" id="status" class="form-select {{ $errors->has('status') ? 'is-invalid' : '' }}">
                                <option value="">Select</option>
                                <option value="1" {{ (string) $statusVal === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (string) $statusVal === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>

                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                    </div>

                    <div class="text-end">
                        <a href="{{ route('input-output-formats.index') }}" class="btn btn-secondary"><i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>{{ __('Back') }}</a>
                        <button type="submit" class="btn btn-primary">{{ $type == 'create' ? 'Save' : 'Update' }}<i class="ti ti-file-upload ms-1 mb-1"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
