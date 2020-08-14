<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FundingStudyProgramRequest extends FormRequest
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
        $kd_dana = ($this->_id) ? decrypt($this->_id) : null;

        return [
            'kd_prodi'          => [
                'required',
                Rule::unique('funding_study_programs')->where(function ($query) {
                    return $query->where('id_ta', $this->id_ta);
                })->ignore($kd_dana,'kd_dana'),
            ],
            'id_ta'             => [
                'required',
                Rule::unique('funding_study_programs')->where(function ($query) {
                    return $query->where('kd_prodi', $this->kd_prodi);
                })->ignore($kd_dana,'kd_dana'),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'kd_prodi' => 'Program Studi',
            'id_ta'    => 'Tahun Akademik',
        ];
    }
}
