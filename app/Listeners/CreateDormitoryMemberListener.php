<?php

namespace App\Listeners;

use App\Events\CreateDormitoryMember;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Dormitory_member;

class CreateDormitoryMemberListener implements ShouldQueue
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
     * @param  CreateDormitoryMember  $event
     * @return void
     */
    public function handle(CreateDormitoryMember $event)
    {
        $DormitoryMember = Dormitory_member::create([
            'dormitory_id' => $event->dormitoryId,
            'student_id' => $event->studentId,
        ]);
    }

    public function failed(OrderShipped $event, $exception)
    {
        session()->flash('danger','录入时发生未知错误，请稍后再试！');
    }
}
