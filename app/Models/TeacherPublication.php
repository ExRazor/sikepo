<?php

namespace App\Models;

use App\Models\BaseModel;

class TeacherPublication extends BaseModel
{
    protected $fillable = [
        'nidn',
        'jenis_publikasi',
        'judul',
        'penerbit',
        'tahun',
        'sesuai_prodi',
        'sitasi',
        'jurnal',
        'akreditasi',
        'tautan',
    ];

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher','nidn');
    }

    public function publicationCategory()
    {
        return $this->belongsTo('App\Models\PublicationCategory','jenis_publikasi');
    }

    public function publicationMembers()
    {
        return $this->hasMany('App\Models\TeacherPublicationMember','id_publikasi');
    }

    public function publicationStudents()
    {
        return $this->hasMany('App\Models\TeacherPublicationStudent','id_publikasi');
    }
}
