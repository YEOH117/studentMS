<?php

namespace App\Policies;

use App\Models\Movestudent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MovestudentPolicy
{
    use HandlesAuthorization;

    public function general(User $user,Movestudent $movestudent){
        return $user->id == $movestudent->target_id ;
    }
}
