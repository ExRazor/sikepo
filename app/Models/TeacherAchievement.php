<?php

namespace App\Models;

use App\Models\BaseModel;

class TeacherAchievement extends BaseModel
{
    protected $fillable = [
        'nidn',
        'id_ta',
        'prestasi',
        'tingkat_prestasi',
        'bukti_nama',
        'bukti_file',
    ];

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher','nidn');
    }

    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear','id_ta');
    }
}
