<?php

namespace App\Listeners;

use App\Events\NotificationToAdmins;
use App\Notifications\NotificationToAdmin;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationToAdminsListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationToAdmins  $event
     * @return void
     */
    public function handle(NotificationToAdmins $event)
    {
        foreach ($event->admin as $user){
            //发送通知
            $user->notify(new NotificationToAdmin($event->title,$event->content));
        }
    }
}
