<?php

namespace App\Models;

use App\Models\BaseModel;

class AlumnusWorkplace extends BaseModel
{
    protected $fillable = [
        'kd_prodi',
        'tahun_lulus',
        'jumlah_lulusan',
        'lulusan_terlacak',
        'kerja_lokal',
        'kerja_nasional',
        'kerja_internasional',
    ];

    public function studyProgram()
    {
        return $this->belongsTo('App\Models\StudyProgram','kd_prodi');
    }
}
