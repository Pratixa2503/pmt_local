@extends('layouts/layoutMaster')

@section('title', 'Collaboration')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="mb-0">My Project Conversations</h5>
  </div>
  <div class="card-body">
    <ul class="list-group">
      @forelse($conversations as $c)
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span>{{ $c->project->project_name }}</span>
          <a href="{{ route('collab.project.conversation', Crypt::encryptString($c->project_id)) }}"
             class="btn btn-sm btn-primary">
            Open
          </a>
        </li>
      @empty
        <li class="list-group-item text-muted">No conversations available.</li>
      @endforelse
    </ul>
  </div>
</div>
@endsection
