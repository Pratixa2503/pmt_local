<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD

class WorkSession extends Model
{
    protected $fillable = ['task_item_id','user_id','started_at','ended_at'];
=======
use Illuminate\Database\Eloquent\SoftDeletes;
class WorkSession extends LoggableModel
{
   
    protected $fillable = ['task_item_id','user_id','started_at','ended_at','counts','notes'];
>>>>>>> 9d9ed85b (for cleaner setup)
    protected $casts = ['started_at' => 'datetime', 'ended_at' => 'datetime'];

    public function taskItem() { return $this->belongsTo(TaskItem::class); }

    // Handy accessor for duration (seconds)
    public function getDurationSecondsAttribute(): int
    {
        $end = $this->ended_at ?? now();
        return $end->diffInSeconds($this->started_at);
    }
<<<<<<< HEAD
=======

  
>>>>>>> 9d9ed85b (for cleaner setup)
}
