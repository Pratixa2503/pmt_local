@extends('layouts/layoutMaster')

@section('title', $title ?? ($type === 'edit' ? 'Edit Feedback Category' : 'Add Feedback Category'))

@section('content')
@php $type = $type ?? 'create'; @endphp

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">{{ $title ?? ($type === 'edit' ? 'Edit Feedback Category' : 'Add Feedback Category') }}</h5>
    <a href="{{ route('feedback-categories.index') }}" class="btn btn-outline-secondary">
      <i class="ti ti-chevron-left me-1"></i> Back
    </a>
  </div>

  <div class="card-body">
    <form method="POST" action="{{ $type === 'edit' ? route('feedback-categories.update', $encryptedId) : route('feedback-categories.store') }}">
      @csrf
      @if($type === 'edit') @method('PUT') @endif

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
  <label class="form-label">Status <span class="text-danger">*</span></label>

          @php
            $currentStatus = old('status', isset($model) ? (string)$model->status : '1');
          @endphp

          <select name="status" class="form-select @error('status') is-invalid @enderror">
            <option value="1" {{ $currentStatus === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $currentStatus === '0' ? 'selected' : '' }}>Inactive</option>
          </select>

          @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

      </div>

      <div class="text-end mt-3">
        <button type="submit" class="btn btn-primary">
          {{ $type === 'edit' ? 'Update' : 'Save' }}
          <i class="ti ti-file-upload ms-1"></i>
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
