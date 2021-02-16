<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchStudent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_penelitian',
        'nim',
        'nama',
        'asal',
    ];

    public function research()
    {
        return $this->belongsTo('App\Models\Research', 'id_penelitian');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'nim');
    }
}
