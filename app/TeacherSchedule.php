<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherSchedule extends Model
{
    protected $fillable = [
        'id_ta',
        'nidn',
        'kd_matkul',
        'nm_matkul',
        'kd_prodi',
        'sesuai_prodi',
        'sesuai_bidang',
    ];

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','id_ta');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Teacher','nidn');
    }

    public function curriculum()
    {
        return $this->belongsTo('App\Curriculum','kd_matkul');
    }

    public function studyProgram()
    {
        return $this->belongsTo('App\StudyProgram','kd_prodi');
    }
}
