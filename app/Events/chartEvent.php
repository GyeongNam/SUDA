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
    public $user;
    public $channel;
    public $room_name;
    public $chat_idx;
    public $time;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user , $channel, $m, $room_name,$chat_idx,$time)
    {
        $this->message = $m;
        $this->user = $user;
        $this->channel = $channel;
        $this->room_name = $room_name;
        $this->chat_idx = $chat_idx;
        $this->time = $time;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel($this->channel);
    }
    // public function broadcastWith()
    // {
    //   return [
    //     "message" => "hello world",
    //     "socket1" => "그런거 없다",
    //   ];
    // }
}
