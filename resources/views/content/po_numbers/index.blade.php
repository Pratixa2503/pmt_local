@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'PO Numbers')

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
      <h4 class="text-dark mb-0">{{ __('PO Numbers') }}</h4>
      <div>
        @if(auth()->check() && auth()->user()->can('create po'))
          <a href="{{ route('po-numbers.create') }}" class="btn btn-primary btn-md" data-toggle="tooltip" title="Add PO">
            <i class="fa fa-plus me-2"></i>{{ __('Add PO') }}
          </a>
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
      <div class='loader' style='display:none;'>
        <div class='loader-img'>
          <img src='{{ asset('assets/img/branding/loader.svg') }}' alt='loader' />
        </div>
      </div>

      <table class="common-datatable table" id="po-table">
        <thead>
          <tr>
            <th></th>
            <th>Customer</th>
            <th>Project</th>
            <th>Sub Project</th>
            <th>PO Number</th>
            <th>Start</th>
            <th>End</th>
            <th>Status</th>
            <th style="width:150px" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0"></tbody>
        <tfoot>
          <tr>
            <th></th>
            <th>Customer</th>
            <th>Project</th>
            <th>Sub Project</th>
            <th>PO Number</th>
            <th>Start</th>
            <th>End</th>
            <th>Status</th>
            <th class="text-center">Actions</th>
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
    const table = $('#po-table').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: '{!! route('po-numbers.index') !!}',
      pageLength: dataTablePageLength,
      columns: [
        {
          className: 'dtr-control',
          orderable: false,
          data: null,
          defaultContent: '',
          responsivePriority: 1,
        },
        { data: 'customer',      name: 'customer.name',      orderable: true,  responsivePriority: 4 },
        { data: 'project',       name: 'project.project_name', orderable: true, responsivePriority: 3 },
        { data: 'sub_project',   name: 'subProject.project_name', orderable: true, responsivePriority: 5 },
        { data: 'po_number',     name: 'po_number',          orderable: true,  responsivePriority: 2 },
        { data: 'start_date',    name: 'start_date',         orderable: true,  responsivePriority: 6 },
        { data: 'end_date',      name: 'end_date',           orderable: true,  responsivePriority: 6 },
        { data: 'status',        name: 'status',             orderable: false, searchable: false, responsivePriority: 6 },
        { data: 'actions',       name: 'actions',            orderable: false, searchable: false, responsivePriority: 2 },
      ],
      columnDefs: [
        { targets: -1, className: 'text-center', width: '150px' }
      ],
      pagingType: 'simple_numbers',
      language: {
        emptyTable: 'No data available.',
        search: '',
        searchPlaceholder: 'Search',
        oPaginate: {
          sNext: '<i class="fas fa-angle-right"></i>',
          sPrevious: '<i class="fas fa-angle-left"></i>',
        }
      },
      order: [[4, 'desc']], // sort by PO Number desc
    });

    // Delete handler
    $(document).on('click', '.btn-po-delete', function () {
      const id = $(this).data('id');
      Swal.fire({
        text: 'Are you sure you want to delete this PO?',
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
            url: "{{ url('po-numbers') }}" + '/' + id,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
              if (res.status === 1 || res.status === true) {
                Swal.fire({
                  icon: 'success',
                  text: res.message || 'Deleted successfully',
                  timer: 3000,
                  customClass: { confirmButton: 'btn btn-primary' }
                });
                table.ajax.reload(null, false);
              } else {
                Swal.fire({
                  icon: 'error',
                  text: res.message || 'Delete failed',
                  timer: 3000,
                  customClass: { confirmButton: 'btn btn-danger me-3' }
                });
              }
            },
            error: function () {
              Swal.fire({
                icon: 'error',
                text: 'Delete failed',
                timer: 3000,
                customClass: { confirmButton: 'btn btn-danger me-3' }
              });
            }
          });
        }
      });
      return false;
    });

    setTimeout(function () { $('.alert-block').remove(); }, 5000);
  });
</script>
@endsection
