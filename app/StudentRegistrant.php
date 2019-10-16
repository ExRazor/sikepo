<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentRegistrant extends Model
{
    protected $primaryKey = 'nisn';

    protected $fillable = [
        'nisn',
        'id_ta',
        'nama',
        'jk',
        'agama',
        'tpt_lhr',
        'tgl_lhr',
        'alamat',
        'no_telp',
        'email',
        'asal_sekolah',
        'jalur_masuk',
        'status_lulus',
    ];

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','id_ta');
    }

    public function studyProgram()
    {
        return $this->belongsTo('App\StudyProgram','dosen_ps');
    }
}
