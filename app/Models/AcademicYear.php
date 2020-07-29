<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;

class AcademicYear extends BaseModel
{
    protected $fillable = [
                    'tahun_akademik',
                    'semester',
                    'status'
                ];

    public function collaboration()
    {
        return $this->hasOne('App\Models\Collaboration','id_ta');
    }

    public function teacherAchievement()
    {
        return $this->hasMany('App\Models\TeacherAchievement','id_ta');
    }

    public function ewmp()
    {
        return $this->hasMany('App\Models\Ewmp','id_ta');
    }

    public function curriculumSchedule()
    {
        if(Auth::user()->hasRole('kaprodi')) {
            return $this->hasMany('App\Models\CurriculumSchedule','id_ta')
                        ->whereHas('curriculum.studyProgram', function($q) {
                            $q->where('kd_prodi',Auth::user()->kd_prodi);
                        });
        } else {
            return $this->hasMany('App\Models\CurriculumSchedule','id_ta');
        }
    }

    public function studentQuota()
    {
        return $this->hasMany('App\Models\StudentQuota','id_ta');
    }

    public function studentStatus()
    {
        return $this->hasMany('App\Models\StudentStatus','id_ta');
    }

    public function studentAchievement()
    {
        return $this->hasMany('App\Models\StudentAchievement','id_ta');
    }

    public function funding()
    {
        return $this->hasMany('App\Models\Funding','id_ta');
    }

    public function research()
    {
        return $this->hasMany('App\Models\Research','id_ta');
    }

    public function teacherPublication()
    {
        return $this->hasMany('App\Models\TeacherPublication','id_ta');
    }

    public function studentPublication()
    {
        return $this->hasMany('App\Models\StudentPublication','id_ta');
    }

    public function minithesis()
    {
        return $this->hasMany('App\Models\Minithesis','id_ta');
    }

    public function curriculumIntegration()
    {
        return $this->hasMany('App\Models\CurriculumIntegration','id_ta');
    }

    public function academicSatisfaction()
    {
        return $this->hasMany('App\Models\AcademicSatisfaction','id_ta');
    }

    public function alumnusSatisfaction()
    {
        return $this->hasMany('App\Models\AlumnusSatisfaction','id_ta');
    }
}
