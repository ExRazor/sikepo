<?php

namespace App;

use App\BaseModel;

class OutputActivityCategory extends BaseModel
{
    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function teacherOutput()
    {
        return $this->hasMany('App\TeacherOutputActivity','id_kategori');
    }

    public function studentOutput()
    {
        return $this->hasMany('App\StudentOutputActivity','id_kategori');
    }
}
