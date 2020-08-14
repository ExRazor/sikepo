<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentPublicationRequest extends FormRequest
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
            'jenis_publikasi' => 'required',
            'nim'             => 'required',
            'judul'           => 'required',
            'penerbit'        => 'required',
            'id_ta'           => 'required',
            'jurnal'          => 'nullable',
            'sesuai_prodi'    => 'nullable',
            'akreditasi'      => 'nullable',
            'sitasi'          => 'nullable|numeric',
            'tautan'          => 'nullable|url',
        ];
    }

    public function attributes()
    {
        return [
            'jenis_publikasi' => 'Jenis Publikasi',
            'nim'             => 'Mahasiswa',
            'judul'           => 'Judul Publikasi',
            'penerbit'        => 'Penerbit',
            'id_ta'           => 'Tahun Publikasi',
            'jurnal'          => 'Nama Jurnal',
            'sesuai_prodi'    => 'Kesesuaian Prodi',
            'akreditasi'      => 'Akreditasi',
            'sitasi'          => 'Jumlah Sitasi',
            'tautan'          => 'Tautan',
        ];
    }
}
