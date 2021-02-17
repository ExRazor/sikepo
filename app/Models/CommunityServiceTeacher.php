<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityServiceTeacher extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_pengabdian',
        'status',
        'sks',
        'nidn',
        'nama',
        'asal',
    ];

    public function communityService()
    {
        return $this->belongsTo('App\Models\CommunityService', 'id_pengabdian');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher', 'nidn');
    }

    public function scopeJurusanKetua($query, $jurusan)
    {
        return $query->whereHas(
            'teacher.latestStatus.studyProgram',
            function ($q1) use ($jurusan) {
                $q1->where('kd_jurusan', $jurusan);
            }
        )
            ->where('status', 'Ketua');
    }

    public function scopeProdiKetua($query, $prodi)
    {
        return $query->whereHas(
            'teacher.latestStatus.studyProgram',
            function ($q1) use ($prodi) {
                $q1->where('kd_prodi', $prodi);
            }
        )
            ->where('status', 'Ketua');
    }

    public function scopeJurusan($query, $kd_jurusan)
    {
        return $query->whereHas(
            'teacher.latestStatus.studyProgram',
            function ($q1) use ($kd_jurusan) {
                $q1->where('kd_jurusan', $kd_jurusan);
            }
        );
    }

    public function scopeProdi($query, $kd_prodi)
    {
        return $query->whereHas(
            'teacher.latestStatus.studyProgram',
            function ($q1) use ($kd_prodi) {
                $q1->where('kd_prodi', $kd_prodi);
            }
        );
    }
}
