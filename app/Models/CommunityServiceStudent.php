<?php

namespace App\Models;

use App\Models\BaseModel;

class CommunityServiceStudent extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'id_pengabdian',
        'nim',
    ];

    public function communityService()
    {
        return $this->belongsTo('App\Models\CommunityService','id_pengabdian');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Student','nim');
    }
}
