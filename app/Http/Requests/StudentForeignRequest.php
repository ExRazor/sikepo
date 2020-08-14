<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentForeignRequest extends FormRequest
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
            'nim'           => [
                'required',
                Rule::unique('student_foreigns')->where(function ($query) {
                    return $query->where('nim', $this->nim);
                })->ignore($id),
            ],
            'asal_negara'   => 'required',
            'durasi'        => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'nim'           => 'NIM',
            'asal_negara'   => 'Asal Negara',
            'durasi'        => 'Durasi Mahasiswa Asing',
        ];
    }
}
