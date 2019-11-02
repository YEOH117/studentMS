<?php

namespace App\Listeners;

use App\Events\UpdateIsArrange;
use App\Models\Student;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateIsArrangeListener implements ShouldQueue
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
     * @param  UpdateIsArrange  $event
     * @return void
     */
    public function handle(UpdateIsArrange $event)
    {
        //
        Student::whereIn('id',$event->id)->update([
            'is_arrange' => 1,
        ]);
    }

    public function failed(OrderShipped $event, $exception)
    {
        session()->flash('danger','录入时发生未知错误，请稍后再试！');
    }
}
