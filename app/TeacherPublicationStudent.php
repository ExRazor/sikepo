<?php

namespace App;

use App\BaseModel;

class TeacherPublicationStudent extends BaseModel
{
    protected $fillable = [
        'id_publikasi',
        'nim',
        'nama',
        'kd_prodi',
    ];

    public function publication()
    {
        return $this->belongsTo('App\TeacherPublication','id_publikasi');
    }

    public function studyProgram()
    {
        return $this->belongsTo('App\StudyProgram','kd_prodi');
    }
}
