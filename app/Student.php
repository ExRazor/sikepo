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

    public function studentForeign()
    {
        return $this->hasOne('App\StudentForeign','nim');
    }

    public function studentStatus()
    {
        return $this->hasMany('App\StudentStatus','nim');
    }

    public function studentStatusArray()
    {
        return $this->hasMany('App\StudentStatus','nim')->toArray();
    }


    public function latestStatus()
    {
        return $this->hasOne('App\StudentStatus','nim')->orderBy('id_ta','desc')->orderBy('id','desc')->limit(1);
    }

    public function research()
    {
        return $this->hasMany('App\ResearchStudent','nim');
    }

    public function studentAchievement()
    {
        return $this->hasMany('App\StudentAchievement','nim');
    }

    public function minithesis()
    {
        return $this->hasOne('App\Minithesis','nim');
    }
}
