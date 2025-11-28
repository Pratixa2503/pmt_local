@php
  $hms = function (int $sec) {
    $sec = max(0, $sec);
    $h = intdiv($sec, 3600);
    $m = intdiv($sec % 3600, 60);
    $s = $sec % 60;
    return sprintf('%02d:%02d:%02d', $h,$m,$s);
  };
@endphp

<tbody id="todayTbody">
  @forelse($todayRows as $r)
    <tr>
      <td>{{ $r->start_time ? \Carbon\Carbon::parse($r->start_time)->format('h:i A') : '-' }}</td>
      <td>{{ $r->end_time ? \Carbon\Carbon::parse($r->end_time)->format('h:i A') : '-' }}</td>
      <td>{{ $r->main_task }}</td>
      <td>{{ $r->sub_task }}</td>
      <td class="text-end">{{ $hms((int)$r->seconds_today) }}</td>
    </tr>
  @empty
    <tr><td colspan="3" class="text-muted">No work recorded yet today.</td></tr>
  @endforelse
</tbody>
