<?php

namespace App;

use App\BaseModel;

class AcademicSatisfaction extends BaseModel
{
    protected $fillable = [
        'kd_kepuasan',
        'id_ta',
        'kd_prodi',
        'id_kategori',
        'sangat_baik',
        'baik',
        'cukup',
        'kurang',
        'tindak_lanjut',
    ];

    public function satisfactionCategory()
    {
        return $this->belongsTo('App\SatisfactionCategory','id_kategori');
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
