@extends('layouts/layoutMaster')
@section('title', $title ?? 'Pricing Details')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
  <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
  {{-- no validators here; this is a read-only “view” with action buttons --}}
@endsection

@section('content')
@php
  use Illuminate\Support\Facades\Storage;
  // convenience helpers
  $statusBadge = (int)($document->status ?? 0) === 1 ? ['text' => 'Active', 'class' => 'bg-success'] : ['text' => 'Inactive', 'class' => 'bg-secondary'];
@endphp

<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h4 class="text-dark mb-0">{{ $title ?? 'Document Details' }}</h4>
      </div>

      <div class="card-body">
        {{-- Basic Details --}}
        <div class="row">
          <div class="col-md-12 my-3">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Customer & Contacts</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Customer Name</label>
                <div class="form-control-plaintext">
                    {{ \App\Helpers\Helpers::getDisplayDocumentValue('customer_id', $document->customer_id) }}
                </div>
            </div>

            <!-- <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Contact No</label>
                <div class="form-control-plaintext">{{ $document->contact_no }}</div>
            </div> -->

            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Description</label>
                <div class="form-control-plaintext">{{ $document->description }}</div>
            </div>
        </div>

        {{-- Contracts & Alerts --}}
        <div class="row">
            <div class="col-md-12 my-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 border-top border-grey"></div>
                    <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Contract Dates & Alerts</span>
                    <div class="flex-grow-1 border-top border-grey"></div>
                </div>
            </div>

            @if($document->contracts && $document->contracts->count() > 0)
                @foreach($document->contracts as $contract)
                    <div class="col-md-12 mb-4 p-3 border rounded">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">Contract Start Date</label>
                                <div class="form-control-plaintext">
                                    {{ \Carbon\Carbon::parse($contract->contract_start_date)->format('m/d/Y') }}
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">Contract End Date</label>
                                <div class="form-control-plaintext">
                                    {{ \Carbon\Carbon::parse($contract->contract_end_date)->format('m/d/Y') }}
                                </div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-semibold">Status</label>
                                <div class="form-control-plaintext">
                                    <span class="badge {{ $contract->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $contract->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Duration</label>
                                <div class="form-control-plaintext">
                                    {{ \Carbon\Carbon::parse($contract->contract_start_date)->diffInDays(\Carbon\Carbon::parse($contract->contract_end_date)) }} days
                                </div>
                            </div>
                        </div>

                        @if($contract->alerts && $contract->alerts->count() > 0)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Files</label>
                                    @foreach($contract->alerts as $alert)
                                        <div class="border rounded p-2 mb-2">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>Alert Days:</strong>
                                                    @if(is_array($alert->alert_days))
                                                        {{ implode(', ', $alert->alert_days) }} Days
                                                    @elseif($alert->alert_days)
                                                        {{ $alert->alert_days }} Days
                                                    @else
                                                        Not set
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    @if($alert->alert_file)
                                                        <strong>File:</strong>
                                                        <a href="{{ Storage::url($alert->alert_file) }}" target="_blank" rel="noopener" class="ms-2">
                                                            <i class="ti ti-file me-1"></i>View File
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="col-md-12 mb-3">
                    <div class="form-control-plaintext text-muted">No contracts found.</div>
                </div>
            @endif
        </div>

        {{-- Ownership--}}

        <div class="row">
            <div class="col-md-12 my-3">
                <div class="d-flex align-items-center">
                <div class="flex-grow-1 border-top border-grey"></div>
                <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Ownership</span>
                <div class="flex-grow-1 border-top border-grey"></div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Project Manager Name</label>
                <div class="form-control-plaintext">
                {{ $projectManager->first_name ?? '-' }} {{ $projectManager->last_name ?? '' }}
                </div>
            </div>
        </div>

        {{-- Configuration --}}
        <div class="row">
          <div class="col-md-12 my-3">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Configuration</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Status</label>
            <div class="form-control-plaintext">
              <span class="badge {{ $document->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                {{ $document->status == 1 ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </div>

          @if($document->file_path)
          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Document File</label>
            <div class="form-control-plaintext">
              <a href="{{ Storage::url($document->file_path) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-file me-1"></i>View File
              </a>
            </div>
          </div>
          @endif

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Industry Vertical</label>
            <div class="form-control-plaintext">
                {{ \App\Helpers\Helpers::getDisplayDocumentValue('industry_vertical_id', $document->industry_vertical_id) }}
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Department / Business Unit</label>
            <div class="form-control-plaintext"> {{ \App\Helpers\Helpers::getDisplayDocumentValue('department_id', $document->department_id) }}</div>
          </div>
        </div>
        {{-- Actions --}}
        <div class="d-flex justify-content-between mt-4">
          <a href="{{ route('document.index') }}" class="btn btn-secondary">
            <i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>Back
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
