<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResearchTeacherRequest extends FormRequest
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
        if ($this->asal_dosen == 'Luar') {
            $anggota_nidn   = 'nullable';
            $anggota_nama   = 'required';
            $anggota_asal   = 'required';
        } else if ($this->asal_dosen == '') {
            $anggota_nidn   = 'nullable';
            $anggota_nama   = 'nullable';
            $anggota_asal   = 'nullable';
        } else {
            $anggota_nidn   = 'required';
            $anggota_nama   = 'nullable';
            $anggota_asal   = 'nullable';
        }

        return [
            'asal_dosen'     => 'required',
            'anggota_nidn'   => $anggota_nidn . '|numeric',
            'anggota_nama'   => $anggota_nama,
            'anggota_asal'   => $anggota_asal,
        ];
    }

    public function attributes()
    {

        return [
            'asal_dosen'     => 'Asal Dosen',
            'anggota_nidn'   => 'NIDN',
            'anggota_nama'   => 'Nama penulis',
            'anggota_asal'   => 'Asal penulis',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute jangan dikosongkan!',
        ];
    }
}
