<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlumnusIdle extends Model
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
        return $this->belongsTo('App\StudyProgram','kd_prodi');
    }
}
