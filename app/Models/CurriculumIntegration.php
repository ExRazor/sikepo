<?php

namespace App\Models;

use App\Models\BaseModel;

class CurriculumIntegration extends BaseModel
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
        return $this->belongsTo('App\Models\AcademicYear','id_ta');
    }

    public function research()
    {
        return $this->belongsTo('App\Models\Research','id_penelitian');
    }

    public function communityService()
    {
        return $this->belongsTo('App\Models\CommunityService','id_pengabdian');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher','nidn');
    }

    public function curriculum()
    {
        return $this->belongsTo('App\Models\Curriculum','kd_matkul','kd_matkul');
    }
}
