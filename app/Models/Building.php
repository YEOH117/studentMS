<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    //
    protected $table = 'building';

    protected $fillable = [
        'area', 'sex', 'building','layers','layer_roon_num','preference','empty_num',
    ];
}
