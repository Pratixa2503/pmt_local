@extends('layouts/layoutMaster')

@section('title', $title ?? 'Invoices')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/css/dataTables.bootstrap5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/css/responsive.dataTables.css') }}">
@endsection

@section('vendor-script')
  <script src="{{ asset('assets/vendor/libs/datatables-bs5/js/dataTables.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-bs5/js/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/js/dataTables.responsive.js') }}"></script>
@endsection

@section('content')
@can('list invoice')
<div class="card mb-3">
  <div class="card-header">
    <h4 class="mb-0">Invoices</h4>
  </div>
  <div class="card-body">
    {{-- Filters --}}
    <div class="row g-2 mb-3">
      <div class="col-md-3">
        <label class="form-label mb-1">Billing Month</label>
        <input type="month" id="filter_month" class="form-control"
               value="{{ request('month', now()->format('Y-m')) }}">
      </div>
      <div class="col-md-4">
        <label class="form-label mb-1">Project (optional)</label>
        <select id="filter_project" class="form-select">
          <option value="">All</option>
          @foreach($projects as $p)
            <option value="{{ $p->id }}">{{ $p->project_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label mb-1">Customer (optional)</label>
        <select id="filter_customer" class="form-select">
          <option value="">All</option>
          @foreach($customers as $c)
            <option value="{{ $c->id }}">{{ $c->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-1 d-flex align-items-end">
        <button type="button" id="btnFilter" class="btn btn-primary w-100">Go</button>
      </div>
    </div>

    {{-- The DataTable --}}
    {!! $dataTable->table(['class' => 'table table-striped w-100', 'style' => 'width:100%']) !!}
  </div>
</div>
@endcan
@endsection

@section('extra-script')
  {!! $dataTable->scripts() !!}

  <script>
    document.getElementById('btnFilter').addEventListener('click', function () {
      // refresh table with current filter values (DataTables service uses the ajax data function)
      window.LaravelDataTables['invoice-table'].ajax.reload();
    });

    // Also reload when month/project/customer changes (optional)
    ['filter_month','filter_project','filter_customer'].forEach(function(id){
      document.getElementById(id).addEventListener('change', function(){
        window.LaravelDataTables['invoice-table'].ajax.reload();
      });
    });
  </script>
@endsection
