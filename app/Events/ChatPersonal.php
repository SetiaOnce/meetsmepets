<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatPersonal implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $from_user;
    public $to_user;
    public $key_chat;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($from, $to, $key_chat, $message)
    {
        $this->from_user = $from;
        $this->to_user = $to;
        $this->key_chat = $key_chat;
        $this->message  = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chat-personal');
    }
}
