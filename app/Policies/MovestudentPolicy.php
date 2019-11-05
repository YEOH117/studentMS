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

    public function look(User $user){
        return $user->grade >= 2;
    }

    public function manage(User $user){
        return $user->grade == 1;
    }
}
