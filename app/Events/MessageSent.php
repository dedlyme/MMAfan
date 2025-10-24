<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = [
            'id' => $message->id,
            'user' => [
                'id' => $message->user->id,
                'name' => $message->user->name,
            ],
            'message' => $message->message,
            'created_at' => $message->created_at
                ? $message->created_at->toDateTimeString()
                : now()->toDateTimeString(),
        ];
    }

    public function broadcastOn()
    {
        return new Channel('chat');
    }

    public function broadcastWith()
    {
        return $this->message;
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}
