<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    protected $primaryKey = 'nim';
    protected $fillable = [
        'kd_prodi',
        'nim',
        'nama',
        'tpt_lhr',
        'tgl_lhr',
        'jk',
        'agama',
        'alamat',
        'kewarganegaraan',
        'kelas',
        'tipe',
        'program',
        'seleksi_jenis',
        'seleksi_jalur',
        'status_masuk',
        'angkatan',
    ];

    public function studyProgram()
    {
        return $this->belongsTo('App\StudyProgram','kd_prodi');
    }

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','masuk_ta');
    }

    public function studentStatus()
    {
        return $this->hasMany('App\StudentStatus','nim');
    }

    public function latestStatus()
    {
        return $this->hasOne('App\StudentStatus','nim')->orderBy('id_ta','desc')->limit(1);
    }

    public function researchStudents()
    {
        return $this->hasMany('App\ResearchStudents','nim');
    }
}
