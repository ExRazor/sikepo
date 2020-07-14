<?php

namespace App\Models;

use App\Models\BaseModel;

class AlumnusIdle extends BaseModel
{
    protected $fillable = [
        'kd_prodi',
        'tahun_lulus',
        'jumlah_lulusan',
        'lulusan_terlacak',
        'kriteria_1',
        'kriteria_2',
        'kriteria_3',
    ];

    public function studyProgram()
    {
        return $this->belongsTo('App\Models\StudyProgram','kd_prodi');
    }
}
