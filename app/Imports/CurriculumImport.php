<?php

namespace App\Imports;

use App\Models\Curriculum;
use App\Models\StudyProgram;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CurriculumImport implements ToModel, WithStartRow
{
    public function model(array $column)
    {
        $prodi      = StudyProgram::where('nama','LIKE','%'.$column[1].'%')->first();
        $capaian    = 'Pengetahuan';

        // dd($prodi);
        return Curriculum::updateOrCreate(
            [
                'kd_matkul'     => $column[2],
            ],
            [
                'kd_prodi'      => $prodi->kd_prodi,
                'nama'          => ucwords(strtolower($column[3])),
                'versi'         => $column[4],
                'semester'      => $column[5],
                'jenis'         => $column[6],
                'sks_teori'     => ($column[7]!='' ? $column[7] : 0),
                'sks_seminar'   => ($column[8]!='' ? $column[8] : 0),
                'sks_praktikum' => ($column[9]!='' ? $column[9] : 0),
                'capaian'       => explode(', ',$capaian),
                'unit_penyelenggara' => $column[10]
            ]
        );
    }

    public function startRow() : int
    {
        return 2;
    }

    public function model_old(array $row)
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
                'nama'          => ucwords(strtolower($row[2])),
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
}
