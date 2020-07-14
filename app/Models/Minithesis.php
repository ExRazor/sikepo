<?php

namespace App\Models;

use App\Models\BaseModel;

class Minithesis extends BaseModel
{
    protected $fillable = [
        'nim',
        'judul',
        'pembimbing_utama',
        'pembimbing_pendamping',
        'id_ta',
    ];

    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear','id_ta');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Student','nim');
    }

    public function pembimbingUtama()
    {
        return $this->belongsTo('App\Models\Teacher','pembimbing_utama');
    }

    public function pembimbingPendamping()
    {
        return $this->belongsTo('App\Models\Teacher','pembimbing_pendamping');
    }

}
