<?php

namespace App\Models;

use App\Models\BaseModel;

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
        return $this->belongsTo('App\Models\SatisfactionCategory','id_kategori');
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
