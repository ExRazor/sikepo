<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\StudentStatus;
use App\Models\StudyProgram;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentImport implements ToCollection, WithStartRow
{
    public function collection(Collection $columns)
    {
        foreach ($columns as $column) {
            if ($column->filter()->isNotEmpty()) {
                $kd_prodi    = StudyProgram::where('nama', $column[3])->first()->kd_prodi;
                $thn_masuk   = AcademicYear::where('tahun_akademik', $column[4])->where('semester', 'Ganjil')->first();

                if ($column[8] != 0) {
                    // $tgl_lhr   = Date::excelToDateTimeObject($column[8])->format('Y-m-d');
                    // $tgl_lhr    = date("Y-m-d", strtotime($column[8]));
                    // $tgl_lhr    = Carbon::createFromFormat('Y-m-d', $column['8']);
                    $tgl_lhr    = Carbon::createFromFormat('Y-m-d', $column['8']);
                } else {
                    $tgl_lhr    = date("Y-m-d");
                }

                Student::updateOrCreate(
                    [
                        'nim'       => $column[1],
                    ],
                    [
                        'nama'              => ucwords(strtolower($column[2])),
                        'kd_prodi'          => $kd_prodi,
                        'angkatan'          => $column[4],
                        'jk'                => $column[5],
                        'agama'             => $column[6],
                        'tpt_lhr'           => $column[7],
                        'tgl_lhr'           => $tgl_lhr,
                        'alamat'            => $column[9],
                        'kewarganegaraan'   => $column[10],
                        'kelas'             => $column[11],
                        'tipe'              => $column[12],
                        'program'           => $column[13],
                        'seleksi_jenis'     => $column[14],
                        'seleksi_jalur'     => $column[15],
                        'status_masuk'      => $column[16],
                    ]
                );

                $cekStatus = StudentStatus::where('nim', $column[1])->count();
                if ($cekStatus == 0) {
                    StudentStatus::create([
                        'id_ta'     => $thn_masuk->id,
                        'nim'       => $column[1],
                        'status'    => 'Aktif'
                    ]);
                }

                if ($cekStatus > 0 && $column[17]) {
                    $ta = explode(' - ', $column[19]);
                    $thn_status = AcademicYear::where('tahun_akademik', $ta[0])->where('semester', $ta[1])->first();

                    StudentStatus::create([
                        'id_ta'         => $thn_status->id,
                        'nim'           => $column[1],
                        'status'        => $column[17],
                        'ipk_terakhir'  => $column[18]
                    ]);
                }
            }
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function collection_old($rows)
    {
        foreach ($rows as $index => $row) {
            // $substr_nim  = substr($row[4],0,-5);
            $kd_prodi    = StudyProgram::where('nama', $row[12])->first()->kd_prodi;
            $smt_awal    = explode('/', $row[18]);
            $thn_masuk   = AcademicYear::where('tahun_akademik', $smt_awal[0])->where('semester', 'Ganjil')->first();

            if ($row[6] != 0) {
                $origDate   = Carbon::instance(Date::excelToDateTimeObject($row[6]));
                $newDate    = date("Y-m-d", strtotime($origDate));
            } else {
                $newDate    = date("Y-m-d");
            }

            if ($row[7] == 'L') {
                $jk = 'Laki-Laki';
            } else if ($row[7] == 'P') {
                $jk = 'Perempuan';
            }

            if ($row[19] == 'Non-Aktif') {
                $row[19] = 'Nonaktif';
            }

            Student::updateOrCreate(
                [
                    'nim'       => $row[4],
                ],
                [
                    'nama'              => ucwords(strtolower($row[5])),
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

            if ($row[19] == 'Nonaktif') {
                StudentStatus::create([
                    'id_ta'     => $thn_masuk->id,
                    'nim'       => $row[4],
                    'status'    => $row[19]
                ]);
            }
        }
    }
}
