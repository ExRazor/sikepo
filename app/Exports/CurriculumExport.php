<?php

namespace App\Exports;

use App\Models\Curriculum;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CurriculumExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    private $i = 1;

    public function __construct($request)
    {
        $this->kd_prodi = $request->input('kd_prodi') ?? null;
    }

    public function query()
    {
        $query = Curriculum::query();

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

        $query->orderBy('versi','desc')->orderBy('kd_prodi','asc')->orderBy('nama','asc');

        return $query;
    }

    public function headings(): array
    {
        return [
            'No',
            'Program Studi',
            'Kode Mata Kuliah',
            'Nama Mata Kuliah',
            'Tahun Kurikulum',
            'Semester',
            'Jenis',
            'SKS Teori',
            'SKS Seminar',
            'SKS Praktikum',
            'Unit Penyelenggara',
        ];
    }

    public function map($curriculum): array
    {
        return [
            $this->i++,
            $curriculum->studyProgram->nama,
            $curriculum->kd_matkul,
            $curriculum->nama,
            $curriculum->versi,
            $curriculum->semester,
            $curriculum->jenis,
            $curriculum->sks_teori,
            $curriculum->sks_seminar,
            $curriculum->sks_praktikum,
            $curriculum->unit_penyelenggara,
        ];
    }
}
