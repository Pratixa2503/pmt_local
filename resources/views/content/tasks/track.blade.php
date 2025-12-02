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
  .d-none { display: none !important; }
  #subTaskRadio { display: flex; flex-direction: row; flex-wrap: wrap; gap: 1rem; align-items: center; }
  #subTaskRadio .form-check { margin-bottom: 0; margin-right: 1rem; }
  #subTaskRadio .form-check-label { cursor: pointer; }
  #generalMainTaskDisplay { min-height: 38px; line-height: 38px; padding: 0 0.75rem; }
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
  $activeSubTaskId     = $activeSubTaskId     ?? null;
  $resolvedType = (int) old('task_type', $activeTaskType ?? 1);

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
            </div>

            <div class="form-check form-check-inline">
              <input class="form-check-input"
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

          {{-- General Main Task (Completely Hidden for Non Production) --}}
          <div class="col-md-3 general_task d-none" id="wrapGeneral">
            <input type="hidden" id="hiddenMainTask" value="{{ ($activeMainTaskId && $activeTaskType == 2) ? $activeMainTaskId : ($generalMainTasks->keys()->first() ?? '') }}">
            <select id="generalMainTask" class="form-select d-none" {{ $isRunning ? 'disabled' : 'disabled' }}>
              <option value="">Select a main task</option>
              @foreach($generalMainTasks as $id => $name)
                <option value="{{ $id }}" {{ ($activeMainTaskId && $activeTaskType == 2 && $activeMainTaskId == $id) ? 'selected' : '' }}>
                  {{ $name }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Sub Task --}}
          <div class="col-md-3" id="subTaskWrapper">
            <label class="form-label">Sub Task <span class="text-danger">*</span></label>
            {{-- Dropdown for Production --}}
            <select id="subTask" class="form-select" {{ $isRunning ? '' : 'disabled' }}>
              <option value="">Select a sub task</option>
            </select>
            {{-- Radio buttons for Non Production --}}
            <div id="subTaskRadio" class="d-none"></div>
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


       
        <div class="border rounded p-3 mb-3">
              <div id="sessionHistory" >
            <h6 class="mb-0">Current Count</h6>
            <p>Loading history...</p>
        </div>
        </div>
  

        <div class="row g-3">

          <div class="col-lg-8">
            {{-- Current Running --}}
            <div class="border rounded p-3 mb-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Current Session</h6>
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
                      <th>Start Time</th>
                      <th>Emd Time</th>
                      <th style="width:40%">Main Task</th>
                      <th style="width:40%">Sub Task</th>
                      <th class="text-end">Time (hh:mm:ss)</th>
                    </tr>
                  </thead>
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
                      <tr><td colspan="5" class="text-muted">No work recorded yet today.</td></tr>
                    @endforelse
                  </tbody>
                  @if($todayRows->count())
                  <tfoot id="todayTfoot">
                    <tr class="table-light">
                      <th colspan="4" class="text-end">Total</th>
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
                <button id="btnPause" class="btn btn-warning {{ ($activeTaskType ?? 1) == 2 ? 'd-none' : '' }}" {{ $isRunning ? '' : 'disabled' }}>
                  <i class="ti ti-player-pause me-1"></i> Pause
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

  // 1) Add this near your other route constants:
const TODAY_URL = "{{ route('taskitems.today', $encryptedId) }}";

// 2) Helper to refresh "Today's Tasks" automatically
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
        if (res.tfoot) {
          const $newFoot = $(res.tfoot);
          if ($oldFoot.length) {
            $oldFoot.replaceWith($newFoot);
          } else {
            // If tfoot doesn't exist, append it after tbody
            $('#todayTbody').closest('table').append($newFoot);
          }
        } else {
          $oldFoot.remove();
        }
      }
      // Update badge count if provided
      if(res && res.count !== undefined){
        $('#todayTbody').closest('.border').find('.badge.bg-label-primary').text(res.count);
      }
    })
    .fail(function(){
      console.warn('Failed to refresh Today\'s Tasks.');
    });
}

  const routes = {
    subtasksByMain: (mainId) => "{{ url('subtasks/by-main') }}/" + mainId,
    start:  "{{ route('taskitems.start') }}",
    pause:  "{{ route('taskitems.pause') }}",
    resume: "{{ route('taskitems.resume') }}",
    end:    "{{ route('taskitems.end') }}",
      // If you were using this one:
    count: "{{ route('taskitems.count')  }}",
  };

  const projectId = {{ (int)$project->id }};
  const csrf = '{{ csrf_token() }}';

  // From server
  const ACTIVE_TASK_TYPE   = @json($activeTaskType);   // 1|2|null
  const ACTIVE_MAIN_TASKID = @json($activeMainTaskId); // int|null
  const ACTIVE_SUB_TASKID  = @json($activeSubTaskId);  // int|null
  const INIT_IS_RUNNING    = {!! $isRunning ? 'true' : 'false' !!};

  let isRunningState = INIT_IS_RUNNING;
  let currentTaskItemId = null;
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
  const $sub = $('#subTask'), $subRadio = $('#subTaskRadio'), $meta = $('#subMeta');
  const $btnStart = $('#btnStart'), $btnPause = $('#btnPause'), $btnEnd = $('#btnEnd');
  const $status = $('#currentStatus'), $elapsedW = $('#elapsedWrap'), $elapsed = $('#elapsed'), $now = $('#now');
  const $pausedTable = $('#pausedTable tbody'), $pausedCount = $('#pausedCount');
  const $hiddenMainTask = $('#hiddenMainTask');

  const $main = () => {
    const taskType = $('input[name="task_type"]:checked').val();
    if (String(taskType) === '2') {
      // For Non Production, return hidden field value and get name from select option
      return { 
        val: () => $hiddenMainTask.val(), 
        find: () => ({ 
          text: () => {
            const mainTaskId = $hiddenMainTask.val();
            const mainTaskOption = generalSelect.querySelector(`option[value="${mainTaskId}"]`);
            return mainTaskOption ? mainTaskOption.textContent : 'General Task';
          }
        }) 
      };
    }
    return $('#mainTask');
  };

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

  // UI states
  function setStateIdle(){
    isRunningState = false;
    stopTick();
    currentTaskItemId=null; startedAtLocal=null; baseSeconds=0;
    $status.text('No running task.'); $elapsedW.addClass('d-none');

    $('#taskType1, #taskType2').prop('disabled', false);
    const taskType = $('input[name="task_type"]:checked').val();
    
    if (String(taskType) === '2') {
      // Non Production - enable radio buttons
      $('.sub-task-radio').prop('disabled', false);
      const hasSelected = $('.sub-task-radio:checked').length > 0;
      $btnStart.prop('disabled', !hasSelected);
    } else {
      // Production - enable dropdown
      $main().prop('disabled', false);
      $sub.prop('disabled', $main().val()==='');
      $btnStart.prop('disabled', !$sub.val());
    }

    $btnPause.prop('disabled', true);
    $btnEnd.prop('disabled', true);
  }
  function setStateRunning(mainLabel, subLabel){
    isRunningState = true;
    $status.html(`Running: <strong>${mainLabel||''}</strong> <span class="text-muted">→</span> <em>${subLabel||''}</em>`);
    $elapsedW.removeClass('d-none');

    $('#taskType1, #taskType2').prop('disabled', true);
    const taskType = $('input[name="task_type"]:checked').val();
    
    if (String(taskType) === '2') {
      // Non Production - disable radio buttons
      $('.sub-task-radio').prop('disabled', true);
    } else {
      // Production - disable dropdown
      $main().prop('disabled', true);
      $sub.prop('disabled', true);
    }

    $btnPause.prop('disabled', false);
    $btnEnd.prop('disabled', false);
    $btnStart.prop('disabled', true);
  }

  // Paused rows helpers
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

  // Subtasks loader
  function loadSubtasks(mainId, selectedSubId=null, cb=null, isNonProduction=false){
    const $subTaskWrapper = $('#subTaskWrapper');
    
    if(!mainId){
      $sub.html('<option value="">Select a sub task</option>').prop('disabled',true);
      $subRadio.empty().addClass('d-none');
      $sub.removeClass('d-none');
      // Reset to col-md-3 for dropdown
      $subTaskWrapper.removeClass('col-md-6').addClass('col-md-3');
      $meta.text('—'); 
      $btnStart.prop('disabled', true); 
      return;
    }
    
    if (isNonProduction) {
      $subRadio.html('<div class="text-muted small">Loading…</div>').removeClass('d-none');
      $sub.addClass('d-none');
      // Change to col-md-6 for radio buttons
      $subTaskWrapper.removeClass('col-md-3').addClass('col-md-6');
    } else {
      $sub.html('<option value="">Loading…</option>').prop('disabled',true).removeClass('d-none');
      $subRadio.addClass('d-none');
      // Change to col-md-3 for dropdown
      $subTaskWrapper.removeClass('col-md-6').addClass('col-md-3');
    }
    
    $.ajax({
      url: routes.subtasksByMain(mainId), type:'GET',
      success: function(data){
        if (isNonProduction) {
          // Display as radio buttons for Non Production
          $subRadio.empty();
          if (!data || data.length === 0) {
            $subRadio.html('<div class="text-muted">No sub tasks available.</div>');
            $meta.text('—');
            $btnStart.prop('disabled', true);
          } else {
            (data||[]).forEach(r=>{
              const radioId = 'subTaskRadio_' + r.id;
              const checked = selectedSubId && String(selectedSubId) === String(r.id) ? 'checked' : '';
              const disabled = isRunningState ? 'disabled' : '';
              const $radio = $(`
                <div class="form-check">
                  <input class="form-check-input sub-task-radio" type="radio" name="sub_task_radio" 
                         id="${radioId}" value="${r.id}" data-task-type="${r.task_type}" 
                         data-bench="${r.benchmarked_time || 'NA'}" ${checked} ${disabled}>
                  <label class="form-check-label" for="${radioId}">${r.name}</label>
                </div>
              `);
              $subRadio.append($radio);
            });
            
            // Update meta and enable start button if one is selected
            const $checked = $subRadio.find('input:checked');
            if ($checked.length) {
              const type = Number($checked.data('task-type')) === 1 ? 'Production' : 'Non-Production';
              const bm = $checked.data('bench') || 'NA';
              $meta.text(`${type} · Benchmarked: ${bm}`);
              if (!isRunningState) $btnStart.prop('disabled', false);
            } else {
              $meta.text('—');
              if (!isRunningState) $btnStart.prop('disabled', true);
            }
          }
        } else {
          // Display as dropdown for Production
          $sub.empty().append('<option value="">Select a sub task</option>');
          (data||[]).forEach(r=>{
            $sub.append($('<option>',{
                value:r.id, text:r.name
              }).attr('data-task-type', r.task_type)
                .attr('data-bench', r.benchmarked_time));
          });

          if (!isRunningState) $sub.prop('disabled', false);

          if (selectedSubId) {
            $sub.val(String(selectedSubId));
            const $opt=$sub.find(':selected');
            if($opt.val()){
              const type=Number($opt.data('task-type'))===1?'Production':'Non-Production';
              const bm=$opt.data('bench')||'NA'; $meta.text(`${type} · Benchmarked: ${bm}`);
              if(!isRunningState) $btnStart.prop('disabled', false);
            }
          } else {
            $meta.text('—');
            if(!isRunningState) $btnStart.prop('disabled', true);
          }
        }

        if(cb) cb();
      },
      error: function(){
        if (isNonProduction) {
          $subRadio.html('<div class="text-danger">Failed to load subtasks.</div>');
        } else {
          $sub.html('<option value="">Select a sub task</option>').prop('disabled',true);
        }
        Swal.fire({icon:'error',text:'Failed to load subtasks.'});
      }
    });
  }

  // Switch between Productive/General main-task selects
  const wrapProductive   = document.getElementById('wrapProductive');
  const wrapGeneral      = document.getElementById('wrapGeneral');
  const prodSelect       = wrapProductive.querySelector('select');
  const generalSelect    = document.getElementById('generalMainTask');
  const subTaskSelect    = document.getElementById('subTask');

  function setVisibleDropdown(type) {
    if (subTaskSelect) {
      subTaskSelect.innerHTML = '<option value="">Select a sub task</option>';
      if (!isRunningState) subTaskSelect.setAttribute('disabled', 'disabled');
      const subMeta = document.getElementById('subMeta');
      if (subMeta) subMeta.innerHTML = '';
    }
    
    // Reset sub task radio buttons
    const $subRadioEl = $('#subTaskRadio');
    const $subTaskWrapper = $('#subTaskWrapper');
    $subRadioEl.empty().addClass('d-none');
    $('#subTask').removeClass('d-none');
    // Reset to col-md-3 for dropdown
    $subTaskWrapper.removeClass('col-md-6').addClass('col-md-3');

    updatePauseVisibility(type);

    if (String(type) === '1') {
      // Production mode
      wrapProductive.classList.remove('d-none');
      wrapGeneral.classList.add('d-none');

      prodSelect.id = 'mainTask';
      if (!isRunningState) prodSelect.removeAttribute('disabled');

      if (generalSelect.id === 'mainTask') generalSelect.id = 'generalMainTask';
      generalSelect.setAttribute('disabled', 'disabled');
      generalSelect.value = '';

    } else if (String(type) === '2') {
      // Non Production mode - auto-select first main task and hide the field completely
      wrapGeneral.classList.add('d-none'); // Hide the entire General section
      wrapProductive.classList.add('d-none');

      // Auto-select first main task for Non Production
      const firstMainTaskId = generalSelect.querySelector('option[value]:not([value=""])')?.value;
      if (firstMainTaskId) {
        generalSelect.value = firstMainTaskId;
        $hiddenMainTask.val(firstMainTaskId);
        
        // Load sub tasks as radio buttons
        loadSubtasks(firstMainTaskId, null, null, true);
      }

      if (prodSelect.id === 'mainTask') prodSelect.id = 'productiveMainTask';
      prodSelect.setAttribute('disabled', 'disabled');
      prodSelect.value = '';
    }
  }

  // Guards while running
  document.querySelectorAll('input[name="task_type"]').forEach(r => {
    r.addEventListener('change', function (e) {
      if (isRunningState) {
        e.preventDefault();
        this.checked = !this.checked;
        Swal.fire({icon:'info', text:'You cannot change task type while a task is running.'});
        return;
      }
      setVisibleDropdown(this.value);
      // Only trigger change for Production mode (Non Production auto-loads)
      if (String(this.value) === '1') {
        $('#mainTask').trigger('change');
      }
    });
  });

  // main task changed (only for Production)
  $(document).on('change', '#mainTask', function(e){
    if (isRunningState) {
      e.preventDefault();
      Swal.fire({icon:'info', text:'You cannot change main task while a task is running.'});
      return;
    }
    const taskType = $('input[name="task_type"]:checked').val();
    loadSubtasks($(this).val(), null, null, String(taskType) === '2');
  });

  // sub task changed (dropdown for Production)
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

  // sub task changed (radio buttons for Non Production)
  $(document).on('change', '.sub-task-radio', function(e){
    if (isRunningState) {
      e.preventDefault();
      $(this).prop('checked', false);
      Swal.fire({icon:'info', text:'You cannot change sub task while a task is running.'});
      return;
    }
    const $radio = $(this);
    if(!$radio.val()){ $meta.text('—'); $btnStart.prop('disabled', true); return; }
    const type=Number($radio.data('task-type'))===1?'Production':'Non-Production';
    const bm=$radio.data('bench')||'NA'; $meta.text(`${type} · Benchmarked: ${bm}`);
    $btnStart.prop('disabled', false);
  });

  // Helper to get selected sub task ID
  function getSelectedSubTaskId() {
    const taskType = $('input[name="task_type"]:checked').val();
    if (String(taskType) === '2') {
      // Non Production - get from radio button
      return $('.sub-task-radio:checked').val() || '';
    } else {
      // Production - get from dropdown
      return $sub.val() || '';
    }
  }

  // Start
  $btnStart.on('click', function(){
    const mainTaskId = $main().val();
    const subTaskId = getSelectedSubTaskId();
    
    $.ajax({
      url: routes.start, type:'POST',
      data:{ _token:csrf, project_id:projectId, main_task_id:mainTaskId, sub_task_id:subTaskId },
      success: function(res){
        if(res.status===1){
          currentTaskItemId = res.task_item_id;
          startedAtLocal    = res.started_at;          // "Y-m-d H:i:s" (local)
          baseSeconds       = res.total_seconds || 0;

          removePausedRow(currentTaskItemId);

          // Get task names based on type
          const taskType = $('input[name="task_type"]:checked').val();
          let mainName, subName;
          if (String(taskType) === '2') {
            // Get main task name from hidden select
            const mainTaskId = $hiddenMainTask.val();
            const mainTaskOption = generalSelect.querySelector(`option[value="${mainTaskId}"]`);
            mainName = mainTaskOption ? mainTaskOption.textContent : 'General Task';
            subName = $('.sub-task-radio:checked').next('label').text();
          } else {
            mainName = $main().find(':selected').text();
            subName = $sub.find(':selected').text();
          }
          
          setStateRunning(mainName, subName);
          $elapsed.text(hms(baseSeconds));
          startTick(startedAtLocal, baseSeconds);

          const typeVal = $('input[name="task_type"]:checked').val() || (ACTIVE_TASK_TYPE ?? '1');
          updatePauseVisibility(typeVal);

        } else {
          Swal.fire({icon:'error', text: res.message || 'Unable to start.'});
        }
      },
      error: function(){ Swal.fire({icon:'error', text:'Failed to start task.'}); }
    });
  });

  function formatSecondsToTime(totalSeconds) {
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;

    const pad = (num) => String(num).padStart(2, '0');

    return `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
  }

  // --- Main History Loading Function ---
  function loadSessionHistory(taskItemId) {
      // Select the container where the history will be displayed (e.g., a tbody or div)
     const $historyContainer = $('#sessionHistory');
    $historyContainer.html('<p>Loading history...</p>');

      $.ajax({
          url: routes.count, // Ensure this route is defined globally
          type: 'POST',
          data: {
              _token: csrf, // Ensure CSRF token is available
              task_item_id: taskItemId
          },
          success: function(response) {
              if (response.status === 1) {
                   // 2. Directly access the summary properties
                const totalCounts = response.total_counts || 0;
                // Use the notes_history string, falling back to a default if null/empty
                const notes = response.notes_history || 'No notes recorded.'; 

                // 3. HTML structure for the summary display (as provided in the last solution)
                const html = `<h6>
                            Total Count: ${totalCounts}
                        </h6>
                        
                        <h6>Notes:</h6>
                        <div>
                            ${notes}
                        </div>
                    
                `;
                
                $historyContainer.html(html);

            } else {
                // Handle error if status is 0 or missing
                $historyContainer.html('<p>Error loading summary data.</p>');
            }

              
          },
          error: function() {
              $container.html('<tr><td colspan="5" class="text-center text-danger">Failed to connect to server to load history.</td></tr>');
          }
      });
  }
  var currentTaskCountType = 0; // 1 for mandatory count, 0 for optional/non-mandatory

// =================================================================
// 1. Core Logic: Pause Button Handler
// =================================================================
$btnPause.on('click', function(){
    // Determine mandatory status based on the assumed global variable
    const isCountMandatory = (typeof currentTaskCountType !== 'undefined' && currentTaskCountType === 1);
    const countPlaceholder = isCountMandatory 
        ? 'Enter Count (REQUIRED)' 
        : 'Enter Count (Optional, Defaults to 0)';

    // 1. Show SweetAlert2 prompt to collect data
 Swal.fire({
title: 'Pause Tracking Details',
 html:
            // Dynamic placeholder based on count type
            '<input id="swal-count" class="swal2-input" placeholder="' + countPlaceholder + '" type="number" min="0">' +
            '<textarea id="swal-notes" class="swal2-textarea" placeholder="Add Notes (Optional)"></textarea>',
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Pause & Save',
        preConfirm: () => {
            let countInput = $('#swal-count').val().trim();
            const notes = $('#swal-notes').val();
            let count;
            
            // --- MANDATORY CHECK ---
            if (isCountMandatory && countInput === '') {
                Swal.showValidationMessage(`Count is REQUIRED for this task.`);
                return false;
            }

            if (countInput === '') {
                // Non-mandatory count: default to 0
                count = 0;
            } else {
                // Mandatory or provided optional count
                count = parseFloat(countInput);
            }
            
            // Validation: check for non-numbers (NaN) or negative values
            if (isNaN(count) || count < 0) {
                Swal.showValidationMessage(`Please enter a valid non-negative numeric count.`);
                return false;
            }

            return { count: count, notes: notes };
        }
    }).then((result) => {
        
        if (result.isConfirmed) {
            const { count, notes } = result.value;

            $.ajax({
                url: routes.pause, 
                type:'POST',
                data:{
                    _token: csrf,
                    task_item_id: currentTaskItemId,
                    count: count,  
                    notes: notes   
                },
                success: function(res){
                    
                    // No console.log here, just rely on success status
                    if(res.status === 1){
                        // 1. Update the row in the main task table
                        const mainName = $main().find(':selected').text();
                        const subName  = $sub.find(':selected').text();
                        
                        addOrUpdatePausedRow(
                            currentTaskItemId, 
                            mainName, 
                            subName, 
                            res.total_seconds || 0, 
                  res.total_counts || 0
                        ); 
                        
                        // 2. Reset the main tracking UI
                        setStateIdle();
                        
                        // 3. Show success message (You confirmed this works)
                        Swal.fire({icon:'success', text: res.message || 'Task paused, count, and notes saved.'});

                        // 4. Reload the session history section
                        loadSessionHistory(res.task_item_id); 

                    } else {
                        Swal.fire({icon:'error', text: res.message || 'Unable to pause and save data.'});
                    }
                },
                error: function(xhr, status, error){ 
                    console.error("Pause AJAX Error:", status, error);
                    
                    let errorMessage = 'Failed to pause task due to a network error.';
                    
                    // Check for Laravel validation errors (Status 422)
                    if (xhr.status === 422) {
                        const responseJson = xhr.responseJSON;
                        if (responseJson && responseJson.message) {
                            // Use the main message from Laravel's validation response
                            errorMessage = responseJson.message;
                        } else {
                            // Fallback if structure is unexpected but status is 422
                            errorMessage = 'Validation failed. Please check your inputs.';
                        }
                    } else if (xhr.status !== 0) {
                        // Handle other non-network related HTTP errors
                        errorMessage = xhr.statusText || `An error occurred (Status: ${xhr.status}).`;
                    }
                    
                    Swal.fire({icon:'error', text: errorMessage}); 
                }
            });
        }
    });
});

  // Pause
  /*  
  $btnPause.on('click', function(){
    // 1. Show SweetAlert2 prompt to collect data
    Swal.fire({
        title: 'Pause Tracking Details',
        html:
            // Input for numeric count
            '<input id="swal-count" class="swal2-input" placeholder="Enter Count (Numeric)" type="number" value="0" min="0">' +
            // Textarea for notes
            '<textarea id="swal-notes" class="swal2-textarea" placeholder="Add Notes (Optional)"></textarea>',
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Pause & Save',
        
        // 2. Validation and Data Collection
        preConfirm: () => {
            const count = $('#swal-count').val();
            const notes = $('#swal-notes').val();
            
            // Basic validation: count must be a non-negative number
            if (isNaN(count) || parseFloat(count) < 0) {
                Swal.showValidationMessage(`Please enter a valid non-negative numeric count.`);
                return false;
            }

            return { count: parseFloat(count), notes: notes };
        }
    }).then((result) => {
        
        // Check if the user confirmed the action
        if (result.isConfirmed) {
            const { count, notes } = result.value;

            // 3. AJAX Request to the Backend Method
            $.ajax({
                // Uses the route for pausing with count and notes
                url: routes.pause, 
                type:'POST',
                data:{
                    _token: csrf,
                    task_item_id: currentTaskItemId,
                    count: count,  
                    notes: notes   
                },
                success: function(res){
                  // =================================================================
                    setTimeout(() => {
                        console.log("Pause AJAX Response (FORCED LOG):", res);
                        // This check tells you which dependency is causing the silent crash (if it says 'undefined').
                        console.log("Dependencies Check:", {
                            mainFunction: typeof $main,
                            subElement: typeof $sub,
                            updateRowFunction: typeof addOrUpdatePausedRow,
                            idleFunction: typeof setStateIdle,
                            historyFunction: typeof loadSessionHistory
                        });
                    }, 0);
                    if(res.status === 1){
                        // Assuming these functions and variables exist for UI updates:
                        const mainName = $main().find(':selected').text();
                        const subName  = $sub.find(':selected').text();
                        
                        addOrUpdatePausedRow(task_item_id, mainName, subName, res.total_seconds || 0, res.total_counts || 0); 
                        setStateIdle();
                        
                        // Show success message
                        Swal.fire({icon:'success', text: res.message || 'Task paused, count, and notes saved.'});

                        // 4. Reload the session history after successful save
                        // NOTE: Ensure the loadSessionHistory function is defined elsewhere
                        loadSessionHistory(task_item_id); 

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
  }); */
  /*
  $btnPause.on('click', function(){
    $.ajax({
      url: routes.pause, type:'POST',
      data:{ _token:csrf, task_item_id: currentTaskItemId },
      success: function(res){
        if(res.status===1){
          // Get task names based on type
          const taskType = $('input[name="task_type"]:checked').val();
          let mainName, subName;
          if (String(taskType) === '2') {
            // Get main task name from hidden select
            const mainTaskId = $hiddenMainTask.val();
            const mainTaskOption = generalSelect.querySelector(`option[value="${mainTaskId}"]`);
            mainName = mainTaskOption ? mainTaskOption.textContent : 'General Task';
            subName = $('.sub-task-radio:checked').next('label').text();
          } else {
            mainName = $main().find(':selected').text();
            subName = $sub.find(':selected').text();
          }
          addOrUpdatePausedRow(currentTaskItemId, mainName, subName, res.total_seconds || 0);
          setStateIdle();
        } else {
          Swal.fire({icon:'error', text: res.message || 'Unable to pause.'});
        }
      },
      error: function(){ Swal.fire({icon:'error', text:'Failed to pause task.'}); }
    });
  });
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
          // Automatically refresh today's tasks via AJAX
          refreshTodaysTasks();
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
    const $tr = $(this).closest('tr');
    const id  = $tr.data('id');

    $.ajax({
      url: routes.resume, type:'POST',
      data:{ _token:csrf, task_item_id:id },
      success: function(res){
        if(res.status===1){
          removePausedRow(id);

          currentTaskItemId = res.task_item_id;
          startedAtLocal    = res.started_at; // local
          baseSeconds       = res.total_seconds || Number($tr.find('[data-seconds]').attr('data-seconds')) || 0;

          const mainName = $tr.children().eq(0).text();
          const subName  = $tr.children().eq(1).text();

          setStateRunning(mainName, subName);
          $elapsed.text(hms(baseSeconds));
          startTick(startedAtLocal, baseSeconds);

          const typeVal = $('input[name="task_type"]:checked').val() || (ACTIVE_TASK_TYPE ?? '1');
          updatePauseVisibility(typeVal);

          loadSessionHistory(currentTaskItemId);
        } else {
          Swal.fire({icon:'error', text: res.message || 'Unable to resume.'});
        }
      },
      error: function(){ Swal.fire({icon:'error', text:'Failed to resume task.'}); }
    });
  });

  // End from paused list
  $(document).on('click', '.btnEndPaused', function(){
    const $tr = $(this).closest('tr');
    const id  = $tr.data('id');

    $.ajax({
      url: routes.end, type:'POST', data:{ _token:csrf, task_item_id:id },
      success: function(res){
        if(res.status===1){
          removePausedRow(id);
          // Automatically refresh today's tasks via AJAX
          refreshTodaysTasks();
          Swal.fire({icon:'success', text:'Task completed.'});
        } else {
          Swal.fire({icon:'error', text: res.message || 'Unable to end task.'});
        }
      },
      error: function(){ Swal.fire({icon:'error', text:'Failed to end task.'}); }
    });
  });


  // Init (show/hide & preselect)
  function initialShowHideAndPreselect(){
    const initialType = (ACTIVE_TASK_TYPE ? String(ACTIVE_TASK_TYPE) : ($('input[name="task_type"]:checked').val() || '1'));
    setVisibleDropdown(initialType);
    updatePauseVisibility(initialType);

    const taskType = String(initialType);
    if (taskType === '2') {
      // Non Production - use hidden main task
      if (ACTIVE_MAIN_TASKID) {
        $hiddenMainTask.val(String(ACTIVE_MAIN_TASKID));
      }
      const mainVal = $hiddenMainTask.val();
      if (mainVal) {
        loadSubtasks(mainVal, ACTIVE_SUB_TASKID || null, function(){
          if (!isRunningState && ACTIVE_SUB_TASKID) {
            $(`.sub-task-radio[value="${ACTIVE_SUB_TASKID}"]`).prop('checked', true).trigger('change');
            $('#btnStart').prop('disabled', false);
          }
        }, true); // Pass isNonProduction=true for Non Production tasks
      }
    } else {
      // Production - use dropdown
      if (ACTIVE_MAIN_TASKID) $('#mainTask').val(String(ACTIVE_MAIN_TASKID));
      const mainVal = $('#mainTask').val();
      if (mainVal) {
        loadSubtasks(mainVal, ACTIVE_SUB_TASKID || null, function(){
          if (!isRunningState && ACTIVE_SUB_TASKID) $('#btnStart').prop('disabled', false);
        }, false);
      } else {
        if (!isRunningState) $('#subTask').prop('disabled', true);
      }
    }
  }
  initialShowHideAndPreselect();

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
  @else
    setStateIdle();
  @endif

});
</script>
@endsection
