<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    protected $fillable = [
        'id_ta',
        'judul_penelitian',
        'tema_penelitian',
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
