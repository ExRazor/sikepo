<?php

namespace App\Http\Controllers;

use App\Teacher;
use App\User;
use App\TeacherAchievement;
use App\Research;
use App\ResearchTeacher;
use App\ResearchStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class TeacherProfileController extends Controller
{
    public function biodata()
    {
        $nidn         = Auth::user()->username;
        $data         = Teacher::where('nidn',$nidn)->first();

        $bidang       = json_decode($data->bidang_ahli);
        $data->bidang_ahli   = implode(', ',$bidang);

        return view('teacher-view.profile',compact(['data']));
    }

    public function achievement()
    {
        $nidn        = Auth::user()->username;
        $achievement = TeacherAchievement::where('nidn',$nidn)->orderBy('id_ta','desc')->get();

        return view('teacher-view.achievement.index',compact(['achievement']));
    }

    public function achievement_edit($id)
    {
        if(request()->ajax()) {
            $id = decode_id($id);
            $data = TeacherAchievement::where('id',$id)->with('teacher.studyProgram','academicYear')->first();

            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function research()
    {
        $penelitian   = Research::whereHas(
                                        'researchTeacher', function($q) {
                                            $q->where('nidn',Auth::user()->username);
                                        }
                                    )
                                    ->get();

        

        return view('teacher-view.research.index',compact(['penelitian']));
    }

    public function show($id)
    {
        $id   = decode_id($id);
        $data         = Research::where('id',$id)->first();

        return view('teacher-view.research.show',compact(['data']));
    }

    public function update_biodata(Request $request)
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

        return redirect()->route('profile.biodata')->with('flash.message', 'Biodata berhasil diperbarui!')->with('flash.class', 'success');
    }

    public function store_achievement(Request $request)
    {
        $nidn = Auth::user()->username;
        if(request()->ajax()) {
            $request->validate([
                'id_ta'             => 'required',
                'prestasi'          => 'required',
                'tingkat_prestasi'  => 'required',
                'bukti_nama'        => 'required',
                'bukti_file'        => 'required|mimes:jpeg,jpg,png,pdf,zip',

            ]);

            $acv                    = new TeacherAchievement;
            $acv->nidn              = $nidn;
            $acv->id_ta             = $request->id_ta;
            $acv->prestasi          = $request->prestasi;
            $acv->tingkat_prestasi  = $request->tingkat_prestasi;
            $acv->bukti_nama        = $request->bukti_nama;

            $storagePath = public_path('upload/teacher/achievement'.$acv->bukti_file);
            if($file = $request->file('bukti_file')) {
                $tgl_skrg = date('Y_m_d_H_i_s');
                $tujuan_upload = public_path('upload/teacher/achievement');
                $filename = $acv->nidn.'_'.str_replace(' ', '', $request->bukti_nama).'_'.$tgl_skrg.'.'.$file->getClientOriginalExtension();
                $file->move($tujuan_upload,$filename);
                $acv->bukti_file = $filename;
            }

            $q = $acv->save();

            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil disimpan',
                    'type'    => 'success'
                ]);
            }
        }
    }

    public function update_achievement(Request $request)
    {
        if(request()->ajax()) {
            $id  = decode_id($request->_id);

            $request->validate([
                'id_ta'             => 'required',
                'prestasi'          => 'required',
                'tingkat_prestasi'  => 'required',
                'bukti_nama'        => 'required',
                'bukti_file'        => 'mimes:jpeg,jpg,png,pdf,zip',
            ]);

            $acv                    = TeacherAchievement::find($id);
            $acv->id_ta             = $request->id_ta;
            $acv->prestasi          = $request->prestasi;
            $acv->tingkat_prestasi  = $request->tingkat_prestasi;
            $acv->bukti_nama        = $request->bukti_nama;

            //Bukti File
            $storagePath = public_path('upload/teacher/achievement/'.$acv->bukti_file);
            $tgl_skrg = date('Y_m_d_H_i_s');
            if($file = $request->file('bukti_file')) {
                if(File::exists($storagePath)) {
                    File::delete($storagePath);
                }

                $tujuan_upload = public_path('upload/teacher/achievement');
                $filename = $acv->nidn.'_'.str_replace(' ', '', $acv->bukti_nama).'_'.$tgl_skrg.'.'.$file->getClientOriginalExtension();
                $file->move($tujuan_upload,$filename);
                $acv->bukti_file = $filename;
            }

            if(isset($acv->bukti_file) && File::exists($storagePath))
            {
                $ekstensi = File::extension($storagePath);
                $filename = $acv->nidn.'_'.str_replace(' ', '', $acv->bukti_nama).'_'.$tgl_skrg.'.'.$ekstensi;
                File::move($storagePath,public_path('upload/teacher/achievement/'.$filename));
                $acv->bukti_file = $filename;
            }

            //Simpan
            $q = $acv->save();

            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil disimpan',
                    'type'    => 'success'
                ]);
            }
        }
    }

    public function destroy_achievement(Request $request)
    {
        if(request()->ajax()) {
            $id     = decode_id($request->_id);
            $data   = TeacherAchievement::find($id);
            $q      = $data->delete();

            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                $this->delete_file_achievement($data->bukti_file);
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil dihapus',
                    'type'    => 'success'
                ]);
            }
        } else {
            return redirect()->route('teacher.achievement');
        }
    }

    public function delete_file_achievement($file)
    {
        $storagePath = public_path('upload/teacher/achievement/'.$file);

        if(File::exists($storagePath)) {
            File::delete($storagePath);
        }
    }
}
