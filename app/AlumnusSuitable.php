<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlumnusSuitable extends Model
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
        return $this->belongsTo('App\StudyProgram','kd_prodi');
    }
}
