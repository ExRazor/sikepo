<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'nim';
    protected $fillable = [
        'nim',
        'nama',
        'tpt_lhr',
        'tgl_lhr',
        'jk',
        'agama',
        'alamat',
        'kd_prodi',
        'kelas',
        'tipe',
        'seleksi_jenis',
        'seleksi_jalur',
        'masuk_status',
        'masuk_ta',
        'status',
    ];

    public function studyProgram()
    {
        return $this->belongsTo('App\StudyProgram','kd_prodi');
    }

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','masuk_ta');
    }
}
