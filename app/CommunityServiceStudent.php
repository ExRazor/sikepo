<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunityServiceStudent extends Model
{
    protected $fillable = [
        'id_pengabdian',
        'nim',
        'nama',
        'kd_prodi',
    ];

    public function communityService()
    {
        return $this->belongsTo('App\CommunityService','id_pengabdian');
    }

    public function studyProgram()
    {
        return $this->belongsTo('App\Studyprogram','kd_prodi');
    }
}
