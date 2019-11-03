<?php

namespace App\Listeners;

use App\Events\NotificationToStudents;
use App\Notifications\NotificationToStudent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationToStudentsListener
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
     * @param  NotificationToStudents  $event
     * @return void
     */
    public function handle(NotificationToStudents $event)
    {
        $target = $event->target;
        //发送通知
        $target->notify(new NotificationToStudent($event->title,$event->content));
    }
}
