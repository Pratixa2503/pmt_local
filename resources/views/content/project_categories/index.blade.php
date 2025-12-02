@extends('layouts/layoutMaster')

@section('title', 'Project Categories')

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
        <div class="justify-content-between d-flex">
            <h4 class="text-dark bold mb-0">{{ __('Project Category List') }}</h4>
            <div class="justify-content-between">
                @can('create project category')
                    <a href="{{ route('project-categories.create') }}" class="btn btn-primary btn-md">
                        <i class="fa fa-plus me-2"></i>{{ __('Add Category') }}
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
            <table class="common-datatable table" id="project-categories-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Category Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>Category Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody class="table-border-bottom-0"></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('extra-script')
<script>
    $(function () {
        const table = $('#project-categories-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: '{!! route('project-categories.index') !!}',
            pageLength: 25,
            columns: [
                {
                    className: 'dtr-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                },
                { data: 'name', name: 'name', orderable: false, searchable: true },
                { data: 'status', name: 'status', orderable: false, searchable: true },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                }
            ],
            columnDefs: [
                {
                    targets: -1,
                    className: 'text-center',
                    width: '150px'
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

        $(document).on('click', '.delete-project-category', function () {
            const id = $(this).data('id');
            Swal.fire({
                text: 'Are you sure you want to delete this project category?',
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
                        url: "{{ url('project-categories') }}/" + id,
                        method: "DELETE",
                        data: {
                            '_token': '{{ csrf_token() }}',
                        },
                        success: function (result) {
                            if (result.status === true || result.status === 1) {
                                Swal.fire({
                                    icon: 'success',
                                    text: result.message,
                                    timer: 3000,
                                    customClass: {
                                        confirmButton: 'btn btn-primary'
                                    }
                                });
                                table.ajax.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    text: result.message,
                                    timer: 3000,
                                    customClass: {
                                        confirmButton: 'btn btn-danger me-3',
                                    },
                                });
                            }
                        }
                    });
                }
            });
        });

        setTimeout(() => {
            $(".alert-block").remove();
        }, 5000);
    });
</script>
@endsection
