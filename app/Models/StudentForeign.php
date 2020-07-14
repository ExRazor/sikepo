<?php

namespace App\Models;

use App\Models\BaseModel;

class StudentForeign extends BaseModel
{
    protected $fillable = [
        'nim',
        'asal_negara',
        'durasi',
    ];

    public function student()
    {
        return $this->belongsTo('App\Models\Student','nim');
    }
}
