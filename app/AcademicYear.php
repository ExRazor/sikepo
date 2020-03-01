<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AcademicYear extends Model
{
    protected $fillable = [
                    'tahun_akademik',
                    'semester',
                    'status'
                ];

    public function collaboration()
    {
        return $this->hasOne('App\Collaboration','id_ta');
    }

    public function teacherAchievement()
    {
        return $this->hasMany('App\TeacherAchievement','id_ta');
    }

    public function ewmp()
    {
        return $this->hasMany('App\Ewmp','id_ta');
    }

    public function curriculumSchedule()
    {
        if(Auth::user()->hasRole('kaprodi')) {
            return $this->hasMany('App\CurriculumSchedule','id_ta')
                        ->whereHas('curriculum.studyProgram', function($q) {
                            $q->where('kd_prodi',Auth::user()->kd_prodi);
                        });
        } else {
            return $this->hasMany('App\CurriculumSchedule','id_ta');
        }
    }

    public function studentQuota()
    {
        return $this->hasMany('App\StudentQuota','id_ta');
    }

    public function studentStatus()
    {
        return $this->hasMany('App\StudentStatus','id_ta');
    }

    public function studentAchievement()
    {
        return $this->hasMany('App\StudentAchievement','id_ta');
    }

    public function funding()
    {
        return $this->hasMany('App\Funding','id_ta');
    }

    public function research()
    {
        return $this->hasMany('App\Research','id_ta');
    }

    public function minithesis()
    {
        return $this->hasMany('App\Minithesis','id_ta');
    }

    public function curriculumIntegration()
    {
        return $this->hasMany('App\CurriculumIntegration','id_ta');
    }

    public function academicSatisfaction()
    {
        return $this->hasMany('App\AcademicSatisfaction','id_ta');
    }

    public function alumnusSatisfaction()
    {
        return $this->hasMany('App\AlumnusSatisfaction','id_ta');
    }
}
