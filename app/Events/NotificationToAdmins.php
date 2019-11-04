<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationToAdmins
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $title;
    public $content;
    public $admin;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($title,$content,$user)
    {
        $this->title = $title;
        $this->content = $content;
        $this->admin = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('admin');
    }
}
