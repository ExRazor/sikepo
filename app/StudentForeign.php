<?php

namespace App;

use App\BaseModel;

class StudentForeign extends BaseModel
{
    protected $fillable = [
        'nim',
        'asal_negara',
        'durasi',
    ];

    public function student()
    {
        return $this->belongsTo('App\Student','nim');
    }
}
