<?php

namespace App;

use App\BaseModel;

class FundingFaculty extends BaseModel
{
    protected $fillable = [
        'id_fakultas',
        'id_ta',
        'id_kategori',
        'nominal',
    ];

    public function fundingCategory()
    {
        return $this->belongsTo('App\FundingCategory','id_kategori');
    }

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','id_ta');
    }

    public function faculty()
    {
        return $this->belongsTo('App\Faculty','id_fakultas');
    }
}
