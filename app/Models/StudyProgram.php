<?php

namespace App\Models;

use App\Models\BaseModel;

class StudyProgram extends BaseModel
{
    protected $primaryKey = 'kd_prodi';
    protected $fillable = [
                        'kd_prodi',
                        'kd_jurusan',
                        'nama',
                        'singkatan',
                        'jenjang',
                        'no_sk',
                        'tgl_sk',
                        'pejabat_sk',
                        'thn_menerima',
                        'nip_kaprodi',
                        'nm_kaprodi',
                    ];

    public function department()
    {
        return $this->belongsTo('App\Models\Department','kd_jurusan');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User','kd_prodi');
    }

    public function collaboration()
    {
        return $this->hasMany('App\Models\Collaboration','kd_prodi');
    }

    public function teacher()
    {
        return $this->hasMany('App\Models\Teacher','kd_prodi');
    }

    public function student()
    {
        return $this->hasMany('App\Models\Student','kd_prodi');
    }

    public function studentQuota()
    {
        return $this->hasMany('App\Models\StudentQuota','kd_prodi');
    }

    public function funding()
    {
        return $this->hasMany('App\Models\Funding','kd_prodi');
    }

    public function curriculum()
    {
        return $this->hasMany('App\Models\Curriculum','kd_prodi');
    }

    public function academicSatisfaction()
    {
        return $this->hasMany('App\Models\AcademicSatisfaction','kd_prodi');
    }

    public function alumnusSatisfaction()
    {
        return $this->hasMany('App\Models\AlumnusSatisfaction','kd_prodi');
    }
}
