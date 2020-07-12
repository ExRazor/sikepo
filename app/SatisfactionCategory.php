<?php

namespace App;

use App\BaseModel;

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
        return $this->hasMany('App\AcademicSatisfaction','id_kategori');
    }

    public function alumnusSatisfaction()
    {
        return $this->hasMany('App\AlumnusSatisfaction','id_kategori');
    }
}
