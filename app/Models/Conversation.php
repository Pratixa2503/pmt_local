<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'project_id', 'is_locked',
        'last_message_id', 'last_message_at',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'last_message_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)
            ->orderBy('sent_at', 'desc')->orderBy('id', 'desc');
    }

    public function stats()
    {
        return $this->hasOne(ConversationStat::class, 'conversation_id');
    }
}
