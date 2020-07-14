<?php

namespace App\Models;

use App\Models\BaseModel;

class StudentStatus extends BaseModel
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    protected $fillable = [
        'id_ta',
        'nim',
        'status',
        'ipk_terakhir',
    ];

    public function student()
    {
        return $this->belongsTo('App\Models\Student','nim');
    }

    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear','id_ta');
    }
}
