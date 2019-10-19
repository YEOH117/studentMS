<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dormitory_member extends Model
{
    //
    protected $table = 'dormitory_members';

    protected $fillable = [
        'dormitory_id', 'student_id',
    ];

   public function dormitory(){
       return $this->belongsTo('App\Models\Dormitory','dormitory_id','id');
   }

}
