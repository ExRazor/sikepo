<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
        $id = ($this->_id) ? decrypt($this->_id) : null;

        if(!$id) {
            $nim = 'required|numeric|min:9|unique:students,nim';
        } else {
            $nim = 'nullable';
        }

        return [
            'nim'               => $nim,
            'kd_prodi'          => 'required',
            'nama'              => 'required',
            'tpt_lhr'           => 'nullable',
            'tgl_lhr'           => 'nullable',
            'jk'                => 'required',
            'agama'             => 'nullable',
            'alamat'            => 'nullable',
            'kewarganegaraan'   => 'required',
            'kelas'             => 'nullable',
            'tipe'              => 'nullable',
            'program'           => 'nullable',
            'seleksi_jenis'     => 'nullable',
            'seleksi_jalur'     => 'nullable',
            'status_masuk'      => 'nullable',
            'tahun_masuk'       => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'nim'               => 'NIM',
            'kd_prodi'          => 'Program Studi',
            'nama'              => 'Nama Mahasiswa',
            'tpt_lhr'           => 'Tempat Lahir',
            'tgl_lhr'           => 'Tanggal Lahir',
            'jk'                => 'Jenis Kelamin',
            'agama'             => 'Agama',
            'alamat'            => 'Alamat',
            'kewarganegaraan'   => 'Kewarganegaraan',
            'kelas'             => 'Kelas',
            'tipe'              => 'Tipe',
            'program'           => 'Program',
            'seleksi_jenis'     => 'Seleksi Masuk',
            'seleksi_jalur'     => 'Jalur Seleksi',
            'status_masuk'      => 'Status Masuk',
            'tahun_masuk'       => 'Angkatan',
        ];
    }
}
