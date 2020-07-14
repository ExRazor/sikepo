<?php

namespace App\Models;

use App\Models\BaseModel;

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
        return $this->belongsTo('App\Models\FundingCategory','id_kategori');
    }

    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear','id_ta');
    }

    public function faculty()
    {
        return $this->belongsTo('App\Models\Faculty','id_fakultas');
    }
}
