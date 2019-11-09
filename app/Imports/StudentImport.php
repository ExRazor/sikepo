<?php

namespace App\Imports;

use App\AcademicYear;
use App\Student;
use App\StudentStatus;
use App\StudyProgram;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentImport implements ToCollection, WithStartRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row)
        {
            // $substr_nim  = substr($row[4],0,-5);
            $kd_prodi    = StudyProgram::where('nama',$row[12])->first()->kd_prodi;
            $smt_awal    = explode('/',$row[18]);
            $thn_masuk   = AcademicYear::where('tahun_akademik',$smt_awal[0])->where('semester','Ganjil')->first();

            if($row[6] != 0) {
                $origDate   = Carbon::instance(Date::excelToDateTimeObject($row[6]));
                $newDate    = date("Y-m-d", strtotime($origDate));
            } else {
                $newDate    = date("Y-m-d");
            }

            if($row[7] == 'L') {
                $jk = 'Laki-Laki';
            } else if ($row[7] == 'P') {
                $jk = 'Perempuan';
            }

            if($row[19] == 'Non-Aktif') {
                $row[19] = 'Nonaktif';
            }

            Student::updateOrCreate(
                [
                    'nim'       => $row[4],
                ],
                [
                    'nama'              => $row[5],
                    'tpt_lhr'           => '',
                    'tgl_lhr'           => $newDate,
                    'jk'                => $jk,
                    'agama'             => $row[8],
                    'alamat'            => '',
                    'kewarganegaraan'   => 'WNI',
                    'kd_prodi'          => $kd_prodi,
                    'kelas'             => $row[13],
                    'tipe'              => $row[14],
                    'program'           => 'Reguler',
                    'seleksi_jenis'     => $row[15],
                    'seleksi_jalur'     => $row[16],
                    'status_masuk'      => $row[17],
                    'angkatan'          => $row[2],
                ]
            );

            StudentStatus::create([
                'id_ta'     => $thn_masuk->id,
                'nim'       => $row[4],
                'status'    => 'Aktif'
            ]);

            if($row[19] == 'Nonaktif') {
                StudentStatus::create([
                    'id_ta'     => $thn_masuk->id,
                    'nim'       => $row[4],
                    'status'    => $row[19]
                ]);
            }
        }
    }

    public function startRow() : int
    {
        return 3;
    }
}
