<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateDormitoryMember
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $dormitoryId;
    public  $studentId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($dormitoryId,$studentId)
    {
        //
        $this->dormitoryId = $dormitoryId;
        $this->studentId = $studentId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
