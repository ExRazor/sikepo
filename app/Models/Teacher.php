<?php

namespace App\Models;

use App\Traits\Uuid;
use App\Models\BaseModel;

class Teacher extends BaseModel
{
    // use Uuid;

    public $incrementing = false;
    protected $primaryKey = 'nidn';
    protected $keyType = 'char';
    protected $fillable = [
        'nidn',
        'nip',
        'nama',
        'jk',
        'agama',
        'tpt_lhr',
        'tgl_lhr',
        'alamat',
        'no_telp',
        'email',
        'pend_terakhir_jenjang',
        'pend_terakhir_jurusan',
        'bidang_ahli',
        'status_kerja',
        'jabatan_akademik',
        'sertifikat_pendidik',
        'sesuai_bidang_ps',
        'foto',
    ];

    public function studyProgram()
    {
        return $this->belongsTo('App\Models\StudyProgram', 'kd_prodi');
    }

    public function teacherStatus()
    {
        return $this->hasMany('App\Models\TeacherStatus', 'nidn')->latest('periode');
    }

    public function firstStatus()
    {
        // return $this->hasOne('App\Models\TeacherStatus','nidn')->orderBy('periode','desc');
        return $this->hasOne('App\Models\TeacherStatus', 'nidn')->orderBy('periode', 'asc')->limit(1);
    }

    public function latestStatus()
    {
        // return $this->hasOne('App\Models\TeacherStatus','nidn')->orderBy('periode','desc');
        return $this->hasOne('App\Models\TeacherStatus', 'nidn')->where('is_active', true);
    }

    public function ewmp()
    {
        return $this->hasMany('App\Models\Ewmp', 'nidn');
    }

    public function achievement()
    {
        return $this->hasMany('App\Models\TeacherAchievement', 'nidn');
    }

    public function curriculumSchedule()
    {
        return $this->hasMany('App\Models\CurriculumSchedule', 'nidn');
    }

    public function researchTeacher()
    {
        return $this->hasMany('App\Models\ResearchTeacher', 'nidn');
    }

    public function minithesis_utama()
    {
        return $this->hasMany('App\Models\Minithesis', 'pembimbing_utama');
    }

    public function minithesis_pendamping()
    {
        return $this->hasMany('App\Models\Minithesis', 'pembimbing_pendamping');
    }

    public function curriculumIntegration()
    {
        return $this->hasMany('App\Models\CurriculumIntegration', 'nidn');
    }

    public function outputActivity()
    {
        return $this->hasMany('App\Models\TeacherOutputActivity', 'nidn');
    }

    public function scopeJurusan($query, $jurusan)
    {
        return $query->whereHas('studyProgram', function ($q) use ($jurusan) {
            $q->where('kd_jurusan', $jurusan);
        });
    }
}
