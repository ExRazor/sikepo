<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
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
        $id = ($this->_id ? $this->_id : null);

        if(!$id) {
            $kd_jurusan = [
                'required',
                'numeric',
                'digits:5',
                Rule::unique('departments')->where(function($query){
                    return $query->where('kd_jurusan', $this->kd_jurusan);
                })->ignore($id,'kd_jurusan'),
            ];
        } else {
            $kd_jurusan   = ['nullable','numeric','digits:5'];
        }

        return [
            'id_fakultas' => 'required',
            'kd_jurusan'  => $kd_jurusan,
            'nama'        => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'id_fakultas' => 'ID Fakultas',
            'kd_jurusan'  => 'Kode Jurusan',
            'nama'        => 'Nama Jurusan',
        ];
    }
}
