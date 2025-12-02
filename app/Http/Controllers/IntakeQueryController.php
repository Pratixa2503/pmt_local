<?php

namespace App\Http\Controllers;

use App\Models\IntakeQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IntakeQueryController extends Controller
{
    /**
     * GET /intake-queries?intake_id=123
     * Return all queries for one intake row.
     */
    public function index(Request $request)
    {
        $v = Validator::make($request->all(), [
            'intake_id' => ['required','integer'],
        ]);

        if ($v->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $v->errors()], 422);
        }

        $intakeId = (int) $request->intake_id;

        $items = IntakeQuery::query()
            ->where('intake_id', $intakeId)
            ->orderByDesc('id')
            ->get();


        return response()->json(['data' => $items], 200);
    }

    /**
     * POST /intake-queries
     * Accepts bulk (your modal form shape) and creates/updates rows.
     *
     * Payload example:
     *  intake_id: 123
     *  queries: [
     *    { id: 10, type_of_queries_id: 1, query_status_id: 2, sb_queries: "...", client_response: "...",
     *      query_raised_date: "2025-01-10", query_resolved_date: "2025-01-15" },
     *    { id: null, ... }
     *  ]
     *  deleted_ids: [5, 7]
     */
    public function store(Request $request)
    {
        return $this->bulkUpsert($request);
    }

    /**
     * POST /intake-queries/bulk
     * Same as store; provided in case you prefer a separate route.
     */
    public function bulk(Request $request)
    {
        return $this->bulkUpsert($request);
    }

    /**
     * DELETE /intake-queries/{intakeQuery}
     * Delete a single query row.
     */
    public function destroy(IntakeQuery $intakeQuery)
    {
        $intakeQuery->delete();

        return response()->json([
            'message' => 'Deleted',
            'id'      => $intakeQuery->id,
        ], 200);
    }

    /**
     * Core bulk create/update/delete.
     */
protected function bulkUpsert(Request $request)
{
    // Normalize incoming array key: you might post `queries` or `items`
    $rows       = $request->input('queries', $request->input('items', []));
    $deletedIds = array_filter((array) $request->input('deleted_ids', []));
    $intakeId   = $request->input('intake_id');

    // Top-level validation
    $top = Validator::make($request->all(), [
        'intake_id'   => ['required','integer'],
        'queries'     => ['sometimes','array'],
        'items'       => ['sometimes','array'],
        'deleted_ids' => ['sometimes','array'],
    ]);

    if ($top->fails()) {
        return response()->json(['message' => 'Validation error', 'errors' => $top->errors()], 422);
    }

    // Row-level mapping (accept synonyms from various UIs and map to DB columns)
    $mapRow = function (array $row): array {
        return [
            'id'                  => $row['id']                  ?? null,
            'type_of_queries_id'  => $row['type_of_queries_id']  ?? ($row['type_id'] ?? null),
            'query_status_id'     => $row['query_status_id']     ?? ($row['status_id'] ?? null),
            'sb_queries'          => $row['sb_queries']          ?? ($row['sb_query'] ?? null),
            'client_response'     => $row['client_response']     ?? null,
            'query_raised_date'   => $row['query_raised_date']   ?? ($row['raised_date'] ?? null),
            'query_resolved_date' => $row['query_resolved_date'] ?? ($row['resolved_date'] ?? null),
        ];
    };

    $errors = [];
    $clean  = [];

    foreach ((array) $rows as $idx => $row) {
        $payload = $mapRow((array) $row);

        if (!empty($payload['client_response']) && empty($payload['query_resolved_date'])) {
            $payload['query_resolved_date'] = now()->toDateString(); // Y-m-d
            $payload['query_status_id'] = 2;
        }

        // Validate each normalized row
        $v = Validator::make($payload, [
            'id'                  => ['nullable','integer'],
            // Both type & status allowed to be null if your schema permits it
            'type_of_queries_id'  => ['nullable','integer'],
            'query_status_id'     => ['nullable','integer'],
            'sb_queries'          => ['nullable','string'],
            'client_response'     => ['nullable','string'],
            'query_raised_date'   => ['nullable','date_format:Y-m-d'],
            'query_resolved_date' => ['nullable','date_format:Y-m-d','after_or_equal:query_raised_date'],
        ], [
            'query_resolved_date.after_or_equal' => 'Resolved date must be on/after Raised date.',
        ]);

        if ($v->fails()) {
            $errors["rows.$idx"] = $v->errors()->toArray();
        } else {
            $clean[] = $payload;
        }
    }

    if (!empty($errors)) {
        return response()->json([
            'message' => 'Row validation error',
            'errors'  => $errors,
        ], 422);
    }

    // Persist
    $created = 0; $updated = 0; $deleted = 0;

    DB::transaction(function () use ($clean, $deletedIds, $intakeId, &$created, &$updated, &$deleted) {
        // Deletes (only those that belong to this intake)
        if (!empty($deletedIds)) {
            $deleted = IntakeQuery::query()
                ->whereIn('id', $deletedIds)
                ->where('intake_id', $intakeId)
                ->delete();
        }

        // Upsert rows
        foreach ($clean as $row) {
            $nowUserId = Auth::id();

            if (!empty($row['id'])) {
                // Update existing (scoped by intake)
                $iq = IntakeQuery::query()
                    ->where('id', $row['id'])
                    ->where('intake_id', $intakeId)
                    ->first();

                if ($iq) {
                    $iq->fill([
                        'type_of_queries_id'  => $row['type_of_queries_id'],
                        'query_status_id'     => $row['query_status_id'],
                        'sb_queries'          => $row['sb_queries'],
                        'client_response'     => $row['client_response'],
                        'query_raised_date'   => $row['query_raised_date'],
                        'query_resolved_date' => $row['query_resolved_date'],
                    ]);
                    if ($nowUserId) {
                        $iq->updated_by = $nowUserId;
                    }
                    $iq->save();
                    $updated++;
                } else {
                    // Not found in this intake — create a fresh one
                    $new = new IntakeQuery();
                    $new->intake_id            = $intakeId;
                    $new->type_of_queries_id   = $row['type_of_queries_id'];
                    $new->query_status_id      = $row['query_status_id'];
                    $new->sb_queries           = $row['sb_queries'];
                    $new->client_response      = $row['client_response'];
                    $new->query_raised_date    = $row['query_raised_date'];
                    $new->query_resolved_date  = $row['query_resolved_date'];
                    if ($nowUserId) {
                        $new->created_by = $nowUserId;
                        $new->updated_by = $nowUserId;
                    }
                    $new->save();
                    $created++;
                }
            } else {
                // Create new
                $new = new IntakeQuery();
                $new->intake_id            = $intakeId;
                $new->type_of_queries_id   = $row['type_of_queries_id'];
                $new->query_status_id      = $row['query_status_id'];
                $new->sb_queries           = $row['sb_queries'];
                $new->client_response      = $row['client_response'];
                $new->query_raised_date    = $row['query_raised_date'];
                $new->query_resolved_date  = $row['query_resolved_date'];
                if ($nowUserId) {
                    $new->created_by = $nowUserId;
                    $new->updated_by = $nowUserId;
                }
                $new->save();
                $created++;
            }
        }
    });

    // Return fresh list so the UI can refresh immediately
    $fresh = IntakeQuery::query()
        ->where('intake_id', $intakeId)
        ->orderBy('id')
        ->get();

    return response()->json([
        'message' => 'Saved',
        'stats'   => compact('created','updated','deleted'),
        'data'    => $fresh,
    ], 200);
}

}
