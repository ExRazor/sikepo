<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommunityServiceRequest extends FormRequest
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
        $id = null;
        if ($this->id) {
            $id = decrypt($this->id);
        }

        return [
            'id_ta'             => 'required',
            'judul_pengabdian'  => 'required|unique:community_services,judul_pengabdian,' . $id,
            'tema_pengabdian'   => 'required',
            'tingkat_pengabdian' => 'required',
            'sks_pengabdian'    => 'required|numeric',
            'sesuai_prodi'      => 'nullable',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
            'jumlah_biaya'      => 'required',
            'bukti_fisik'       => 'mimes:pdf,zip,rar|size:4096',
        ];
    }

    public function attributes()
    {
        return [
            'id_ta'             => 'Tahun Akademik',
            'judul_pengabdian'  => 'Judul Pengabdian',
            'tema_pengabdian'   => 'Tema Pengabdian',
            'tingkat_pengabdian'   => 'Tingkat Pengabdian',
            'sks_pengabdian'    => 'SKS Pengabdian',
            'sesuai_prodi'      => 'Kesesuaian Prodi',
            'sumber_biaya'      => 'Sumber Biaya',
            'sumber_biaya_nama' => 'Lembaga Sumber Biaya',
            'jumlah_biaya'      => 'Jumlah Biaya',
            'bukti_fisik'       => 'Bukti Fisik',
        ];
    }
}
