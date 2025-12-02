<?php
// app/Models/IntakeQuery.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntakeQuery extends LoggableModel
{
   
    protected $table = 'intake_queries';

    protected $fillable = [
        'intake_id',
        'type_of_queries_id',
        'query_status_id',
        'sb_queries',
        'client_response',
        'query_raised_date',
        'query_resolved_date',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'query_raised_date'   => 'date:Y-m-d',
        'query_resolved_date' => 'date:Y-m-d',
    ];

    // If you have a model for the intake row (rename class & FK as needed):
    // public function intakeRow()
    // {
    //     return $this->belongsTo(\App\Models\ProjectIntakeRow::class, 'intake_id');
    // }
}
