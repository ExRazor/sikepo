<?php

namespace App\Http\Controllers\Teacher;

use App\Teacher;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class BiodataController extends Controller
{
    public function index()
    {
        $nidn         = Auth::user()->username;
        $data         = Teacher::where('nidn',$nidn)->first();

        $bidang       = json_decode($data->bidang_ahli);
        $data->bidang_ahli   = implode(', ',$bidang);

        return view('teacher-view.profile',compact(['data']));
    }

    public function update(Request $request)
    {
        $id = Auth::user()->username;
        $request->validate([
            'nip'                   => 'nullable|numeric|digits:18',
            'nama'                  => 'required',
            'jk'                    => 'required',
            'agama'                 => 'nullable',
            'tpt_lhr'               => 'nullable',
            'tgl_lhr'               => 'nullable',
            'email'                 => 'email|nullable',
            'pend_terakhir_jenjang' => 'nullable',
            'pend_terakhir_jurusan' => 'nullable',
            'bidang_ahli'           => 'nullable',
            'sesuai_bidang_ps'      => 'nullable',
            'ikatan_kerja'          => 'required',
            'jabatan_akademik'      => 'required',
            'foto'                  => 'mimes:jpeg,jpg,png',
        ]);

        $bidang_ahli = explode(", ",$request->bidang_ahli);

        $Teacher                            = Teacher::find($id);
        $Teacher->nip                       = $request->nip;
        $Teacher->nama                      = $request->nama;
        $Teacher->jk                        = $request->jk;
        $Teacher->agama                     = $request->agama;
        $Teacher->tpt_lhr                   = $request->tpt_lhr;
        $Teacher->tgl_lhr                   = $request->tgl_lhr;
        $Teacher->alamat                    = $request->alamat;
        $Teacher->no_telp                   = $request->no_telp;
        $Teacher->email                     = $request->email;
        $Teacher->pend_terakhir_jenjang     = $request->pend_terakhir_jenjang;
        $Teacher->pend_terakhir_jurusan     = $request->pend_terakhir_jurusan;
        $Teacher->bidang_ahli               = json_encode($bidang_ahli);
        $Teacher->ikatan_kerja              = $request->ikatan_kerja;
        $Teacher->jabatan_akademik          = $request->jabatan_akademik;
        $Teacher->sertifikat_pendidik       = $request->sertifikat_pendidik;
        $Teacher->sesuai_bidang_ps          = $request->sesuai_bidang_ps;

        $storagePath = public_path('upload/teacher/'.$Teacher->foto);
        if($request->file('foto')) {
            if(File::exists($storagePath)) {
                File::delete($storagePath);
            }

            $file = $request->file('foto');
            $tujuan_upload = public_path('upload/teacher');
            $filename = $request->nidn.'_'.str_replace(' ', '', $request->nama).'.'.$file->getClientOriginalExtension();
            $file->move($tujuan_upload,$filename);
            $Teacher->foto = $filename;
        }

        if(isset($Teacher->foto) && File::exists($storagePath))
        {
            $ekstensi = File::extension($storagePath);
            $filename = $request->nidn.'_'.str_replace(' ', '', $request->nama).'.'.$ekstensi;
            File::move($storagePath,public_path('upload/teacher/'.$filename));
            $Teacher->foto = $filename;
        }

        $Teacher->save();

        //Update User Dosen
        $user          = User::where('username',$id)->first();
        $user->name    = $request->nama;
        $user->save();

        return redirect()->route('teacher-view.biodata')->with('flash.message', 'Biodata berhasil diperbarui!')->with('flash.class', 'success');
    }

}
