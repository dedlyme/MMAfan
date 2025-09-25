<?php
// app/Events/FightUpdated.php
namespace App\Events;

use App\Models\Dreamfight;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class FightUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public $fight;

    public function __construct(Dreamfight $fight)
    {
        $this->fight = $fight;
    }

    public function broadcastOn()
    {
        return new Channel('fight.' . $this->fight->id);
    }

    public function broadcastAs()
    {
        return 'FightUpdated';
    }
}
