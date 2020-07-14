<?php

namespace App\Models;

use App\Models\BaseModel;

class CurriculumSchedule extends BaseModel
{
    protected $fillable = [
        'id_ta',
        'nidn',
        'kd_matkul',
        'sesuai_prodi',
        'sesuai_bidang',
    ];

    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear','id_ta');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher','nidn');
    }

    public function curriculum()
    {
        return $this->belongsTo('App\Models\Curriculum','kd_matkul','kd_matkul');
    }

    public function scopeCurriculumProdi($query, $prodi)
    {
       return $query->whereHas('curriculum', function($q) use ($prodi) {
            $q->where('kd_prodi', $prodi);
       });
    }

    public function studyProgram()
    {
        return $this->belongsTo('App\Models\StudyProgram','kd_prodi');
    }
}
