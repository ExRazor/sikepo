<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherPublicationMember extends Model
{
    protected $fillable = [
        'id_publikasi',
        'nidn',
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
