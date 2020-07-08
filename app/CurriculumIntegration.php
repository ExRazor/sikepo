<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurriculumIntegration extends Model
{
    protected $fillable = [
        'id_ta',
        'id_penelitian',
        'id_pengabdian',
        'kegiatan',
        'nidn',
        'kd_matkul',
        'bentuk_integrasi'
    ];

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','id_ta');
    }

    public function research()
    {
        return $this->belongsTo('App\Research','id_penelitian');
    }

    public function communityService()
    {
        return $this->belongsTo('App\CommunityService','id_pengabdian');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Teacher','nidn');
    }

    public function curriculum()
    {
        return $this->belongsTo('App\Curriculum','kd_matkul','kd_matkul');
    }
}
