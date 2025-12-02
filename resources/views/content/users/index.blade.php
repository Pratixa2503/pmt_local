@extends('layouts/layoutMaster')

@section('title', 'Users')

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
  <div class="card-header border-bottom">
    <div class="d-flex justify-content-between align-items-center">
      <h4 class="text-dark fw-bold mb-0">{{ __('Users Management') }}</h4>
      @if( auth()->check() && auth()->user()->can('create user') )
      <a href="{{ route('users.create') }}" class="btn btn-primary btn-md" data-toggle="tooltip" title="Add User">
        <i class="fa fa-plus me-2"></i>{{ __('Add User') }}
      </a>
      @endif
    </div>
  </div>

  <div class="card-body">
    <!-- Styled Tabs -->
    <ul class="nav nav-pills mb-3 justify-content-start" id="userTabs" role="tablist" style="gap:10px;">
      <li class="nav-item" role="presentation">
        <button class="nav-link active shadow-sm px-4 py-2 rounded-pill" id="assign-tab" data-bs-toggle="tab" data-bs-target="#assign" type="button" role="tab" aria-controls="assign" aria-selected="true">
          <i class="fas fa-user-plus me-2"></i> Assign Users
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link shadow-sm px-4 py-2 rounded-pill" id="unassign-tab" data-bs-toggle="tab" data-bs-target="#unassign" type="button" role="tab" aria-controls="unassign" aria-selected="false">
          <i class="fas fa-user-minus me-2"></i> Unassign Users
        </button>
      </li>
    </ul>

    <div class="tab-content" id="userTabsContent">
      <!-- Assign Users -->
      <div class="tab-pane fade show active" id="assign" role="tabpanel" aria-labelledby="assign-tab">
        <div class="table-responsive text-nowrap">
          <div class='loader' style='display: none;'>
            <div class='loader-img'>
              <img src='{{ asset('assets/img/branding/loader.svg') }}' alt='loader' />
            </div>
          </div>

          <table class="common-datatable table table-hover" id="users-table">
            <thead>
              <tr>
                <th></th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0"></tbody>
            <tfoot>
              <tr>
                <th></th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <!-- Unassign Users -->
      <div class="tab-pane fade" id="unassign" role="tabpanel" aria-labelledby="unassign-tab">
        <!-- Unassign Users -->
        <div class="tab-pane" id="unassign" role="tabpanel" aria-labelledby="unassign-tab">
          <div class="table-responsive text-nowrap">
            <table class="common-datatable table table-hover" id="unassigned-users-table">
              <thead>
                <tr>
                  <!-- <th></th> -->
                  <th>Name</th>
                  <th>Email</th>
                  <th>Role(s)</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0"></tbody>
              <tfoot>
                <tr>
                  <!-- <th></th> -->
                  <th>Name</th>
                  <th>Email</th>
                  <th>Role(s)</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection



@section('extra-script')
<script>
  $(function() {
    var table = $('#users-table').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: '{!! route('users.index') !!}',
      pageLength: 25,
      columns: [{
          data: 'expand',
          name: 'expand',
          orderable: false,
          searchable: false,
          className: 'text-center',
          width: '30px',
          responsivePriority: 1
        },
        {
          data: 'name',
          name: 'name',
          orderable: false,
          searchable: false,
          responsivePriority: 2
        },
        {
          data: 'email',
          name: 'email',
          orderable: false,
          searchable: true,
          responsivePriority: 3
        },
        // {
        //   data: 'contact_no',
        //   name: 'contact_no',
        //   orderable: false,
        //   searchable: false,
        //   responsivePriority: 5
        // },
        {
          data: 'role_name',
          name: 'role_name',
          orderable: false,
          searchable: false,
          responsivePriority: 6
        },
        {
          data: 'status',
          name: 'status',
          orderable: false,
          searchable: false,
          responsivePriority: 7
        },
        {
          data: 'actions',
          name: 'actions',
          orderable: false,
          searchable: false,
          responsivePriority: 3
        }
      ],
      columnDefs: [{
        targets: -1,
        className: 'text-center',
        width: '150px'
      }],
      pagingType: 'simple_numbers',
      language: {
        emptyTable: 'No data available.',
        search: '',
        searchPlaceholder: 'Search',
        oPaginate: {
          sNext: '<i class="fas fa-angle-right"></i>',
          sPrevious: '<i class="fas fa-angle-left"></i>'
        }
      }
    });

    $('#users-table').on('click', 'a.details-control', function() {
      var $btn = $(this);
      var tr = $btn.closest('tr');
      var row = table.row(tr);
      var encId = $btn.data('id');

      if (row.child.isShown()) {
        row.child.hide();
        tr.removeClass('shown');
        return;
      }

      row.child('<div class="p-2">Loading team...</div>').show();
      tr.addClass('shown');

      $.get("{{ route('users.team-members', ':id') }}".replace(':id', encId))
        .done(function(res) {
          row.child(res.html || '<div class="p-2">No data</div>').show();
        })
        .fail(function() {
          row.child('<div class="p-2 text-danger">Failed to load team members.</div>').show();
        });
    });

    // ---------- Delete User ----------
    $(document).on('click', '#delete-user', function() {
      var id = $(this).data('id');
      var $btn = $(this);
      
      // Determine which table this delete button belongs to
      var $closestTable = $btn.closest('table');
      var isUnassignedTable = $closestTable.length > 0 && $closestTable.attr('id') === 'unassigned-users-table';
      
      if (!id) {
        Swal.fire({
          icon: 'error',
          text: 'Invalid user ID.',
          customClass: {
            confirmButton: 'btn btn-danger me-3'
          }
        });
        return false;
      }
      
      // URL encode the encrypted ID to handle special characters
      var encodedId = encodeURIComponent(id);
      
      Swal.fire({
        text: 'Are you sure you want to delete this User?',
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
            url: "{{ url('users') }}/" + encodedId,
            method: 'DELETE',
            data: {
              _token: '{{ csrf_token() }}'
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
                // Reload the appropriate table(s)
                if (isUnassignedTable) {
                  // Reload unassigned table
                  if (typeof unassignedTable !== 'undefined') {
                    unassignedTable.ajax.reload(null, false);
                  }
                } else {
                  // Reload main users table
                  if (typeof table !== 'undefined') {
                    table.ajax.reload(null, false);
                  }
                }
              } else {
                Swal.fire({
                  icon: 'error',
                  text: result.message || 'Failed to delete user.',
                  timer: 3000,
                  customClass: {
                    confirmButton: 'btn btn-danger me-3'
                  }
                });
              }
            },
            error: function(xhr) {
              var errorMsg = 'Failed to delete user.';
              if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
              }
              Swal.fire({
                icon: 'error',
                text: errorMsg,
                timer: 3000,
                customClass: {
                  confirmButton: 'btn btn-danger me-3'
                }
              });
            }
          });
        }
      });
      return false;
    });

    var unassignedTable = $('#unassigned-users-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: '{!! route('users.unassigned') !!}',
        pageLength: 25,
        columns: [
         
          {
            data: 'name',
            name: 'name',
            orderable: false,
            searchable: true,
            responsivePriority: 2
          },
          {
            data: 'email',
            name: 'email',
            orderable: false,
            searchable: true,
            responsivePriority: 3
          },
          {
            data: 'role_name',
            name: 'role_name',
            orderable: false,
            searchable: true,
            responsivePriority: 4
          },
          {
            data: 'status',
            name: 'status',
            orderable: false,
            searchable: false,
            responsivePriority: 5
          },
          {
            data: 'actions',
            name: 'actions',
            orderable: false,
            searchable: false,
            responsivePriority: 3
          }
        ],
        columnDefs: [
          { targets: -1, className: 'text-center', width: '150px' }
        ],
        pagingType: 'simple_numbers',
        language: {
          emptyTable: 'No unassigned users found.',
          search: '',
          searchPlaceholder: 'Search',
          oPaginate: {
            sNext: '<i class="fas fa-angle-right"></i>',
            sPrevious: '<i class="fas fa-angle-left"></i>'
          }
        }
      });
    // ---------- Flash cleanup ----------
    setTimeout(function() {
      $('.alert-block').remove();
    }, 5000);
  });
</script>
@endsection