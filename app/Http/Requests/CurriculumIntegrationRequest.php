<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurriculumIntegrationRequest extends FormRequest
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
            'id_ta'             => 'required',
            'kegiatan'          => 'required',
            'nidn'              => 'required',
            'kd_matkul'         => 'required',
            'bentuk_integrasi'  => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'id_ta'             => 'Tahun Akademik',
            'kegiatan'          => 'Nama Kegiatan',
            'nidn'              => 'Dosen',
            'kd_matkul'         => 'Mata Kuliah',
            'bentuk_integrasi'  => 'Bentuk Integrasi',
        ];
    }
}
