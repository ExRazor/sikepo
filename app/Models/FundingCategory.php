<?php

namespace App\Models;

use App\Models\BaseModel;

class FundingCategory extends BaseModel
{
    protected $fillable = [
        'id_parent',
        'nama',
        'deskripsi',
        'jenis',
    ];

    public function scopeParent($query)
    {
        return $query->where('id_parent',null);
    }

    public function children()
    {
        return $this->hasMany('App\Models\FundingCategory','id_parent');
    }

    public function funding()
    {
        return $this->hasMany('App\Models\Funding','id_kategori');
    }
}
