<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollaborationRequest extends FormRequest
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
        $bukti_file = ($this->id ? 'mimes:pdf' : 'required|mimes:pdf');

        return [
            'kd_prodi'          => 'required',
            'id_ta'             => 'required',
            'jenis'             => 'required',
            'nama_lembaga'      => 'required',
            'tingkat'           => 'required',
            'judul_kegiatan'    => 'required',
            'manfaat_kegiatan'  => 'required',
            'waktu'             => 'required',
            'durasi'            => 'required',
            'bukti_nama'        => 'required',
            'bukti_file'        => $bukti_file,
        ];
    }

    public function attributes()
    {
        return [
            'kd_prodi'          => 'Program Studi',
            'id_ta'             => 'Tahun Akademik',
            'jenis'             => 'Jenis Kerja Sama',
            'nama_lembaga'      => 'Nama Lembaga',
            'tingkat'           => 'Tingkat Kerja Sama',
            'judul_kegiatan'    => 'Kegiatan Kerja sama',
            'manfaat_kegiatan'  => 'Manfaat Kegiatan',
            'waktu'             => 'Waktu Dilaksanakan',
            'durasi'            => 'Durasi Pelaksanaan',
            'bukti_nama'        => 'Nama Bukti Kerja Sama',
            'bukti_file'        => 'Berkas Bukti',
        ];
    }
}
