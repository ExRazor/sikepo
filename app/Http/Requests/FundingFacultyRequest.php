<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FundingFacultyRequest extends FormRequest
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
            'id_ta'             => [
                'required',
                Rule::unique('funding_faculties')->where(function ($query) {
                    return $query->where('id_fakultas', decrypt($this->id_fakultas));
                })->ignore($kd_dana,'kd_dana'),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'id_ta' => 'Tahun Akademik',
        ];
    }
}
