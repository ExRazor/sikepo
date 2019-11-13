<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutputActivityCategory extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function outputActivity()
    {
        return $this->hasMany('App\OutputActivity','id_kategori');
    }
}
