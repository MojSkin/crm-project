<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AlarmEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $alarms;

    /**
     * Create a new event instance.
     */
    public function __construct($alarms)
    {
        $this->alarms = $alarms;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $events = [];
        foreach ($this->alarms as $alarm) {
            $key = $alarm[0]->user->username;
            $events[] = new PrivateChannel('user-alarms-'.$key, $alarm);
        }
//        return $events;
        return [new PrivateChannel('user-alarms')];
    }
}
