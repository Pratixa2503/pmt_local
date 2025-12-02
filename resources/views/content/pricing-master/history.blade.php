@extends('layouts.layoutMaster')
@section('title', 'Pricing History')

@section('content')

<div class="card">
    <div class="card-header"><h5>Activity Log for {{ $pricing->name ?? 'Pricing Master' }}</h5></div>
    <div class="card-body">
        @foreach($activities as $activity)
        
    <div class="card mb-2">
        <div class="card-body">
            <strong>{{ ucfirst($activity->description) }}</strong><br>
           
            <small class="text-muted">
                By: {{ optional($activity->causer)->first_name ?? 'System' }} {{ optional($activity->causer)->last_name ?? '' }} at {{ $activity->created_at->format('d M Y H:i') }}
            </small>
            <ul class="mt-2">
                @foreach($activity->properties['attributes'] ?? [] as $key => $newValue)
                   @continue($key == "pending_changes")
                    @php
                        $oldValue = $activity->properties['old'][$key] ?? null;
                        $oldDisplay = Helper::getDisplayValue($key, $oldValue);
                        $newDisplay =  Helper::getDisplayValue($key, $newValue);
                    @endphp
                    <li>
                        <strong>{{ ucwords(str_replace('_id', ' ', $key)) }}</strong>: 
                        {{ $oldDisplay }} → <span class="text-success">{{ $newDisplay }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endforeach
        <!-- <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Description</th>
                    <th>Changes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $activity)
                    <tr>
                        <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $activity->causer?->name ?? 'System' }}</td>
                        <td>{{ $activity->description }}</td>
                        <td>
                            @if($activity->properties->has('attributes'))
                                <strong>New:</strong>
                                <ul>
                                    @foreach($activity->properties['attributes'] as $key => $val)
                                        <li>{{ $key }}: {{ $val }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if($activity->properties->has('old'))
                                <strong>Old:</strong>
                                <ul>
                                    @foreach($activity->properties['old'] as $key => $val)
                                        <li>{{ $key }}: {{ $val }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No activity log found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table> -->
    </div>
</div>
@endsection
