<?php

namespace App\Models;

use App\Models\BaseModel;

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
        'bukti_fisik'
    ];

    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear', 'id_ta');
    }

    public function researchTeacher()
    {
        return $this->hasMany('App\Models\ResearchTeacher', 'id_penelitian');
    }

    public function researchKetua()
    {
        return $this->hasOne('App\Models\ResearchTeacher', 'id_penelitian')->where('status', 'Ketua');
    }

    public function researchStatus($nidn)
    {
        return $this->hasOne('App\Models\ResearchTeacher', 'id_penelitian')->where('nidn', $nidn);
    }

    public function researchAnggota()
    {
        return $this->hasMany('App\Models\ResearchTeacher', 'id_penelitian')->where('status', 'Anggota');
    }

    public function researchStudent()
    {
        return $this->hasMany('App\Models\ResearchStudent', 'id_penelitian');
    }

    public function curriculumIntegration()
    {
        return $this->hasMany('App\Models\CurriculumIntegration', 'id_penelitian');
    }
}
