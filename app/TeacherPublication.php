<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherPublication extends Model
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
        return $this->belongsTo('App\Teacher','nidn');
    }

    public function publicationCategory()
    {
        return $this->belongsTo('App\PublicationCategory','jenis_publikasi');
    }

    public function publicationMembers()
    {
        return $this->hasMany('App\TeacherPublicationMember','id_publikasi');
    }

    public function publicationStudents()
    {
        return $this->hasMany('App\TeacherPublicationStudent','id_publikasi');
    }
}
