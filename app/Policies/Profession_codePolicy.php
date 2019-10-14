<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Profession_codePolicy
{
    use HandlesAuthorization;


    public function add(User $user){
        return $user->grade >= 2;
    }
}
