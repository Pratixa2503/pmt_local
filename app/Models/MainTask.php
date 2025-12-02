<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainTask extends LoggableModel
{
    use SoftDeletes;
    protected $fillable = ['name','status','task_type'];
    public function subtasks() {
        return $this->hasMany(SubTask::class);
    }
}
