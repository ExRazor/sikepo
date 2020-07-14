<?php

namespace App\Models;

use App\Models\BaseModel;

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
        return $this->belongsTo('App\Models\StudyProgram','kd_prodi');
    }

    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear','id_ta');
    }
}
