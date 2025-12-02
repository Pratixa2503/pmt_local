@php
  $hms = function (int $sec) {
    $sec = max(0, $sec);
    $h = intdiv($sec, 3600);
    $m = intdiv($sec % 3600, 60);
    $s = $sec % 60;
    return sprintf('%02d:%02d:%02d', $h,$m,$s);
  };
@endphp

@if(($todayRows->count() ?? 0) > 0)
  <tfoot id="todayTfoot">
    <tr class="table-light">
      <th colspan="4" class="text-end">Total</th>
      <th class="text-end">{{ $hms((int)$secondsToday) }}</th>
    </tr>
  </tfoot>
@else
  <tfoot id="todayTfoot"></tfoot>
@endif
