<?php

namespace App\Models;

use App\Models\BaseModel;

class Department extends BaseModel
{
    protected $primaryKey = 'kd_jurusan';
    protected $fillable = [
                            'kd_jurusan',
                            'id_fakultas',
                            'nama',
                            'nip_kajur',
                            'nm_kajur',
                        ];

    public function faculty()
    {
        return $this->belongsTo('App\Models\Faculty','id_fakultas');
    }

    public function studyProgram()
    {
        return $this->hasMany('App\Models\StudyProgram','kd_jurusan');
    }
}
