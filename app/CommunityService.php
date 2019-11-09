<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunityService extends Model
{
    protected $fillable = [
        'nidn',
        'tema_pengabdian',
        'judul_pengabdian',
        'tahun_pengabdian',
        'sumber_biaya',
        'sumber_nama',
    ];

    public function teacher()
    {
        return $this->belongsTo('App\Teacher','nidn');
    }

    public function communityServiceStudent()
    {
        return $this->hasMany('App\CommunityServiceStudent','id_pengabdian');
    }
}
