<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportTridharmaRequest extends FormRequest
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
            'jenis'                 => 'required',
            'periode_awal'          => 'required',
            'periode_akhir'         => 'nullable',
            'kd_prodi'              => 'nullable',
            'disahkan'              => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'jenis'         => 'Jenis Tridharma',
            'periode_awal'  => 'Periode Awal',
            'periode_akhir' => 'Periode Akhir',
            'kd_prodi'      => "Program Studi",
            'disahkan'      => 'Tanggal disahkan',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute wajib diisi!',
            'unique'   => ':attribute sudah pernah ada!',
        ];
    }
}
