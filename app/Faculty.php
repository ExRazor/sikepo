<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
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
