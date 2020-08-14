<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MinithesisRequest extends FormRequest
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
        $id = ($this->id) ? decrypt($this->id) : null;

        return [
            'nim'                   => 'required|unique:minitheses,nim,'.$id,
            'judul'                 => 'required',
            'pembimbing_utama'      => 'required',
            'pembimbing_pendamping' => 'required',
            'id_ta'                 => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'nim'                   => 'NIM',
            'judul'                 => 'Judul Tugas Akhir',
            'pembimbing_utama'      => 'Pembimbing Utama',
            'pembimbing_pendamping' => 'Pembimbing Pendamping',
            'id_ta'                 => 'Tahun Akademik',
        ];
    }
}
