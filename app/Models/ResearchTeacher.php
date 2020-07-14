<?php

namespace App\Models;

use App\Models\BaseModel;

class ResearchTeacher extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'id_penelitian',
        'nidn',
        'status',
        'sks',
    ];

    public function research()
    {
        return $this->belongsTo('App\Models\Research','id_penelitian');
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
