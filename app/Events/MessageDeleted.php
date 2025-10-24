<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDeleted implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $messageId;

    public function __construct(int $messageId)
    {
        $this->messageId = $messageId;
    }

    public function broadcastOn()
    {
        return new Channel('chat');
    }

    public function broadcastWith()
    {
        return ['id' => $this->messageId];
    }

    public function broadcastAs()
    {
        return 'message.deleted';
    }
}
