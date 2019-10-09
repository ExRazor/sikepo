<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $primaryKey = 'nidn';
    protected $fillable = [
        'nidn',
        'nama'                 ,
        'jk'                   ,
        'agama'                ,
        'tpt_lhr'              ,
        'tgl_lhr'              ,
        'alamat'               ,
        'no_telp'              ,
        'email'                ,
        'pend_terakhir_jenjang',
        'pend_terakhir_jurusan',
        'bidang_ahli'          ,
        'dosen_ps'             ,
        'status_pengajar'      ,
        'jabatan_akademik'     ,
        'sertifikat_pendidik'     ,
        'sesuai_bidang_ps'     ,
        'foto'     ,
    ];

    public function studyProgram()
    {
        return $this->belongsTo('App\StudyProgram','dosen_ps');
    }

    public function ewmp()
    {
        return $this->hasMany('App\Ewmp');
    }
}
