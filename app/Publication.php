<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $fillable = [
        'nidn',
        'jenis_publikasi',
        'judul',
        'penerbit',
        'tahun',
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

    public function publicationStudents()
    {
        return $this->hasMany('App\PublicationStudents','id_publikasi');
    }
}
