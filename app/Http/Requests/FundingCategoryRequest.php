<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FundingCategoryRequest extends FormRequest
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
        if(!$this->id_parent) {
            $jenis = 'required';
        } else {
            $jenis = 'nullable';
        }

        return [
            'id_parent'  => 'nullable',
            'jenis'      => $jenis,
            'nama'       => 'required',
            'deskripsi'  => 'nullable',
        ];
    }

    public function attributes()
    {
        return [
            'id_parent'  => 'Induk Kategori',
            'jenis'      => 'Jenis Dana',
            'nama'       => 'Nama Kategori',
            'deskripsi'  => 'Deskripsi',
        ];
    }
}
