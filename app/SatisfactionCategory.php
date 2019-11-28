<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SatisfactionCategory extends Model
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
