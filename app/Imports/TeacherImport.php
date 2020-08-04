<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\Teacher;
use App\Models\TeacherStatus;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Hash;

class TeacherImport implements ToCollection, WithStartRow
{
    public function collection(Collection $columns)
    {
        foreach ($columns as $column)
        {
            $kd_prodi    = StudyProgram::where('nama',$column[4])->first()->kd_prodi;

            if($column[6] != 0) {
                $origDate   = Carbon::instance(Date::excelToDateTimeObject($column[8]));
                $tgl_lhr    = date("Y-m-d", strtotime($origDate));
            } else {
                $tgl_lhr    = date("Y-m-d");
            }

            Teacher::updateOrCreate(
                [
                    'nidn'       => $column[1],
                ],
                [
                    'nama'              => ucwords(strtolower($column[2])),
                    'nip'               => $column[3],
                    'jk'                => $column[5],
                    'agama'             => $column[6],
                    'tpt_lhr'           => $column[7],
                    'tgl_lhr'           => $tgl_lhr,
                    'alamat'            => $column[9],
                    'no_telp'           => $column[10],
                    'email'             => $column[11],
                    'pend_terakhir_jenjang' => $column[12],
                    'pend_terakhir_jurusan' => $column[13],
                    'bidang_ahli'       => json_encode(explode(', ',$column[14])),
                    'sesuai_bidang_ps'  => $column[15],
                    'ikatan_kerja'      => $column[16],
                    'jabatan_akademik'  => $column[17],
                    'sertifikat_pendidik' => $column[18],
                ]
            );


            $cekStatus = TeacherStatus::where('nidn',$column[1])->count();
            if($cekStatus == 0) {
                TeacherStatus::create([
                    'nidn'      => $column[1],
                    'periode'   => date('Y-m-d'),
                    'jabatan'   => 'Dosen',
                    'kd_prodi'  => $kd_prodi,
                    'is_active' => true
                ]);
            }

            $cekUserDosen = User::where('username',$column[1])->where('role','dosen')->count();
            if($cekUserDosen == 0) {
                User::create([
                    'username'   => $column[1],
                    'password'   => Hash::make($column[1]),
                    'role'       => 'dosen',
                    'name'       => ucwords(strtolower($column[2])),
                    'defaultPass'=> true,
                    'is_active'  => true,
                    'created_at' => now()
                ]);
            }
        }
    }

    public function startRow() : int
    {
        return 2;
    }
}
