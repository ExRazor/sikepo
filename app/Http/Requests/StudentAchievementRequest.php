<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentAchievementRequest extends FormRequest
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
        return [
            'nim'               => 'required',
            'id_ta'             => 'required',
            'kegiatan_nama'     => 'required',
            'kegiatan_tingkat'  => 'required',
            'prestasi'          => 'required',
            'prestasi_jenis'    => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'nim'               => 'Mahasiswa',
            'id_ta'             => 'Tahun Akademik',
            'kegiatan_nama'     => 'Nama Kegiatan',
            'kegiatan_tingkat'  => 'Tingkat Kegiatan',
            'prestasi'          => 'Nama Prestasi',
            'prestasi_jenis'    => 'Jenis Prestasi',
        ];
    }
}
