<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Models\Project;
use App\Models\MainTask;
use App\Models\SubTask;
use App\Models\TaskItem;
use App\Models\WorkSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class TaskTrackerController extends Controller
{
    public function generalView(string $encryptedId, Request $request)
    {
        $tz        = config('app.timezone', 'Asia/Kolkata');
        $projectId = Crypt::decryptString($encryptedId);
        $project   = Project::with('mainTasks')->findOrFail($projectId);

        $userId    = (int) auth()->id();
        
        // Get only productive main tasks that are selected for this project
        $projectMainTaskIds = $project->mainTasks->pluck('id')->toArray();
        $productiveMainTasks = MainTask::where('task_type', 1)
            ->when(!empty($projectMainTaskIds), function($q) use ($projectMainTaskIds) {
                return $q->whereIn('id', $projectMainTaskIds);
            })
            ->orderBy('name')
            ->pluck('name', 'id');
        
        $generalMainTasks    = MainTask::where('task_type', 2)->orderBy('name')->pluck('name', 'id');

        $open = WorkSession::where('user_id', $userId)
            ->whereNull('ended_at')
            ->latest('started_at')
            ->first();

        $currentRunning   = null;
        $activeTaskType   = null; // 1=Productive, 2=General
        $activeMainTaskId = null;
        $activeSubTaskId  = null;

        if ($open) {
            $item = TaskItem::with([
                'mainTask:id,name,task_type',
                'subTask:id,name'
            ])
                ->where('id', $open->task_item_id)
                ->where('project_id', $projectId)
                ->first();

            if ($item) {
                $currentRunning = [
                    'task_item_id'   => $item->id,
                    'main_task_id'   => $item->main_task_id,
                    'main_task_name' => $item->mainTask?->name,
                    'sub_task_name'  => $item->subTask?->name,
                    'sub_task_id'    => $item->sub_task_id,
                    // emit LOCAL time (no Z / no offset)
                    'started_at'     => $open->started_at?->timezone($tz)->format('Y-m-d H:i:s'),
                    'total_seconds'  => (int) $item->total_seconds,
                ];

                $activeMainTaskId = $item->main_task_id;
                $activeSubTaskId  = $item->sub_task_id ?? null;
                $activeTaskType   = (int) ($item->mainTask?->task_type ?? null);
            }
        }

        // Paused items (no date filter)
        $pausedItems = TaskItem::with(['mainTask:id,name', 'subTask:id,name'])
            ->where([
                'project_id' => $projectId,
                'user_id'    => $userId,
                'status'     => 2, // paused
            ])
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn($p) => [
                'task_item_id'   => $p->id,
                'main_task_name' => $p->mainTask?->name,
                'sub_task_name'  => $p->subTask?->name,
                'total_seconds'  => (int) $p->total_seconds,
            ]);

        // ----- Today window in LOCAL TZ only -----
        $nowLocal   = Carbon::now($tz);
        $startLocal = $nowLocal->copy()->startOfDay();
        $endLocal   = $nowLocal->copy()->endOfDay();

        $todayRows = DB::table('work_sessions as ws')
            ->join('task_items as ti', 'ti.id', '=', 'ws.task_item_id')
            ->join('main_tasks as mt', 'mt.id', '=', 'ti.main_task_id')
            ->join('sub_tasks  as st', 'st.id', '=', 'ti.sub_task_id')
            ->where('ti.project_id', $projectId)
            ->where('ti.user_id', $userId)
            ->where(function ($q) use ($startLocal, $endLocal) {
                // session intersects the [startLocal, endLocal] window
                $q->where('ws.started_at', '<=', $endLocal)
                    ->where(function ($q2) use ($startLocal) {
                        $q2->whereNull('ws.ended_at')
                            ->orWhere('ws.ended_at', '>=', $startLocal);
                    });
            })
            ->select([
                'ti.id as task_item_id',
                'mt.name as main_task',
                'st.name as sub_task',
                // optional: aggregated session bounds if you want to display them
                DB::raw('MIN(ws.started_at) as start_time'),
                DB::raw('MAX(ws.ended_at)   as end_time'),
                DB::raw("
                SUM(
                GREATEST(
                    0,
                    TIMESTAMPDIFF(
                    SECOND,
                    GREATEST(ws.started_at, ?),
                    LEAST(COALESCE(ws.ended_at, ?), ?)
                    )
                )
                ) AS seconds_today
            "),
            ])
            ->groupBy('ti.id', 'mt.name', 'st.name')

            ->orderByDesc('ti.id')
            // bind for the three ? in the SELECT RAW (startLocal, nowLocal, endLocal)
            ->setBindings([$startLocal, $nowLocal, $endLocal], 'select')
            ->get();


        $secondsToday     = (int) $todayRows->sum(fn($r) => (int) $r->seconds_today);
        $targetSeconds    = 9 * 3600;
        $remainingSeconds = max(0, $targetSeconds - $secondsToday);
        $todayDateLocal   = $nowLocal->format('M d, Y');

        return view('content.tasks.track', [
            'title'                => 'Task Tracker',
            'project'              => $project,
            'encryptedId'          => $encryptedId,
            'productiveMainTasks'  => $productiveMainTasks,
            'generalMainTasks'     => $generalMainTasks,
            'currentRunning'       => $currentRunning,
            'pausedItems'          => $pausedItems,
            // Preselect helpers for the view
            'activeTaskType'       => $activeTaskType,    // null|1|2
            'activeMainTaskId'     => $activeMainTaskId,  // null|int
            'activeSubTaskId'      => $activeSubTaskId,
            // Today panel data
            'todayRows'        => $todayRows,
            'secondsToday'     => $secondsToday,
            'targetSeconds'    => $targetSeconds,
            'remainingSeconds' => $remainingSeconds,
            'appTz'            => $tz,
            'todayDateLocal'   => $todayDateLocal,
        ]);
    }

    public function subtasksByMain(int $mainTaskId)
    {
        return SubTask::where('main_task_id', $mainTaskId)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'task_type', 'benchmarked_time']);
    }

    public function start(Request $request)
    {
        $tz   = config('app.timezone', 'Asia/Kolkata');
        $user = Auth::user();

        $data = $request->validate([
            'project_id'   => ['required', 'exists:projects,id'],
            'main_task_id' => ['required', 'exists:main_tasks,id'],
            'sub_task_id'  => ['required', 'exists:sub_tasks,id'],
        ]);

        // Ensure sub_task belongs to main_task and is active
        $subValid = SubTask::where('id', $data['sub_task_id'])
            ->where('main_task_id', $data['main_task_id'])
            ->where('status', 1)
            ->exists();
        if (!$subValid) {
            return response()->json(['status' => 0, 'message' => 'Invalid sub task selected.'], 422);
        }

        $nowLocal = now($tz);

        return DB::transaction(function () use ($user, $data, $nowLocal, $tz) {
            $pausedPrev = null;

            /** @var TaskItem|null $targetItem */
            $targetItem = TaskItem::where([
                'project_id'   => $data['project_id'],
                'user_id'      => $user->id,
                'main_task_id' => $data['main_task_id'],
                'sub_task_id'  => $data['sub_task_id'],
            ])
                ->whereIn('status', [0, 1, 2]) // pending / in_progress / paused
                ->lockForUpdate()
                ->first();

            /** @var WorkSession|null $open */
            $open = WorkSession::where('user_id', $user->id)
                ->whereNull('ended_at')
                ->latest('started_at')
                ->lockForUpdate()
                ->first();

            // If an open session exists for the same item, return idempotent
            if ($open && $targetItem && $open->task_item_id === $targetItem->id) {
                if ($targetItem->status !== 1) {
                    $targetItem->status = 1;
                    if (is_null($targetItem->started_at)) {
                        $targetItem->started_at = $nowLocal;
                    }
                    $targetItem->save();
                }

                return response()->json([
                    'status'        => 1,
                    'message'       => 'Task already running.',
                    'task_item_id'  => $targetItem->id,
                    'started_at'    => $open->started_at?->timezone($tz)->format('Y-m-d H:i:s'),
                    'total_seconds' => (int) $targetItem->total_seconds,
                    'paused_prev'   => null,
                ]);
            }

            // If open on different item → close it & roll time
            if ($open) {
                DB::update(
                    "UPDATE task_items ti
                     JOIN work_sessions ws ON ws.task_item_id = ti.id
                     SET ws.ended_at = ?,
                         ti.total_seconds = ti.total_seconds + GREATEST(TIMESTAMPDIFF(SECOND, ws.started_at, ?), 1),
                         ti.status = 2
                     WHERE ws.id = ? AND ws.user_id = ? AND ws.ended_at IS NULL",
                    [$nowLocal, $nowLocal, $open->id, $user->id]
                );

                $prev = TaskItem::with(['mainTask:id,name', 'subTask:id,name'])
                    ->lockForUpdate()->find($open->task_item_id);

                if ($prev) {
                    $pausedPrev = [
                        'task_item_id'   => $prev->id,
                        'main_task_name' => $prev->mainTask?->name,
                        'sub_task_name'  => $prev->subTask?->name,
                        'total_seconds'  => (int)$prev->total_seconds,
                    ];
                }
            }

            // Ensure we have a target item
            if (!$targetItem) {
                $targetItem = TaskItem::create([
                    'project_id'     => $data['project_id'],
                    'user_id'        => $user->id,
                    'main_task_id'   => $data['main_task_id'],
                    'sub_task_id'    => $data['sub_task_id'],
                    'status'         => 1,
                    'total_seconds'  => 0,
                    'started_at'     => $nowLocal,
                    'completed_at'   => null,
                ]);
            } else {
                if (is_null($targetItem->started_at)) {
                    $targetItem->started_at = $nowLocal;
                }
                $targetItem->status = 1;
                $targetItem->save();
            }

            // open a new (local) work session
            WorkSession::create([
                'task_item_id' => $targetItem->id,
                'user_id'      => $user->id,
                'started_at'   => $nowLocal,
                'ended_at'     => null,
            ]);

            return response()->json([
                'status'        => 1,
                'message'       => 'Task started.',
                'task_item_id'  => $targetItem->id,
                'started_at'    => $nowLocal->format('Y-m-d H:i:s'),
                'total_seconds' => (int)$targetItem->total_seconds,
                'paused_prev'   => $pausedPrev,
            ]);
        });
    }
    public function pause(Request $request)
    {
        $tz = config('app.timezone', 'Asia/Kolkata');
        $user = Auth::user();
        $nowLocal = now($tz);

        $taskItemId = $request->input('task_item_id');

        // 1. Load TaskItem and its SubTask to check count_type
        $taskItem = TaskItem::with('subTask')->find($taskItemId);

        if (!$taskItem) {
            return response()->json(['status' => 0, 'message' => 'Task item not found.'], 404);
        }
        
        // Determine if the count is mandatory (1)
        $isCountMandatory = ($taskItem->subTask && $taskItem->subTask->count_type === 1);

        // 2. Define dynamic validation rules
        $rules = [
            'task_item_id' => 'required|integer|exists:task_items,id',
            'notes'        => 'nullable|string|max:1000',
        ];

        if ($isCountMandatory) {
            // If count is mandatory (count_type = 1), it must be present and at least 1.
            $rules['count'] = 'required|numeric|min:1';
        } else {
            // If count is optional (count_type != 1), it can be 0 or null (though frontend sends 0 if skipped).
            $rules['count'] = 'nullable|numeric|min:0';
        }

        // 3. Apply the dynamic validation
        $request->validate($rules);
        
        // Input is clean and ready
        $newCount = (int)$request->input('count');
        $newNotes = trim($request->input('notes'));

        return DB::transaction(function () use ($user, $taskItem, $nowLocal, $newCount, $newNotes) {
            // Find the currently running session for this user and task item
            $openSession = WorkSession::where('user_id', $user->id)
                ->where('task_item_id', $taskItem->id)
                ->whereNull('ended_at')
                ->latest('started_at')
                ->lockForUpdate()
                ->first();

            if (!$openSession) {
                return response()->json(['status' => 0, 'message' => 'No running session found for this task.']);
            }
            
            // --- 4. Update WorkSession ---
            $openSession->ended_at = $nowLocal;
            $openSession->count = $newCount;
            $openSession->notes = $newNotes;
            $openSession->save();


            // --- 5. Update TaskItem (Aggregate totals) ---
            // $taskItem is already loaded and lockedForUpdate() is not strictly needed here if we rely on the DB::transaction lock,
            // but we'll lock it anyway just in case the transaction doesn't fully cover it.
            // Or, better yet, refetch/lock if we didn't lock it earlier:
            $item = TaskItem::lockForUpdate()->findOrFail($taskItem->id);
            
            // Calculate the duration of the finished session
            $sessionDuration = $openSession->started_at->diffInSeconds($nowLocal);

            // a. Update total_seconds and status
            $item->total_seconds += $sessionDuration;
            $item->total_counts += $newCount; 
            $item->status = 2; // Set to Paused

            // b. Append notes to TaskItem's notes field
            if (!empty($newNotes)) {
                $cleanedNewNotes = trim($newNotes); 
                $prefix = !empty($item->notes) ? "\n---\n" : '';
                $item->notes = trim($item->notes . $prefix . $cleanedNewNotes);
            }
            
            $item->save();

            return response()->json([
                'status'=> 1,
                'message' => 'Task paused, count tt, and notes saved.',
                'task_item_id' => $item->id,
                'total_seconds' => (int)$item->total_seconds,
                'total_counts' => (int)$item->total_counts, 
            ]);
        });
    }

    /* public function pause(Request $request)
    {
        $tz = config('app.timezone', 'Asia/Kolkata');
        $user = Auth::user();
        $nowLocal = now($tz);

        // 1. Validation for input fields
        $request->validate([
            'task_item_id' => 'required|integer|exists:task_items,id',
            'count'        => 'required|numeric|min:0', // Count is now required from the frontend popup
            'notes'        => 'nullable|string|max:1000',
        ]);
        
        $taskItemId = $request->input('task_item_id');
        $newCount   = (int)$request->input('count');
        $newNotes   = trim($request->input('notes'));

        return DB::transaction(function () use ($user, $taskItemId, $nowLocal, $newCount, $newNotes) {
            // Find the currently running session for this user and task item
            $openSession = WorkSession::where('user_id', $user->id)
                ->where('task_item_id', $taskItemId)
                ->whereNull('ended_at')
                ->latest('started_at')
                ->lockForUpdate()
                ->first();

            if (!$openSession) {
                return response()->json(['status' => 0, 'message' => 'No running session found for this task.']);
            }
            
            // --- 2. Update WorkSession (Set End Time, Count, and Notes) ---
            $openSession->ended_at = $nowLocal;
            $openSession->count = $newCount;
            $openSession->notes = $newNotes;
            $openSession->save();


            // --- 3. Update TaskItem (Aggregate totals) ---
            $item = TaskItem::lockForUpdate()->findOrFail($taskItemId);
            
            // Calculate the duration of the finished session
            $sessionDuration = $openSession->started_at->diffInSeconds($nowLocal);

            // a. Update total_seconds and status
            $item->total_seconds += $sessionDuration;
            $item->total_counts += $newCount; // 🌟 Add the new count to the total
            $item->status = 2; // Set to Paused

            // b. Append notes to TaskItem's notes field
             if (!empty($newNotes)) {
                $cleanedNewNotes = trim($newNotes); 
                
                // 🌟 CHANGED: Use a simple separator if notes already exist 🌟
                $prefix = '';
                if (!empty($item->notes)) {
                    $prefix = "\n---\n"; // Simple separator
                }
                
                // Append the new notes
                $item->notes = trim($item->notes . $prefix . $cleanedNewNotes);
            }
            
            $item->save();

            return response()->json([
                'status'        => 1,
                'message'       => 'Task paused ttiui, count, and notes saved.',
                'task_item_id'  => $item->id,
                'total_seconds' => (int)$item->total_seconds,
                'total_counts'  => (int)$item->total_counts, // Return the new total count
            ]);
        });
    } */
    /* public function pause(Request $request)
    {
        $tz         = config('app.timezone', 'Asia/Kolkata');
        $user       = Auth::user();
        $taskItemId = $request->input('task_item_id'); // optional
        $nowLocal   = now($tz);

        return DB::transaction(function () use ($user, $taskItemId, $nowLocal) {
            $q = WorkSession::where('user_id', $user->id)->whereNull('ended_at');
            if ($taskItemId) $q->where('task_item_id', $taskItemId);

            $open = $q->latest('started_at')->lockForUpdate()->first();
            if (!$open) {
                return response()->json(['status' => 0, 'message' => 'No running session found.']);
            }

            DB::update(
                "UPDATE task_items ti
                 JOIN work_sessions ws ON ws.task_item_id = ti.id
                 SET ws.ended_at = ?,
                     ti.total_seconds = ti.total_seconds + GREATEST(TIMESTAMPDIFF(SECOND, ws.started_at, ?), 1),
                     ti.status = 2
                 WHERE ws.id = ? AND ws.user_id = ? AND ws.ended_at IS NULL",
                [$nowLocal, $nowLocal, $open->id, $user->id]
            );

            $item = TaskItem::lockForUpdate()->findOrFail($open->task_item_id);

            return response()->json([
                'status'        => 1,
                'message'       => 'Task paused.',
                'task_item_id'  => $item->id,
                'total_seconds' => (int)$item->total_seconds,
            ]);
        });
    } */

    public function resume(Request $request)
    {
        $tz   = config('app.timezone', 'Asia/Kolkata');
        $user = Auth::user();

        $data = $request->validate([
            'task_item_id' => ['required', 'exists:task_items,id'],
        ]);

        $nowLocal = now($tz);

        return DB::transaction(function () use ($user, $data, $nowLocal, $tz) {
            $open = WorkSession::where('user_id', $user->id)
                ->whereNull('ended_at')
                ->latest('started_at')
                ->lockForUpdate()
                ->first();

            if ($open) {
                DB::update(
                    "UPDATE task_items ti
                     JOIN work_sessions ws ON ws.task_item_id = ti.id
                     SET ws.ended_at = ?,
                         ti.total_seconds = ti.total_seconds + GREATEST(TIMESTAMPDIFF(SECOND, ws.started_at, ?), 1),
                         ti.status = 2
                     WHERE ws.id = ? AND ws.user_id = ? AND ws.ended_at IS NULL",
                    [$nowLocal, $nowLocal, $open->id, $user->id]
                );
            }

            $item = TaskItem::where(['id' => $data['task_item_id'], 'user_id' => $user->id])
                ->lockForUpdate()->firstOrFail();

            if (is_null($item->started_at)) {
                $item->started_at = $nowLocal;
            }
            $item->status = 1;
            $item->save();

            WorkSession::create([
                'task_item_id' => $item->id,
                'user_id'      => $user->id,
                'started_at'   => $nowLocal,
                'ended_at'     => null,
            ]);

            return response()->json([
                'status'        => 1,
                'message'       => 'Task resumed.',
                'task_item_id'  => $item->id,
                'started_at'    => $nowLocal->format('Y-m-d H:i:s'),
                'total_seconds' => (int)$item->total_seconds,
            ]);
        });
    }

    public function end(Request $request)
    {
        $tz         = config('app.timezone', 'Asia/Kolkata');
        $user       = Auth::user();
        $taskItemId = $request->input('task_item_id');
        $nowLocal   = now($tz);

        return DB::transaction(function () use ($user, $taskItemId, $nowLocal, $tz) {
            $q = WorkSession::where('user_id', $user->id)->whereNull('ended_at');
            if ($taskItemId) $q->where('task_item_id', $taskItemId);

            $open = $q->latest('started_at')->lockForUpdate()->first();

            if ($open) {
                DB::update(
                    "UPDATE task_items ti
                     JOIN work_sessions ws ON ws.task_item_id = ti.id
                     SET ws.ended_at = ?,
                         ti.total_seconds = ti.total_seconds + GREATEST(TIMESTAMPDIFF(SECOND, ws.started_at, ?), 1)
                     WHERE ws.id = ? AND ws.user_id = ? AND ws.ended_at IS NULL",
                    [$nowLocal, $nowLocal, $open->id, $user->id]
                );

                $taskItemId = $taskItemId ?: $open->task_item_id;
            }

            if (!$taskItemId) {
                return response()->json(['status' => 0, 'message' => 'No task to end.']);
            }

            $item = TaskItem::where(['id' => $taskItemId, 'user_id' => $user->id])
                ->lockForUpdate()->firstOrFail();

            $item->status = 3; // completed
            $item->completed_at = $nowLocal;
            $item->save();

            return response()->json([
                'status'        => 1,
                'message'       => 'Task completed.',
                'task_item_id'  => $item->id,
                'total_seconds' => (int)$item->total_seconds,
                'completed_at'  => $item->completed_at?->timezone($tz)->format('Y-m-d H:i:s'),
            ]);
        });
    }

    public function adminView(string $encryptedId, Request $request)
    {
        $tz        = config('app.timezone', 'Asia/Kolkata');
        $projectId = Crypt::decryptString($encryptedId);
        $project   = Project::findOrFail($projectId);

        $user = auth()->user();
        if (!(method_exists($user, 'hasRole') && ($user->hasRole('super admin') || $user->hasRole('project manager')))) {
            abort(403);
        }


        $filterUserId = $request->integer('user_id') ?: null;
        $pausedItems = TaskItem::with(['mainTask:id,name', 'subTask:id,name', 'user:id,first_name'])->where(['project_id' => $projectId, 'status' => 2,])->when($filterUserId, fn($q) => $q->where('user_id', $filterUserId))->orderByDesc('updated_at')->get()->map(fn($p) => ['task_item_id' => $p->id, 'user_id' => $p->user_id, 'user_name' => $p->user?->first_name, 'main_task_name' => $p->mainTask?->name, 'sub_task_name' => $p->subTask?->name, 'total_seconds' => (int) $p->total_seconds,]);
        $startDateStr = $request->input('start_date');
        $endDateStr   = $request->input('end_date');

        try {
            $startLocal = $startDateStr
                ? Carbon::parse($startDateStr, $tz)->startOfDay()
                : Carbon::now($tz)->startOfDay();

            $endLocal = $endDateStr
                ? Carbon::parse($endDateStr, $tz)->endOfDay()
                : Carbon::now($tz)->endOfDay();
        } catch (\Exception $e) {
            $startLocal = Carbon::now($tz)->startOfDay();
            $endLocal   = Carbon::now($tz)->endOfDay();
        }

        // Swap if reversed
        if ($startLocal->gt($endLocal)) {
            [$startLocal, $endLocal] = [$endLocal, $startLocal];
        }

        $nowLocal       = Carbon::now($tz);
        $upperOpenLocal = $endLocal->lt($nowLocal) ? $endLocal : $nowLocal;

        // Preload users
        $uidsUnion = DB::query()
            ->fromSub(function ($q) use ($projectId) {
                $q->from('task_items')->select('user_id')->where('project_id', $projectId)
                    ->unionAll(
                        DB::table('project_user')->select('user_id')->where('project_id', $projectId)
                    )
                    ->unionAll(
                        DB::table('project_member_assignments')->select('member_id as user_id')->where('project_id', $projectId)
                    )
                    ->unionAll(
                        DB::table('project_intakes')->select('abstractor_id as user_id')->where('parent_id', $projectId)
                    )
                    ->unionAll(
                        DB::table('project_intakes')->select('reviewer_id as user_id')->where('parent_id', $projectId)
                    )
                    ->unionAll(
                        DB::table('project_intakes')->select('sense_check_ddr_id as user_id')->where('parent_id', $projectId)
                    )
                    ->unionAll(
                        DB::table('project_intakes')->select('property_manager_id as user_id')->where('parent_id', $projectId)
                    );
            }, 'assoc')
            ->select('user_id')
            ->whereNotNull('user_id')
            ->distinct();

        $projectUsers = DB::table('users as u')
            ->joinSub($uidsUnion, 'assoc', 'assoc.user_id', '=', 'u.id')
            ->orderBy('u.first_name')
            ->get(['u.id', 'u.first_name']);

        // Running sessions (no date filter)
        $running = WorkSession::with([
            'taskItem:id,project_id,user_id,main_task_id,sub_task_id,total_seconds,status',
            'taskItem.mainTask:id,name',
            'taskItem.subTask:id,name',
            'taskItem.user:id,first_name'
        ])
            ->whereNull('ended_at')
            ->whereHas('taskItem', function ($q) use ($projectId, $filterUserId) {
                $q->where('project_id', $projectId);
                if ($filterUserId) $q->where('user_id', $filterUserId);
            })
            ->orderByDesc('started_at')
            ->get()
            ->map(function ($ws) use ($tz) {
                return [
                    'task_item_id'   => $ws->task_item_id,
                    'user_id'        => $ws->taskItem?->user_id,
                    'user_name'      => $ws->taskItem?->user?->first_name,
                    'main_task_name' => $ws->taskItem?->mainTask?->name,
                    'sub_task_name'  => $ws->taskItem?->subTask?->name,
                    'started_at'     => $ws->started_at?->timezone($tz)->format('Y-m-d H:i:s'),
                    'total_seconds'  => (int)($ws->taskItem?->total_seconds ?? 0),
                ];
            });

        $todayRows = DB::table('work_sessions as ws')
            ->join('task_items as ti', 'ti.id', '=', 'ws.task_item_id')
            ->join('main_tasks as mt', 'mt.id', '=', 'ti.main_task_id')
            ->join('sub_tasks  as st', 'st.id', '=', 'ti.sub_task_id')
            ->join('users as u',  'u.id',  '=', 'ti.user_id')
            ->where('ti.project_id', $projectId)
            ->when($filterUserId, fn($q) => $q->where('ti.user_id', $filterUserId))
            ->where(function ($q) use ($startLocal, $endLocal) {
                $q->where('ws.started_at', '<=', $endLocal)
                    ->where(function ($q2) use ($startLocal) {
                        $q2->whereNull('ws.ended_at')
                            ->orWhere('ws.ended_at', '>=', $startLocal);
                    });
            })
            ->groupBy('ti.user_id', 'u.first_name', 'mt.name', 'st.name', 'ti.id')
            ->orderByDesc('ti.id')
            ->select([
                'ti.user_id',
                'u.first_name as user_name',
                'mt.name as main_task',
                'st.name as sub_task',
                DB::raw("
            SUM(
              GREATEST(
                0,
                TIMESTAMPDIFF(
                  SECOND,
                  GREATEST(ws.started_at, ?),
                  LEAST(COALESCE(ws.ended_at, ?), ?)
                )
              )
            ) AS seconds_today
        "),
                // earliest clamped start within the window
                DB::raw("MIN(GREATEST(ws.started_at, ?)) as start_time"),
                // latest clamped end within the window
                DB::raw("MAX(LEAST(COALESCE(ws.ended_at, ?), ?)) as end_time"),
                'ti.id as task_item_id',
            ])

            ->setBindings([
                $startLocal,
                $upperOpenLocal,
                $endLocal,
                $startLocal,
                $upperOpenLocal,
                $endLocal,
            ], 'select')
            ->get();
        $secondsToday     = (int) $todayRows->sum(fn($r) => (int) $r->seconds_today);
        $targetSeconds    = 9 * 3600;
        $remainingSeconds = max(0, $targetSeconds - $secondsToday);
        $todayDateLocal   = $startLocal->format('M d, Y') . ' – ' . $endLocal->format('M d, Y');

        return view('content.tasks.admin.track', [
            'title'            => 'Project Tracking (Admin)',
            'project'          => $project,
            'encryptedId'      => $encryptedId,

            'projectUsers'     => $projectUsers,
            'filterUserId'     => $filterUserId,

            'running'          => $running,
            'pausedItems'      => $pausedItems,
            'todayRows'        => $todayRows,

            'secondsToday'     => $secondsToday,
            'targetSeconds'    => $targetSeconds,
            'remainingSeconds' => $remainingSeconds,
            'appTz'            => $tz,
            'todayDateLocal'   => $todayDateLocal,

            'startDate'        => $startLocal->toDateString(),
            'endDate'          => $endLocal->toDateString(),
        ]);
    }


    // app/Http/Controllers/TaskTrackerController.php

    public function todayFragment(string $encryptedId)
    {
        $tz        = config('app.timezone', 'Asia/Kolkata');
        $projectId = \Illuminate\Support\Facades\Crypt::decryptString($encryptedId);
        $userId    = (int) auth()->id();

        // ----- Today window in LOCAL TZ only (same as initial page load) -----
        $nowLocal   = Carbon::now($tz);
        $startLocal = $nowLocal->copy()->startOfDay();
        $endLocal   = $nowLocal->copy()->endOfDay();

        $todayRows = DB::table('work_sessions as ws')
            ->join('task_items as ti', 'ti.id', '=', 'ws.task_item_id')
            ->join('main_tasks as mt', 'mt.id', '=', 'ti.main_task_id')
            ->join('sub_tasks  as st', 'st.id', '=', 'ti.sub_task_id')
            ->where('ti.project_id', $projectId)
            ->where('ti.user_id', $userId)
            ->where(function ($q) use ($startLocal, $endLocal) {
                // session intersects the [startLocal, endLocal] window
                $q->where('ws.started_at', '<=', $endLocal)
                    ->where(function ($q2) use ($startLocal) {
                        $q2->whereNull('ws.ended_at')
                            ->orWhere('ws.ended_at', '>=', $startLocal);
                    });
            })
            ->select([
                'ti.id as task_item_id',
                'mt.name as main_task',
                'st.name as sub_task',
                // optional: aggregated session bounds if you want to display them
                DB::raw('MIN(ws.started_at) as start_time'),
                DB::raw('MAX(ws.ended_at)   as end_time'),
                DB::raw("
                SUM(
                GREATEST(
                    0,
                    TIMESTAMPDIFF(
                    SECOND,
                    GREATEST(ws.started_at, ?),
                    LEAST(COALESCE(ws.ended_at, ?), ?)
                    )
                )
                ) AS seconds_today
            "),
            ])
            ->groupBy('ti.id', 'mt.name', 'st.name')
            ->orderByDesc('ti.id')
            // bind for the three ? in the SELECT RAW (startLocal, nowLocal, endLocal) - same as initial load
            ->setBindings([$startLocal, $nowLocal, $endLocal], 'select')
            ->get();

        $secondsToday = (int) $todayRows->sum(fn($r) => (int) $r->seconds_today);

        // Render small partials (tbody + optional tfoot)
        $tbody = view('content.tasks.partials.today_tbody', compact('todayRows'))->render();
        $tfoot = view('content.tasks.partials.today_tfoot', compact('todayRows', 'secondsToday'))->render();

        return response()->json([
            'tbody' => $tbody,
            'tfoot' => $tfoot,
            'count' => $todayRows->count(), // Add count for badge update
        ]);
    }

 public function adminExport(string $encryptedId, Request $request)
{
    $tz        = config('app.timezone', 'Asia/Kolkata');
    $projectId = Crypt::decryptString($encryptedId);

    $project = \App\Models\Project::findOrFail($projectId);
    $filterUserId = $request->integer('user_id') ?: null;

    // Date window parsing and validation
    try {
        $startLocal = $request->filled('start_date')
            ? \Carbon\Carbon::parse($request->input('start_date'), $tz)->startOfDay()
            : \Carbon\Carbon::now($tz)->startOfDay();

        $endLocal = $request->filled('end_date')
            ? \Carbon\Carbon::parse($request->input('end_date'), $tz)->endOfDay()
            : \Carbon\Carbon::now($tz)->endOfDay();
    } catch (\Throwable $e) {
        $startLocal = \Carbon\Carbon::now($tz)->startOfDay();
        $endLocal   = \Carbon\Carbon::now($tz)->endOfDay();
    }
    if ($startLocal->gt($endLocal)) {
        [$startLocal, $endLocal] = [$endLocal, $startLocal];
    }

    $nowLocal       = \Carbon\Carbon::now($tz);
    $upperOpenLocal = $endLocal->lt($nowLocal) ? $endLocal : $nowLocal;

    // Query (unchanged from your latest): seconds_total + clipped start/end
    $rows = DB::table('work_sessions as ws')
        ->join('task_items as ti', 'ti.id', '=', 'ws.task_item_id')
        ->join('main_tasks as mt', 'mt.id', '=', 'ti.main_task_id')
        ->join('sub_tasks  as st', 'st.id', '=', 'ti.sub_task_id')
        ->join('users as u',  'u.id',  '=', 'ti.user_id')
        ->where('ti.project_id', $projectId)
        ->when($filterUserId, fn($q) => $q->where('ti.user_id', $filterUserId))
        ->where(function ($q) use ($startLocal, $endLocal) {
            $q->where('ws.started_at', '<=', $endLocal)
              ->where(function ($q2) use ($startLocal) {
                  $q2->whereNull('ws.ended_at')
                     ->orWhere('ws.ended_at', '>=', $startLocal);
              });
        })
        ->groupBy('ti.user_id', 'u.first_name', 'mt.name', 'st.name', 'ti.id','ti.total_counts', 'ti.notes')
        ->orderBy('u.first_name')
        ->orderBy('mt.name')
        ->orderBy('st.name')
        ->select([
            'ti.user_id',
            'u.first_name as user_name',
            'mt.name as main_task',
            'st.name as sub_task',
            'ti.total_counts', 
            'ti.notes',
            DB::raw("
                SUM(
                  GREATEST(
                    0,
                    TIMESTAMPDIFF(
                      SECOND,
                      GREATEST(ws.started_at, ?),
                      LEAST(COALESCE(ws.ended_at, ?), ?)
                    )
                  )
                ) AS seconds_total
            "),

            DB::raw("MIN(GREATEST(ws.started_at, ?)) AS start_time"),
            DB::raw("MAX(LEAST(COALESCE(ws.ended_at, ?), ?)) AS end_time"),

            'ti.id as task_item_id',
        ])
        ->setBindings(
            [
                // seconds_total
                $startLocal, $upperOpenLocal, $endLocal,
                // start_time
                $startLocal,
                // end_time
                $upperOpenLocal, $endLocal,
            ],
            'select'
        )
        ->get();

    $filename = sprintf(
        'tracking_%s_%s_to_%s.csv',
        \Illuminate\Support\Str::slug($project->project_name ?: 'project'),
        $startLocal->format('Ymd'),
        $endLocal->format('Ymd')
    );

    // ⬇️ CSV: replace “Seconds” with Hours, Minutes, Seconds columns
    $callback = function () use ($rows, $project, $startLocal, $endLocal) {
        echo "\xEF\xBB\xBF"; // UTF-8 BOM for Excel

        $out = fopen('php://output', 'w');

        // Header Row
        fputcsv($out, [
            'Project',
            'Date Range (local)',
            'User',
            'Main Task',
            'Sub Task',
            'Start Time',
            'End Time',
            'Hours',
            'Minutes',
            'Seconds',
            'HH:MM:SS',
             'Total Count', 
            'Notes History',
        ]);

        $dateRange = $startLocal->format('Y-m-d') . ' to ' . $endLocal->format('Y-m-d');

        foreach ($rows as $r) {
            $secTotal = max(0, (int) $r->seconds_total);
            $hours    = intdiv($secTotal, 3600);
            $minutes  = intdiv($secTotal % 3600, 60);
            $seconds  = $secTotal % 60;
            $hms      = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            // Format start/end as mm-dd-YYYY HH:ii:ss
            $startFmt = $r->start_time
                ? \Carbon\Carbon::parse($r->start_time)->format('m-d-Y H:i:s')
                : '';
            $endFmt   = $r->end_time
                ? \Carbon\Carbon::parse($r->end_time)->format('m-d-Y H:i:s')
                : '';

            fputcsv($out, [
                $project->project_name,
                $dateRange,
                $r->user_name,
                $r->main_task,
                $r->sub_task,
                $startFmt,
                $endFmt,
                $hours,
                $minutes,
                $seconds,
                $hms,
                $r->total_counts, 
                $r->notes,
            ]);
        }

        fclose($out);
    };

    return response()->stream($callback, 200, [
        'Content-Type'        => 'text/csv; charset=UTF-8',
        'Cache-Control'       => 'no-store, no-cache, must-revalidate',
        'Pragma'              => 'no-cache',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ]);
}

public function count(Request $request)
{
    //dd($request);
    // 1. Validation and Input
    // Ensure task_item_id is present and exists in the task_items table.
    // The 'exists' rule handles the case of "No query results for model" exception.
    $request->validate(['task_item_id' => 'required|integer|exists:task_items,id']);
    
    $taskItemId = $request->input('task_item_id');

  
    // 2. Query the TaskItem for the summary fields
    // We use findOrFail here because validation already checked existence, but find is safer.
    // Since the TaskItem should exist (based on validation), we'll use find.
    $item = TaskItem::find($taskItemId);

    // This check is a failsafe, but validation should prevent it from being reached.
    if (!$item) {
        return response()->json(['status' => 0, 'message' => 'Task Item not found.']);
    }

    // 3. Return JSON Response with the two summary fields
    return response()->json([
        'status' => 1, 
        // Return the fields directly from the TaskItem model
        'total_counts' => (int)$item->total_counts,
        'notes_history' => $item->notes // This contains all appended notes
    ]);
}

}
