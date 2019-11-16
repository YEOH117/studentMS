<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\BatchEntry' => [
            'App\Listeners\BatchEntryListener',
        ],
        'App\Events\CreateDormitoryMember' => [
            'App\Listeners\CreateDormitoryMemberListener',
        ],
        'App\Events\UpdateIsArrange' => [
            'App\Listeners\UpdateIsArrangeListener',
        ],
        'App\Events\NotificationToStudents' => [
            'App\Listeners\NotificationToStudentsListener',
        ],
        'App\Events\NotificationToAdmins' => [
            'App\Listeners\NotificationToAdminsListener',
        ],
        'App\Events\SendEmailVerifyCode' => [
            'App\Listeners\SendEmailVerifyCodeListener',
        ],
        'App\Events\SendEmailActive' => [
            'App\Listeners\SendEmailActiveListener',
        ],
        'App\Events\RestPassWordEmail' => [
            'App\Listeners\RestPassWordEmailListener',
        ],
        'App\Events\SendNotice' => [
            'App\Listeners\SendNoticeListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
