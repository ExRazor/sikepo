<?php

namespace App\Exports;

use App\Models\Collaboration;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;


class CollaborationExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    private $i = 1;

    public function __construct($request)
    {
        $this->periode_awal     = $request->input('periode_awal');
        $this->periode_akhir    = $request->input('periode_akhir') ?? null;
        $this->kd_prodi         = $request->input('kd_prodi') ?? null;
    }

    public function query()
    {
        if(empty($this->periode_akhir)) {
            $batas = [$this->periode_awal,$this->periode_awal];
        } else {
            $batas = [$this->periode_awal,$this->periode_akhir];
        }

        $query = Collaboration::whereHas(
            'academicYear', function($q) use($batas) {
                $q->whereBetween('tahun_akademik',$batas);
            }
        );

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

        $query->orderBy('id_ta','desc');

        return $query;
    }

    public function headings(): array
    {
        return [
            'No',
            'Program Studi',
            'Lembaga Mitra',
            'Tahun',
            'Jenis Kerjasama',
            'Tingkat Kerjasama',
            'Judul Kegiatan',
            'Manfaat Kegiatan',
            'Waktu Kegiatan',
            'Durasi Kegiatan',
        ];
    }

    public function map($collaboration): array
    {
        return [
            $this->i++,
            $collaboration->studyProgram->nama,
            $collaboration->nama_lembaga,
            $collaboration->academicYear->tahun_akademik.' - '.$collaboration->academicYear->semester,
            $collaboration->jenis,
            $collaboration->tingkat,
            $collaboration->judul_kegiatan,
            $collaboration->manfaat_kegiatan,
            $collaboration->waktu,
            $collaboration->durasi,
        ];
    }
}
