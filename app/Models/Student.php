<?php

namespace App\Models;

use App\Models\BaseModel;

class Student extends BaseModel
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    protected $increment = false;
    protected $primaryKey = 'nim';
    protected $keyType = 'char';
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
        return $this->belongsTo('App\Models\StudyProgram','kd_prodi');
    }

    public function studentForeign()
    {
        return $this->hasOne('App\Models\StudentForeign','nim');
    }

    public function studentStatus()
    {
        return $this->hasMany('App\Models\StudentStatus','nim');
    }

    public function studentStatusArray()
    {
        return $this->hasMany('App\Models\StudentStatus','nim')->toArray();
    }

    public function latestStatus()
    {
        return $this->hasOne('App\Models\StudentStatus','nim')->orderBy('id_ta','desc')->orderBy('id','desc');
    }

    public function research()
    {
        return $this->hasMany('App\Models\ResearchStudent','nim');
    }

    public function studentAchievement()
    {
        return $this->hasMany('App\Models\StudentAchievement','nim');
    }

    public function minithesis()
    {
        return $this->hasOne('App\Models\Minithesis','nim');
    }

    public function outputActivity()
    {
        return $this->hasMany('App\Models\StudentOutputActivity','nim');
    }
}
