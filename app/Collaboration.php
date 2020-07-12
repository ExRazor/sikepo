<?php

namespace App;

use App\BaseModel;

class Collaboration extends BaseModel
{
    protected $fillable = [
                            'kd_prodi',
                            'id_ta',
                            'jenis',
                            'nama_lembaga',
                            'tingkat',
                            'judul_kegiatan',
                            'manfaat_kegiatan',
                            'waktu',
                            'durasi',
                            'bukti_nama',
                            'bukti_file',
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
