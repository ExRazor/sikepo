<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TeacherAchievementRequest extends FormRequest
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
        $id = ($this->_id) ? $this->_id : null;

        return [
            'nidn'              => ($id) ? 'nullable' : 'required',
            'id_ta'             => 'required',
            'prestasi'          => 'required',
            'tingkat_prestasi'  => 'required',
            'bukti_nama'        => 'required',
            'bukti_file'        => 'required|mimes:jpeg,jpg,png,pdf,zip',
        ];
    }

    public function attributes()
    {
        return [
            'nidn'              => 'Dosen',
            'id_ta'             => 'Tahun Akademik',
            'prestasi'          => 'Nama Prestasi',
            'tingkat_prestasi'  => 'Tingkat Prestasi',
            'bukti_nama'        => 'Bukti Prestasi',
            'bukti_file'        => 'Berkas Bukti',
        ];
    }
}
