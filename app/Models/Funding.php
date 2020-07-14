<?php

namespace App\Models;

use App\Models\BaseModel;

class Funding extends BaseModel
{
    protected $fillable = [
        'kd_prodi',
        'id_ta',
        'id_kategori',
        'unit',
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

    public function studyProgram()
    {
        return $this->belongsTo('App\Models\StudyProgram','kd_prodi');
    }
}
