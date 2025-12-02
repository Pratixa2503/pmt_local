@php $configData = Helper::appClasses(); @endphp
@extends('layouts/layoutMaster')

@section('title', 'Intake Statuses')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/css/dataTables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/css/responsive.dataTables.css') }}">
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
      <h4 class="text-dark mb-0">{{ __('Intake Statuses') }}</h4>
      @if(auth()->check() && auth()->user()->can('create intake status'))
        <a href="{{ route('intake-statuses.create') }}" class="btn btn-primary">
          <i class="fa fa-plus me-2"></i>{{ __('Add Intake Status') }}
        </a>
      @endif
    </div>
  </div>

  @if (Session::get('success'))
    <div class="alert alert-success alert-block mt-2 mx-3">
      <strong>{{ Session::get('success') }}</strong>
    </div>
  @endif

  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <table class="table" id="intake-statuses-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0"></tbody>
        <tfoot>
          <tr>
            <th>#</th>
            <th>Name</th>
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

  $(function() {
    const table = $('#intake-statuses-table').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: '{!! route('intake-statuses.index') !!}',
      pageLength: dataTablePageLength,
      columns: [
        { data: 'id', name: 'id', className: 'align-middle', width: '80px' },
        { data: 'name', name: 'name', className: 'align-middle' },
        { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center align-middle', width: '120px' }
      ],
      pagingType: "simple_numbers",
      language: {
        emptyTable: "No data available.",
        search: "",
        searchPlaceholder: "Search",
        oPaginate: { sNext: '<i class="fas fa-angle-right"></i>', sPrevious: '<i class="fas fa-angle-left"></i>' }
      },
      order: [[0, 'desc']],
    });

    // Delete handler
    $(document).on('click', '.delete-intake-status', function () {
      const id = $(this).data('id');
      Swal.fire({
        text: 'Are you sure you want to delete this intake status?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: { confirmButton: 'btn btn-info me-3', cancelButton: 'btn btn-label-secondary' },
        buttonsStyling: false
      }).then(function(result) {
        if (result.value) {
          $.ajax({
            url: "{{ url('intake-statuses') }}/" + id,
            method: "DELETE",
            data: { '_token': '{{ csrf_token() }}' },
            success: function(res) {
              if (res.status) {
                Swal.fire({ icon: 'success', text: res.message, timer: 2500 });
                table.ajax.reload(null, false);
              } else {
                Swal.fire({ icon: 'error', text: res.message || 'Delete failed', timer: 3000 });
              }
            },
            error: function(xhr) {
              Swal.fire({ icon: 'error', text: xhr.responseJSON?.message || 'Delete failed', timer: 3000 });
            }
          });
        }
      });
    });

    setTimeout(() => $(".alert-block").remove(), 5000);
  });
</script>
@endsection
