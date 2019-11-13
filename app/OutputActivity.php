<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutputActivity extends Model
{
    protected $fillable = [
        'id_kategori',
        'id_penelitian',
        'id_pengabdian',
        'kegiatan',
        'judul_luaran',
        'tahun_luaran',
        'keterangan',
    ];

    public function outputActivityCategory()
    {
        return $this->belongsTo('App\OutputActivityCategory','id_kategori');
    }

    public function research()
    {
        return $this->belongsTo('App\Research','id_penelitian');
    }

    public function communityService()
    {
        return $this->belongsTo('App\CommunityService','id_pengabdian');
    }
}
