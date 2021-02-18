<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicationMemberRequest extends FormRequest
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
        if ($this->status_penulis == 'Dosen') {
            $penulis_nidn   = 'required';
            $penulis_nim    = 'nullable';
            $penulis_nama   = 'nullable';
            $penulis_asal   = 'nullable';
        } else if ($this->status_penulis == 'Mahasiswa') {
            $penulis_nidn   = 'nullable';
            $penulis_nim    = 'required';
            $penulis_nama   = 'nullable';
            $penulis_asal   = 'nullable';
        } else if ($this->status_penulis == 'Lainnya') {
            $penulis_nidn   = 'nullable';
            $penulis_nim    = 'nullable';
            $penulis_nama   = 'required';
            $penulis_asal   = 'required';
        } else {
            $penulis_nidn   = 'nullable';
            $penulis_nim    = 'nullable';
            $penulis_nama   = 'nullable';
            $penulis_asal   = 'nullable';
        }

        return [
            'status_penulis' => 'required',
            'penulis_nidn'   => $penulis_nidn . '|numeric',
            'penulis_nim'    => $penulis_nim . '|numeric',
            'penulis_nama'   => $penulis_nama,
            'penulis_asal'   => $penulis_asal,
        ];
    }

    public function attributes()
    {
        return [
            'status_penulis' => 'Status Penulis',
            'penulis_nidn'   => 'NIDN',
            'penulis_nim'    => 'NIM',
            'penulis_nama'   => 'Nama penulis',
            'penulis_asal'   => 'Asal penulis',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute jangan dikosongkan!',
        ];
    }
}
