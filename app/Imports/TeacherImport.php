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
        foreach ($columns as $column) {
            if ($column->filter()->isNotEmpty()) {

                if (empty($column[1]) || $column[1] == "") {
                    continue;
                }

                //Program Studi
                $prodi = ucwords(strtolower($column[4]));
                $kd_prodi    = StudyProgram::where('nama', $prodi)->first()->kd_prodi;

                //Nama
                $fullname = explode(',', $column[2]);
                $fullname[0] = ucwords(strtolower($fullname[0]));
                $nama = implode(',', $fullname);

                if ($column[8] != 0) {
                    $tgl_lhr = Date::excelToDateTimeObject($column[8])->format('Y-m-d');
                    // $origDate   = Date::excelToDateTimeObject()->format('Y-m-d');
                    // $tgl_lhr    = date("Y-m-d", strtotime($origDate));
                    // $tgl_lhr    = Carbon::createFromFormat('Y-m-d', $column['8']);
                    // dd($tgl_lhr);
                } else {
                    $tgl_lhr    = date("Y-m-d");
                }

                Teacher::updateOrCreate(
                    [
                        'nidn'       => $column[1],
                    ],
                    [
                        'nama'              => $nama,
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
                        'bidang_ahli'       => json_encode(explode(', ', $column[14])),
                        'sesuai_bidang_ps'  => $column[15],
                        'status_kerja'      => $column[16],
                        'jabatan_akademik'  => $column[17],
                        'sertifikat_pendidik' => $column[18],
                    ]
                );


                $cekStatus = TeacherStatus::where('nidn', $column[1])->count();
                if ($cekStatus == 0) {
                    TeacherStatus::create([
                        'nidn'      => $column[1],
                        'periode'   => date('Y-m-d'),
                        'jabatan'   => 'Dosen',
                        'kd_prodi'  => $kd_prodi,
                        'is_active' => true
                    ]);
                }

                $cekUserDosen = User::where('username', $column[1])->where('role', 'dosen')->count();
                if ($cekUserDosen == 0) {
                    User::create([
                        'username'    => $column[1],
                        'password'    => Hash::make($column[1]),
                        'role'        => 'dosen',
                        'name'        => ucwords(strtolower($column[2])),
                        'defaultPass' => true,
                        'is_active'   => true,
                        'created_at'  => now()
                    ]);
                }
            }
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
