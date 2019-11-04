<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;


    //只有用户本人可以查看通知
    public function look(User $user,Notification $notification){

        return $user->id == $notification->notifiable_id;
    }
}
