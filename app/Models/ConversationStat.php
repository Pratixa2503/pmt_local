<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationStat extends Model
{
    protected $primaryKey = 'conversation_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'conversation_id',
        'message_count',
        'last_message_id',
        'last_message_preview',
        'last_message_at',
        'updated_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
