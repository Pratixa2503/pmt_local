<div class="d-flex justify-content-center">
  @can('edit intake work type')
    <a href="{{ route('intake-work-types.edit', $enc) }}" class="text-primary me-2" data-bs-toggle="tooltip" title="Edit">
      <i class="ti ti-edit ti-sm"></i>
    </a>
  @endcan

  @can('delete intake work type')
    <a href="javascript:void(0);" class="text-danger delete-intake-work-type" data-id="{{ $enc }}" data-bs-toggle="tooltip" title="Delete">
      <i class="ti ti-trash ti-sm"></i>
    </a>
  @endcan
</div>
