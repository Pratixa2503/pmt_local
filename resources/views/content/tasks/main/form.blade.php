@extends('layouts/layoutMaster')

@section('title', $edit ? 'Edit Main Task' : 'Add Main Task')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0 text-dark">{{ $edit ? 'Edit Main Task' : 'Add Main Task' }}</h5>
        <a href="{{ route('maintasks.index') }}" class="btn btn-sm btn-secondary">
            <i class="ti ti-chevron-left me-1"></i> Back
        </a>
    </div>

    <div class="card-body">
        <form id="mainTaskForm" method="POST"
              action="{{ $edit ? route('maintasks.update', Crypt::encryptString($mainTask->id)) : route('maintasks.store') }}">
            @csrf
            @if($edit)
                @method('PUT')
            @endif

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="name" class="form-label">Main Task Name <span class="text-danger">*</span></label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name', $mainTask->name ?? '') }}"
                           placeholder="Enter main task name"
                           required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 col-md-6">
                    <label for="task_type" class="form-label">Task Type <span class="text-danger">*</span></label>
                    <select id="task_type"
                            name="task_type"
                            class="form-control form-select {{ $errors->has('task_type') ? 'is-invalid' : '' }}"
                            required>
                        <option value="">Select Task Type</option>
                        <option value="1" {{ old('task_type', $mainTask->task_type ?? '') == 1 ? 'selected' : '' }}>Productive</option>
                        <option value="2" {{ old('task_type', $mainTask->task_type ?? '') == 2 ? 'selected' : '' }}>General</option>
                    </select>
                    @error('task_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select id="status"
                            name="status"
                            class="form-control form-select {{ $errors->has('status') ? 'is-invalid' : '' }}"
                            required>
                        <option value="">Select Status</option>
                        <option value="1" {{ old('status', $mainTask->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $mainTask->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">
                    {{ $edit ? 'Update Task' : 'Save Task' }}
                    <i class="ti ti-file-upload ms-1"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
