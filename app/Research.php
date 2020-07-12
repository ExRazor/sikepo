<?php

namespace App;

use App\BaseModel;

class Research extends BaseModel
{
    protected $table = 'researches';

    protected $fillable = [
        'id_ta',
        'judul_penelitian',
        'tema_penelitian',
        'tingkat_penelitian',
        'sesuai_prodi',
        'sks_penelitian',
        'sumber_biaya',
        'sumber_nama',
        'jumlah_biaya',
    ];

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','id_ta');
    }

    public function researchTeacher()
    {
        return $this->hasMany('App\ResearchTeacher','id_penelitian');
    }

    public function researchKetua()
    {
        return $this->hasOne('App\ResearchTeacher','id_penelitian')->where('status','Ketua');
    }

    public function researchStatus($nidn)
    {
        return $this->hasOne('App\ResearchTeacher','id_penelitian')->where('nidn',$nidn);
    }

    public function researchAnggota()
    {
        return $this->hasMany('App\ResearchTeacher','id_penelitian')->where('status','Anggota');
    }

    public function researchStudent()
    {
        return $this->hasMany('App\ResearchStudent','id_penelitian');
    }

    public function curriculumIntegration()
    {
        return $this->hasMany('App\CurriculumIntegration','id_penelitian');
    }
}
