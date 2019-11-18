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
    ];

    public function studyProgram()
    {
        return $this->belongsTo('App\StudyProgram','kd_prodi');
    }

    public function teacherSchedule()
    {
        return $this->hasMany('App\TeacherSchedule','kd_matkul');
    }
}
