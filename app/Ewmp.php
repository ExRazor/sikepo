<?php

namespace App;

use App\BaseModel;

class Ewmp extends BaseModel
{

    protected $fillable = [
        'nidn',
        'id_ta',
        'ps_intra',
        'ps_lain',
        'ps_luar',
        'penelitian',
        'pkm',
        'tugas_tambahan',
        'total_sks',
        'rata_sks'
    ];

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','id_ta');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Teacher','nidn');
    }
}
