@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Projects')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/css/dataTables.bootstrap5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/css/responsive.dataTables.css') }}">
@endsection

@section('page-style')
  <!-- Page -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
  <style>
    button.swal2-deny.btn.btn-label-secondary {
    display: none !important;
}
  </style>
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
      <h4 class="text-dark bold mb-0">{{ __('Projects') }}</h4>
      <div class="justify-content-between">
        @if(auth()->check() && auth()->user()->can('create project'))
          <!-- <a href="{{ route('projects.create') }}" class="btn btn-primary btn-md" data-toggle="tooltip" title="Add Project">
            <i class="fa fa-plus me-2"></i>{{ __('Add Project') }}
          </a> -->
        @endif
          
      </div>
    </div>
  </div>

  @if (Session::get('success'))
    <div class="alert alert-success alert-block mt-2" style="margin-left: 24px; margin-right: 24px;">
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

      <table class="common-datatable table" id="project-table">
        <thead>
          <tr>
            <th></th>
            <th>Name</th>
            <th>Customer</th>
            <!-- <th>Project Type</th> -->
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0"></tbody>
        <tfoot>
          <tr>
            <th></th>
            <th>Name</th>
            <th>Customer</th>
            <!-- <th>Project Type</th> -->
            <th>Status</th>
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
    var table = $('#project-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{!! route('projects.index') !!}', // your main projects DataTable route
      columns: [
        {
          data: 'expand',
          name: 'expand',
          orderable: false,
          searchable: false,
          className: 'text-center',
          width: '30px'
        },
        { data: 'project_name', name: 'projects.project_name' },
        { data: 'customer_name', name: 'c.name' },
        // { data: 'project_type_name', name: 'pt.name' },
        { data: 'status_name', name: 'ps.name' },
        {
          data: 'actions',
          name: 'actions',
          orderable: false,
          searchable: false,
          className: 'text-center',
          width: '120px'
        }
      ],
      pagingType: 'simple_numbers',
      language: {
        emptyTable: 'No projects available.',
        search: '',
        searchPlaceholder: 'Search projects...',
        oPaginate: {
          sNext: '<i class="fas fa-angle-right"></i>',
          sPrevious: '<i class="fas fa-angle-left"></i>'
        }
      }
    });

  // ---------- Expand / Collapse Subprojects ----------
 // helper → swap FA icons and update aria-expanded
function setExpanderIcon($btn, isOpen) {
  const $i = $btn.find('i').first();
  $i.removeClass('fa-plus-circle fa-minus-circle')
    .addClass(isOpen ? 'fa-minus-circle' : 'fa-plus-circle');
  $btn.attr('aria-expanded', isOpen ? 'true' : 'false');
}

$('#project-table').on('click', 'a.details-control', function () {
  var $btn = $(this);
  var tr   = $btn.closest('tr');
  var row  = table.row(tr);
  var encId = $btn.data('id');

  if (row.child.isShown()) {
    // close
    row.child.hide();
    tr.removeClass('shown');
    setExpanderIcon($btn, false);
    return;
  }

  // open + loading
  row.child('<div class="p-2">Loading subprojects...</div>').show();
  tr.addClass('shown');
  setExpanderIcon($btn, true);

  $.get("{{ route('projects.subprojects', ':id') }}".replace(':id', encId))
    .done(function (res) {
      row.child(res.html || '<div class="p-2">No subprojects.</div>').show();
    })
    .fail(function () {
      row.child('<div class="p-2 text-danger">Failed to load subprojects.</div>').show();
    });
});

// optional: after a redraw, reset all expanders to "closed" icon
$('#project-table').on('draw.dt', function () {
  $('#project-table a.details-control').each(function () {
    setExpanderIcon($(this), false);
  });
});

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


  // Auto-dismiss flash
  setTimeout(function () { $(".alert-block").remove(); }, 5000);
  
</script>
@endsection
