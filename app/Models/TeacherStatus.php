<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherStatus extends Model
{
    protected $fillable = [
        'nidn',
        'periode',
        'jabatan',
        'kd_prodi',
        'is_active',
    ];

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher','nidn');
    }

    // public function academicYear()
    // {
    //     return $this->belongsTo('App\Models\AcademicYear','id_ta')->orderBy('tahun_akademik','desc');
    // }

    public function studyProgram()
    {
        return $this->belongsTo('App\Models\StudyProgram','kd_prodi');
    }
}
