<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class WorkSession extends LoggableModel
{
   
    protected $fillable = ['task_item_id','user_id','started_at','ended_at','count','notes'];
    protected $casts = ['started_at' => 'datetime', 'ended_at' => 'datetime'];

    public function taskItem() { return $this->belongsTo(TaskItem::class); }

    // Handy accessor for duration (seconds)
    public function getDurationSecondsAttribute(): int
    {
        $end = $this->ended_at ?? now();
        return $end->diffInSeconds($this->started_at);
    }
}
