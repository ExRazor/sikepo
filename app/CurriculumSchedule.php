<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurriculumSchedule extends Model
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
        return $this->belongsTo('App\AcademicYear','id_ta');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Teacher','nidn');
    }

    public function curriculum()
    {
        return $this->belongsTo('App\Curriculum','kd_matkul','kd_matkul');
    }

    public function scopeCurriculumProdi($query, $prodi)
    {
       return $query->whereHas('curriculum', function($q) use ($prodi) {
            $q->where('kd_prodi', $prodi);
       });
    }

    public function studyProgram()
    {
        return $this->belongsTo('App\StudyProgram','kd_prodi');
    }
}
