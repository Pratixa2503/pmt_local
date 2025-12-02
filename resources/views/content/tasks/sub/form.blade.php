@extends('layouts/layoutMaster')

@section('title', $edit ? 'Edit Sub Task' : 'Add Sub Task')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0 text-dark">{{ $edit ? 'Edit Sub Task' : 'Add Sub Task' }}</h5>
        <a href="{{ route('subtasks.index') }}" class="btn btn-sm btn-secondary">
            <i class="ti ti-chevron-left me-1"></i> Back
        </a>
    </div>

    <div class="card-body">
        <form id="subTaskForm" method="POST"
              action="{{ $edit ? route('subtasks.update', Crypt::encryptString($subTask->id)) : route('subtasks.store') }}">
            @csrf
            @if($edit)
                @method('PUT')
            @endif

            <div class="row">
                {{-- Main Task --}}
                <div class="mb-3 col-md-6">
                    <label for="main_task_id" class="form-label">Main Task <span class="text-danger">*</span></label>
                    <select id="main_task_id"
                            name="main_task_id"
                            class="form-control form-select {{ $errors->has('main_task_id') ? 'is-invalid' : '' }}"
                            required>
                        <option value="">Select Main Task</option>
                        @foreach($mainTasks as $id => $name)
                            <option value="{{ $id }}"
                                {{ (string) old('main_task_id', $subTask->main_task_id ?? '') === (string) $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('main_task_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Sub Task Name --}}
                <div class="mb-3 col-md-6">
                    <label for="name" class="form-label">Sub Task Name <span class="text-danger">*</span></label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name', $subTask->name ?? '') }}"
                           placeholder="Enter sub task name"
                           required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Task Type --}}
                <div class="mb-3 col-md-6">
                    <label class="form-label d-block">Task Type <span class="text-danger">*</span></label>
                    @php $tt = (int) old('task_type', $subTask->task_type ?? 1); @endphp
                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                               type="radio"
                               name="task_type"
                               id="task_type_prod"
                               value="1"
                               {{ $tt === 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="task_type_prod">Production</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                               type="radio"
                               name="task_type"
                               id="task_type_nonprod"
                               value="2"
                               {{ $tt === 2 ? 'checked' : '' }}>
                        <label class="form-check-label" for="task_type_nonprod">Non-Production</label>
                    </div>
                    @error('task_type')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Benchmarked Time --}}
                <div class="mb-3 col-md-6">
                    <label for="benchmarked_time" class="form-label">Benchmarked Time</label>
                    <input type="text"
                           id="benchmarked_time"
                           name="benchmarked_time"
                           class="form-control {{ $errors->has('benchmarked_time') ? 'is-invalid' : '' }}"
                           value="{{ old('benchmarked_time', $subTask->benchmarked_time ?? '') }}"
                           placeholder="HH:MM:SS e.g. 00:05:00">
                    @error('benchmarked_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label d-block">Count Type <span class="text-danger">*</span></label>
                    {{-- Use 'ct' for Count Type, defaults to 1 (Mandatory) --}}
                    @php $ct = (int) old('count_type', $subTask->count_type ?? 1); @endphp 
                    
                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                            type="radio"
                            name="count_type"
                            id="count_type_mandatory"
                            value="1"
                            {{ $ct === 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="count_type_mandatory">Mandatory</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                            type="radio"
                            name="count_type"
                            id="count_type_optional"
                            value="2"
                            {{ $ct === 2 ? 'checked' : '' }}>
                        <label class="form-check-label" for="count_type_optional">Optional</label>
                    </div>
                    @error('count_type')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- Status --}}
                <div class="mb-3 col-md-6">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select id="status"
                            name="status"
                            class="form-control form-select {{ $errors->has('status') ? 'is-invalid' : '' }}"
                            required>
                        <option value="">Select Status</option>
                        <option value="1" {{ old('status', $subTask->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $subTask->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">
                    {{ $edit ? 'Update Sub Task' : 'Save Sub Task' }}
                    <i class="ti ti-file-upload ms-1"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
