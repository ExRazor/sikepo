<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EwmpRequest extends FormRequest
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
        $id = ($this->_id ? decrypt($this->_id) : null);

        return [
            'id_ta'             => [
                'required',
                Rule::unique('ewmps')->where(function ($query) {
                    return $query->where('nidn', decrypt($this->nidn));
                })->ignore($id),
            ],
            'ps_intra'              => 'nullable|numeric',
            'ps_lain'               => 'nullable|numeric',
            'ps_luar'               => 'required|numeric',
            'penelitian'            => 'nullable|numeric',
            'pkm'                   => 'nullable|numeric',
            'tugas_tambahan'        => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'id_ta'          => 'Tahun Akademik',
            'ps_intra'       => 'SKS Dalam Prodi',
            'ps_lain'        => 'SKS Prodi Lain dalam PT',
            'ps_luar'        => 'SKS Prodi Lain Luar PT',
            'penelitian'     => 'SKS Penelitian',
            'pkm'            => 'SKS PkM',
            'tugas_tambahan' => 'SKS Tugas Tambahan',
        ];
    }
}
