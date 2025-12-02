@extends('layouts/layoutMaster')

@section('title', $title ?? 'Project Tracking (Admin)')

@section('vendor-script')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-style')
<style>
  .hint { font-size:.85rem; color:#6c757d; }
  .metric { font-weight:600; }
  .table-sm td, .table-sm th { padding: .45rem .6rem; }
  .progress { height:.6rem; }
  .sticky-top-lite { position: sticky; top: 0; background: #fff; z-index: 2; }
</style>
@endsection

@section('content')
@php
  $hms = function (int $sec) {
    $sec = max(0, $sec);
    $h = intdiv($sec, 3600);
    $m = intdiv($sec % 3600, 60);
    $s = $sec % 60;
    return sprintf('%02d:%02d:%02d', $h,$m,$s);
  };
@endphp

<div class="row">
  <div class="col-12 col-xxl-11 mx-auto">
    <div class="card shadow-sm">

      {{-- Header / Toolbar --}}
      <div class="card-header sticky-top-lite bg-white d-flex flex-wrap gap-2 justify-content-between align-items-center">
        <div class="me-2">
          <h5 class="text-dark mb-1 d-flex align-items-center">
            <i class="ti ti-clock-hour-4 me-2 text-primary"></i>
            Admin Tracking — {{ $project->project_name }}
          </h5>
          <div class="small text-muted">
            Today: {{ $todayDateLocal }} · {{ $appTz }}
          </div>
        </div>

        <div class="d-flex flex-wrap gap-2 align-items-center">
          <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-chevron-left me-1"></i> Back
          </a>

          @php
            $exportUrl = route('projects.admin.tracking.export', $encryptedId) . '?' . http_build_query([
              'user_id'    => $filterUserId,
              'start_date' => $startDate ?? '',
              'end_date'   => $endDate ?? '',
            ]);
          @endphp
          <a class="btn btn-success" href="{{ $exportUrl }}">
            <i class="ti ti-download me-1"></i> Export CSV
          </a>
        </div>
      </div>

      <div class="card-body">

        {{-- Inline Filters --}}
        <form method="GET" class="row g-2 g-sm-3 align-items-end mb-3">
          <div class="col-12 col-md-4 col-lg-3">
            <label class="form-label mb-1">User</label>
            <select name="user_id" class="form-select">
              <option value="">All users</option>
              @foreach($projectUsers as $u)
                <option value="{{ $u->id }}" {{ (int)($filterUserId ?? 0) === (int)$u->id ? 'selected' : '' }}>
                  {{ $u->first_name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-6 col-md-3 col-lg-2">
            <label class="form-label mb-1">Start date</label>
            <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="form-control">
          </div>

          <div class="col-6 col-md-3 col-lg-2">
            <label class="form-label mb-1">End date</label>
            <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="form-control">
          </div>

          <div class="col-6 col-md-2 col-lg-2">
            <button class="btn btn-primary w-100">
              <i class="ti ti-filter me-1"></i> Apply
            </button>
          </div>

          <div class="col-6 col-md-2 col-lg-2">
            <a href="{{ route('projects.admin.tracking', $encryptedId) }}" class="btn btn-outline-secondary w-100">
              Reset
            </a>
          </div>
        </form>

        {{-- Active filter chips --}}
        @if(($filterUserId ?? null) || ($startDate ?? null) || ($endDate ?? null))
          <div class="mb-3 d-flex flex-wrap gap-2">
            <span class="badge bg-light text-dark border">
              <i class="ti ti-filter me-1"></i> Filters:
            </span>
            @if($filterUserId ?? null)
              @php
                $activeUser = $projectUsers->firstWhere('id', (int)$filterUserId);
              @endphp
              <span class="badge bg-secondary">
                User: {{ $activeUser?->first_name ?? $filterUserId }}
              </span>
            @endif
            @if($startDate ?? null)
              <span class="badge bg-secondary">Start: {{ $startDate }}</span>
            @endif
            @if($endDate ?? null)
              <span class="badge bg-secondary">End: {{ $endDate }}</span>
            @endif
          </div>
        @endif

        {{-- Your table/content continues here… --}}

      </div>
    </div>
  </div>
</div>


</form>


        <div class="row g-3">
          {{-- Left: Live & Today --}}
          <div class="col-lg-8">
            {{-- Running Sessions --}}
            <div class="border rounded p-3 mb-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Currently Running</h6>
                <div id="now" class="text-muted small"></div>
              </div>

              <div class="table-responsive">
                <table class="table table-sm align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>User</th>
                      <th>Main Task</th>
                      <th>Sub Task</th>
                      <th class="text-center">Started</th>
                      <th class="text-end">Elapsed</th>
                    </tr>
                  </thead>
                  <tbody id="runningBody">
                    @forelse($running as $r)
                      @php $rowId = 'r_'.$r['task_item_id']; @endphp
                      <tr id="{{ $rowId }}" data-start="{{ $r['started_at'] }}" data-base="{{ (int)$r['total_seconds'] }}">
                        <td>{{ $r['user_name'] }}</td>
                        <td>{{ $r['main_task_name'] }}</td>
                        <td>{{ $r['sub_task_name'] }}</td>
                        <td class="text-center">
                          {{ \Carbon\Carbon::parse($r['started_at'])->timezone($appTz)->format('H:i') }}
                        </td>
                        <td class="text-end"><span class="elapsed">—</span></td>
                      </tr>
                    @empty
                      <tr><td colspan="5" class="text-muted">No active sessions.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            {{-- Today’s Activity --}}
            <div class="border rounded p-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Today’s Tasks (Aggregated)</h6>
                <span class="badge bg-label-primary">{{ $todayRows->count() }}</span>
              </div>

              <div class="table-responsive">
                <table class="table table-sm align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>User</th>
                      <th>Start Time</th>
                      <th>End Time</th>
                      <th>Main Task</th>
                      <th>Sub Task</th>
                      <th class="text-end">Time (hh:mm:ss)</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($todayRows as $r)
                      <tr>
                        <td>{{ $r->user_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->start_time)->format('m-d-Y H:i:s') }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->end_time)->format('m-d-Y H:i:s') }}</td>
                        <td>{{ $r->main_task }}</td>
                        <td>{{ $r->sub_task }}</td>
                        <td class="text-end">{{ $hms((int)$r->seconds_today) }}</td>
                      </tr>
                    @empty
                      <tr><td colspan="4" class="text-muted">No work recorded yet today.</td></tr>
                    @endforelse
                  </tbody>
                  @if($todayRows->count())
                  <tfoot>
                    <tr class="table-light">
                      <th colspan="5" class="text-end">Total</th>
                      <th class="text-end">{{ $hms((int)$secondsToday) }}</th>
                    </tr>
                  </tfoot>
                  @endif
                </table>
              </div>
            </div>
          </div>

          {{-- Right: Summary & Paused --}}
          <div class="col-lg-4">
            {{-- Today Summary --}}
            <div class="border rounded p-3">
              <h6 class="mb-2">Today ({{ $todayDateLocal }})</h6>

              <div class="d-flex justify-content-between">
                <span class="text-muted">Total Spent</span>
                <span class="metric">{{ $hms((int)$secondsToday) }}</span>
              </div>
              <div class="d-flex justify-content-between mt-1">
                <span class="text-muted">Target</span>
                <span class="metric">{{ $hms((int)$targetSeconds) }}</span>
              </div>
              <div class="d-flex justify-content-between mt-1">
                <span class="text-muted">Remaining</span>
                <span class="metric">{{ $hms((int)$remainingSeconds) }}</span>
              </div>

              @php
                $pct = $targetSeconds > 0 ? round(min(100, ($secondsToday / $targetSeconds) * 100)) : 0;
              @endphp
              <div class="progress mt-2">
                <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                  {{ $pct }}%
                </div>
              </div>
              <div class="hint mt-2">Auto-refresh to update numbers.</div>
            </div>

            {{-- Paused --}}
            <div class="border rounded p-3 mt-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Paused Items</h6>
                <span class="badge bg-label-warning">{{ $pausedItems->count() }}</span>
              </div>

              <div class="table-responsive">
                <table class="table table-sm align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>User</th>
                      <th>Main</th>
                      <th>Sub</th>
                      <th class="text-end">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($pausedItems as $p)
                      <tr>
                        <td>{{ $p['user_name'] }}</td>
                        <td>{{ $p['main_task_name'] }}</td>
                        <td>{{ $p['sub_task_name'] }}</td>
                        <td class="text-end">{{ $hms((int)$p['total_seconds']) }}</td>
                      </tr>
                    @empty
                      <tr><td colspan="4" class="text-muted">No paused tasks.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>

      </div> {{-- /card-body --}}
    </div>
  </div>
</div>
@endsection

@section('extra-script')
<script>
(function(){
  // Header clock
  const $now = document.getElementById('now');
  const tickClock = () => { if ($now) $now.textContent = new Date().toLocaleString(); };
  setInterval(tickClock, 1000); tickClock();

  // Live elapsed for running sessions
  function hms(sec){ sec=Math.max(0,Number(sec||0)); const h=Math.floor(sec/3600), m=Math.floor((sec%3600)/60), s=Math.floor(sec%60); return [h,m,s].map(v=>String(v).padStart(2,'0')).join(':'); }
  function startRowTimers(){
    document.querySelectorAll('#runningBody tr[id^="r_"]').forEach(tr=>{
      const startIso = tr.getAttribute('data-start');
      const base     = Number(tr.getAttribute('data-base') || 0);
      const $el      = tr.querySelector('.elapsed');
      if(!startIso || !$el) return;
      const startMs  = new Date(startIso).getTime();
      const update   = ()=>{ const sec = Math.floor((Date.now() - startMs) / 1000) + base; $el.textContent = hms(sec); };
      update();
      setInterval(update, 1000);
    });
  }
  startRowTimers();
})();
</script>
@endsection
