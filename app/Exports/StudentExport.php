<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\StudentStatus;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    private $i = 1;

    public function __construct($request)
    {
        $this->kd_prodi = $request->input('kd_prodi') ?? null;
        $this->angkatan = $request->input('angkatan') ?? null;
        $this->kewarganegaraan = $request->input('kewarganegaraan') ?? null;
        $this->status_terakhir = $request->input('status_terakhir') ?? null;
    }

    public function query()
    {
        $query = Student::query();

        $query->whereHas(
            'studyProgram', function($query) {
                if(Auth::user()->hasRole('kaprodi')) {
                    $query->where('kd_prodi',Auth::user()->kd_prodi);
                } else {
                    if($this->kd_prodi) {
                        $query->where('kd_prodi',$this->kd_prodi);
                    } else {
                        $query->where('kd_jurusan',setting('app_department_id'));
                    }
                }
            }
        );

        $query->orderBy('angkatan','desc')->orderBy('nama','asc');

        return $query;
    }

    public function headings(): array
    {
        return [
            'No',
            'NIM',
            'Nama',
            'Program Studi',
            'Angkatan',
            'Jenis Kelamin',
            'Agama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'Kewarganegaraan',
            'Kelas Mahasiswa',
            'Tipe Mahasiswa',
            'Program Akademik',
            'Seleksi Masuk',
            'Seleksi Jalur',
            'Status Masuk',
            'Status Terakhir',
            'IPK Terakhir',
            'TA Status Terakhir',
        ];
    }

    public function map($student): array
    {
        $cekStatus = StudentStatus::where('nim',$student->nim)->count();
        if($cekStatus>1) {
            $status['status'] = $student->latestStatus->status;
            $status['ipk']    = $student->latestStatus->ipk_terakhir;
            $status['tahun_akademik']  = $student->latestStatus->academicYear->tahun_akademik.' - '.$student->latestStatus->academicYear->semester;
        } else {
            $status['status'] = null;
            $status['ipk']    = null;
            $status['tahun_akademik']  = null;
        }

        return [
            $this->i++,
            $student->nim,
            $student->nama,
            $student->studyProgram->nama,
            $student->angkatan,
            $student->jk,
            $student->agama,
            $student->tpt_lhr,
            $student->tgl_lhr,
            $student->alamat,
            $student->kewarganegaraan,
            $student->kelas,
            $student->tipe,
            $student->program,
            $student->seleksi_jenis,
            $student->seleksi_jalur,
            $student->status_masuk,
            $status['status'],
            $status['ipk'],
            $status['tahun_akademik'],
        ];
    }
}
