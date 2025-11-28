<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // use Now to avoid queue issues during setup
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;


class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public string $conversationId;
    public array $payload;

    public function __construct(Message $message)
    {
        $this->conversationId = $message->conversation_id;
        $this->payload = [
            'id'              => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_type'     => $message->sender_type,
            'sender_id'       => $message->sender_id,
            'kind'            => $message->kind,
            'body'            => $message->body,
            'meta'            => $message->meta,
            'sent_at'         => optional($message->sent_at)->toIso8601String(),
            'created_at'      => optional($message->created_at)->toIso8601String(),
            // 👇 Add name from the sender relation
    'sender_name'     => optional($message->sender)->first_name 
                        ?? optional($message->sender)->name 
                        ?? 'Unknown',
        ];
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel("conversation.{$this->conversationId}")];
    }

    // Give it a short name to listen for
    public function broadcastAs(): string
    {
        return 'MessageSent';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
