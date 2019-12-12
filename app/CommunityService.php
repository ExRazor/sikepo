<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunityService extends Model
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
    ];

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','id_ta');
    }

    public function serviceTeacher()
    {
        return $this->hasMany('App\CommunityServiceTeacher','id_pengabdian');
    }

    public function serviceKetua()
    {
        return $this->hasOne('App\CommunityServiceTeacher','id_pengabdian')->where('status','Ketua');
    }

    public function serviceAnggota()
    {
        return $this->hasMany('App\CommunityServiceTeacher','id_pengabdian')->where('status','Anggota');
    }

    public function serviceStudent()
    {
        return $this->hasMany('App\CommunityServiceStudent','id_pengabdian');
    }

    public function curriculumIntegration()
    {
        return $this->hasMany('App\CurriculumIntegration','id_pengabdian');
    }
}
