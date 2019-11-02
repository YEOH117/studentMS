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
