<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlumnusSatisfactionRequest extends FormRequest
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
        $id       = ($this->id ? decrypt($this->id) : null);

        if(!$id) {
            $kd_prodi = [
                'required',
                Rule::unique('alumnus_satisfactions')->where(function($query){
                    return $query->where('id_ta', $this->id_ta);
                })->ignore($id,'kd_kepuasan'),
            ];

            $id_ta = [
                'required',
                Rule::unique('alumnus_satisfactions')->where(function($query){
                    return $query->where('kd_prodi', $this->kd_prodi);
                })->ignore($id,'kd_kepuasan'),
            ];
        } else {
            $kd_prodi   = ['nullable'];
            $id_ta      = ['nullable'];
        }

        return [
            'kd_prodi' => $kd_prodi,
            'id_ta'    => $id_ta,
        ];
    }

    public function attributes()
    {
        return [
            'kd_prodi'  => 'Program Studi',
            'id_ta'     => 'Tahun Akademik',
        ];
    }
}
