<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dormitory extends Model
{
    //
    protected $table = 'dormitorys';

    protected $fillable = [
        'house_num', 'building_id', 'floor','num','preference','last_preference','roon_people','Remain_number',
    ];
}
