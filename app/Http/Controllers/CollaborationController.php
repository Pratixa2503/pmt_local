<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationStat;
use App\Models\Message;
use App\Models\MessageRead;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Events\MessageSent;


class CollaborationController extends Controller
{
    /**
     * List the single conversation for a project (or create if missing).
     * Route: GET /projects/{encryptedId}/conversation
     */
    public function showProjectConversation(string $encryptedId, Request $request)
    {
        $projectId = Crypt::decryptString($encryptedId);
        $project   = Project::findOrFail($projectId);

        $this->authorizeProjectAccess($projectId); // throws 403 if not a member/client

        // Ensure 1 conversation per project
        $conversation = Conversation::firstOrCreate(
            ['project_id' => $projectId],
            ['id' => (string) Str::uuid()]
        );

        // eager stats
        $conversation->load('stats');

        return view('content.collab.show', [
            'project'      => $project,
            'conversation' => $conversation,
            'encryptedId'  => $encryptedId,
        ]);
    }

    /**
     * Fetch messages (keyset pagination).
     * Query params: before_id, before_ts (ISO8601) or simple page size
     * Route: GET /conversations/{id}/messages
     */
    public function listMessages(string $id, Request $request)
    {
        $conversation = Conversation::findOrFail($id);

        $this->authorizeProjectAccess($conversation->project_id);

        $limit = min(max((int) $request->query('limit', 50), 1), 100);

        $beforeTs = $request->query('before_ts');
        $beforeId = $request->query('before_id');

        $q = Message::where('conversation_id', $conversation->id);

        if ($beforeTs && $beforeId) {
            // keyset: (sent_at, id) <
            $q->where(function ($w) use ($beforeTs, $beforeId) {
                $w->where('sent_at', '<', $beforeTs)
                    ->orWhere(function ($w2) use ($beforeTs, $beforeId) {
                        $w2->where('sent_at', '=', $beforeTs)
                            ->where('id', '<', $beforeId);
                    });
            });
        }

        $messages = $q->orderBy('sent_at', 'desc')
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();

        // Optional: eager sender names if you need (avoid N+1)
        // resolve morphs outside or add DTO mapping here.

        return response()->json([
            'data' => $messages,
            'next' => $messages->last() ? [
                'before_ts' => $messages->last()->sent_at?->toIso8601String(),
                'before_id' => $messages->last()->id,
            ] : null,
        ]);
    }

    /**
     * Post a text message.
     * Body: { body: string, meta?: array }
     * Route: POST /conversations/{id}/messages
     */
    public function sendMessage(string $id, Request $request)
    {


        $conversation = Conversation::findOrFail($id);
        $this->authorizeProjectAccess($conversation->project_id);
        if ($conversation->is_locked && !auth()->user()->hasRole('super admin')) {
            abort(403, 'Conversation is locked.');
        }

        $data = $request->validate([
            'body' => ['nullable', 'string'],
            'meta' => ['nullable', 'array'],
        ]);

        $actor = auth()->user();
        $now   = now();

        //dd($conversation->id);
        $message = DB::transaction(function () use ($conversation, $actor, $data, $now) {
            $msg = Message::create([
                'id'              => (string) \Illuminate\Support\Str::uuid(),
                'conversation_id' => $conversation->id,
                'sender_type'     => get_class($actor),
                'sender_id'       => $actor->getKey(),
                'kind'            => 'text',
                'body'            => $data['body'] ?? null,
                'meta'            => $data['meta'] ?? null,
                'sent_at'         => $now,
            ]);

            // denorm updates (unchanged)
            $preview = \Illuminate\Support\Str::limit(strip_tags((string) ($msg->body ?? '')), 180);
            $conversation->update(['last_message_id' => $msg->id, 'last_message_at' => $msg->sent_at]);
            \App\Models\ConversationStat::updateOrCreate(
                ['conversation_id' => $conversation->id],
                [
                    'message_count'        => DB::raw('message_count + 1'),
                    'last_message_id'      => $msg->id,
                    'last_message_preview' => $preview ?: null,
                    'last_message_at'      => $msg->sent_at,
                    'updated_at'           => $now,
                ]
            );

            // (optional) mark sender read
            \App\Models\MessageRead::updateOrCreate(
                ['message_id' => $msg->id, 'participant_type' => get_class($actor), 'participant_id' => $actor->getKey()],
                ['read_at' => $now]
            );

            return $msg;
        });

        // 🔔 broadcast out of the transaction
        MessageSent::dispatch($message);

        return response()->json(['status' => 1, 'message' => $message], 201);
    }

    /**
     * Mark last N messages read (or a specific message) for current actor.
     * Body: { message_id?: uuid } – if omitted, uses latest message in conversation.
     * Route: POST /conversations/{id}/read
     */
    public function markRead(string $id, Request $request)
    {
        $conversation = Conversation::findOrFail($id);
        $this->authorizeProjectAccess($conversation->project_id);

        $actor = auth()->user(); // or client contact
        $now   = now();

        $messageId = $request->input('message_id');

        if (!$messageId) {
            $latest = Message::where('conversation_id', $conversation->id)
                ->orderByDesc('sent_at')->orderByDesc('id')->first();

            if (!$latest) {
                return response()->json(['status' => 1]); // nothing to read
            }
            $messageId = $latest->id;
        }

        MessageRead::updateOrCreate(
            [
                'message_id'       => $messageId,
                'participant_type' => get_class($actor),
                'participant_id'   => $actor->getKey(),
            ],
            ['read_at' => $now]
        );

        return response()->json(['status' => 1]);
    }

    /**
     * Centralized authorization:
     * - Team member on project (project_user OR project_member_assignments)
     * - OR present as an assigned client contact for the project
     */
    protected function authorizeProjectAccess(int $projectId): void
    {
        $user = auth()->user();
        if (!$user) abort(401);

        // Super Admin can access any conversation
        if (method_exists($user, 'hasRole') && $user->hasRole('super admin')) {
            return;
        }

        // Team via project_user pivot
        $isPM = DB::table('project_user')
            ->where('project_id', $projectId)
            ->where('user_id', $user->id)
            ->exists();
       
        // Team via project_member_assignments
        $isMember = DB::table('project_member_assignments')
            ->where('project_id', $projectId)
            ->where('member_id', $user->id)
            ->exists();

        $isCustomer = DB::table('contact_project')
            ->where('project_id', $projectId)
            ->where('contact_id', $user->id)
            ->exists();
        // Intake role columns (cat=2) — optional, keep if applicable in your app
        $inIntake = DB::table('project_intakes')
            ->where('parent_id', $projectId)
            ->where(function ($q) use ($user) {
                $q->where('abstractor_id', $user->id)
                    ->orWhere('reviewer_id', $user->id)
                    ->orWhere('sense_check_ddr_id', $user->id)
                    ->orWhere('property_manager_id', $user->id);
            })
            ->exists();

        // If you support client contacts logging in, add that check here:
        // $isClientContact = ... (e.g., session guard for clients and relation to project)

        if (!($isPM || $isMember || $inIntake || $isCustomer)) {
            abort(403);
        }
    }

    // app/Http/Controllers/CollaborationController.php

    // app/Http/Controllers/CollaborationController.php

    public function inbox(\Illuminate\Http\Request $request)
    {
        $user = $request->user();

        $projectsQuery = Project::query()
            ->whereNull('projects.parent_id')
            ->select(['projects.id', 'projects.project_name', 'projects.project_category'])
            ->orderBy('projects.project_name')
            ->distinct();

        if (!($user && method_exists($user, 'hasRole') && $user->hasRole('super admin'))) {
            $projectsQuery->where(function ($root) use ($user) {
                // PM via project_member_assignments.pm_id
                $root->orWhereExists(function ($q) use ($user) {
                    $q->selectRaw(1)
                        ->from('project_member_assignments as pma')
                        ->whereColumn('pma.project_id', 'projects.id')
                        ->where('pma.pm_id', $user->id);
                });

                // Team member via project_member_assignments.member_id
                $root->orWhereExists(function ($q) use ($user) {
                    $q->selectRaw(1)
                        ->from('project_member_assignments as pma2')
                        ->whereColumn('pma2.project_id', 'projects.id')
                        ->where('pma2.member_id', $user->id);
                });

                // Customer via contact_project (if user->contact_id is present)
                // if (!empty($user->contact_id)) {
                    $root->orWhereExists(function ($q) use ($user) {
                        $q->selectRaw(1)
                            ->from('contact_project as cp')
                            ->whereColumn('cp.project_id', 'projects.id')
                            ->where('cp.contact_id', $user->id);
                    });
                // }

                // Intake roles (category 2)
                $root->orWhereExists(function ($q) use ($user) {
                    $q->selectRaw(1)
                        ->from('project_intakes as pi')
                        ->whereColumn('pi.parent_id', 'projects.id')
                        ->where(function ($w) use ($user) {
                            $w->where('pi.abstractor_id', $user->id)
                                ->orWhere('pi.reviewer_id', $user->id)
                                ->orWhere('pi.sense_check_ddr_id', $user->id)
                                ->orWhere('pi.property_manager_id', $user->id);
                        });
                });

                // (Optional) legacy pivot
                $root->orWhereExists(function ($q) use ($user) {
                    $q->selectRaw(1)->from('project_user as pu')
                      ->whereColumn('pu.project_id','projects.id')
                      ->where('pu.user_id',$user->id);
                });
            });
        }

        $projects = $projectsQuery->get();

        // If no projects at all, return empty state
        if ($projects->isEmpty()) {
            return view('content.collab.inbox', ['rows' => collect()]);
        }

        // 2) Ensure a conversation exists per project
        $projectIds = $projects->pluck('id')->all();
        $existingConvos = Conversation::whereIn('project_id', $projectIds)->get()->keyBy('project_id');
        //dd($projectIds);
        foreach ($projects as $p) {
            if (!$existingConvos->has($p->id)) {
                $conv = Conversation::create([
                    'id'         => (string) \Illuminate\Support\Str::uuid(),
                    'project_id' => $p->id,
                    // 'is_locked' => false, // if you have this column with default
                ]);
                $existingConvos->put($p->id, $conv);
            }
        }

        // 3) Preload stats for these conversations (last preview/time)
        $convoIds = $existingConvos->pluck('id')->all();

        $stats = ConversationStat::whereIn('conversation_id', $convoIds)
            ->get()
            ->keyBy('conversation_id');

        // 4) Build rows with unread counts (0 if no messages)
        $meType = get_class($user);
        $meId   = (int) $user->id;

        $rows = $projects->map(function ($p) use ($existingConvos, $stats, $meType, $meId) {
            $conv = $existingConvos->get($p->id);
            $stat = $stats->get($conv->id);

            // Unread count: messages in this conversation not read by me (and not sent by me)
            $unread = 0;
            if ($stat && ($stat->message_count ?? 0) > 0) {
                $unread = \DB::table('messages as m')
                    ->leftJoin('message_reads as r', function ($j) use ($meType, $meId) {
                        $j->on('r.message_id', '=', 'm.id')
                            ->where('r.participant_type', $meType)
                            ->where('r.participant_id', $meId);
                    })
                    ->where('m.conversation_id', $conv->id)
                    ->where(function ($q) use ($meType, $meId) {
                        $q->where('m.sender_type', '!=', $meType)
                            ->orWhere('m.sender_id', '!=', $meId);
                    })
                    ->whereNull('r.id')
                    ->count();
            }

            // Fallbacks for preview/time if no messages yet
            $preview = $stat->last_message_preview ?? 'No messages yet';
            $lastAt  = $stat->last_message_at ?? null;

            return (object) [
                'conversation_id'      => $conv->id,
                'project_id'           => $p->id,
                'project_name'         => $p->project_name,
                'last_message_preview' => $preview,
                'last_message_at'      => $lastAt,
                'unread_count'         => $unread, // 0 if no messages
            ];
        });

        return view('content.collab.inbox', ['rows' => $rows]);
    }


    // CollaborationController.php
    public function show(string $id)
    {
        $conversation = \App\Models\Conversation::with('project')->findOrFail($id);
        $this->authorizeProjectAccess($conversation->project_id);

        $user = auth()->user();

        // Mark all messages in this conversation as read for this user
        $now = now();
        $messages = \App\Models\Message::where('conversation_id', $conversation->id)->pluck('id');
        foreach ($messages as $mid) {
            \App\Models\MessageRead::updateOrCreate(
                [
                    'message_id'       => $mid,
                    'participant_type' => get_class($user),
                    'participant_id'   => $user->id,
                ],
                ['read_at' => $now]
            );
        }

        return view('content.collab.show', [
            'conversation' => $conversation,
            'project'      => $conversation->project,
        ]);
    }

    public function markAllRead(string $id)
    {
        $conversation = \App\Models\Conversation::findOrFail($id);
        $this->authorizeProjectAccess($conversation->project_id);

        $user = auth()->user();
        $now  = now();

        $messages = \App\Models\Message::where('conversation_id', $conversation->id)->pluck('id');
        foreach ($messages as $mid) {
            \App\Models\MessageRead::updateOrCreate(
                [
                    'message_id'       => $mid,
                    'participant_type' => get_class($user),
                    'participant_id'   => $user->id,
                ],
                ['read_at' => $now]
            );
        }

        return response()->json(['status' => 1]);
    }
}
