<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movestudent extends Model
{
    //
    protected $table = 'movestudents';

    protected $fillable = [
        'target_id', 'user_id', 'state','token',
    ];
}
