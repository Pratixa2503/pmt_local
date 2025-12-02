<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class TaskItem extends LoggableModel
{
    
    protected $fillable = [
        'project_id','user_id','main_task_id','sub_task_id',
        'status','total_seconds','total_counts','started_at','completed_at','notes'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function project()   { return $this->belongsTo(Project::class); }
    public function user()      { return $this->belongsTo(User::class); }
    public function mainTask()  { return $this->belongsTo(MainTask::class); }
    public function subTask()   { return $this->belongsTo(SubTask::class); }
    public function sessions()  { return $this->hasMany(WorkSession::class); }
}
