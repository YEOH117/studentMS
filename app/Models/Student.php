<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    protected $fillable = [
        'the_student_id','name','sex','profession','college','class'
    ];

    public function dormitory_member(){
        return $this->hasOne('App\Models\Dormitory_member','student_id','id');
    }

    public function user(){
        return $this->hasOne('App\Models\User','account','the_student_id');
    }
}
