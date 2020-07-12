<?php

namespace App;

use App\BaseModel;

class ResearchStudent extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'id_penelitian',
        'nim',
    ];

    public function research()
    {
        return $this->belongsTo('App\Research','id_penelitian');
    }

    public function student()
    {
        return $this->belongsTo('App\Student','nim');
    }
}
