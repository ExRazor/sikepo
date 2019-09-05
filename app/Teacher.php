<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $primaryKey = 'nidn';
    protected $fillable = [
        'nidn',
        'id_ta',
        'nama_lembaga',
        'tingkat',
        'judul_kegiatan',
        'manfaat_kegiatan',
        'waktu',
        'durasi',
        'bukti',
    ];

    public function studyProgram()
    {
        return $this->belongsTo('App\StudyProgram','kd_prodi');
    }
}
