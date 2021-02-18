<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommunityServiceTeacherRequest extends FormRequest
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
        if ($this->asal_penyelenggara == 'Luar') {
            $anggota_nidn   = 'nullable';
            $anggota_nama   = 'required';
            $anggota_asal   = 'required';
        } else if ($this->asal_penyelenggara == '') {
            $anggota_nidn   = 'nullable';
            $anggota_nama   = 'nullable';
            $anggota_asal   = 'nullable';
        } else {
            $anggota_nidn   = 'required';
            $anggota_nama   = 'nullable';
            $anggota_asal   = 'nullable';
        }

        return [
            'asal_penyelenggara'     => 'required',
            'anggota_nidn'   => $anggota_nidn . '|numeric',
            'anggota_nama'   => $anggota_nama,
            'anggota_asal'   => $anggota_asal,
        ];
    }

    public function attributes()
    {

        return [
            'asal_penyelenggara'    => 'Asal Dosen',
            'anggota_nidn'          => 'NIDN',
            'anggota_nama'          => 'Nama Dosen',
            'anggota_asal'          => 'Asal Lokasi Dosen',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute jangan dikosongkan!',
        ];
    }
}
