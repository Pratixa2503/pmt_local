@extends('layouts/layoutMaster')

@section('title', $title ?? 'Invoices')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/css/dataTables.bootstrap5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/css/responsive.dataTables.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}">
@endsection

@section('vendor-script')
  <script src="{{ asset('assets/vendor/libs/datatables-bs5/js/dataTables.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-bs5/js/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/js/dataTables.responsive.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('content')
@can('list invoice')
<div class="card mb-3">
  <div class="card-header">
    <h4 class="mb-0">Invoices</h4>
  </div>
  <div class="card-body">
    {{-- Filters --}}
    <div class="row g-2 mb-3">
      <div class="col-md-3">
        <label class="form-label mb-1">Billing Month</label>
        <input type="month" id="filter_month" class="form-control"
               value="{{ request('month', now()->format('Y-m')) }}">
      </div>
      <div class="col-md-4">
        <label class="form-label mb-1">Project (optional)</label>
        <select id="filter_project" class="form-select">
          <option value="">All</option>
          @foreach($projects as $p)
            <option value="{{ $p->id }}">{{ $p->project_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label mb-1">Customer (optional)</label>
        <select id="filter_customer" class="form-select">
          <option value="">All</option>
          @foreach($customers as $c)
            <option value="{{ $c->id }}">{{ $c->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-1 d-flex align-items-end">
        <button type="button" id="btnFilter" class="btn btn-primary w-100">Go</button>
      </div>
    </div>

    {{-- The DataTable --}}
    {!! $dataTable->table(['class' => 'table table-striped w-100', 'style' => 'width:100%']) !!}
  </div>
</div>

{{-- Payment Completed Modal --}}
<div class="modal fade" id="paymentCompleteModal" tabindex="-1" aria-labelledby="paymentCompleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentCompleteModalLabel">Mark Payment as Completed</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="paymentCompleteForm">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="payment_completed_date" class="form-label">Payment Completed Date <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="payment_completed_date" name="payment_completed_date" 
                   placeholder="MM-DD-YYYY" autocomplete="off" required>
            <div class="invalid-feedback"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Mark as Completed</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endcan
@endsection

@section('extra-script')
  {!! $dataTable->scripts() !!}

  <script>
    document.getElementById('btnFilter').addEventListener('click', function () {
      // refresh table with current filter values (DataTables service uses the ajax data function)
      window.LaravelDataTables['invoice-table'].ajax.reload();
    });

    // Also reload when month/project/customer changes (optional)
    ['filter_month','filter_project','filter_customer'].forEach(function(id){
      document.getElementById(id).addEventListener('change', function(){
        window.LaravelDataTables['invoice-table'].ajax.reload();
      });
    });

    // Payment Completed Modal and Datepicker
    $(document).ready(function() {
      var currentInvoiceId = null;
      var $modal = $('#paymentCompleteModal');
      var $form = $('#paymentCompleteForm');
      var $dateInput = $('#payment_completed_date');

      // Initialize datepicker
      $dateInput.datepicker({
        format: 'mm-dd-yyyy',
        autoclose: true,
        todayHighlight: true,
        endDate: new Date(), // No future dates
        startDate: null // Allow past dates
      });

      // Set today's date as default
      var today = new Date();
      var todayStr = String(today.getMonth() + 1).padStart(2, '0') + '-' + 
                     String(today.getDate()).padStart(2, '0') + '-' + 
                     today.getFullYear();
      $dateInput.val(todayStr);

      // Open modal when Payment Completed icon is clicked
      $(document).on('click', '.payment-complete-icon', function() {
        currentInvoiceId = $(this).data('id');
        $dateInput.val(todayStr);
        $dateInput.datepicker('update', today);
        $form[0].reset();
        $dateInput.removeClass('is-invalid');
        $dateInput.next('.invalid-feedback').text('');
        $modal.modal('show');
      });

      // Handle form submission
      $form.on('submit', function(e) {
        e.preventDefault();
        
        if (!currentInvoiceId) {
          alert('Invalid invoice ID');
          return;
        }

        var dateValue = $dateInput.val();
        if (!dateValue) {
          $dateInput.addClass('is-invalid');
          $dateInput.next('.invalid-feedback').text('Please select a payment date');
          return;
        }

        // Convert MM-DD-YYYY to YYYY-MM-DD for backend
        var dateParts = dateValue.split('-');
        if (dateParts.length === 3) {
          var formattedDate = dateParts[2] + '-' + dateParts[0] + '-' + dateParts[1];
        } else {
          formattedDate = dateValue;
        }

        $.ajax({
          url: "{{ route('invoices.completePayment', ':id') }}".replace(':id', currentInvoiceId),
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            payment_completed_date: formattedDate
          },
          success: function(response) {
            if (response.status === 1) {
              $modal.modal('hide');
              // Reload DataTable
              if (window.LaravelDataTables && window.LaravelDataTables['invoice-table']) {
                window.LaravelDataTables['invoice-table'].ajax.reload(null, false);
              }
              // Show success message
              if (typeof Swal !== 'undefined') {
                Swal.fire({
                  icon: 'success',
                  text: response.message || 'Payment marked as completed successfully.',
                  timer: 3000,
                  showConfirmButton: false
                });
              } else {
                alert(response.message || 'Payment marked as completed successfully.');
              }
            } else {
              alert(response.message || 'Failed to mark payment as completed.');
            }
          },
          error: function(xhr) {
            var errorMsg = 'Failed to mark payment as completed.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMsg = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
              var errors = xhr.responseJSON.errors;
              if (errors.payment_completed_date) {
                $dateInput.addClass('is-invalid');
                $dateInput.next('.invalid-feedback').text(errors.payment_completed_date[0]);
                return;
              }
            }
            alert(errorMsg);
          }
        });
      });

      // Reset form when modal is closed
      $modal.on('hidden.bs.modal', function() {
        currentInvoiceId = null;
        $form[0].reset();
        $dateInput.val(todayStr);
        $dateInput.removeClass('is-invalid');
        $dateInput.next('.invalid-feedback').text('');
      });
    });
  </script>
@endsection
