<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Minithesis extends Model
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
        return $this->belongsTo('App\AcademicYear','id_ta');
    }

    public function student()
    {
        return $this->belongsTo('App\Student','nim');
    }

    public function pembimbingUtama()
    {
        return $this->belongsTo('App\Teacher','pembimbing_utama');
    }

    public function pembimbingPendamping()
    {
        return $this->belongsTo('App\Teacher','pembimbing_pendamping');
    }

}
