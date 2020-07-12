<?php

namespace App;

use App\BaseModel;

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
        return $this->belongsTo('App\Faculty','id_fakultas');
    }

    public function studyProgram()
    {
        return $this->hasMany('App\StudyProgram','kd_jurusan');
    }
}
