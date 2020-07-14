<?php

namespace App\Models;

use App\Models\BaseModel;

class AlumnusSuitable extends BaseModel
{
    protected $fillable = [
        'kd_prodi',
        'tahun_lulus',
        'jumlah_lulusan',
        'lulusan_terlacak',
        'sesuai_rendah',
        'sesuai_sedang',
        'sesuai_tinggi',
    ];

    public function studyProgram()
    {
        return $this->belongsTo('App\Models\StudyProgram','kd_prodi');
    }
}
