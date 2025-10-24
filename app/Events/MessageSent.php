<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $message; // the message model (or array)

    /**
     * Create a new event instance.
     *
     * @param  mixed  $message
     * @return void
     */
    public function __construct($message)
    {
        // Make sure the payload is serializable and small
        $this->message = [
            'id' => $message->id,
            'user' => [
                'id' => $message->user->id,
                'name' => $message->user->name,
            ],
            'message' => $message->message,
            'created_at' => $message->created_at ? $message->created_at->toDateTimeString() : now()->toDateTimeString()
        ];
    }

    /**
     * The channel the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chat'); // public channel 'chat'
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
