<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherStatus extends Model
{
    protected $fillable = [
        'id_ta',
        'nidn',
        'jabatan',
        'kd_prodi',
    ];

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher','nidn');
    }

    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear','id_ta')->orderBy('tahun_akademik','desc');
    }

    public function studyProgram()
    {
        return $this->belongsTo('App\Models\StudyProgram','kd_prodi');
    }
}
