<?php

namespace App;

use App\BaseModel;

class PublicationCategory extends BaseModel
{
    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function publication()
    {
        return $this->hasMany('App\Publication','jenis_publikasi');
    }
}
