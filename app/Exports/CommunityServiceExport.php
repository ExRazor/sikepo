<?php

namespace App\Exports;

use App\Models\CommunityService;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use App\Library\NumberFormatExcel as NumberFormat;


class CommunityServiceExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    use Exportable;

    private $i = 1;

    public function __construct($request)
    {
        $this->periode_awal     = $request->input('periode_awal');
        $this->periode_akhir    = $request->input('periode_akhir') ?? null;
        $this->tipe             = $request->input('tipe');
        $this->kd_prodi         = $request->input('kd_prodi') ?? null;
        $this->nidn             = $request->input('nidn') ?? null;
    }

    public function query()
    {
        if(empty($this->periode_akhir)) {
            $batas = [$this->periode_awal,$this->periode_awal];
        } else {
            $batas = [$this->periode_awal,$this->periode_akhir];
        }

        $query = CommunityService::whereHas(
            'academicYear', function($q) use($batas) {
                $q->whereBetween('tahun_akademik',$batas);
            }
        );

        switch($this->tipe) {
            case 'prodi':
                $query->whereHas(
                    'serviceTeacher', function($query) {
                        if(Auth::user()->hasRole('kaprodi')) {
                            $query->prodiKetua(Auth::user()->kd_prodi);
                        } else {
                            if($this->kd_prodi) {
                                $query->prodiKetua($this->kd_prodi);
                            } else {
                                $query->jurusanKetua(setting('app_department_id'));
                            }
                        }
                    }
                );
            break;
            case 'individu':
                $query->whereHas(
                    'serviceTeacher', function($q) {
                        $q->where('nidn',$this->nidn)->where('status','Ketua');
                    }
                );
            break;
        }

        $query->orderBy('id_ta','desc');

        return $query;
    }

    public function headings(): array
    {
        return [
            'No',
            'NIDN',
            'Nama',
            'Program Studi',
            'Judul',
            'Tahun',
            'Tema',
            'SKS',
            'Sumber Biaya',
            'Lembaga Sumber Biaya',
            'Jumlah Biaya',
        ];
    }

    public function map($research): array
    {
        return [
            $this->i++,
            $research->serviceKetua->teacher->nidn,
            $research->serviceKetua->teacher->nama,
            $research->serviceKetua->teacher->latestStatus->studyProgram->nama,
            $research->judul_pengabdian,
            $research->academicYear->tahun_akademik.' - '.$research->academicYear->semester,
            $research->tema_pengabdian,
            $research->sks_pengabdian,
            $research->sumber_biaya,
            $research->sumber_biaya_nama,
            $research->jumlah_biaya,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
            'K' => NumberFormat::FORMAT_ACCOUNTING_IDR,
        ];
    }
}
