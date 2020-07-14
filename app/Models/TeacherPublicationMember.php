<?php

namespace App\Models;

use App\Models\BaseModel;

class TeacherPublicationMember extends BaseModel
{
    protected $fillable = [
        'id_publikasi',
        'nidn',
        'nama',
        'kd_prodi',
    ];

    public function publication()
    {
        return $this->belongsTo('App\Models\TeacherPublication','id_publikasi');
    }

    public function studyProgram()
    {
        return $this->belongsTo('App\Models\StudyProgram','kd_prodi');
    }
}
