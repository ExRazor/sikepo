<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentForeign extends Model
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
