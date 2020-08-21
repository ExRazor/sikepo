<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TeacherRequest extends FormRequest
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
            $nidn = 'required|numeric|min:8|unique:teachers,nidn';
        } else {
            $nidn = 'nullable';
        }

        return [
            'nidn'                  => $nidn,
            'nip'                   => 'nullable|numeric|digits:18',
            'nama'                  => 'required',
            'jk'                    => 'required',
            'agama'                 => 'nullable',
            'tpt_lhr'               => 'nullable',
            'tgl_lhr'               => 'nullable',
            'email'                 => 'email|nullable',
            'pend_terakhir_jenjang' => 'nullable',
            'pend_terakhir_jurusan' => 'nullable',
            'bidang_ahli'           => 'nullable',
            'sesuai_bidang_ps'      => 'nullable',
            'ikatan_kerja'          => 'required',
            'jabatan_akademik'      => 'required',
            'foto'                  => 'mimes:jpeg,jpg,png',
        ];
    }

    public function attributes()
    {
        return [
            'nidn'                  => 'NIDN',
            'nip'                   => 'NIP',
            'nama'                  => 'Nama Dosen',
            'jk'                    => 'Jenis Kelamin',
            'agama'                 => 'Agama',
            'tpt_lhr'               => 'Tempat Lahir',
            'tgl_lhr'               => 'Tanggal Lahir',
            'email'                 => 'Email',
            'pend_terakhir_jenjang' => 'Jenjang Pendidikan Terakhir',
            'pend_terakhir_jurusan' => 'Jurusan Pendidikan Terakhir',
            'bidang_ahli'           => 'Bidang Keahlian',
            'sesuai_bidang_ps'      => 'Kesesuaian Bidang',
            'ikatan_kerja'          => 'Ikatan Kerja',
            'jabatan_akademik'      => 'Jabatan Akademik',
            'foto'                  => 'Foto',
        ];
    }
}
