<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResearchStudents extends Model
{
    protected $fillable = [
        'id_penelitian',
        'nim',
        'nama',
        'kd_prodi',
    ];

    public function research()
    {
        return $this->belongsTo('App\Research','id_penelitian');
    }

    public function studyProgram()
    {
        return $this->belongsTo('App\Studyprogram','kd_prodi');
    }
}
