<?php

namespace App\Models;

use App\Models\BaseModel;

class Setting extends BaseModel
{
    protected $fillable = [
        'name',
        'value',
    ];
}
