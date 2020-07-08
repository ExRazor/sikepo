<?php

namespace App\Imports;

use App\Curriculum;
use App\StudyProgram;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CurriculumImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $prodi = StudyProgram::where('nama','LIKE','%'.$row[0].'%')->first();
        $pengetahuan = 'Pengetahuan, Sikap';

        // dd($prodi);
        return Curriculum::updateOrCreate(
            [
                'kd_matkul'     => $row[1],
            ],
            [
                'kd_prodi'      => $prodi->kd_prodi,
                'nama'          => ucfirst(strtolower($row[2])),
                'versi'         => $row[3],
                'jenis'         => $row[4],
                'semester'      => $row[5],
                'sks_teori'     => $row[6],
                'sks_seminar'   => '0',
                'sks_praktikum' => '0',
                'capaian'       => explode(', ',$pengetahuan),
                'dokumen_nama'  => 'RPB - 2017',
                'unit_penyelenggara'  => 'Program Studi'
            ]
        );
    }

    public function startRow() : int
    {
        return 2;
    }
}
