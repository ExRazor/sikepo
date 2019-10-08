<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherAchievement extends Model
{
    protected $fillable = [
        'nidn',
        'prestasi',
        'tingkat_prestasi',
        'tanggal',
        'bukti_pendukung',
    ];

    public function teacher()
    {
        return $this->belongsTo('App\Teacher','nidn');
    }
}
