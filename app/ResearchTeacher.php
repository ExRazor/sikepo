<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResearchTeacher extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_penelitian',
        'nidn',
        'status',
    ];

    public function research()
    {
        return $this->belongsTo('App\Research','id_penelitian');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Teacher','nidn');
    }

    public function scopeJurusanKetua($query, $jurusan)
    {
        return $query->whereHas(
                                'teacher.studyProgram.department', function($q1) use($jurusan) {
                                    $q1->where('kd_jurusan',$jurusan);
                                }
                        )
                      ->where('status','Ketua');
    }
}
