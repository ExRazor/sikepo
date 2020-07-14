<?php

namespace App\Models;

use App\Models\BaseModel;

class PublicationCategory extends BaseModel
{
    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function publication()
    {
        return $this->hasMany('App\Models\Publication','jenis_publikasi');
    }
}
