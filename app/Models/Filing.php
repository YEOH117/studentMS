<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filing extends Model
{
    protected $table = 'filings';

    protected $fillable = [
        'student_id', 'sort', 'info',
    ];

    public function student(){
        return $this->hasOne('App\Models\Student','id','student_id');
    }
}
