<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResearchTeacherRequest extends FormRequest
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
        if ($this->asal_peneliti == 'Jurusan') {
            $peneliti_nidn   = 'required';
            $peneliti_nama   = 'nullable';
            $peneliti_asal   = 'nullable';
        } else if ($this->asal_peneliti == 'Luar') {
            $peneliti_nidn   = 'nullable';
            $peneliti_nama   = 'required';
            $peneliti_asal   = 'required';
        } else {
            $peneliti_nidn   = 'nullable';
            $peneliti_nama   = 'nullable';
            $peneliti_asal   = 'nullable';
        }

        return [
            'asal_peneliti'     => 'required',
            'peneliti_nidn'   => $peneliti_nidn . '|numeric',
            'peneliti_nama'   => $peneliti_nama,
            'peneliti_asal'   => $peneliti_asal,
        ];
    }

    public function attributes()
    {

        return [
            'asal_peneliti'  => 'Asal Dosen',
            'peneliti_nidn'   => 'NIDN',
            'peneliti_nama'   => 'Nama Dosen',
            'peneliti_asal'   => 'Asal Lokasi Dosen',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute jangan dikosongkan!',
        ];
    }
}
