<?php

namespace App\Models;

use App\Models\BaseModel;

class Faculty extends BaseModel
{
    protected $fillable = [
        'nama',
        'singkatan',
        'nip_dekan',
        'nm_dekan',
    ];

    public function department()
    {
        return $this->hasMany('App\Models\Department','id_fakultas');
    }
}
