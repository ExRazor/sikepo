<?php

namespace App\Models;

use App\Models\BaseModel;

class SatisfactionCategory extends BaseModel
{
    protected $fillable = [
        'jenis',
        'nama',
        'alias',
        'deskripsi',
    ];

    public function academicSatisfaction()
    {
        return $this->hasMany('App\Models\AcademicSatisfaction','id_kategori');
    }

    public function alumnusSatisfaction()
    {
        return $this->hasMany('App\Models\AlumnusSatisfaction','id_kategori');
    }
}
