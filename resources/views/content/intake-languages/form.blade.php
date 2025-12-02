@extends('layouts/layoutMaster')

@section('title', $title ?? ($edit ?? false ? 'Edit Language' : 'Add Language'))

@section('content')
@php $edit = $edit ?? false; @endphp

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">{{ $title ?? ($edit ? 'Edit Language' : 'Add Language') }}</h5>
    <a href="{{ route('intake-languages.index') }}" class="btn btn-outline-secondary">
      <i class="ti ti-chevron-left me-1"></i> Back
    </a>
  </div>

  <div class="card-body">
    <form method="POST" action="{{ $edit ? route('intake-languages.update', Crypt::encryptString($item->id)) : route('intake-languages.store') }}">
      @csrf
      @if($edit) @method('PUT') @endif

      <div class="row">
        <div class="mb-3 col-md-6">
          <label class="form-label">Name <span class="text-danger">*</span></label>
          <input type="text"
                 name="name"
                 value="{{ old('name', $item->name ?? '') }}"
                 class="form-control @error('name') is-invalid @enderror"
                 required autofocus>
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="text-end mt-3">
        <button type="submit" class="btn btn-primary">
          {{ $edit ? 'Update' : 'Save' }}
          <i class="ti ti-file-upload ms-1"></i>
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
