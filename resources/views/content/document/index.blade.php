@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Document')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/css/dataTables.bootstrap5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/css/responsive.dataTables.css') }}">
@endsection

@section('page-style')
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
@endsection

@section('vendor-script')
  <script src="{{ asset('assets/vendor/libs/datatables-bs5/js/dataTables.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-bs5/js/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/js/dataTables.responsive.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/js/responsive.dataTables.js') }}"></script>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div class="d-flex justify-content-between align-items-center">
      <h4 class="text-dark fw-bold mb-0">Document</h4>
      <!-- @can('create customer')
        <a href="{{ route('document.create') }}" class="btn btn-primary btn-md" title="Add Customer">
          <i class="fa fa-plus me-2"></i>Add Document
        </a>
      @endcan -->
    </div>
  </div>

  @if (Session::get('success'))
    <div class="alert alert-success alert-block mt-2 mx-3">
      <strong>{{ Session::get('success') }}</strong>
    </div>
  @endif

  <div class="card-body">
    {{-- Filters --}}
    <div class="row g-2 mb-3">
      <div class="col-md-4">
        <label class="form-label mb-1">Customer</label>
        <select id="filter_customer" class="form-select">
          <option value="">All</option>
          @foreach($customers ?? [] as $c)
            <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>
              {{ $c->name }}
            </option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="table-responsive text-nowrap position-relative">
      <div class="loader" style="display: none;">
        <div class="loader-img">
          <img src="{{ asset('assets/img/branding/loader.svg') }}" alt="loader" />
        </div>
      </div>

      <table class="table common-datatable" id="document-table">
        <thead>
          <tr>
            <th></th>
            <th>Customer</th>
            <th>Department</th>
            <th>Industry</th>
            <th>Description</th>
            <!-- <th>Contact No</th> -->
            <th>Actions</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th></th>
            <th>Customer</th>
            <th>Department</th>
            <th>Industry</th>
            <th>Description</th>
            <!-- <th>Contact No</th> -->
            <th>Actions</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection

@section('extra-script')
<script>
  const dataTablePageLength = {{ env('DATATABLEPAGELENGTH', 10) }};

  $(function () {
    $('#document-table').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: {
        url: '{!! route('document.index') !!}',
        data: function(d) {
          d.customer_id = $('#filter_customer').val() || '';
        }
      },
      pageLength: dataTablePageLength,
      columns: [
        {
          className: 'dtr-control',
          orderable: false,
          data: null,
          defaultContent: '',
          responsivePriority: 1
        },
        { data: 'customer',               name: 'customer',               responsivePriority: 2 },
        { data: 'department',             name: 'department',             responsivePriority: 3 },
        { data: 'industry',               name: 'industry',               responsivePriority: 4 },
        { data: 'description',            name: 'description',            responsivePriority: 5 },
        
        {
          data: 'actions',
          name: 'actions',
          orderable: false,
          searchable: false,
          responsivePriority: 1
        }
      ],
      columnDefs: [
        { targets: -1, className: 'text-center', width: '150px' }
      ],
      pagingType: "simple_numbers",
      language: {
        emptyTable: "No data available.",
        search: "",
        searchPlaceholder: "Search",
        oPaginate: {
          sNext: '<i class="fas fa-angle-right"></i>',
          sPrevious: '<i class="fas fa-angle-left"></i>',
        }
      },
      order: [[0, 'desc']]
    });

    // Auto-select and filter if customer_id is in URL
    const urlParams = new URLSearchParams(window.location.search);
    const customerId = urlParams.get('customer_id');
    
    if (customerId) {
      $('#filter_customer').val(customerId);
      // Trigger table reload to apply filter
      $('#document-table').DataTable().ajax.reload();
    }
  });

  $(document).on("click", "#delete-document-master", function () {
    var id = $(this).data('id');
    Swal.fire({
      text: 'Are you sure you want to delete this Document?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: {
        confirmButton: 'btn btn-info me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.value) {
        $.ajax({
          url: "{{ url('document') }}/" + id,
          method: "DELETE",
          data: { _token: '{{ csrf_token() }}' },
          success: function (response) {
            if (response.status === true) {
              Swal.fire({
                icon: 'success',
                text: response.message,
                timer: 3000,
                customClass: { confirmButton: 'btn btn-primary' }
              });
              $('#document-table').DataTable().ajax.reload();
            } else {
              Swal.fire({
                icon: 'error',
                text: response.message,
                timer: 3000,
                customClass: { confirmButton: 'btn btn-danger me-3' }
              });
            }
          }
        });
      }
    });
    return false;
  });

  setTimeout(function () {
    $(".alert-block").remove();
  }, 5000);

  // Auto-reload when customer filter changes
  $('#filter_customer').on('change', function() {
    $('#document-table').DataTable().ajax.reload();
  });
</script>
@endsection
