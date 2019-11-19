<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    protected $fillable = [
        'tema_penelitian',
        'judul_penelitian',
        'tahun_penelitian',
        'tahun_penelitian',
        'sumber_biaya',
        'sumber_nama',
        'jumlah_biaya',
    ];

    public function teacher()
    {
        return $this->belongsTo('App\Teacher','nidn');
    }

    public function researchTeacher()
    {
        return $this->hasMany('App\ResearchTeacher','id_penelitian');
    }

    public function researchStudents()
    {
        return $this->hasMany('App\ResearchStudents','id_penelitian');
    }

    public function researchKetua()
    {
        return $this->hasOne('App\ResearchTeacher','nidn')->where('status','Ketua');
    }

    public function researchAnggota()
    {
        return $this->hasMany('App\ResearchTeacher','nidn')->where('status','Anggota');
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
