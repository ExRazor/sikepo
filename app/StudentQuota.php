<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentQuota extends Model
{
    protected $fillable = [
        'kd_prodi',
        'id_ta',
        'daya_tampung',
        'calon_pendaftar',
        'calon_lulus',
    ];

    public function studyProgram()
    {
        return $this->belongsTo('App\StudyProgram','kd_prodi');
    }

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','id_ta');
    }
}
