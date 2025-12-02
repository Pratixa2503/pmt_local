<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoNumber extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'project_id',
        'sub_project_id',
        'start_date',
        'end_date',
        'po_number',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'status'     => 'integer',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(\App\Models\Company::class, 'customer_id');
    }

    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class, 'project_id');
    }

    public function subProject()
    {
        return $this->belongsTo(\App\Models\Project::class, 'sub_project_id');
    }
}
