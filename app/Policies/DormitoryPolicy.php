<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DormitoryPolicy
{
    use HandlesAuthorization;

    public function general(User $user){
        return $user->grade >= 3;
    }
}
