<?php

namespace App\Models;

use App\Models\BaseModel;

class Curriculum extends BaseModel
{
    public $incrementing    = false;
    // protected $primaryKey   = 'kd_matkul';
    protected $casts        = ['capaian' => 'array'];

    protected $fillable = [
        'kd_matkul',
        'kd_prodi',
        'versi',
        'nama',
        'semester',
        'jenis',
        'sks_teori',
        'sks_seminar',
        'sks_praktikum',
        'capaian',
        'kompetensi_prodi',
        'dokumen_nama',
        'unit_penyelenggara',
    ];

    public function studyProgram()
    {
        return $this->belongsTo('App\Models\StudyProgram','kd_prodi');
    }

    public function curriculumSchedule()
    {
        return $this->hasMany('App\Models\CurriculumSchedule','kd_matkul');
    }

    public function curriculumIntegration()
    {
        return $this->hasMany('App\Models\CurriculumIntegration','kd_matkul');
    }
}
