<?php

// app/Events/FightListUpdated.php
namespace App\Events;

use App\Models\Dreamfight;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class FightListUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public $fight;

    public function __construct(Dreamfight $fight)
    {
        $this->fight = $fight;
    }

    public function broadcastOn()
    {
        return new Channel('fights');
    }

    public function broadcastAs()
    {
        return 'FightListUpdated';
    }
}
