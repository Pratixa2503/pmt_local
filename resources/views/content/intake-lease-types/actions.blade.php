<div class="d-flex justify-content-center">
  @can('edit intake lease type')
    <a href="{{ route('intake-lease-types.edit', $enc) }}" class="text-primary me-2" data-bs-toggle="tooltip" title="Edit">
      <i class="ti ti-edit ti-sm"></i>
    </a>
  @endcan

  @can('delete intake lease type')
    <a href="javascript:void(0);" class="text-danger delete-intake-lease-type" data-id="{{ $enc }}" data-bs-toggle="tooltip" title="Delete">
      <i class="ti ti-trash ti-sm"></i>
    </a>
  @endcan
</div>
