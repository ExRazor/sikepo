<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutputActivityCategory extends Model
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
