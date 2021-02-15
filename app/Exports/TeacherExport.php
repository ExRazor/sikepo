<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Library\NumberFormatExcel as NumberFormat;

class TeacherExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    private $i = 1;

    public function __construct($request)
    {
        $this->kd_prodi = $request->input('kd_prodi') ?? null;
    }

    public function query()
    {
        $query = Teacher::query();

        $query->whereHas(
            'latestStatus.studyProgram',
            function ($query) {
                if (Auth::user()->hasRole('kaprodi')) {
                    $query->where('kd_prodi', Auth::user()->kd_prodi);
                } else {
                    if ($this->kd_prodi) {
                        $query->where('kd_prodi', $this->kd_prodi);
                    } else {
                        $query->where('kd_jurusan', setting('app_department_id'));
                    }
                }
            }
        );

        return $query;
    }

    public function headings(): array
    {
        return [
            'No',
            'NIDN',
            'Nama',
            'NIP',
            'Program Studi',
            'Jenis Kelamin',
            'Agama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'No Telp',
            'Email',
            'Jenjang Pendidikan',
            'Jurusan Pendidikan',
            'Bidang Keahlian',
            'Sesuai Bidang Prodi',
            'Status Kerja',
            'Jabatan Akademik',
            'No. Sertifikat Pendidik',
        ];
    }

    public function map($teacher): array
    {
        return [
            $this->i++,
            $teacher->nidn,
            $teacher->nama,
            $teacher->nip,
            $teacher->latestStatus->studyProgram->nama,
            $teacher->jk,
            $teacher->agama,
            $teacher->tpt_lhr,
            $teacher->tgl_lhr,
            $teacher->alamat,
            $teacher->no_telp,
            $teacher->email,
            $teacher->pend_terakhir_jenjang,
            $teacher->pend_terakhir_jurusan,
            implode(', ', json_decode($teacher->bidang_ahli)),
            $teacher->sesuai_bidang_ps,
            $teacher->status_kerja,
            $teacher->jabatan_akademik,
            $teacher->sertifikat_pendidik,
        ];
    }
}
