<?php

namespace App\Models;

use App\Models\BaseModel;

class TeacherOutputActivity extends BaseModel
{
    protected $fillable = [
        'kegiatan',
        'nm_kegiatan',
        'nidn',
        'id_kategori',
        'judul_luaran',
        'id_ta',
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

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher','nidn');
    }

    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear','id_ta');
    }
}
