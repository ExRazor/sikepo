<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collaboration extends Model
{
    protected $fillable = [
                            'kd_prodi',
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
    public function academicYear()
    {
        return $this->belongsTo('App\AcademicYear','id_ta');
    }
}
