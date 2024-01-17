<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusOnline implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $users;
    public $message;
    public $totalOnline;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $statusOnline, $totalOnline)
    {
        $this->totalOnline = $totalOnline;
        $this->users = $user;
        $this->message  = "{$user} sedang {$statusOnline}";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('status-online');
    }
}
