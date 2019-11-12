<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicationCategory extends Model
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
