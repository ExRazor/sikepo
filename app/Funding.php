<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Funding extends Model
{
    protected $fillable = [
        'id_ta',
        'id_parent',
        'daya_tampung',
        'calon_pendaftar',
        'calon_lulus',
    ];

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','id_ta');
    }
}
