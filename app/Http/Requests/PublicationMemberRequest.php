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
        if ($this->status_penulis == 'Lainnya') {
            $penulis_idunik = 'nullable';
            $penulis_nama   = 'required';
            $penulis_asal   = 'required';
        } else if ($this->status_penulis == '') {
            $penulis_idunik = 'nullable';
            $penulis_nama   = 'nullable';
            $penulis_asal   = 'nullable';
        } else {
            $penulis_idunik = 'required';
            $penulis_nama   = 'nullable';
            $penulis_asal   = 'nullable';
        }

        return [
            'status_penulis' => 'required',
            'penulis_idunik' => $penulis_idunik,
            'penulis_nama'   => $penulis_nama,
            'penulis_asal'   => $penulis_asal,
        ];
    }

    public function attributes()
    {
        if ($this->status_penulis == 'Dosen') {
            $nidn_nim = 'NIDN';
        } else {
            $nidn_nim = 'NIM';
        }

        return [
            'status_penulis' => 'Status Penulis',
            'penulis_idunik' => $nidn_nim,
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
