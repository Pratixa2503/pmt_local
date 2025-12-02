@extends('layouts/layoutMaster')

@section('title', 'Main Tasks')

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
    <div class="card-header d-flex justify-content-between">
        <h4 class="text-dark mb-0">{{ __('Main Task List') }}</h4>
        @can('create task')
            <a href="{{ route('maintasks.create') }}" class="btn btn-primary btn-md">
                <i class="fa fa-plus me-2"></i>{{ __('Add Main Task') }}
            </a>
        @endcan
    </div>

    @if (Session::get('success'))
        <div class="alert alert-success alert-block mt-2 mx-3">
            <strong>{{ Session::get('success') }}</strong>
        </div>
    @endif

    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="common-datatable table" id="maintasks-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Main Task Name</th>
                        <th>Task Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>Main Task Name</th>
                        <th>Task Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('extra-script')
<script>
$(function () {
    const table = $('#maintasks-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: '{!! route('maintasks.index') !!}',
        pageLength: 25,
        columns: [
            { className: 'dtr-control', orderable: false, data: null, defaultContent: '' },
            { data: 'name', name: 'name' },
            { data: 'task_type', name: 'task_type' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        language: {
            emptyTable: "No tasks available.",
            search: "",
            searchPlaceholder: "Search",
            oPaginate: { sNext: '<i class="fas fa-angle-right"></i>', sPrevious: '<i class="fas fa-angle-left"></i>' }
        }
    });

    $(document).on('click', '.delete-maintask', function () {
        const id = $(this).data('id');
        Swal.fire({
            text: 'Are you sure you want to delete this main task?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            customClass: { confirmButton: 'btn btn-info me-3', cancelButton: 'btn btn-label-secondary' },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: "{{ url('tasks/main') }}/" + id,
                    method: "DELETE",
                    data: { '_token': '{{ csrf_token() }}' },
                    success: function (res) {
                        if (res.status === 1) {
                            Swal.fire({ icon: 'success', text: res.message, timer: 3000 });
                            table.ajax.reload();
                        } else {
                            Swal.fire({ icon: 'error', text: res.message, timer: 3000 });
                        }
                    }
                });
            }
        });
    });

    setTimeout(() => $(".alert-block").remove(), 5000);
});
</script>
@endsection
