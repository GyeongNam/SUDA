<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class chartEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    public $id1;
    public $id2;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id1 , $id2, $m)
    {
        $this->message = $m;
        $this->id1 = $id1;
        $this->id2 = $id2;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('ccit');
    }
    // public function broadcastWith()
    // {
    //   return [
    //     "message" => "hello world",
    //     "socket1" => "그런거 없다",
    //   ];
    // }
}
