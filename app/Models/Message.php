<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $appends = ['sender_name'];
    
    protected $fillable = [
        'id', 'conversation_id',
        'sender_type', 'sender_id',
        'kind', 'body', 'meta', 'sent_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'sent_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->morphTo(__FUNCTION__, 'sender_type', 'sender_id');
    }

    public function reads()
    {
        return $this->hasMany(MessageRead::class);
    }

    public function getSenderNameAttribute(): string
    {
        $s = $this->sender;
        if (!$s) return 'Unknown';

        // try common fields in order
        foreach (['first_name', 'name', 'full_name', 'company_name'] as $attr) {
            if (!empty($s->{$attr})) return (string) $s->{$attr};
        }
        // final fallback
        return $s->email ?? 'Unknown';
    }
}
