<?php

namespace App;

use App\BaseModel;

class Setting extends BaseModel
{
    protected $fillable = [
        'name',
        'value',
    ];
}
