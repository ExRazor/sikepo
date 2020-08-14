<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CurriculumRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = ($this->id ? decrypt($this->id) : null);

        return [
            'kd_matkul'         => [
                                    'required',
                                    Rule::unique('curricula')->where(function ($query) {
                                        return $query->where('kd_matkul', $this->kd_matkul);
                                    })->ignore($id),
                                ],
            'kd_prodi'          => 'required',
            'versi'             => 'required|numeric|digits:4',
            'nama'              => 'required',
            'semester'          => 'required|numeric',
            'jenis'             => 'required',
            'sks_teori'         => 'nullable|numeric',
            'sks_seminar'       => 'nullable|numeric',
            'sks_praktikum'     => 'nullable|numeric',
            'capaian'           => 'required',
            'kompetensi_prodi'  => 'nullable',
            'dokumen_nama'      => 'nullable',
            'unit_penyelenggara'=> 'required',
        ];
    }

    public function attributes()
    {
        return [
            'kd_matkul'         => 'Kode Mata Kuliah',
            'kd_prodi'          => 'Program Studi',
            'versi'             => 'Tahun Kurikulum',
            'nama'              => 'Nama Mata Kuliah',
            'semester'          => 'Semester Mata Kuliah',
            'jenis'             => 'Jenis Mata Kuliah',
            'sks_teori'         => 'SKS Teori',
            'sks_seminar'       => 'SKS Seminar',
            'sks_praktikum'     => 'SKS Praktikum',
            'capaian'           => 'Capaian Mata Kuliah',
            'kompetensi_prodi'  => 'Kesesuaian Prodi',
            'dokumen_nama'      => 'Nama Dokumen Kurikulum',
            'unit_penyelenggara'=> 'Unit Penyelenggara',
        ];
    }
}
