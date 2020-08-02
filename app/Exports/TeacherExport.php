<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

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

        if(Auth::user()->hasRole('kaprodi')) {
            $query->whereHas(
                'latestStatus.studyProgram', function($query) {
                    $query->where('kd_prodi',Auth::user()->kd_prodi);
                }
            );
        } else {
            $query->whereHas(
                'latestStatus.studyProgram', function($query) {
                    if($this->kd_prodi) {
                        $query->where('kd_prodi',$this->kd_prodi);
                    } else {
                        $query->where('kd_jurusan',setting('app_department_id'));
                    }
                }
            );
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'No',
            'NIDN',
            'Nama',
            'NIP',
            'Jenis Kelamin',
            'Agama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'No Telp',
            'Email',
            'Program Studi',
            'Pendidikan Terakhir',
            'Ikatan Kerja',
            'Jabatan Akademik',
        ];
    }

    public function map($teacher): array
    {
        return [
            $this->i++,
            $teacher->nidn,
            $teacher->nama,
            $teacher->nip,
            $teacher->jk,
            $teacher->agama,
            $teacher->tpt_lhr,
            $teacher->tgl_lhr,
            $teacher->alamat,
            $teacher->no_telp,
            $teacher->email,
            $teacher->latestStatus->studyProgram->nama,
            $teacher->pend_terakhir_jenjang,
            $teacher->ikatan_kerja,
            $teacher->jabatan_akademik,
        ];
    }
}
