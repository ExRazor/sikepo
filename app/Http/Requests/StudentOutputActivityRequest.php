<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentOutputActivityRequest extends FormRequest
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
            'kegiatan'          => 'required',
            'nm_kegiatan'       => 'nullable',
            'nim'               => 'required',
            'id_kategori'       => 'required',
            'judul_luaran'      => 'required',
            'id_ta'             => 'required',
            'jenis_luaran'      => 'required',
            'url'               => 'url|nullable',
            'file_karya'        => 'mimes:pdf',
        ];
    }

    public function attributes()
    {
        return [
            'kegiatan'          => 'Jenis Kegiatan',
            'nm_kegiatan'       => 'Nama Kegiatan',
            'nim'               => 'Mahasiswa',
            'id_kategori'       => 'Kategori Luaran',
            'judul_luaran'      => 'Judul Luaran',
            'id_ta'             => 'Tahun Luaran',
            'jenis_luaran'      => 'Jenis Luaran',
            'url'               => 'Tautan Luaran',
            'file_karya'        => 'Berkas',
        ];
    }
}
