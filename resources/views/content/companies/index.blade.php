@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Companies')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/css/dataTables.bootstrap5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/css/responsive.dataTables.css') }}">
@endsection

@section('page-style')
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
  <style>
  button.swal2-deny.btn.btn-label-secondary {
      display: none !important;
  }
  /* Optional: make the expand cell narrower & centered */
  #companies-table td.details-control {
    vertical-align: middle;
    text-align: center;
    width: 32px;
  }
  #companies-table td.details-control .btn {
    padding: 2px 6px;
  }
</style>
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
      <h4 class="text-dark fw-bold mb-0">Customers</h4>
      @can('create customer')
        <a href="{{ route('companies.create') }}" class="btn btn-primary btn-md" title="Add Customer">
          <i class="fa fa-plus me-2"></i>Add Customer
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
    <div class="table-responsive text-nowrap position-relative">
      <div class="loader" style="display: none;">
        <div class="loader-img">
          <img src="{{ asset('assets/img/branding/loader.svg') }}" alt="loader" />
        </div>
      </div>

      <table class="table common-datatable" id="companies-table">
        <thead>
          <tr>
            <th></th>
            <th>Name</th>
            <th>Address</th>
            <!-- <th>Location</th> -->
            <th>Contact No</th>
            <!-- <th>Website</th> -->
            <!-- <th>Team</th> -->
            <th>Actions</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th></th>
            <th>Name</th>
            <th>Address</th>
            <!-- <th>Location</th> -->
            <th>Contact No</th>
            <!-- <th>Website</th> -->
            <!-- <th>Team</th> -->
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

  // A small helper to create a unique child-table id for each parent row
  function childTableIdFor(encId) {
    return 'projects-of-' + encId.replace(/[^a-zA-Z0-9_-]/g, '');
  }

  // HTML for the child container (a nested table)
  function formatChildTable(rowData) {
    const encId = rowData.id_encrypted || '';           // provided by CompanyDataTable
    const childId = childTableIdFor(encId);

    return `
      <div class="p-2">
        <table id="${childId}" class="table table-sm table-striped w-100 align-middle">
          <thead>
            <tr>
              <th>Project</th>
              <th>Category</th>
              <th>Status</th>
              <th>Start</th>
              <th>End</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    `;
  }

  $(function () {
    // Initialize the parent Companies DataTable
    const dt = $('#companies-table').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: '{!! route('companies.index') !!}',
      pageLength: dataTablePageLength,
      columns: [
        // Expand/Collapse control column
        {
          data: null,
          className: 'details-control',
          orderable: false,
          searchable: false,
          width: '32px',
          defaultContent: '<button class="btn btn-sm btn-outline-secondary toggle-child" title="Expand/Collapse"><i class="fas fa-plus"></i></button>',
          responsivePriority: 1,
        },
        { data: 'name',       name: 'name',       responsivePriority: 2 },
        { data: 'address',    name: 'address',    responsivePriority: 3 },
        { data: 'contact_no', name: 'contact_no', responsivePriority: 3 },
        {
          data: 'actions',
          name: 'actions',
          orderable: false,
          searchable: false,
          className: 'text-center',
          width: '150px',
          responsivePriority: 2
        },

        // Keep id_encrypted in the client row data (hidden); we’ll read it when expanding
        { data: 'id_encrypted', name: 'id_encrypted', visible: false, searchable: false }
      ],
      columnDefs: [
        { targets: -2, className: 'text-center' } // actions column
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
      // Order by Name (since first column is the expand control)
      order: [[1, 'desc']]
    });

    // Expand/Collapse logic
    $('#companies-table tbody').on('click', 'td.details-control button.toggle-child', function (e) {
      e.preventDefault();
      const tr  = $(this).closest('tr');
      const row = dt.row(tr);

      if (row.child.isShown()) {
        // collapse
        row.child.hide();
        tr.removeClass('shown');
        $(this).find('i').removeClass('fa-minus').addClass('fa-plus');
        return;
      }

      // expand: render child container
      const data = row.data();
      if (!data || !data.id_encrypted) return;

      row.child(formatChildTable(data)).show();
      tr.addClass('shown');
      $(this).find('i').removeClass('fa-plus').addClass('fa-minus');

      // init the nested DataTable for this company’s projects
      const encId = data.id_encrypted;
      const childId = childTableIdFor(encId);

      $('#' + childId).DataTable({
        processing: true,
        serverSide: true,
        searching: false,      // remove search box
        lengthChange: false,   // remove "show N entries"
        info: false,         
        pagingType: 'simple_numbers',
        ajax: '{{ route('companies.projects', ['encrypted' => '___ID___']) }}'.replace('___ID___', encId),
        columns: [
          { data: 'project_name',     name: 'project_name', orderable: false, searchable: false },
          { data: 'project_category', name: 'project_category' , orderable: false, searchable: false},
          { data: 'status',           name: 'status', orderable: false, searchable: false },
          { data: 'start_date',       name: 'start_date', orderable: false, searchable: false },
          { data: 'end_date',         name: 'end_date', orderable: false, searchable: false },
          { data: 'actions',          name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ],
        language: {
          emptyTable: "No projects found."
        }
      });
    });

    // Delete Company (no changes)
    $(document).on("click", ".delete-company", function () {
      var id = $(this).data('id');
      Swal.fire({
        text: 'Are you sure you want to delete this Company?',
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
            url: "{{ url('companies') }}/" + id,
            method: "DELETE",
            data: { _token: '{{ csrf_token() }}' },
            success: function (response) {
              if (response.status === 1) {
                Swal.fire({
                  icon: 'success',
                  text: response.message,
                  timer: 3000,
                  customClass: { confirmButton: 'btn btn-primary' }
                });
                $('#companies-table').DataTable().ajax.reload();
              } else {
                Swal.fire({
                  icon: 'error',
                  text: response.message,
                  timer: 3000,
                  customClass: { confirmButton: 'btn btn-danger me-3' }
                });
              }
            }
          });
        }
      });
      return false;
    });
      // Delete handler
$(document).on('click', '.delete-project', function (e) {
    e.preventDefault();

    let encryptedId = $(this).data('id');
    let row = $(this).closest("tr"); // if you want to remove row after delete
    
    Swal.fire({
        title: "Are you sure?",
        text: "This project will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/projects/" + encryptedId,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE"
                },
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "Deleted!",
                        text: response.message || "Project deleted successfully."
                    });

                    // Remove row without reloading page
                    row.fadeOut(500, function(){ $(this).remove(); });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: xhr.responseJSON?.message || "Something went wrong!"
                    });
                }
            });
        }
    });
});
    // Optional: auto-hide success alert
    setTimeout(function () {
      $(".alert-block").remove();
    }, 5000);
  });
</script>


@endsection
