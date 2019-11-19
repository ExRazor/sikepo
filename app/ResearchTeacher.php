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

    public function researchKetua()
    {
        return $this->hasOne('App\ResearchTeacher','nidn')->where('status','Ketua');
    }

    public function researchAnggota()
    {
        return $this->hasMany('App\ResearchTeacher','nidn')->where('status','Anggota');
    }
}
