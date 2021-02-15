<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicationMember extends Model
{
    protected $fillable = [
        'id_publikasi',
        'id_unik',
        'nama',
        'asal',
        'status',
        'penulis_utama',
    ];

    public function publication()
    {
        return $this->belongsTo('App\Models\TeacherPublication', 'id_publikasi');
    }

    public function studyProgram()
    {
        return $this->belongsTo('App\Models\StudyProgram', 'asal');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher', 'id_unik');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'id_unik');
    }
}
