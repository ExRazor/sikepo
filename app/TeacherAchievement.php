<?php

namespace App;

use App\BaseModel;

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
        return $this->belongsTo('App\Teacher','nidn');
    }

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','id_ta');
    }
}
