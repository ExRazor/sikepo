<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlumnusWorkplace extends Model
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
        return $this->belongsTo('App\StudyProgram','kd_prodi');
    }
}
