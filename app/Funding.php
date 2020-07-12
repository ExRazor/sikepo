<?php

namespace App;

use App\BaseModel;

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
        return $this->belongsTo('App\FundingCategory','id_kategori');
    }

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','id_ta');
    }

    public function studyProgram()
    {
        return $this->belongsTo('App\StudyProgram','kd_prodi');
    }
}
