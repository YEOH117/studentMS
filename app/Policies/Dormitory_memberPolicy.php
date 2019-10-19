<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Dormitory_memberPolicy
{
    use HandlesAuthorization;

    public function general(User $user){
        return $user->grade >= 2;
    }
}
