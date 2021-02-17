<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityServiceStudent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_pengabdian',
        'nim',
        'nama',
        'asal',
    ];

    public function communityService()
    {
        return $this->belongsTo('App\Models\CommunityService', 'id_pengabdian');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'nim');
    }
}
