<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResearchStudentRequest extends FormRequest
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
        if ($this->asal_mahasiswa == 'Luar') {
            $anggota_nim    = 'nullable';
            $anggota_nama   = 'required';
            $anggota_asal   = 'required';
        } else if ($this->asal_mahasiswa == '') {
            $anggota_nim    = 'nullable';
            $anggota_nama   = 'nullable';
            $anggota_asal   = 'nullable';
        } else {
            $anggota_nim    = 'required';
            $anggota_nama   = 'nullable';
            $anggota_asal   = 'nullable';
        }

        return [
            'asal_mahasiswa' => 'required',
            'anggota_nim'    => $anggota_nim . '|numeric',
            'anggota_nama'   => $anggota_nama,
            'anggota_asal'   => $anggota_asal,
        ];
    }

    public function attributes()
    {

        return [
            'asal_mahasiswa' => 'Asal Mahasiswa',
            'anggota_nim'    => 'NIM',
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
