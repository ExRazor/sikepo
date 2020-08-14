<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AcademicYearRequest extends FormRequest
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
            'tahun_akademik' => [
                                    'required',
                                    'digits:4',
                                    Rule::unique('academic_years')->where(function ($query) {
                                        return $query->where('semester', $this->semester);
                                    })->ignore($id),
                                ],
            'semester'       => [
                                    'required',
                                    Rule::unique('academic_years')->where(function ($query) {
                                        return $query->where('tahun_akademik', $this->tahun_akademik);
                                    })->ignore($id),
                                ],
        ];
    }

    public function attributes()
    {
        return [
            'tahun_akademik' => 'Tahun Akademik',
            'semester'       => 'Semester',
        ];
    }
}
