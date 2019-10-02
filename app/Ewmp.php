<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ewmp extends Model
{

    protected $fillable = [
        'nidn',
        'id_ta',
        'ps_intra',
        'ps_lain',
        'ps_luar',
        'penelitian',
        'pkm',
        'tugas_tambahan',
    ];

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','id_ta');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Teacher','nidn');
    }
}
