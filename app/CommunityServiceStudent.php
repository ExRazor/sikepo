<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunityServiceStudent extends Model
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
