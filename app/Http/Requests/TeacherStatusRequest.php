<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     // return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = 0;
        if($this->_id) {
            $id = decrypt($this->_id);
        }
        return [
            'jabatan'       => 'required',
            // 'id_ta'         => [
            //                     'required',
            //                     Rule::unique('teacher_statuses')->where(function($query) {
            //                         return $query->where('nidn',$this->_nidn);
            //                     })->ignore($id)
            //                 ],
            'periode'       => 'required',
            'kd_prodi'      => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'jabatan'  => 'Jabatan',
            'periode'  => 'Tahun Periode',
            'kd_prodi' => "Klaster",
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
