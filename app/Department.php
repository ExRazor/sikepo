<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
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
