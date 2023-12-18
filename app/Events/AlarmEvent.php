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
    private $username;

    /**
     * Create a new event instance.
     */
    public function __construct($username, $alarms)
    {
        $this->alarms = $alarms;
        $this->username = $username;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        dd($this->username);
        return [new PrivateChannel('user-alarms-'.$this->username, $this->alarms)];
    }
}
