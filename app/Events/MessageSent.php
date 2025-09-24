<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load('user');
    }

    public function broadcastOn()
    {
        return new Channel('chat'); // must match frontend Echo.channel('chat')
    }

    public function broadcastAs()
    {
        return 'MessageSent'; // matches frontend listen('.MessageSent')
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'user' => [
                'id' => $this->message->user->id,
                'name' => $this->message->user->name,
            ],
            'message' => $this->message->message,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
}
