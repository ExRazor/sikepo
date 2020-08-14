<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentQuotaRequest extends FormRequest
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
            'kd_prodi'          => [
                'required',
                Rule::unique('student_quotas')->where(function ($query){
                    return $query->where('id_ta', $this->id_ta);
                })->ignore($id),
            ],
            'id_ta'             => [
                'required',
                Rule::unique('student_quotas')->where(function ($query){
                    return $query->where('kd_prodi', $this->kd_prodi);
                })->ignore($id),
            ],
            'daya_tampung'      => 'required|numeric',
            'calon_pendaftar'   => 'numeric|nullable',
            'calon_lulus'       => 'numeric|nullable',
        ];
    }

    public function attributes()
    {
        return [
            'kd_prodi'          => 'Program Studi',
            'id_ta'             => 'Tahun Akademik',
            'daya_tampung'      => 'Daya Tampung',
            'calon_pendaftar'   => 'Jumlah Calon Pendaftar',
            'calon_lulus'       => 'Jumlah Calon Pendaftar yang Lulus',
        ];
    }
}
