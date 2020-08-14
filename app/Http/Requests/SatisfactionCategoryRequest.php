<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SatisfactionCategoryRequest extends FormRequest
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
        return [
            'jenis'      => 'required',
            'nama'       => 'required',
            'alias'      => 'nullable',
            'deskripsi'  => 'nullable',
        ];
    }

    public function attributes()
    {
        return [
            'jenis'      => 'Jenis Kategori',
            'nama'       => 'Nama Kategori',
            'alias'      => 'Alias',
            'deskripsi'  => 'Deskripsi',
        ];
    }
}
