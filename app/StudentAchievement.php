<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentAchievement extends Model
{
    protected $fillable = [
        'nim',
        'id_ta',
        'kegiatan_nama',
        'kegiatan_tingkat',
        'prestasi',
        'prestasi_jenis',
    ];

    public function student()
    {
        return $this->belongsTo('App\Student','nim');
    }

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','id_ta');
    }
}
