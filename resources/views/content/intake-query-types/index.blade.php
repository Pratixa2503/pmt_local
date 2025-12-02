@extends('layouts/layoutMaster')

@section('title', 'Intake Query Types')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/css/dataTables.bootstrap5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/css/responsive.dataTables.css') }}">
@endsection

@section('vendor-script')
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-bs5/js/dataTables.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-bs5/js/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/js/dataTables.responsive.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/js/responsive.dataTables.js') }}"></script>
  {{-- SweetAlert (if not already globally included) --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="text-dark mb-0">{{ __('Intake Query Types') }}</h4>
    @if(auth()->check() && auth()->user()->can('create intake query type'))
      <a href="{{ route('intake-query-types.create') }}" class="btn btn-primary">
        <i class="fa fa-plus me-2"></i>{{ __('Add Intake Query Type') }}
      </a>
    @endif
  </div>

  @if (Session::get('success'))
    <div class="alert alert-success alert-block mt-2 mx-3">
      <strong>{{ Session::get('success') }}</strong>
    </div>
  @endif

  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <table class="table" id="intake-query-types-table">
        <thead>
          <tr>
            <th style="width:80px">#</th>
            <th>Name</th>
            <th style="width:120px" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0"></tbody>
        <tfoot>
          <tr>
            <th>#</th>
            <th>Name</th>
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
  // Ensure all AJAX requests include the CSRF token (prevents 419 errors)
  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
  });

  $(function () {
    const pageLength = {{ env('DATATABLEPAGELENGTH', 10) }};

    // ---- DataTable AJAX (server-side) ----
    const table = $('#intake-query-types-table').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      // This route must return the Yajra DataTables JSON for IntakeQueryTypeDataTable
      ajax: {
        url: '{!! route('intake-query-types.index') !!}',
        type: 'GET' // or 'POST' if you configured it that way
      },
      pageLength: pageLength,
      columns: [
        { data: 'id',      name: 'id',      className: 'align-middle', width: '80px' },
        { data: 'name',    name: 'name',    className: 'align-middle' },
        { data: 'actions', name: 'actions', className: 'text-center align-middle', orderable: false, searchable: false, width: '120px' }
      ],
      pagingType: 'simple_numbers',
      language: {
        emptyTable: 'No data available.',
        search: '',
        searchPlaceholder: 'Search',
        oPaginate: { sNext: '<i class="fas fa-angle-right"></i>', sPrevious: '<i class="fas fa-angle-left"></i>' }
      },
      order: [[0, 'desc']]
    });

    // ---- Delete row (AJAX) ----
    $(document).on('click', '.delete-intake-query-type', function () {
      const encId = $(this).data('id');
      Swal.fire({
        text: 'Are you sure you want to delete this Intake Query Type?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'btn btn-info me-3',
          cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
      }).then(function(result) {
        if (!result.value) return;

        $.ajax({
          url: "{{ url('intake-query-types') }}/" + encId,
          method: 'DELETE',
          data: {}, // CSRF already added by $.ajaxSetup
          success: function (res) {
            if (res.status) {
              Swal.fire({ icon: 'success', text: res.message || 'Deleted', timer: 2200 });
              table.ajax.reload(null, false);
            } else {
              Swal.fire({ icon: 'error', text: res.message || 'Delete failed', timer: 3000 });
            }
          },
          error: function (xhr) {
            const msg = xhr.responseJSON?.message || 'Delete failed';
            Swal.fire({ icon: 'error', text: msg, timer: 3000 });
          }
        });
      });
    });

    // Auto-clear flash message
    setTimeout(() => $('.alert-block').remove(), 5000);
  });
</script>
@endsection
