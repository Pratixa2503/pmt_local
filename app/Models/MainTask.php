<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD

class MainTask extends Model
{
=======
use Illuminate\Database\Eloquent\SoftDeletes;

class MainTask extends LoggableModel
{
    use SoftDeletes;
>>>>>>> 9d9ed85b (for cleaner setup)
    protected $fillable = ['name','status','task_type'];
    public function subtasks() {
        return $this->hasMany(SubTask::class);
    }
}
