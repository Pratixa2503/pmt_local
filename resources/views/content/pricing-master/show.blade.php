@extends('layouts/layoutMaster')
@section('title', $title ?? 'Pricing Details')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
  <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
  {{-- no validators here; this is a read-only "view" with action buttons --}}
@endsection

@section('extra-script')
<script>
$(document).ready(function() {
  // Handle Send for Approval form submission
  $('#sendForApprovalForm').on('submit', function(e) {
    e.preventDefault();
    
    var $form = $(this);
    var $btn = $('#sendForApprovalBtn');
    var originalText = $btn.html();
    
    // Disable button and show loading state
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Sending...');
    
    $.ajax({
      url: $form.attr('action'),
      method: 'POST',
      data: $form.serialize(),
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      success: function(response) {
        if (response.status === true) {
          // Show SweetAlert
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: response.message || 'Your request is sent for approval.',
            confirmButtonText: 'OK',
            customClass: {
              confirmButton: 'btn btn-primary'
            }
          }).then(function() {
            // Hide the button and form after success
            $form.fadeOut(300, function() {
              $(this).remove();
            });
            
            // Optionally reload the page to reflect status change
            setTimeout(function() {
              window.location.reload();
            }, 500);
          });
        } else {
          // Show error if status is false
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: response.message || 'Failed to send for approval.',
            confirmButtonText: 'OK',
            customClass: {
              confirmButton: 'btn btn-danger'
            }
          });
          $btn.prop('disabled', false).html(originalText);
        }
      },
      error: function(xhr) {
        var errorMsg = 'Failed to send for approval.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMsg = xhr.responseJSON.message;
        } else if (xhr.responseText) {
          try {
            var response = JSON.parse(xhr.responseText);
            errorMsg = response.message || errorMsg;
          } catch(e) {
            errorMsg = xhr.responseText.substring(0, 100);
          }
        }
        
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: errorMsg,
          confirmButtonText: 'OK',
          customClass: {
            confirmButton: 'btn btn-danger'
          }
        });
        $btn.prop('disabled', false).html(originalText);
      }
    });
    
    return false;
  });
});
</script>
@endsection

@section('content')
@php
  // convenience helpers
  $statusBadge = (int)($data->status ?? 0) === 1 ? ['text' => 'Active', 'class' => 'bg-success'] : ['text' => 'Inactive', 'class' => 'bg-secondary'];
  $approvalColors = [
    'draft'   => 'bg-secondary',
    'pending' => 'bg-warning',
    'approved'=> 'bg-success',
    'rejected'=> 'bg-danger',
  ];
  $approvalBadge = $approvalColors[$data->approval_status ?? 'draft'] ?? 'bg-secondary';
@endphp

<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h4 class="text-dark mb-0">{{ $title ?? 'Pricing Details' }}</h4>

        <div class="d-flex gap-2">
          <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['text'] }}</span>
          <span class="badge {{ $approvalBadge }} text-dark text-uppercase">{{ $data->approval_status }}</span>
        </div>
      </div>

      <div class="card-body">
        {{-- Basic Details --}}
        <div class="row">
          <div class="col-md-12 my-3">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Basic Details</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Pricing Type</label>
            <div class="form-control-plaintext text-capitalize">{{ $data->pricing_type === 'static' ? 'Standard' : ($data->pricing_type === 'custom' ? 'Custom' : '-') }}</div>
            

          </div>

         <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Industry Vertical</label>
            <div class="form-control-plaintext">
                {{ \App\Helpers\Helpers::getDisplayValue('industry_vertical_id', $data->industry_vertical_id) }}
            </div>
        </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Department / Business Unit</label>
            <div class="form-control-plaintext"> {{ \App\Helpers\Helpers::getDisplayValue('department_id', $data->department_id) }}</div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Service Offering</label>
            <div class="form-control-plaintext"> {{ \App\Helpers\Helpers::getDisplayValue('service_offering_id', $data->service_offering_id) }}</div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Unit of Measurement</label>
            <div class="form-control-plaintext">{{ \App\Helpers\Helpers::getDisplayValue('unit_of_measurement_id', $data->unit_of_measurement_id) }}</div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Description</label>
            <div class="form-control-plaintext">{{ \App\Helpers\Helpers::getDisplayValue('description_id', $data->description_id) }}</div>
          </div>
        </div>

        {{-- Pricing Details --}}
        <div class="row">
          <div class="col-md-12 my-3">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Pricing Details</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Currency</label>
            <div class="form-control-plaintext">{{ \App\Helpers\Helpers::getDisplayValue('currency_id', $data->currency_id) }}</div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Rate</label>
            <div class="form-control-plaintext">{{ number_format((float)$price, 2) }}</div>
          </div>

          @if($data->pricing_type === 'custom')
            <div class="col-md-4 mb-3">
              <label class="form-label fw-semibold">Project Management Cost</label>
              <div class="form-control-plaintext">{{ number_format((float)$data->project_management_cost, 2) }}</div>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-semibold">Vendor Cost</label>
              <div class="form-control-plaintext">{{ number_format((float)$data->vendor_cost, 2) }}</div>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-semibold">Infrastructure Cost</label>
              <div class="form-control-plaintext">{{ number_format((float)$data->infrastructure_cost, 2) }}</div>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-semibold">Overhead (%)</label>
              <div class="form-control-plaintext">{{ number_format((float)$data->overhead_percentage, 2) }}</div>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-semibold">Margin (%)</label>
              <div class="form-control-plaintext">{{ number_format((float)$data->margin_percentage, 2) }}</div>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-semibold">Volume</label>
              <div class="form-control-plaintext">{{ number_format((float)$data->volume, 2) }}</div>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-semibold">Volume Based Additions/Discounts</label>
              <div class="form-control-plaintext">{{ number_format((float)$data->volume_based_discount, 2) }}</div>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-semibold">Conversion Rate</label>
              <div class="form-control-plaintext">{{ number_format((float)$data->conversion_rate, 4) }}</div>
            </div>
          @endif
        </div>

        {{-- Skills (only for custom) --}}
        @if($data->pricing_type === 'custom')
        <div class="row">
          <div class="col-md-12 my-3">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Skills</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-sm table-striped">
                <thead>
                  <tr>
                    <th style="width:60%">Skill</th>
                    <th style="width:40%">Average Handling Time (Min)</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($data->skillLines as $line)
                    <tr>
                      <td>{{ \App\Helpers\Helpers::getDisplayValue('skills', $line->skill_id) }}</td>
                      <td>{{ (int)$line->average_handling_time }}</td>
                    </tr>
                  @empty
                    <tr><td colspan="2" class="text-muted">No skills configured.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
        @endif

        {{-- Status and Meta --}}
        <div class="row">
          <div class="col-md-12 my-3">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 border-top border-grey"></div>
              <span class="mx-3 text-grey fw-semibold text-uppercase small bg-light px-3 py-1 rounded">Status & Info</span>
              <div class="flex-grow-1 border-top border-grey"></div>
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Name</label>
            <div class="form-control-plaintext">{{ $data->name }}</div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Record Status</label>
            <div class="form-control-plaintext">{{ $statusBadge['text'] }}</div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Approval Status</label>
            <div class="form-control-plaintext text-uppercase">{{ $data->approval_status }}</div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Created By</label>
            <div class="form-control-plaintext">{{ $data->creator->name ?? Helper::user_full_name($data->created_by) }}</div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Updated By</label>
            <div class="form-control-plaintext">{{ $data->updater->name ?? Helper::user_full_name($data->updated_by) }}</div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Approved By</label>
            <div class="form-control-plaintext">{{ $data->approver->name ?? (Helper::user_full_name($data->approved_by) ?? '—') }}</div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Submitted At</label>
            <div class="form-control-plaintext">{{ optional($data->submitted_at)->format('d M Y H:i') ?? '—' }}</div>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Approved/Rejected At</label>
            <div class="form-control-plaintext">{{ optional($data->approved_at)->format('d M Y H:i') ?? '—' }}</div>
          </div>

          <div class="col-md-12 mb-3">
            <label class="form-label fw-semibold">{{ $data->approval_status === 'rejected' ? 'Reject Note' : 'Approval Note' }}</label>
            <div class="form-control" readonly style="min-height:70px;">{{ $data->approval_note ?? '' }}</div>
          </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-between mt-4">
          <a href="{{ route('pricing-master.index') }}" class="btn btn-secondary">
            <i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i>Back
          </a>

          <div class="d-flex gap-2">
            <!-- {{-- Show Submit button in draft --}}
            @if($data->approval_status === 'pending')
              <form method="POST" action="{{ route('pricing-master.submit', Crypt::encryptString($data->id)) }}">
                @csrf
                <button type="submit" class="btn btn-warning">
                  Submit for Approval
                </button>
              </form>
            @endif -->

            {{-- Show Approve/Reject buttons in pending --}}
            @if($data->approval_status === 'pending' && auth()->user()->can('approve pricing master'))
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                Approve
              </button>
              <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                Reject
              </button>
            @endif

            {{-- Show Send for Approval button when rejected or pending, but only if not already submitted (submitted_at is null) --}}
            @php
              $canShowButton = !auth()->user()->hasRole('super admin') && 
                               ($data->approval_status === 'rejected' || $data->approval_status === 'pending') &&
                               empty($data->submitted_at);
            @endphp
            @if($canShowButton)
              <form method="POST" action="{{ route('pricing-master.send-for-approval', Crypt::encryptString($data->id)) }}" class="d-inline" id="sendForApprovalForm">
                @csrf
                <button type="submit" class="btn btn-warning" id="sendForApprovalBtn">
                  Send for Approval
                  <i class="ti ti-send ms-1"></i>
                </button>
              </form>
            @endif

            {{-- Show Need Modification button for super admin when approved --}}
            @if($data->approval_status === 'approved' && auth()->user()->hasRole('super admin'))
              <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modificationModal">
                Need Modification
              </button>
            @endif

            {{-- Show Edit button only if not approved OR user is super admin --}}
            @if($data->approval_status !== 'approved' || auth()->user()->hasRole('super admin'))
              <a href="{{ route('pricing-master.edit', Crypt::encryptString($data->id)) }}" class="btn btn-primary">
                <i class="ti ti-pencil me-1"></i>Edit
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Approve Modal --}}
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('pricing-master.approve', Crypt::encryptString($data->id)) }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="approveModalLabel">Approve Pricing</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Approval Note</label>
          <textarea name="approval_note" class="form-control" rows="3" placeholder="Add a note" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-success">Approve</button>
      </div>
    </form>
  </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('pricing-master.reject', Crypt::encryptString($data->id)) }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="rejectModalLabel">Reject Pricing</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Rejection Note <span class="text-danger">*</span></label>
          <textarea name="approval_note" class="form-control" rows="3" placeholder="Why is this being rejected?" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Reject</button>
      </div>
    </form>
  </div>
</div>

{{-- Need Modification Modal --}}
@if($data->approval_status === 'approved' && auth()->user()->hasRole('super admin'))
<div class="modal fade" id="modificationModal" tabindex="-1" aria-labelledby="modificationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('pricing-master.need-modification', Crypt::encryptString($data->id)) }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="modificationModalLabel">Request Modification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Modification Notes <span class="text-danger">*</span></label>
          <textarea name="modification_notes" class="form-control" rows="3" placeholder="What modifications are required?" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Modification Parameter <span class="text-danger">*</span></label>
          <textarea name="modification_parameter" class="form-control" rows="3" placeholder="What parameters need to be changed?" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-warning">Send Modification Request</button>
      </div>
    </form>
  </div>
</div>
@endif
@endsection
