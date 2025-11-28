@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Currencies')

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
    <div class="d-flex justify-content-between">
      <h4 class="text-dark bold mb-0">Descriptions</h4>
      @can('create description')
        <a href="{{ route('descriptions.create') }}" class="btn btn-primary btn-md" title="Add Description">
          <i class="fa fa-plus me-2"></i>Add Description
        </a>
      @endcan
    </div>
  </div>

  @if (Session::get('success'))
    <div class="alert alert-success alert-block mt-2 mx-3">
      <strong>{{ Session::get('success') }}</strong>
    </div>
  @endif

  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <div class="loader" style="display: none;">
        <div class="loader-img">
          <img src="{{ asset('assets/img/branding/loader.svg') }}" alt="loader" />
        </div>
      </div>

      <table class="table common-datatable" id="input-output-format-table">
        <thead>
          <tr>
            <th></th>
            <th>Name</th>
            
            <th>Actions</th>
          </tr>
        </thead>
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

  $(function () {
    $('#input-output-format-table').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: '{!! route('descriptions.index') !!}',
      pageLength: dataTablePageLength,
      columns: [
        {
          className: 'dtr-control',
          orderable: false,
          data: null,
          defaultContent: '',
          responsivePriority: 1,
        },
        { data: 'name', name: 'name', responsivePriority: 2 },
        
        {
          data: 'actions',
          name: 'actions',
          orderable: false,
          searchable: false,
          responsivePriority: 4
        }
      ],
      columnDefs: [
        {
          targets: -1,
          className: 'text-center',
          width: '150px',
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
      order: [[0, 'desc']]
    });
  });

  $(document).on("click", "#delete-descriptions", function () {
    var id = $(this).data('id');
    Swal.fire({
      text: 'Are you sure you want to delete this Description?',
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
          url: "{{ url('descriptions') }}/" + id,
          method: "DELETE",
          data: {
            '_token': '{{ csrf_token() }}',
          },
          success: function (response) {
            if (response.status === true) {
              Swal.fire({
                icon: 'success',
                text: response.message,
                timer: 3000,
                customClass: {
                  confirmButton: 'btn btn-primary'
                }
              });
              $('#input-output-format-table').DataTable().ajax.reload();
            } else {
              Swal.fire({
                icon: 'error',
                text: response.message,
                timer: 3000,
                customClass: {
                  confirmButton: 'btn btn-danger me-3'
                }
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
</script>
@endsection
