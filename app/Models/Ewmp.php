<?php

namespace App\Models;

use App\Models\BaseModel;

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
        return $this->belongsTo('App\Models\AcademicYear','id_ta');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher','nidn');
    }
}
