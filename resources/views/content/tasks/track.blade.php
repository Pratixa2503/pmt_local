@extends('layouts/layoutMaster')

@section('title', $title ?? 'Task Tracker')

@section('vendor-script')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-style')
<style>
  .hint { font-size:.85rem; color:#6c757d; }
  .metric { font-weight:600; }
  .table-sm td, .table-sm th { padding: .4rem .5rem; }
  .progress { height:.6rem; }
  .tasks-table .table-light tr th {
    background: #f1f0f2;
    color: #5d596c !important;
    vertical-align: middle;
  }
  .tasks-table .table-light .text-end {
    background: #f1f0f2;
    color: #5d596c !important;
    vertical-align: middle;
  }
  .start-btn { height: 38px; }
  .tasks-table tbody tr td { border-bottom: 1px solid #eee !important; }
<<<<<<< HEAD
  /* Ensure pause button is fully hidden when JS toggles .d-none */
=======
>>>>>>> 9d9ed85b (for cleaner setup)
  .d-none { display: none !important; }
</style>
@endsection

@section('content')
@php
  // Safe defaults
  $pausedItems         = $pausedItems         ?? collect();
  $todayRows           = $todayRows           ?? collect();
  $productiveMainTasks = $productiveMainTasks ?? collect();
  $generalMainTasks    = $generalMainTasks    ?? collect();
  $appTz               = $appTz               ?? config('app.timezone');
  $secondsToday        = (int)($secondsToday  ?? 0);
  $targetSeconds       = (int)($targetSeconds ?? 32400);
  $remainingSeconds    = (int)($remainingSeconds ?? 0);
  $activeTaskType      = $activeTaskType      ?? null; // 1=Productive, 2=General
  $activeMainTaskId    = $activeMainTaskId    ?? null;
<<<<<<< HEAD
  $activeSubTaskId     = $activeSubTaskId     ?? null; // <-- make sure controller sets this when running
  $resolvedType = (int) old('task_type', $activeTaskType ?? 1);
  // Helper
=======
  $activeSubTaskId     = $activeSubTaskId     ?? null;
  $resolvedType = (int) old('task_type', $activeTaskType ?? 1);

>>>>>>> 9d9ed85b (for cleaner setup)
  $hms = function (int $sec) {
    $sec = max(0, $sec);
    $h = intdiv($sec, 3600);
    $m = intdiv($sec % 3600, 60);
    $s = $sec % 60;
    return sprintf('%02d:%02d:%02d', $h,$m,$s);
  };

  $isRunning = !empty($currentRunning);
@endphp

<div class="row">
  <div class="col-xl-12 col-lg-11 mx-auto">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <h4 class="text-dark mb-0">Project: {{ $project->project_name }}</h4>
        </div>
        <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
          <i class="ti ti-chevron-left me-1"></i> Back
        </a>
      </div>

      <div class="card-body">
        {{-- Selectors --}}
        <div class="row g-3">
          {{-- Task Type --}}
          <div class="col-md-3">
<<<<<<< HEAD
            <label class="form-label d-block">Task Type <span class="text-danger">*</span></label>
              <div class="form-check form-check-inline">
              <input class="form-check-input"
                    type="radio"
                    name="task_type"
                    id="taskType1"
                    value="1"
                    {{ $resolvedType === 1 ? 'checked' : '' }}
                    {{ $isRunning ? 'disabled' : '' }}>
              <label class="form-check-label" for="taskType1">Productive</label>
=======
            <label class="form-label d-block">Project Tracker <span class="text-danger">*</span></label>
            <div class="form-check form-check-inline">
              <input class="form-check-input"
                     type="radio"
                     name="task_type"
                     id="taskType1"
                     value="1"
                     {{ $resolvedType === 1 ? 'checked' : '' }}
                     {{ $isRunning ? 'disabled' : '' }}>
              <label class="form-check-label" for="taskType1">Production</label>
>>>>>>> 9d9ed85b (for cleaner setup)
            </div>

            <div class="form-check form-check-inline">
              <input class="form-check-input"
<<<<<<< HEAD
                    type="radio"
                    name="task_type"
                    id="taskType2"
                    value="2"
                    {{ $resolvedType === 2 ? 'checked' : '' }}
                    {{ $isRunning ? 'disabled' : '' }}>
              <label class="form-check-label" for="taskType2">General</label>
            </div>
          </div>

          {{-- Productive Main Task (default visible) --}}
=======
                     type="radio"
                     name="task_type"
                     id="taskType2"
                     value="2"
                     {{ $resolvedType === 2 ? 'checked' : '' }}
                     {{ $isRunning ? 'disabled' : '' }}>
              <label class="form-check-label" for="taskType2">Non Production</label>
            </div>
          </div>

          {{-- Productive Main Task --}}
>>>>>>> 9d9ed85b (for cleaner setup)
          <div class="col-md-3 main_task" id="wrapProductive">
            <label class="form-label">Main Task <span class="text-danger">*</span></label>
            <select id="mainTask" class="form-select" {{ $isRunning ? 'disabled' : '' }}>
              <option value="">Select a main task</option>
              @foreach($productiveMainTasks as $id => $name)
                <option value="{{ $id }}" {{ ($activeMainTaskId && $activeTaskType == 1 && $activeMainTaskId == $id) ? 'selected' : '' }}>
                  {{ $name }}
                </option>
              @endforeach
            </select>
          </div>

<<<<<<< HEAD
          {{-- General Main Task (hidden initially) --}}
=======
          {{-- General Main Task --}}
>>>>>>> 9d9ed85b (for cleaner setup)
          <div class="col-md-3 general_task d-none" id="wrapGeneral">
            <label class="form-label">General <span class="text-danger">*</span></label>
            <select id="generalMainTask" class="form-select" {{ $isRunning ? 'disabled' : 'disabled' }}>
              <option value="">Select a main task</option>
              @foreach($generalMainTasks as $id => $name)
                <option value="{{ $id }}" {{ ($activeMainTaskId && $activeTaskType == 2 && $activeMainTaskId == $id) ? 'selected' : '' }}>
                  {{ $name }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Sub Task --}}
          <div class="col-md-3">
            <label class="form-label">Sub Task <span class="text-danger">*</span></label>
            <select id="subTask" class="form-select" {{ $isRunning ? '' : 'disabled' }}>
              <option value="">Select a sub task</option>
            </select>
            <div id="subMeta" class="hint mt-1"></div>
          </div>

          {{-- Start --}}
          <div class="col-md-3 d-flex align-items-top pt-4">
            <button id="btnStart" class="btn btn-primary w-100 start-btn" {{ $isRunning ? 'disabled' : 'disabled' }}>
              <i class="ti ti-player-play me-1"></i> Start
            </button>
          </div>
        </div>

        <hr class="my-4">

<<<<<<< HEAD
=======
        <div id="sessionHistory" style="border: 1px solid #ccc; padding: 15px; max-height: 400px; overflow-y: auto;">
            <h6 class="mb-0">Current Count</h6>
            <p>Loading history...</p>
        </div>

        <hr class="my-4">

>>>>>>> 9d9ed85b (for cleaner setup)
        <div class="row g-3">
          <div class="col-lg-8">
            {{-- Current Running --}}
            <div class="border rounded p-3 mb-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
<<<<<<< HEAD
                <h6 class="mb-0">Current Session</h6>
=======
                <h6 class="mb-0">Current Session Test</h6>
>>>>>>> 9d9ed85b (for cleaner setup)
                <div id="now" class="text-muted small"></div>
              </div>
              <div id="currentStatus" class="text-muted">No running task.</div>

              <div id="elapsedWrap" class="mt-2 d-none">
                <span class="text-muted">Elapsed:</span>
                <span id="elapsed" class="metric">00:00:00</span>
              </div>
            </div>

            {{-- Paused Tasks --}}
            <div class="border rounded p-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Paused Tasks</h6>
                <span id="pausedCount" class="badge bg-label-warning">{{ $pausedItems->count() }}</span>
              </div>
              <div class="table-responsive">
                <table class="table table-sm align-middle tasks-table" id="pausedTable">
                  <thead class="table-light">
                    <tr>
                      <th style="width:40%">Main Task</th>
                      <th style="width:40%">Sub Task</th>
                      <th class="text-center">Total (hh:mm:ss)</th>
                      <th class="text-end">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($pausedItems as $p)
                      @php $sec = (int)($p['total_seconds'] ?? 0); @endphp
                      <tr data-id="{{ $p['task_item_id'] }}">
                        <td>{{ $p['main_task_name'] }}</td>
                        <td>{{ $p['sub_task_name'] }}</td>
                        <td class="text-center" data-seconds="{{ $sec }}">{{ $hms($sec) }}</td>
                        <td class="text-end">
                          <button class="btn btn-success btn-sm btnResume" title="Resume"><i class="ti ti-player-play"></i></button>
                          <button class="btn btn-outline-danger btn-sm btnEndPaused ms-1" title="End"><i class="ti ti-player-stop"></i></button>
                        </td>
                      </tr>
                    @empty
                      <tr class="no-paused"><td colspan="4" class="text-muted">No paused tasks.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            {{-- Today’s Tasks --}}
            <div class="border rounded p-3 mt-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Today’s Tasks</h6>
                <span class="badge bg-label-primary">{{ $todayRows->count() }}</span>
              </div>
              <div class="table-responsive">
                <table class="table table-sm align-middle tasks-table">
                  <thead class="table-light">
                    <tr>
<<<<<<< HEAD
=======
                      <th>Start Time</th>
                      <th>Emd Time</th>
>>>>>>> 9d9ed85b (for cleaner setup)
                      <th style="width:40%">Main Task</th>
                      <th style="width:40%">Sub Task</th>
                      <th class="text-end">Time (hh:mm:ss)</th>
                    </tr>
                  </thead>
<<<<<<< HEAD
                  <tbody>
                    @forelse($todayRows as $r)
                      <tr>
=======
                  <tbody id="todayTbody">
                    @forelse($todayRows as $r)
                      <tr>
                        <td>{{ $r->start_time ? \Carbon\Carbon::parse($r->start_time)->format('h:i A') : '-' }}</td>
                        <td>{{ $r->end_time ? \Carbon\Carbon::parse($r->end_time)->format('h:i A') : '-' }}</td>
>>>>>>> 9d9ed85b (for cleaner setup)
                        <td>{{ $r->main_task }}</td>
                        <td>{{ $r->sub_task }}</td>
                        <td class="text-end">{{ $hms((int)$r->seconds_today) }}</td>
                      </tr>
                    @empty
<<<<<<< HEAD
                      <tr><td colspan="3" class="text-muted">No work recorded yet today.</td></tr>
                    @endforelse
                  </tbody>
                  @if($todayRows->count())
                  <tfoot>
                    <tr class="table-light">
                      <th colspan="2" class="text-end">Total</th>
=======
                      <tr><td colspan="5" class="text-muted">No work recorded yet today.</td></tr>
                    @endforelse
                  </tbody>
                  @if($todayRows->count())
                  <tfoot id="todayTfoot">
                    <tr class="table-light">
                      <th colspan="4" class="text-end">Total</th>
>>>>>>> 9d9ed85b (for cleaner setup)
                      <th class="text-end">{{ $hms($secondsToday) }}</th>
                    </tr>
                  </tfoot>
                  @endif
                </table>
              </div>
            </div>
          </div>

          {{-- Controls + Today Summary --}}
          <div class="col-lg-4">
            <div class="border rounded p-3">
              <h6 class="mb-3">Controls</h6>
              <div class="d-grid gap-2">
<<<<<<< HEAD
                {{-- Pause is hidden if Task Type is General (handled by JS + init) --}}
                <button id="btnPause" class="btn btn-warning {{ ($activeTaskType ?? 1) == 2 ? 'd-none' : '' }}" {{ $isRunning ? '' : 'disabled' }}>
                  <i class="ti ti-player-pause me-1"></i> Pause
=======
                <button id="btnPause" class="btn btn-warning {{ ($activeTaskType ?? 1) == 2 ? 'd-none' : '' }}" {{ $isRunning ? '' : 'disabled' }}>
                  <i class="ti ti-player-pause me-1"></i> Pause this
>>>>>>> 9d9ed85b (for cleaner setup)
                </button>
                <button id="btnEnd" class="btn btn-danger" {{ $isRunning ? '' : 'disabled' }}>
                  <i class="ti ti-player-stop me-1"></i> End
                </button>
              </div>
            </div>

            <div class="border rounded p-3 mt-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Today ({{ $todayDateLocal ?? '' }})</h6>
                <span class="badge bg-label-info">{{ $appTz }}</span>
              </div>

              <div class="d-flex justify-content-between">
                <span class="text-muted">Spent</span>
                <span class="metric">{{ $hms($secondsToday) }}</span>
              </div>

              <div class="d-flex justify-content-between mt-1">
                <span class="text-muted">Target</span>
                <span class="metric">{{ $hms($targetSeconds) }}</span>
              </div>

              <div class="d-flex justify-content-between mt-1">
                <span class="text-muted">Remaining</span>
                <span class="metric">{{ $hms($remainingSeconds) }}</span>
              </div>

              @php
                $pct = $targetSeconds > 0 ? round(min(100, ($secondsToday / $targetSeconds) * 100)) : 0;
              @endphp
              <div class="progress mt-2">
                <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                  {{ $pct }}%
                </div>
              </div>

              <div class="hint mt-2">Updated on refresh.</div>
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
$(function () {
<<<<<<< HEAD
=======

  // 1) Add this near your other route constants:
const TODAY_URL = "{{ route('taskitems.today', $encryptedId) }}";

// 2) Helper to refresh "Today’s Tasks"
function refreshTodaysTasks(){
  $.get(TODAY_URL)
    .done(function(res){
      if(res && res.tbody){
        // Replace tbody
        const $oldBody = $('#todayTbody');
        const $newBody = $(res.tbody);
        $oldBody.replaceWith($newBody);
      }
      if(res && res.tfoot !== undefined){
        // Replace (or clear) tfoot
        const $oldFoot = $('#todayTfoot');
        const $newFoot = $(res.tfoot);
        $oldFoot.replaceWith($newFoot);
      }
    })
    .fail(function(){
      console.warn('Failed to refresh Today’s Tasks.');
    });
}


>>>>>>> 9d9ed85b (for cleaner setup)
  const routes = {
    subtasksByMain: (mainId) => "{{ url('subtasks/by-main') }}/" + mainId,
    start:  "{{ route('taskitems.start') }}",
    pause:  "{{ route('taskitems.pause') }}",
    resume: "{{ route('taskitems.resume') }}",
    end:    "{{ route('taskitems.end') }}",
<<<<<<< HEAD
=======
    getSessionHistory: "{{ route('getSessionHistory') }}"
>>>>>>> 9d9ed85b (for cleaner setup)
  };

  const projectId = {{ (int)$project->id }};
  const csrf = '{{ csrf_token() }}';

  // From server
<<<<<<< HEAD
  const ACTIVE_TASK_TYPE   = @json($activeTaskType);     // 1|2|null
  const ACTIVE_MAIN_TASKID = @json($activeMainTaskId);   // int|null
  const ACTIVE_SUB_TASKID  = @json($activeSubTaskId);    // int|null
=======
  const ACTIVE_TASK_TYPE   = @json($activeTaskType);   // 1|2|null
  const ACTIVE_MAIN_TASKID = @json($activeMainTaskId); // int|null
  const ACTIVE_SUB_TASKID  = @json($activeSubTaskId);  // int|null
>>>>>>> 9d9ed85b (for cleaner setup)
  const INIT_IS_RUNNING    = {!! $isRunning ? 'true' : 'false' !!};

  let isRunningState = INIT_IS_RUNNING;
  let currentTaskItemId = null;
<<<<<<< HEAD
  let startedAtISO = null;  // last session started_at (ISO)
  let baseSeconds  = 0;     // continue-from
  let tickTimer    = null;

  // Elements (the main select id can swap between productive/general)
=======
  let startedAtLocal = null;  // last session start (LOCAL string)
  let baseSeconds  = 0;       // continue-from seconds
  let tickTimer    = null;

  // STRICT LOCAL PARSER:
  // - "YYYY-MM-DD HH:MM:SS" → interpret as LOCAL (do NOT add Z)
  // - If string has Z or ±HH:MM, let browser handle it
  function parseServerDateToMs(input) {
    if (!input) return Date.now();
    if (typeof input === 'number') return input;
    if (input instanceof Date) return input.getTime();

    const raw = String(input).trim();

    if (/[Zz]$/.test(raw) || /[+\-]\d{2}:\d{2}$/.test(raw)) {
      return Date.parse(raw.replace(' ', 'T'));
    }

    if (/^\d{4}-\d{2}-\d{2}[ T]\d{2}:\d{2}:\d{2}$/.test(raw)) {
      return new Date(raw.replace(' ', 'T')).getTime(); // LOCAL
    }

    const ms = Date.parse(raw.replace(' ', 'T'));
    return isNaN(ms) ? Date.now() : ms;
  }

  // Elements
>>>>>>> 9d9ed85b (for cleaner setup)
  const $sub = $('#subTask'), $meta = $('#subMeta');
  const $btnStart = $('#btnStart'), $btnPause = $('#btnPause'), $btnEnd = $('#btnEnd');
  const $status = $('#currentStatus'), $elapsedW = $('#elapsedWrap'), $elapsed = $('#elapsed'), $now = $('#now');
  const $pausedTable = $('#pausedTable tbody'), $pausedCount = $('#pausedCount');

<<<<<<< HEAD
  // Helper to always fetch the current visible main select
  const $main = () => $('#mainTask');

  // clock for current-session header
  function setNowClock(){ $now.text(new Date().toLocaleString()); }
  setInterval(setNowClock, 1000); setNowClock();

  // time helpers
  function hms(sec){ sec=Math.max(0,Number(sec||0)); const h=Math.floor(sec/3600), m=Math.floor((sec%3600)/60), s=Math.floor(sec%60); return [h,m,s].map(v=>String(v).padStart(2,'0')).join(':'); }
  function stopTick(){ if (tickTimer) { clearInterval(tickTimer); tickTimer=null; } }
  function startTick(fromIso, already=0){
    stopTick();
    const startMs = new Date(fromIso).getTime();
    $elapsed.text(hms(already));
    tickTimer = setInterval(()=>{ const e=Math.floor((Date.now()-startMs)/1000)+(already||0); $elapsed.text(hms(e)); },1000);
  }

  // Show/hide Pause based on current Task Type (1=Productive show; 2=General hide)
  function updatePauseVisibility(currentType) {
    if (String(currentType) === '2') {
      $btnPause.addClass('d-none');
    } else {
      $btnPause.removeClass('d-none');
    }
  }

=======
  const $main = () => $('#mainTask');

  // Live clock (local)
  function setNowClock(){ $now.text(new Date().toLocaleString()); }
  setInterval(setNowClock, 1000); setNowClock();

  // Time helpers
  function hms(sec){ sec=Math.max(0,Number(sec||0)); const h=Math.floor(sec/3600), m=Math.floor((sec%3600)/60), s=Math.floor(sec%60); return [h,m,s].map(v=>String(v).padStart(2,'0')).join(':'); }
  function stopTick(){ if (tickTimer) { clearInterval(tickTimer); tickTimer=null; } }

  function startTick(fromLocalString, already = 0) {
    stopTick();
    const startMs = parseServerDateToMs(fromLocalString);
    $elapsed.text(hms(already));
    tickTimer = setInterval(() => {
      const e = Math.floor((Date.now() - startMs) / 1000) + (already || 0);
      $elapsed.text(hms(e));
    }, 1000);
  }

  // Pause visibility by task type
  function updatePauseVisibility(currentType) {
    if (String(currentType) === '2') $btnPause.addClass('d-none');
    else $btnPause.removeClass('d-none');
  }

  
>>>>>>> 9d9ed85b (for cleaner setup)
  // UI states
  function setStateIdle(){
    isRunningState = false;
    stopTick();
<<<<<<< HEAD
    currentTaskItemId=null; startedAtISO=null; baseSeconds=0;
    $status.text('No running task.'); $elapsedW.addClass('d-none');

    // enable editing
    $('#taskType1, #taskType2').prop('disabled', false);
    $main().prop('disabled', false);
    // sub is enabled only after main chosen
=======
    currentTaskItemId=null; startedAtLocal=null; baseSeconds=0;
    $status.text('No running task.'); $elapsedW.addClass('d-none');

    $('#taskType1, #taskType2').prop('disabled', false);
    $main().prop('disabled', false);
>>>>>>> 9d9ed85b (for cleaner setup)
    $sub.prop('disabled', $main().val()==='');

    $btnPause.prop('disabled', true);
    $btnEnd.prop('disabled', true);
    $btnStart.prop('disabled', !$sub.val());
  }
  function setStateRunning(mainLabel, subLabel){
    isRunningState = true;
    $status.html(`Running: <strong>${mainLabel||''}</strong> <span class="text-muted">→</span> <em>${subLabel||''}</em>`);
    $elapsedW.removeClass('d-none');

<<<<<<< HEAD
    // lock editing while running
=======
>>>>>>> 9d9ed85b (for cleaner setup)
    $('#taskType1, #taskType2').prop('disabled', true);
    $main().prop('disabled', true);
    $sub.prop('disabled', true);

    $btnPause.prop('disabled', false);
    $btnEnd.prop('disabled', false);
    $btnStart.prop('disabled', true);
  }

<<<<<<< HEAD
  // paused table helpers
=======
  // Paused rows helpers
>>>>>>> 9d9ed85b (for cleaner setup)
  function updatePausedCount(){
    const count = $pausedTable.find('tr').not('.no-paused').length;
    $pausedCount.text(count);
  }
  function ensureNoPausedRow(){
    if($pausedTable.find('tr').length===0){
      $pausedTable.append('<tr class="no-paused"><td colspan="4" class="text-muted">No paused tasks.</td></tr>');
    }
  }
  function addOrUpdatePausedRow(itemId, mainName, subName, totalSec){
    const $row = $pausedTable.find(`tr[data-id="${itemId}"]`);
    if ($row.length){
      $row.find('[data-seconds]').attr('data-seconds', totalSec).text(hms(totalSec));
    } else {
      $pausedTable.find('tr.no-paused').remove();
      const $tr = $(`
        <tr data-id="${itemId}">
          <td>${mainName}</td>
          <td>${subName}</td>
          <td class="text-center" data-seconds="${totalSec}">${hms(totalSec)}</td>
          <td class="text-end">
            <button class="btn btn-success btn-sm btnResume" title="Resume"><i class="ti ti-player-play"></i></button>
            <button class="btn btn-outline-danger btn-sm btnEndPaused ms-1" title="End"><i class="ti ti-player-stop"></i></button>
          </td>
        </tr>
      `);
      $pausedTable.prepend($tr);
    }
    updatePausedCount();
  }
  function removePausedRow(itemId){
    $pausedTable.find(`tr[data-id="${itemId}"]`).remove();
    ensureNoPausedRow(); updatePausedCount();
  }

<<<<<<< HEAD
  // load subtasks, optionally selecting one
=======
  // Subtasks loader
>>>>>>> 9d9ed85b (for cleaner setup)
  function loadSubtasks(mainId, selectedSubId=null, cb=null){
    if(!mainId){
      $sub.html('<option value="">Select a sub task</option>').prop('disabled',true); $meta.text('—'); $btnStart.prop('disabled', true); return;
    }
    $sub.html('<option value="">Loading…</option>').prop('disabled',true);
    $.ajax({
      url: routes.subtasksByMain(mainId), type:'GET',
      success: function(data){
        $sub.empty().append('<option value="">Select a sub task</option>');
        (data||[]).forEach(r=>{
          $sub.append($('<option>',{
              value:r.id, text:r.name
            }).attr('data-task-type', r.task_type)
              .attr('data-bench', r.benchmarked_time));
        });

<<<<<<< HEAD
        // if running, keep disabled; else enable
        if (!isRunningState) {
          $sub.prop('disabled', false);
        }

        // Preselect subtask if provided
        if (selectedSubId) {
          $sub.val(String(selectedSubId));
          // show meta
=======
        if (!isRunningState) $sub.prop('disabled', false);

        if (selectedSubId) {
          $sub.val(String(selectedSubId));
>>>>>>> 9d9ed85b (for cleaner setup)
          const $opt=$sub.find(':selected');
          if($opt.val()){
            const type=Number($opt.data('task-type'))===1?'Production':'Non-Production';
            const bm=$opt.data('bench')||'NA'; $meta.text(`${type} · Benchmarked: ${bm}`);
            if(!isRunningState) $btnStart.prop('disabled', false);
          }
        } else {
<<<<<<< HEAD
          // reset meta when no sub selected
=======
>>>>>>> 9d9ed85b (for cleaner setup)
          $meta.text('—');
          if(!isRunningState) $btnStart.prop('disabled', true);
        }

        if(cb) cb();
      },
      error: function(){
        $sub.html('<option value="">Select a sub task</option>').prop('disabled',true);
        Swal.fire({icon:'error',text:'Failed to load subtasks.'});
      }
    });
  }

<<<<<<< HEAD
  // ---- Task Type <-> Main Task show/hide & id swapping ----
  const wrapProductive   = document.getElementById('wrapProductive');
  const wrapGeneral      = document.getElementById('wrapGeneral');
  const prodSelect       = wrapProductive.querySelector('select');      // starts as id="mainTask"
  const generalSelect    = document.getElementById('generalMainTask');  // starts disabled
  const subTaskSelect    = document.getElementById('subTask');

  function setVisibleDropdown(type) {
    // Clear subtask (but we'll load when main changes/preselects)
    if (subTaskSelect) {
      subTaskSelect.innerHTML = '<option value="">Select a sub task</option>';
      // keep disabled if not running? we set below on load
=======
  // Switch between Productive/General main-task selects
  const wrapProductive   = document.getElementById('wrapProductive');
  const wrapGeneral      = document.getElementById('wrapGeneral');
  const prodSelect       = wrapProductive.querySelector('select');
  const generalSelect    = document.getElementById('generalMainTask');
  const subTaskSelect    = document.getElementById('subTask');

  function setVisibleDropdown(type) {
    if (subTaskSelect) {
      subTaskSelect.innerHTML = '<option value="">Select a sub task</option>';
>>>>>>> 9d9ed85b (for cleaner setup)
      if (!isRunningState) subTaskSelect.setAttribute('disabled', 'disabled');
      const subMeta = document.getElementById('subMeta');
      if (subMeta) subMeta.innerHTML = '';
    }

<<<<<<< HEAD
    // Update Pause button visibility
    updatePauseVisibility(type);

    if (String(type) === '1') {
      // Show Productive, hide General
      wrapProductive.classList.remove('d-none');
      wrapGeneral.classList.add('d-none');

      // Ensure Productive select has id="mainTask"
      prodSelect.id = 'mainTask';
      if (!isRunningState) prodSelect.removeAttribute('disabled');

      // Ensure General does NOT have id="mainTask"
=======
    updatePauseVisibility(type);

    if (String(type) === '1') {
      wrapProductive.classList.remove('d-none');
      wrapGeneral.classList.add('d-none');

      prodSelect.id = 'mainTask';
      if (!isRunningState) prodSelect.removeAttribute('disabled');

>>>>>>> 9d9ed85b (for cleaner setup)
      if (generalSelect.id === 'mainTask') generalSelect.id = 'generalMainTask';
      generalSelect.setAttribute('disabled', 'disabled');
      generalSelect.value = '';

    } else if (String(type) === '2') {
<<<<<<< HEAD
      // Show General, hide Productive
      wrapGeneral.classList.remove('d-none');
      wrapProductive.classList.add('d-none');

      // Assign id="mainTask" to General select (so existing listeners still work)
      generalSelect.id = 'mainTask';
      if (!isRunningState) generalSelect.removeAttribute('disabled');

      // Remove id from Productive select
=======
      wrapGeneral.classList.remove('d-none');
      wrapProductive.classList.add('d-none');

      generalSelect.id = 'mainTask';
      if (!isRunningState) generalSelect.removeAttribute('disabled');

>>>>>>> 9d9ed85b (for cleaner setup)
      if (prodSelect.id === 'mainTask') prodSelect.id = 'productiveMainTask';
      prodSelect.setAttribute('disabled', 'disabled');
      prodSelect.value = '';
    }
  }

<<<<<<< HEAD
  // ---- Guards: prevent edits while running ----
=======
  // Guards while running
>>>>>>> 9d9ed85b (for cleaner setup)
  document.querySelectorAll('input[name="task_type"]').forEach(r => {
    r.addEventListener('change', function (e) {
      if (isRunningState) {
        e.preventDefault();
        this.checked = !this.checked;
        Swal.fire({icon:'info', text:'You cannot change task type while a task is running.'});
        return;
      }
      setVisibleDropdown(this.value);
<<<<<<< HEAD
      // trigger change so subtasks refresh when switching groups
=======
>>>>>>> 9d9ed85b (for cleaner setup)
      $('#mainTask').trigger('change');
    });
  });

<<<<<<< HEAD
  // Delegate because #mainTask may swap between selects
=======
  // main task changed
>>>>>>> 9d9ed85b (for cleaner setup)
  $(document).on('change', '#mainTask', function(e){
    if (isRunningState) {
      e.preventDefault();
      Swal.fire({icon:'info', text:'You cannot change main task while a task is running.'});
      return;
    }
    loadSubtasks($(this).val(), null);
  });

<<<<<<< HEAD
=======
  // sub task changed
>>>>>>> 9d9ed85b (for cleaner setup)
  $sub.on('change', function(e){
    if (isRunningState) {
      e.preventDefault();
      Swal.fire({icon:'info', text:'You cannot change sub task while a task is running.'});
      return;
    }
    const $opt=$(this).find(':selected');
    if(!$opt.val()){ $meta.text('—'); $btnStart.prop('disabled', true); return; }
    const type=Number($opt.data('task-type'))===1?'Production':'Non-Production';
    const bm=$opt.data('bench')||'NA'; $meta.text(`${type} · Benchmarked: ${bm}`);
    $btnStart.prop('disabled', false);
  });

<<<<<<< HEAD
  // ---- Actions ----
=======
  // Start
>>>>>>> 9d9ed85b (for cleaner setup)
  $btnStart.on('click', function(){
    $.ajax({
      url: routes.start, type:'POST',
      data:{ _token:csrf, project_id:projectId, main_task_id:$main().val(), sub_task_id:$sub.val() },
      success: function(res){
        if(res.status===1){
          currentTaskItemId = res.task_item_id;
<<<<<<< HEAD
          startedAtISO      = res.started_at;
=======
          startedAtLocal    = res.started_at;          // "Y-m-d H:i:s" (local)
>>>>>>> 9d9ed85b (for cleaner setup)
          baseSeconds       = res.total_seconds || 0;

          removePausedRow(currentTaskItemId);

          setStateRunning($main().find(':selected').text(), $sub.find(':selected').text());
          $elapsed.text(hms(baseSeconds));
<<<<<<< HEAD
          startTick(startedAtISO, baseSeconds);

          // Also enforce pause visibility after start
=======
          startTick(startedAtLocal, baseSeconds);

>>>>>>> 9d9ed85b (for cleaner setup)
          const typeVal = $('input[name="task_type"]:checked').val() || (ACTIVE_TASK_TYPE ?? '1');
          updatePauseVisibility(typeVal);

        } else {
          Swal.fire({icon:'error', text: res.message || 'Unable to start.'});
        }
      },
      error: function(){ Swal.fire({icon:'error', text:'Failed to start task.'}); }
    });
  });

<<<<<<< HEAD
=======
  // Pause
  $btnPause.on('click', function(){
    // 1. Show SweetAlert2 prompt to collect data
      Swal.fire({
          title: 'Pause Tracking Details',
          html:
              // Input for numeric count
              '<input id="swal-counts" class="swal2-input" placeholder="Enter Count (Numeric)" type="number" value="0" min="0">' +
              // Textarea for notes
              '<textarea id="swal-notes" class="swal2-textarea" placeholder="Add Notes (Optional)"></textarea>',
          focusConfirm: false,
          showCancelButton: true, // Allow user to cancel
          confirmButtonText: 'Pause & Save',
          // 2. Validation and Data Collection
          preConfirm: () => {
              const counts = $('#swal-counts').val();
              const notes = $('#swal-notes').val();
              
              // Simple validation: count must be a non-negative number
              if (isNaN(counts) || parseFloat(counts) < 0) {
                  Swal.showValidationMessage(`Please enter a valid non-negative numeric count.`);
                  return false;
              }

              return { counts: parseFloat(counts), notes: notes };
          }
      }).then((result) => {
          // Check if the user clicked the 'Pause & Save' button
          if (result.isConfirmed) {
              const { counts, notes } = result.value;

              // 3. Modified AJAX Request
              $.ajax({
                  // Change the route to your new dedicated backend function (e.g., routes.pauseWithData)
                  url: routes.pause, 
                  type:'POST',
                  data:{
                      _token: csrf,
                      task_item_id: currentTaskItemId,
                      counts: counts,  // Send the collected count
                      notes: notes   // Send the collected notes
                  },
                  success: function(res){
                      if(res.status === 1){
                          const mainName = $main().find(':selected').text();
                          const subName  = $sub.find(':selected').text();
                          
                          // Pass total_seconds and possibly the new total_counts if needed for display
                          addOrUpdatePausedRow(currentTaskItemId, mainName, subName, res.total_seconds || 0, res.total_counts || 0); 
                          setStateIdle();
                          
                          Swal.fire({icon:'success', text: res.message || 'Task paused, count, and notes saved.'});

                          loadSessionHistory(currentTaskItemId);
                      } else {
                          Swal.fire({icon:'error', text: res.message || 'Unable to pause and save data.'});
                      }
                  },
                  error: function(){ 
                      Swal.fire({icon:'error', text:'Failed to pause task.'}); 
                  }
              });
          }
      });
  });
  /*
>>>>>>> 9d9ed85b (for cleaner setup)
  $btnPause.on('click', function(){
    $.ajax({
      url: routes.pause, type:'POST',
      data:{ _token:csrf, task_item_id: currentTaskItemId },
      success: function(res){
        if(res.status===1){
          const mainName = $main().find(':selected').text();
          const subName  = $sub.find(':selected').text();
          addOrUpdatePausedRow(currentTaskItemId, mainName, subName, res.total_seconds || 0);
          setStateIdle();
        } else {
          Swal.fire({icon:'error', text: res.message || 'Unable to pause.'});
        }
      },
      error: function(){ Swal.fire({icon:'error', text:'Failed to pause task.'}); }
    });
  });
<<<<<<< HEAD

  $btnEnd.on('click', function(){
    $.ajax({
      url: routes.end, type:'POST',
      data:{ _token:csrf, task_item_id: currentTaskItemId },
      success: function(res){
        if(res.status===1){
          setStateIdle();
          removePausedRow(currentTaskItemId);
          Swal.fire({icon:'success', text:'Task completed.'});
        } else {
          Swal.fire({icon:'error', text: res.message || 'Unable to end.'});
        }
      },
      error: function(){ Swal.fire({icon:'error', text:'Failed to end.'}); }
    });
  });

  // Resume from paused list
  $(document).on('click', '.btnResume', function(){
=======
  */
  // End
  $btnEnd.on('click', function(){
  $.ajax({
    url: routes.end, type:'POST',
    data:{ _token:csrf, task_item_id: currentTaskItemId },
    success: function(res){
      if(res.status===1){
        setStateIdle();
        removePausedRow(currentTaskItemId);
        refreshTodaysTasks();              // <— ADDED
        Swal.fire({icon:'success', text:'Task completed.'});
      } else {
        Swal.fire({icon:'error', text: res.message || 'Unable to end.'});
      }
    },
    error: function(){ Swal.fire({icon:'error', text:'Failed to end.'}); }
  });
});


  // Resume from paused list
  $(document).on('click', '.btnResume', function(){

>>>>>>> 9d9ed85b (for cleaner setup)
    const $tr = $(this).closest('tr');
    const id  = $tr.data('id');

    $.ajax({
      url: routes.resume, type:'POST',
      data:{ _token:csrf, task_item_id:id },
      success: function(res){
        if(res.status===1){
          removePausedRow(id);

          currentTaskItemId = res.task_item_id;
<<<<<<< HEAD
          startedAtISO      = res.started_at;
=======
          startedAtLocal    = res.started_at; // local
>>>>>>> 9d9ed85b (for cleaner setup)
          baseSeconds       = res.total_seconds || Number($tr.find('[data-seconds]').attr('data-seconds')) || 0;

          const mainName = $tr.children().eq(0).text();
          const subName  = $tr.children().eq(1).text();

          setStateRunning(mainName, subName);
          $elapsed.text(hms(baseSeconds));
<<<<<<< HEAD
          startTick(startedAtISO, baseSeconds);
=======
          startTick(startedAtLocal, baseSeconds);
>>>>>>> 9d9ed85b (for cleaner setup)

          const typeVal = $('input[name="task_type"]:checked').val() || (ACTIVE_TASK_TYPE ?? '1');
          updatePauseVisibility(typeVal);

        } else {
          Swal.fire({icon:'error', text: res.message || 'Unable to resume.'});
        }
      },
      error: function(){ Swal.fire({icon:'error', text:'Failed to resume task.'}); }
    });
  });

  // End from paused list
<<<<<<< HEAD
  $(document).on('click', '.btnEndPaused', function(){
    const $tr = $(this).closest('tr');
    const id  = $tr.data('id');

    $.ajax({
      url: routes.end, type:'POST', data:{ _token:csrf, task_item_id:id },
      success: function(res){
        if(res.status===1){
          removePausedRow(id);
          Swal.fire({icon:'success', text:'Task completed.'});
        } else {
          Swal.fire({icon:'error', text: res.message || 'Unable to end task.'});
        }
      },
      error: function(){ Swal.fire({icon:'error', text:'Failed to end task.'}); }
    });
  });

  // ---- Initial show/hide for main-task selects + preselect + pause visibility ----
=======
 $(document).on('click', '.btnEndPaused', function(){
  const $tr = $(this).closest('tr');
  const id  = $tr.data('id');

  $.ajax({
    url: routes.end, type:'POST', data:{ _token:csrf, task_item_id:id },
    success: function(res){
      if(res.status===1){
        removePausedRow(id);
        refreshTodaysTasks();              // <— ADDED
        Swal.fire({icon:'success', text:'Task completed.'});
      } else {
        Swal.fire({icon:'error', text: res.message || 'Unable to end task.'});
      }
    },
    error: function(){ Swal.fire({icon:'error', text:'Failed to end task.'}); }
  });
});

function loadSessionHistory(taskItemId) {
    const $historyContainer = $('#sessionHistory');
    $historyContainer.html('<p>Loading history...</p>');

    $.ajax({
        // url: routes.history, 
        url: routes.getSessionHistory,
        type: 'GET', // Or POST, depending on your route definition
        data: { id: taskItemId },
        success: function(res) {
    const $historyContainer = $('#sessionHistory'); // Ensure this is available

    // 1. Check only for the successful status (status: 1)
    if (res.status === 1) {
        
        // 2. Directly access the summary properties
        const totalCounts = res.total_counts || 0;
        // Use the notes_history string, falling back to a default if null/empty
        const notes = res.notes_history || 'No notes recorded.'; 

        // 3. HTML structure for the summary display (as provided in the last solution)
        const html = `
            <div style="padding: 10px; border: 1px solid #ddd; background-color: #f9f9f9;">
                <h6>
                    Total Count: ${totalCounts}
                </h6>
                
                <h6>Notes:</h6>
                <div>
                    ${notes}
                </div>
            </div>
        `;
        
        $historyContainer.html(html);

    } else {
         // Handle error if status is 0 or missing
         $historyContainer.html('<p>Error loading summary data.</p>');
    }
},
error: function() {
    $historyContainer.html('<p>Failed to load task summary from server.</p>');
}
    });
}


  // Init (show/hide & preselect)
>>>>>>> 9d9ed85b (for cleaner setup)
  function initialShowHideAndPreselect(){
    const initialType = (ACTIVE_TASK_TYPE ? String(ACTIVE_TASK_TYPE) : ($('input[name="task_type"]:checked').val() || '1'));
    setVisibleDropdown(initialType);
    updatePauseVisibility(initialType);

<<<<<<< HEAD
    // Preselect main
    if (ACTIVE_MAIN_TASKID) {
      $('#mainTask').val(String(ACTIVE_MAIN_TASKID));
    }

    // Load subtasks and preselect sub if provided
    const mainVal = $('#mainTask').val();
    if (mainVal) {
      loadSubtasks(mainVal, ACTIVE_SUB_TASKID || null, function(){
        // If running, keep disabled; else enable Start if sub selected
        if (!isRunningState && ACTIVE_SUB_TASKID) {
          $('#btnStart').prop('disabled', false);
        }
      });
    } else {
      // No main preselected: if not running, keep sub disabled
      if (!isRunningState) { $('#subTask').prop('disabled', true); }
=======
    if (ACTIVE_MAIN_TASKID) $('#mainTask').val(String(ACTIVE_MAIN_TASKID));

    const mainVal = $('#mainTask').val();
    if (mainVal) {
      loadSubtasks(mainVal, ACTIVE_SUB_TASKID || null, function(){
        if (!isRunningState && ACTIVE_SUB_TASKID) $('#btnStart').prop('disabled', false);
      });
    } else {
      if (!isRunningState) $('#subTask').prop('disabled', true);
>>>>>>> 9d9ed85b (for cleaner setup)
    }
  }
  initialShowHideAndPreselect();

<<<<<<< HEAD
  // ---- Bootstrap running state from server ----
  @if(!empty($currentRunning))
    (function initRunning(){
      currentTaskItemId = {{ (int)$currentRunning['task_item_id'] }};
      startedAtISO      = @json($currentRunning['started_at']);
      baseSeconds       = {{ (int)$currentRunning['total_seconds'] }};
      setStateRunning(@json($currentRunning['main_task_name']), @json($currentRunning['sub_task_name']));
      $elapsed.text(hms(baseSeconds));
      startTick(startedAtISO, baseSeconds);

      // If running type is general, ensure Pause hidden
      updatePauseVisibility(String(ACTIVE_TASK_TYPE ?? '1'));
    })();
=======
  // Bootstrap running state from server (LOCAL times)
  @if(!empty($currentRunning))
    (function initRunning(){
      currentTaskItemId = {{ (int)$currentRunning['task_item_id'] }};
      startedAtLocal    = @json($currentRunning['started_at']); // "Y-m-d H:i:s"
      baseSeconds       = {{ (int)$currentRunning['total_seconds'] }};
      setStateRunning(@json($currentRunning['main_task_name']), @json($currentRunning['sub_task_name']));
      $elapsed.text(hms(baseSeconds));
      startTick(startedAtLocal, baseSeconds);

      updatePauseVisibility(String(ACTIVE_TASK_TYPE ?? '1'));
    })();

    if (typeof currentTaskItemId !== 'undefined' && currentTaskItemId) {
        // Call the function defined in the previous response
        loadSessionHistory(currentTaskItemId);
    } else {
        // Handle the case where no task is active/loaded
        $('#sessionHistory').html('<p>No active or paused task selected to display history.</p>');
    }
>>>>>>> 9d9ed85b (for cleaner setup)
  @else
    setStateIdle();
  @endif

});
</script>
@endsection
