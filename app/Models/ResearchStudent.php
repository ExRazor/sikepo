<?php

namespace App\Models;

use App\Models\BaseModel;

class ResearchStudent extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'id_penelitian',
        'nim',
    ];

    public function research()
    {
        return $this->belongsTo('App\Models\Research','id_penelitian');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Student','nim');
    }
}
