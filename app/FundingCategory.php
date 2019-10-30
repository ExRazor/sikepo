<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FundingCategory extends Model
{
    protected $fillable = [
        'id_parent',
        'nama',
        'deskripsi',
        'jenis',
    ];

    public function children()
    {
        return $this->hasMany('App\FundingCategory','id_parent');
    }

    public function funding()
    {
        return $this->hasMany('App\Funding','id_kategori');
    }
}
