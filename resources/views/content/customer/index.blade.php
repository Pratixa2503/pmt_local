@extends('layouts/layoutMaster')

@section('title', 'Customers')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/css/dataTables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/css/responsive.dataTables.css') }}">
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/js/dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/js/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/js/responsive.dataTables.js') }}"></script>
@endsection

@section('page-script')

@endsection

@section('content')
<div class="card">
    <div class="card-header">
    <div class="justify-content-between d-flex">
        <h4 class="text-dark bold mb-0">{{ __('Customers List') }}</h4>
        <div class="justify-content-between">
            @if( auth()->check() && auth()->user()->can('create customer') )
                <a href="{{ route('customers.create') }}" class="btn btn-primary btn-md" data-toggle="tooltip" title="Add Customer"><i class="fa fa-plus me-2"></i>{{ __('Add Customer') }}</a>
            @endif
        </div>
    </div>
    </div>
    @if (Session::get('success'))
        <div class="alert alert-success alert-block mt-2" style="margin-left: 24px; margin-right: 24px;">
            <strong>{{ Session::get('success') }}</strong>
        </div>
    @endif

    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <div class='loader' style='display: none;'>
                <div class='loader-img'>
                    <img src='{{ asset('assets/img/branding/loader.svg') }}' alt='loader' />
                </div>
            </div>
            <table class="common-datatable table" id="customers-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact No</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact No</th>
                        <th>Role</th>
                        <th>Status</th>
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
        $(function() {
            var table = $('#customers-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('customers.index') !!}',
                pageLength: 25,
                columns: [
                    {
                        className: 'dtr-control',
                        orderable: false,
                        targets: 0,
                        data: null,
                        defaultContent: '',
                        responsivePriority: 1,
                    },
                    // {
                    //     data: null,
                    //     name: 'id',
                    //     render: function (data, type, row, meta) {
                    //         return meta.row + meta.settings._iDisplayStart + 1;
                    //     },
                    //     orderable: false,
                    //     searchable: false,
                    //     responsivePriority: 4,
                    // },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: false,
                        responsivePriority: 2,
                    },
                    {
                        data: 'email',
                        name: 'email',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 3,
                    },
                    {
                        data: 'contact_no',
                        name: 'contact_no',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 5,
                    },
                    {
                        data: 'role_name',
                        name: 'role_name',
                        orderable: false,
                        searchable: true,
                        responsivePriority: 6,
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 7,
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 3,
                    }
                ],
                columnDefs: [
                    {
                        targets: -1, // Last column
                        className: 'text-center', // Align text to right
                        width: '150px', // Fixed width
                    }
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
                }
            });
        });

        // ONE-TIME: include the CSRF token on all AJAX calls
$.ajaxSetup({
  headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
});

$(document).on('click', '.delete-customer', function (e) {
  e.preventDefault();

  const id = $(this).data('id'); // should be your encrypted or numeric id
  if (!id) {
    Swal.fire({icon: 'error', text: 'Missing customer id.'});
    return;
  }

  Swal.fire({
    text: 'Are you sure you want to delete this Customer?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel',
    buttonsStyling: false,
    customClass: {
      confirmButton: 'btn btn-info me-3',
      cancelButton: 'btn btn-label-secondary'
    }
  }).then(function (result) {
    if (!result.isConfirmed) return;

    // Do the delete as POST + _method=DELETE (best with Laravel)
    $.ajax({
      url: "{{ url('customers') }}/" + encodeURIComponent(id),
      type: 'POST',
      dataType: 'json',
      data: {
        _method: 'DELETE'
        // _token not needed in body because we send it in header via ajaxSetup
      },
      cache: false
    })
    .done(function (res) {
        console.log(res);
        alert(res);
      const ok = res && (res.status === 1 || res.status === true || res.status === '1');

      if (ok) {
        Swal.fire({
          title: 'Deleted!',
          text: res.message || 'Customer deleted successfully.',
          // Force a green success icon even if a global mixin overrides `icon`
          iconHtml: '<i class="ti ti-checks" style="font-size:42px;"></i>',
          iconColor: '#16a34a',
          buttonsStyling: false,
          customClass: { confirmButton: 'btn btn-success' }
        });

        // Reload DataTable without resetting pagination
        const dt = $('#customers-table').DataTable();
        dt.ajax.reload(null, false);
      } else {
        Swal.fire({
          title: 'Oops',
          text: (res && res.message) ? res.message : 'Failed to delete customer.',
          icon: 'error',
          buttonsStyling: false,
          customClass: { confirmButton: 'btn btn-danger' }
        });
      }
    })
    .fail(function (xhr) {
      // Build a helpful error message
      let msg = 'Failed to delete customer.';
      if (xhr.status === 419) msg = 'Session expired. Please refresh and try again.';
      else if (xhr.status === 404) msg = 'Customer not found.';
      else if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
      else if (xhr.responseText) msg = xhr.responseText;
       
      Swal.fire({
        title: 'Error',
        text: msg,
        icon: 'error',
        buttonsStyling: false,
        customClass: { confirmButton: 'btn btn-danger' }
      });
    });
  });
});



        setTimeout(function() {
            $(".alert-block").remove();
        }, 5000);
    </script>
@endsection
