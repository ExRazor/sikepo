<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommunityServiceStudentRequest extends FormRequest
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
            $mahasiswa_nim    = 'nullable';
            $mahasiswa_nama   = 'required';
            $mahasiswa_asal   = 'required';
        } else if ($this->asal_mahasiswa == '') {
            $mahasiswa_nim    = 'nullable';
            $mahasiswa_nama   = 'nullable';
            $mahasiswa_asal   = 'nullable';
        } else {
            $mahasiswa_nim    = 'required';
            $mahasiswa_nama   = 'nullable';
            $mahasiswa_asal   = 'nullable';
        }

        return [
            'asal_mahasiswa'   => 'required',
            'mahasiswa_nim'    => $mahasiswa_nim . '|numeric',
            'mahasiswa_nama'   => $mahasiswa_nama,
            'mahasiswa_asal'   => $mahasiswa_asal,
        ];
    }

    public function attributes()
    {
        return [
            'asal_mahasiswa' => 'Asal Mahasiswa',
            'mahasiswa_nim'    => 'NIM',
            'mahasiswa_nama'   => 'Nama Mahasiswa',
            'mahasiswa_asal'   => 'Asal Lokasi Mahasiswa',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute jangan dikosongkan!',
        ];
    }
}
