<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CurriculumScheduleRequest extends FormRequest
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

        return [
            'id_ta'      => [
                            'required',
                            Rule::unique('curriculum_schedules')->where(function ($query){
                                return $query->where('nidn', $this->nidn)->where('kd_matkul',$this->kd_matkul);
                            })->ignore($id),
                        ],
            'nidn'       => [
                            'required',
                            Rule::unique('curriculum_schedules')->where(function ($query){
                                return $query->where('id_ta', $this->id_ta)->where('kd_matkul',$this->kd_matkul);
                            })->ignore($id),
                        ],
            'kd_matkul'  => [
                            'required',
                            Rule::unique('curriculum_schedules')->where(function ($query){
                                return $query->where('id_ta', $this->id_ta)->where('nidn',$this->nidn);
                            })->ignore($id),
                        ],
            'sesuai_bidang'  => 'nullable',
        ];
    }

    public function attributes()
    {
        return [
            'id_ta'         => 'Tahun Akademik',
            'nidn'          => 'Dosen',
            'kd_matkul'     => 'Mata Kuliah',
            'sesuai_bidang' => 'Sesuai Bidang',
        ];
    }
}
