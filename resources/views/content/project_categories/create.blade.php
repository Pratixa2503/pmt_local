{{-- resources/views/content/project_categories/form.blade.php --}}
@extends('layouts/layoutMaster')

@section('title', $title ?? ($type === 'edit' ? 'Edit Project Category' : 'Add Project Category'))

@section('content')
@php
  $isEdit = ($type ?? '') === 'edit';
  $resolvedStatus = old('status', isset($model) ? (int)($model->status ?? 1) : 1); // default 1
@endphp

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">{{ $title ?? ($isEdit ? 'Edit Project Category' : 'Add Project Category') }}</h5>
    <a href="{{ route('project-categories.index') }}" class="btn btn-outline-secondary">
      <i class="ti ti-chevron-left me-1"></i> Back
    </a>
  </div>

  <div class="card-body">
    <form method="POST"
          action="{{ $isEdit
                      ? route('project-categories.update', $encryptedId ?? Crypt::encryptString($model->id))
                      : route('project-categories.store') }}">
      @csrf
      @if($isEdit) @method('PUT') @endif

      <div class="row">
        <div class="mb-3 col-md-6">
          <label class="form-label">Name <span class="text-danger">*</span></label>
          <input type="text"
                 name="name"
                 value="{{ old('name', $model->name ?? '') }}"
                 class="form-control @error('name') is-invalid @enderror"
                 required autofocus>
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3 col-md-6">
          <label for="status" class="form-label">Status</label>
          <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
            <option value="1" {{ (string)$resolvedStatus === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ (string)$resolvedStatus === '0' ? 'selected' : '' }}>Inactive</option>
          </select>
          @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="text-end mt-3">
        <button type="submit" class="btn btn-primary">
          {{ $isEdit ? 'Update' : 'Save' }}
          <i class="ti ti-file-upload ms-1"></i>
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
