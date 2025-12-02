@extends('layouts/layoutMaster')

@section('title', 'Mode of Delivery')

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

@section('page-script')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="justify-content-between d-flex">
            <h4 class="text-dark bold mb-0">{{ __('Mode of Delivery') }}</h4>
            <div>
                @can('create mode of delivery')
                    <a href="{{ route('mode-of-delivery.create') }}" class="btn btn-primary btn-md" title="Add Department">
                        <i class="fa fa-plus me-2"></i>{{ __('Add Mode of Delivery') }}
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
            <div class='loader' style='display: none;'>
                <div class='loader-img'>
                    <img src='{{ asset('assets/img/branding/loader.svg') }}' alt='loader' />
                </div>
            </div>
            <table class="common-datatable table" id="mode-of-delivery-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0"></tbody>
                <tfoot>
                    <tr>
                        <th></th>
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
        $('#mode-of-delivery-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: '{!! route('mode-of-delivery.index') !!}',
            pageLength: dataTablePageLength,
            columns: [
                {
                    className: 'dtr-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                    responsivePriority: 1,
                },
                {
                    data: 'name',
                    name: 'name',
                    responsivePriority: 2,
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    width: '150px',
                    responsivePriority: 4,
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
            },
            order: [[0, 'desc']],
        });
    });

    $(document).on("click", "#delete-mode-of-delivery", function() {
        var id = $(this).data('id');
        Swal.fire({
            text: 'Are you sure you want to delete this Mode of Delivery?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            customClass: {
                confirmButton: 'btn btn-info me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ url('mode-of-delivery') }}/" + id,
                    method: "DELETE",
                    data: {
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(result) {
                        if (result.status === true) {
                            Swal.fire({
                                icon: 'success',
                                text: result.message,
                                timer: 3000,
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            });
                            $('#mode-of-delivery-table').DataTable().ajax.reload();
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
        return false;
    });

    setTimeout(function() {
        $(".alert-block").remove();
    }, 5000);
</script>
@endsection
