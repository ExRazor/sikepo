<?php

namespace App;

use App\BaseModel;

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
        return $this->hasMany('App\Department','id_fakultas');
    }
}
