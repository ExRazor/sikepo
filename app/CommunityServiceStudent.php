<?php

namespace App;

use App\BaseModel;

class CommunityServiceStudent extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'id_pengabdian',
        'nim',
    ];

    public function communityService()
    {
        return $this->belongsTo('App\CommunityService','id_pengabdian');
    }

    public function student()
    {
        return $this->belongsTo('App\Student','nim');
    }
}
