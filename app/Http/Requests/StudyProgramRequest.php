<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudyProgramRequest extends FormRequest
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
        $id = ($this->kd_prodi) ? $this->kd_prodi : null;

        if(!$id) {
            $kd_prodi = 'required|numeric|digits:5|unique:study_programs,kd_prodi';
        } else {
            $kd_prodi = 'nullable';
        }

        return [
            'kd_prodi'      => $kd_prodi,
            'kd_unik'       => 'required|numeric|digits:4',
            'kd_jurusan'    => 'required',
            'nama'          => 'required',
            'jenjang'       => 'required',
            'thn_menerima'  => 'numeric|digits:4|nullable',
        ];
    }

    public function attributes()
    {
        return [
            'kd_prodi'      => 'Kode Program Studi',
            'kd_unik'       => 'Kode Unik',
            'kd_jurusan'    => 'Jurusan',
            'nama'          => 'Nama Program Studi',
            'jenjang'       => 'Jenjang Program Studi',
            'thn_menerima'  => 'Tahun Pertama Menerima Mahasiswa',
        ];
    }
}
