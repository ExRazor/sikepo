<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    protected $fillable = [
        'id_ta',
        'judul_penelitian',
        'tema_penelitian',
        'sks_penelitian',
        'sumber_biaya',
        'sumber_nama',
        'jumlah_biaya',
    ];

    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','id_ta');
    }

    public function researchTeacher()
    {
        return $this->hasMany('App\ResearchTeacher','id_penelitian');
    }

    public function researchKetua()
    {
        return $this->hasOne('App\ResearchTeacher','id_penelitian')->where('status','Ketua');
    }

    public function researchAnggota()
    {
        return $this->hasMany('App\ResearchTeacher','id_penelitian')->where('status','Anggota');
    }

    public function researchStudent()
    {
        return $this->hasMany('App\ResearchStudent','id_penelitian');
    }

    // public function scopeKetuaPenelitian($query, $jurusan = null)
    // {
    //    return $query->whereHas('researchTeacher', function($q1) use($jurusan) {
    //         $q1->where('status','Ketua');
    //         if($jurusan) {
    //             $q1->whereHas('teacher.studyProgram', function($q2) use($jurusan) {
    //                 return $q2->where('kd_jurusan', $jurusan);
    //             });
    //         }
    //    });
    // }

    // public function scopeAnggotaPenelitian($query)
    // {
    //    return $query->whereHas('researchTeacher', function($q) {
    //         $q->where('status', 'Anggota');
    //    });
    // }
}
