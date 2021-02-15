<?php

namespace App\Models;

use App\Models\BaseModel;

class CommunityService extends BaseModel
{
    protected $fillable = [
        'id_ta',
        'judul_pengabdian',
        'tema_pengabdian',
        'sesuai_prodi',
        'sks_pengabdian',
        'sumber_biaya',
        'sumber_nama',
        'jumlah_biaya',
        'bukti_fisik'
    ];

    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear', 'id_ta');
    }

    public function serviceTeacher()
    {
        return $this->hasMany('App\Models\CommunityServiceTeacher', 'id_pengabdian')->orderBy('sks', 'desc');
    }

    public function serviceKetua()
    {
        return $this->hasOne('App\Models\CommunityServiceTeacher', 'id_pengabdian')->where('status', 'Ketua');
    }

    public function serviceAnggota()
    {
        return $this->hasMany('App\Models\CommunityServiceTeacher', 'id_pengabdian')->where('status', 'Anggota');
    }

    public function serviceStudent()
    {
        return $this->hasMany('App\Models\CommunityServiceStudent', 'id_pengabdian');
    }

    public function curriculumIntegration()
    {
        return $this->hasMany('App\Models\CurriculumIntegration', 'id_pengabdian');
    }
}
