@if($subs->isEmpty())
  <div class="p-2 text-muted">No subprojects.</div>
@else
  <div class="p-2">
    <table class="table table-sm table-bordered mb-0">
      <thead>
        <tr>
          <th>Subproject</th>
          <th>Customer</th>
          <!-- <th>Type</th> -->
          <th>Status</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($subs as $sp)
          @php $encId = Crypt::encryptString($sp->id); @endphp
          <tr>
            <td>{{ $sp->project_name }}</td>
            <td>{{ $sp->customer_name }}</td>
            <!-- <td>{{ $sp->project_type_name }}</td> -->
            <td>{{ $sp->status_name ?? '-' }}</td>
            <td class="text-center">
              @if(auth()->check() && auth()->user()->hasRole('super admin'))
                @php
                  $url   = route('projects.admin.tracking', $encId);
                  $title = 'Admin Tracking';
                  $icon  = 'fa-solid fa-user-shield';
                @endphp

               
                <a href="{{ $url }}" title="{{ $title }}">
                  <i class="{{ $icon }}"></i>
                </a>
               
              @endif

              @can('create project')
                  <a href="{{ route('projects.fileView', ['parent' => $encId]) }}" class="me-2">
                      <i class="fa-solid fa-file-excel" title="View Files"></i>
                  </a>
              @endcan
              
              @can('edit project')
                <a href="{{ route('projects.edit', $encId) }}">
                  <i class="fa-solid fa-pen-to-square" title="Edit"></i>
                </a>
              @endcan

              {{-- Delete --}}
              @can('delete project')
                <a href="javascript:void(0)" data-id="{{ $encId }}" class="delete-project">
                  <i class="fa-solid fa-trash" title="Delete"></i>
                </a>
              @endcan
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endif
