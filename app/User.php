<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Traits\Uuid;

    public $incrementing = false;
    protected $hidden = array('password', 'remember_token','created_at','updated_at');

    protected $fillable = [
        'username'            ,
        'password'            ,
        'role'                ,
        'kd_prodi'            ,
        'remember_token'      ,
        'name'                ,
    ];

    public function studyProgram()
    {
        return $this->belongsTo('App\StudyProgram','kd_prodi');
    }
}
