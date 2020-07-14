<?php

namespace App\Models;

use App\Models\BaseModel;

class StudentAchievement extends BaseModel
{
    protected $fillable = [
        'nim',
        'id_ta',
        'kegiatan_nama',
        'kegiatan_tingkat',
        'prestasi',
        'prestasi_jenis',
    ];

    public function student()
    {
        return $this->belongsTo('App\Models\Student','nim');
    }

    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear','id_ta');
    }
}
