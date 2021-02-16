<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicationMember extends Model
{
    protected $fillable = [
        'id_publikasi',
        'id_unik',
        'nama',
        'asal',
        'status',
        'penulis_utama',
    ];

    public function publication()
    {
        return $this->belongsTo('App\Models\TeacherPublication', 'id_publikasi');
    }

    public function studyProgram()
    {
        return $this->belongsTo('App\Models\StudyProgram', 'asal');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher', 'id_unik');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'id_unik');
    }

    public function scopeDosen($query, $nidn)
    {
        return $query->where('id_unik', $nidn);
    }

    public function scopeMahasiswa($query, $nim)
    {
        return $query->where('id_unik', $nim);
    }

    public function scopeJurusan($query, $kd_jurusan, $type)
    {
        if ($type == 'Dosen') {
            $queryRelation = 'teacher.latestStatus.studyProgram';
        } else {
            $queryRelation = 'student.studyProgram';
        }

        return $query->whereHas(
            $queryRelation,
            function ($q1) use ($kd_jurusan) {
                $q1->where('kd_jurusan', $kd_jurusan);
            }
        );
    }

    public function scopeProdi($query, $kd_prodi, $type, $status = null)
    {
        if ($type == 'Dosen') {
            $queryRelation = 'teacher.latestStatus.studyProgram';
        } else {
            $queryRelation = 'student.studyProgram';
        }

        return $query->whereHas(
            $queryRelation,
            function ($q1) use ($kd_prodi) {
                $q1->where('kd_prodi', $kd_prodi);
            }
        );
    }
}
