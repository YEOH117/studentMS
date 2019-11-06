<?php

namespace App\Policies;

use App\Models\User;
use App\Student;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    public function add(User $user){
        return $user->grade >= 2;
    }

    public function general(User $user){
        return $user->grade >= 2;
    }

    public function ajax(User $user){
        return $user->grade == 1;
    }

    /**
     * Determine whether the user can view the student.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Student  $student
     * @return mixed
     */
    public function view(User $user, Student $student)
    {
        //
    }

    /**
     * Determine whether the user can create students.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the student.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Student  $student
     * @return mixed
     */
    public function update(User $user, Student $student)
    {
        //
    }

    /**
     * Determine whether the user can delete the student.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Student  $student
     * @return mixed
     */
    public function delete(User $user, Student $student)
    {
        //
    }
}
