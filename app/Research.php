<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    protected $fillable = [
        'nidn',
        'tema_penelitian',
        'judul_penelitian',
        'tahun_penelitian',
        'tahun_penelitian',
        'sumber_biaya',
        'sumber_nama',
    ];

    public function teacher()
    {
        return $this->belongsTo('App\Teacher','nidn');
    }

    public function researchStudents()
    {
        return $this->hasMany('App\ResearchStudents','id_penelitian');
    }
}
