<?php

namespace App\Models;

use App\Models\BaseModel;

class StudentOutputActivity extends BaseModel
{
    protected $fillable = [
        'kegiatan',
        'nm_kegiatan',
        'nim',
        'id_kategori',
        'judul_luaran',
        'thn_luaran',
        'jenis_luaran',
        'nama_karya',
        'jenis_karya',
        'pencipta_karya',
        'tgl_sah',
        'issn',
        'no_paten',
        'no_permohonan',
        'tgl_permohonan',
        'penerbit',
        'penyelenggara',
        'url',
        'keterangan',
        'file_karya',
    ];

    public function outputActivityCategory()
    {
        return $this->belongsTo('App\Models\OutputActivityCategory','id_kategori');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Student','nim');
    }
}
