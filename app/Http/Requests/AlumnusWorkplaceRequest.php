<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlumnusWorkplaceRequest extends FormRequest
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
        $kd_prodi = decrypt($this->kd_prodi);
        $id       = ($this->id ? decrypt($this->id) : null);

        if($id) {
            $tahun_lulus = [
                'nullable',
                'numeric',
            ];
        } else {
            $tahun_lulus = [
                'required',
                'numeric',
                Rule::unique('alumnus_workplaces')->where(function ($query) use($kd_prodi) {
                    return $query->where('kd_prodi', $kd_prodi);
                })->ignore($id),
            ];
        }

        return [
            'tahun_lulus'         => $tahun_lulus,
            'jumlah_lulusan'      => 'required|numeric',
            'lulusan_bekerja'     => 'required|numeric',
            'kerja_lokal'         => 'required|numeric',
            'kerja_nasional'      => 'required|numeric',
            'kerja_internasional' => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'tahun_lulus'         => 'Tahun Lulus',
            'jumlah_lulusan'      => 'Jumlah Lulusan',
            'lulusan_bekerja'     => 'Lulusan Bekerja',
            'kerja_lokal'         => 'Tingkat Wilayah',
            'kerja_nasional'      => 'Tingkat Nasional',
            'kerja_internasional' => 'Tingkat Internasional',
        ];
    }
}
