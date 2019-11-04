<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

        'App\Models\Profession_code' => 'App\Policies\Profession_codePolicy',
        'App\Models\Student' => 'App\Policies\StudentPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Building' => 'App\Policies\BuildingPolicy',
        'App\Models\Dormitory' => 'App\Policies\DormitoryPolicy',
        'App\Models\Dormitory_member' => 'App\Policies\Dormitory_memberPolicy',
        'App\Models\Notification' => 'App\Policies\NotificationPolicy',
        'App\Models\Movestudent' => 'App\Policies\MovestudentPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
