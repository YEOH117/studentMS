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
    //一般的权限要求
    public function general(User $user){
        return $user->grade >= 2;
    }

    //添加
    public function create(User $user){
        return $user->grade >= 5;
    }

    //修改
    public function edit(User $user){
        return $user->grade >= 5;
    }
}
