@extends('layouts/layoutMaster')

@section('title', $title ?? 'Intake Lease Types')

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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="text-dark mb-0">{{ __('Intake Lease Types') }}</h4>
    @can('create intake lease type')
      <a href="{{ route('intake-lease-types.create') }}" class="btn btn-primary">
        <i class="fa fa-plus me-2"></i>{{ __('Add Lease Type') }}
      </a>
    @endcan
  </div>

  @if (Session::get('success'))
    <div class="alert alert-success alert-block mt-2 mx-3"><strong>{{ Session::get('success') }}</strong></div>
  @endif

  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <table class="table" id="intake-lease-types-table">
        <thead>
          <tr>
            <th style="width:80px">#</th>
            <th>Name</th>
            <th style="width:140px" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
          <tr>
            <th>#</th><th>Name</th><th class="text-center">Actions</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection

@section('extra-script')
<script>
  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

  $(function () {
    const table = $('#intake-lease-types-table').DataTable({
      responsive: true, processing: true, serverSide: true,
      ajax: '{!! route('intake-lease-types.index') !!}',
      pageLength: {{ env('DATATABLEPAGELENGTH', 10) }},
      columns: [
        { data: 'id', name: 'id', className:'align-middle', width:'80px' },
        { data: 'name', name: 'name', className:'align-middle' },
        { data: 'actions', name: 'actions', orderable:false, searchable:false, className:'text-center align-middle', width:'140px' }
      ],
      language: {
        emptyTable: 'No data available.', search: '', searchPlaceholder:'Search',
        oPaginate:{ sNext:'<i class="fas fa-angle-right"></i>', sPrevious:'<i class="fas fa-angle-left"></i>' }
      },
      order: [[0,'desc']]
    });

    $(document).on('click', '.delete-intake-lease-type', function () {
      const encId = $(this).data('id');
      Swal.fire({
        text: 'Delete this Lease Type?',
        icon: 'warning', showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: { confirmButton: 'btn btn-info me-3', cancelButton:'btn btn-label-secondary' },
        buttonsStyling: false
      }).then(res => {
        if (!res.value) return;
        $.ajax({
          url: "{{ url('intake-lease-types') }}/" + encId, method: 'DELETE',
          success: (r)=>{ Swal.fire({icon:'success',text:r.message||'Deleted',timer:2000}); table.ajax.reload(null,false); },
          error: (x)=>{ Swal.fire({icon:'error',text:x.responseJSON?.message||'Delete failed',timer:3000}); }
        });
      });
    });

    setTimeout(()=>$('.alert-block').remove(), 5000);
  });
</script>
@endsection
