@extends('layouts/layoutMaster')

@section('title', $title ?? 'Intake Work Types')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/css/dataTables.bootstrap5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/css/responsive.dataTables.css') }}">
@endsection

@section('vendor-script')
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-bs5/js/dataTables.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-bs5/js/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/js/responsive.dataTables.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/js/responsive.dataTables.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div class="d-flex justify-content-between">
      <h4 class="text-dark mb-0">{{ __('Intake Work Types') }}</h4>
      <div>
        @can('create intake work type')
          <a href="{{ route('intake-work-types.create') }}" class="btn btn-primary btn-md" data-toggle="tooltip" title="Add Work Type">
            <i class="fa fa-plus me-2"></i>{{ __('Add Work Type') }}
          </a>
        @endcan
      </div>
    </div>
  </div>

  @if (Session::get('success'))
    <div class="alert alert-success alert-block mt-2 mx-3">
      <strong>{{ Session::get('success') }}</strong>
    </div>
  @endif

  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <div class='loader' style='display:none'>
        <div class='loader-img'>
          <img src='{{ asset('assets/img/branding/loader.svg') }}' alt='loader' />
        </div>
      </div>

      <table class="common-datatable table" id="work-types-table">
        <thead>
          <tr>
            <th></th>
            <th>Name</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0"></tbody>
        <tfoot>
          <tr>
            <th></th>
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
  const dataTablePageLength = {{ env('DATATABLEPAGELENGTH', 10) }};

  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

  $(function() {
    const table = $('#work-types-table').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: '{!! route('intake-work-types.index') !!}',
      pageLength: dataTablePageLength,
      columns: [
        {
          className: 'dtr-control',
          orderable: false,
          data: null,
          defaultContent: '',
          responsivePriority: 1,
        },
        { data: 'name', name: 'name', orderable: true, responsivePriority: 2 },
        { data: 'actions', name: 'actions', orderable: false, searchable: false, responsivePriority: 3 }
      ],
      pagingType: "simple_numbers",
      language: {
        emptyTable: "No data available.",
        search: "",
        searchPlaceholder: "Search",
        oPaginate: { sNext: '<i class="fas fa-angle-right"></i>', sPrevious: '<i class="fas fa-angle-left"></i>' }
      },
      order: [[1, 'asc']],
    });

    // Delete
    $(document).on('click', '.delete-intake-work-type', function() {
      const id = $(this).data('id');
      Swal.fire({
        text: 'Are you sure you want to delete this Work Type?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: { confirmButton: 'btn btn-info me-3', cancelButton: 'btn btn-label-secondary' },
        buttonsStyling: false
      }).then(function(result) {
        if (result.value) {
          $.ajax({
            url: "{{ url('intake-work-types') }}/" + id,
            method: "DELETE",
            success: function(res) {
              if (res.status) {
                Swal.fire({ icon: 'success', text: res.message || 'Deleted', timer: 2000, customClass: { confirmButton: 'btn btn-primary' }});
                table.ajax.reload(null, false);
              } else {
                Swal.fire({ icon: 'error', text: res.message || 'Delete failed', timer: 3000 });
              }
            }
          });
        }
      });
      return false;
    });

    setTimeout(function() { $(".alert-block").remove(); }, 5000);
  });
</script>
@endsection
