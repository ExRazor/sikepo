<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Uuid;

    protected $primaryKey = 'uuid';
    protected $fillable = [
        'username'            ,
        'password'            ,
        'role'                ,
        'kd_prodi'            ,
        'remember_token'      ,
        'name'                ,
    ];
}
