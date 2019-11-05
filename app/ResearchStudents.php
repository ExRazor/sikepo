<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResearchStudents extends Model
{
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
