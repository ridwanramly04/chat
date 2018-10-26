<?php

namespace App\Events;

use App\Message;
use App\Conversation;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSend extends Event implements ShouldBroadcast
{
    use SerializesModels;
    // public $conversation;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->conversation = $conversation;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
       return ['conversation.'];
    }

    public function broadcastWith()
    {
        return ['user' => 'test'];
    }
}
