<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuildingPolicy
{
    use HandlesAuthorization;

    //查看
    public function look(User $user){
        return $user->grade >= 2;
    }

    //创建
    public function create(User $user){
        return $user->grade >= 3;
    }
    //更新
    public function update(User $user){
        return $user->grade >= 3;
    }
    //删除
    public function delete(User $user){
        return $user->grade >= 3;
    }
}
