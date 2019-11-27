<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherAchievement extends Model
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
