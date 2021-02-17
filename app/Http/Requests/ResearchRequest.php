<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResearchRequest extends FormRequest
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
            'judul_penelitian'  => 'required',
            'tema_penelitian'   => 'required',
            'tingkat_penelitian' => 'required',
            'sks_penelitian'    => 'required|numeric',
            'sesuai_prodi'      => 'nullable',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
            'jumlah_biaya'      => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'id_ta'             => 'Tahun Akademik',
            'judul_penelitian'  => 'Judul Penelitian',
            'tema_penelitian'   => 'Tema Penelitian',
            'tingkat_penelitian' => 'Tingkat Penelitian',
            'sks_penelitian'    => 'SKS Penelitian',
            'sesuai_prodi'      => 'Kesesuaian Prodi',
            'sumber_biaya'      => 'Sumber Biaya',
            'sumber_biaya_nama' => 'Lembaga Sumber Biaya',
            'jumlah_biaya'      => 'Jumlah Biaya',
        ];
    }
}
