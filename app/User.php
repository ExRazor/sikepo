<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Traits\Uuid;

    public $incrementing = false;
    protected $hidden = array('password', 'remember_token','created_at','updated_at');

    protected $fillable = [
        'username'            ,
        'password'            ,
        'role'                ,
        'kd_prodi'            ,
        'name'                ,
        'remember_token'      ,
        'defaultPass'         ,
    ];

    public function studyProgram()
    {
        return $this->belongsTo('App\StudyProgram','kd_prodi');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Teacher','username');
    }

    public function hasRole(...$roles)
    {
        $userRole = Auth::user()->role;

        foreach($roles as $role)
        {
            if($userRole==$role)
            {
                return true;
            }
        }

        return false;
    }
}
