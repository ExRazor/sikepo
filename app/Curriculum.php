<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    public $incrementing    = false;
    protected $primaryKey   = 'kd_matkul';
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
        'dokumen_nama',
        'unit_penyelenggara',
    ];

    public function studyProgram()
    {
        return $this->belongsTo('App\StudyProgram','kd_prodi');
    }

    public function curriculumSchedule()
    {
        return $this->hasMany('App\CurriculumSchedule','kd_matkul');
    }

    public function curriculumIntegration()
    {
        return $this->hasMany('App\CurriculumIntegration','kd_matkul');
    }
}
