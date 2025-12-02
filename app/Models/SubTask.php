<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubTask extends LoggableModel
{
    use SoftDeletes;
    protected $fillable = ['main_task_id','name','task_type','count_type','benchmarked_time','status'];

    public function mainTask() {
        return $this->belongsTo(MainTask::class);
    }

    public function getTaskTypeLabelAttribute(): string
    {
        return $this->task_type === 1 ? 'Production' : 'Non-Production';
    }
}
