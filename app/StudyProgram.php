<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    protected $primaryKey = 'kd_prodi';
    protected $fillable = ['kd_prodi','nama','jenjang','no_sk','tgl_sk','pejabat_sk','thn_menerima','singkatan'];

    public function collaboration()
    {
        return $this->hasMany('App\Collaboration');
    }

    public function teacher()
    {
        return $this->hasMany('App\Teacher');
    }
}
