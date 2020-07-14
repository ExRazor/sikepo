<?php

namespace App\Models;

use App\Models\BaseModel;

class OutputActivityCategory extends BaseModel
{
    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function teacherOutput()
    {
        return $this->hasMany('App\Models\TeacherOutputActivity','id_kategori');
    }

    public function studentOutput()
    {
        return $this->hasMany('App\Models\StudentOutputActivity','id_kategori');
    }
}
