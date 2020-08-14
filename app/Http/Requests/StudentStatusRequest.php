<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentStatusRequest extends FormRequest
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
        $id = ($this->_id) ? decrypt($this->_id) : null;

        return [
            'id_ta'             => 'required',
            'status'            => ($id) ? 'nullable' : 'required',
            'ipk_terakhir'      => 'nullable',
        ];
    }

    public function attributes()
    {
        return [
            'id_ta'             => 'Tahun Akademik',
            'status'            => 'Status',
            'ipk_terakhir'      => 'IPK Terakhir',
        ];
    }
}
