<?php

namespace App\Models;

use App\Models\BaseModel;

class CommunityServiceTeacher extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'id_pengabdian',
        'nidn',
        'status',
        'sks',
    ];

    public function communityService()
    {
        return $this->belongsTo('App\Models\CommunityService','id_pengabdian');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher','nidn');
    }

    public function scopeJurusanKetua($query, $jurusan)
    {
        return $query->whereHas(
                                'teacher.studyProgram', function($q1) use($jurusan) {
                                    $q1->where('kd_jurusan',$jurusan);
                                }
                        )
                      ->where('status','Ketua');
    }

    public function scopeProdiKetua($query, $prodi)
    {
        return $query->whereHas(
                                'teacher', function($q1) use($prodi) {
                                    $q1->where('kd_prodi',$prodi);
                                }
                        )
                      ->where('status','Ketua');
    }
}
