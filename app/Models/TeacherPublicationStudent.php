<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherPublicationStudent extends Model
{
    protected $fillable = [
        'id_publikasi',
        'nim',
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
