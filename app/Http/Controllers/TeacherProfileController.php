<?php

namespace App\Http\Controllers;

use App\Teacher;
use App\User;
use App\TeacherAchievement;
use App\Research;
use App\ResearchTeacher;
use App\ResearchStudent;
use App\CommunityService;
use App\CommunityServiceTeacher;
use App\CommunityServiceStudent;
use App\TeacherPublication;
use App\TeacherPublicationMember;
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
        $penelitianKetua    = Research::whereHas(
                                            'researchTeacher', function($q) {
                                                $q->where('nidn',Auth::user()->username)->where('status','Ketua');
                                            }
                                        )
                                        ->get();

        $penelitianAnggota   = Research::whereHas(
                                            'researchTeacher', function($q) {
                                                $q->where('nidn',Auth::user()->username)->where('status','Anggota');
                                            }
                                        )
                                        ->get();

        return view('teacher-view.research.index',compact(['penelitianKetua','penelitianAnggota']));
    }

    public function research_create()
    {
        return view('teacher-view.research.form');
    }

    public function research_show($id)
    {
        $id     = decode_id($id);
        $nidn   = Auth::user()->username;
        $data   = Research::where('id',$id)->first();
        $status = ResearchTeacher::where('id_penelitian',$id)->where('nidn',$nidn)->first()->status;

        return view('teacher-view.research.show',compact(['data','status']));
    }

    public function research_edit($id)
    {
        $id     = decode_id($id);
        $nidn   = Auth::user()->username;
        $data   = Research::where('id',$id)->first();
        $status = ResearchTeacher::where('id_penelitian',$id)->where('nidn',$nidn)->first()->status;

        if($status=='Ketua') {
            return view('teacher-view.research.form',compact(['data']));
        } else {
            return abort(404);
        }
    }

    public function commuService()
    {
        $pengabdianKetua     = CommunityService::whereHas(
                                                    'serviceTeacher', function($q) {
                                                        $q->where('nidn',Auth::user()->username)->where('status','Ketua');
                                                    }
                                                )
                                                ->get();

        $pengabdianAnggota   = CommunityService::whereHas(
                                                    'serviceTeacher', function($q) {
                                                        $q->where('nidn',Auth::user()->username)->where('status','Anggota');
                                                    }
                                                )
                                                ->get();

        // return response()->json($pengabdian);die;

        return view('teacher-view.community-service.index',compact(['pengabdianKetua','pengabdianAnggota']));
    }

    public function commuService_show($id)
    {
        $id     = decode_id($id);
        $nidn   = Auth::user()->username;
        $data   = CommunityService::where('id',$id)->first();
        $status = CommunityServiceTeacher::where('id_pengabdian',$id)->where('nidn',$nidn)->first()->status;

        return view('teacher-view.community-service.show',compact(['data','status']));
    }

    public function commuService_create()
    {
        return view('teacher-view.community-service.form');
    }

    public function commuService_edit($id)
    {
        $id     = decode_id($id);
        $nidn   = Auth::user()->username;
        $data   = CommunityService::where('id',$id)->first();
        $status = CommunityServiceTeacher::where('id_pengabdian',$id)->where('nidn',$nidn)->first()->status;

        if($status=='Ketua') {
            return view('teacher-view.community-service.form',compact(['data']));
        } else {
            return abort(404);
        }
    }

    public function publication()
    {
        $publikasiKetua    = TeacherPublication::where('nidn',Auth::user()->username)->get();

        $publikasiAnggota  = TeacherPublication::whereHas(
                                                    'publicationMembers', function($query) {
                                                        $query->where('nidn',Auth::user()->username);
                                                    }
                                                )
                                                ->get();

        return view('teacher-view.publication.index',compact(['publikasiKetua','publikasiAnggota']));
    }

    public function publication_create()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $jenis        = PublicationCategory::all();

        return view('teacher-view.publication.form',compact(['studyProgram','jenis']));
    }

    public function publication_show($id)
    {
        $id   = decode_id($id);
        $data = TeacherPublication::find($id);

        return view('teacher-view.publication.show',compact(['data']));
    }

    public function publication_edit($id)
    {
        $id   = decode_id($id);

        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $jenis        = PublicationCategory::all();
        $data         = TeacherPublication::with('teacher','publicationStudents')->where('id',$id)->first();
        $teacher      = Teacher::where('kd_prodi',$data->teacher->kd_prodi)->get();

        return view('publication.teacher.form',compact(['jenis','data','studyProgram','teacher']));
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

        return redirect()->route('teacher-view.biodata')->with('flash.message', 'Biodata berhasil diperbarui!')->with('flash.class', 'success');
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

    public function research_store(Request $request)
    {
        $request->validate([
            'id_ta'             => 'required',
            'ketua_nidn'        => 'required',
            'judul_penelitian'  => 'required',
            'tema_penelitian'   => 'required',
            'sks_penelitian'    => 'required|numeric',
            'sesuai_prodi'      => 'nullable',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
            'jumlah_biaya'      => 'required',
        ]);

        //Simpan Data Penelitian
        $research                    = new Research;
        $research->id_ta             = $request->id_ta;
        $research->judul_penelitian  = $request->judul_penelitian;
        $research->tema_penelitian   = $request->tema_penelitian;
        $research->sks_penelitian    = $request->sks_penelitian;
        $research->sesuai_prodi      = $request->sesuai_prodi;
        $research->sumber_biaya      = $request->sumber_biaya;
        $research->sumber_biaya_nama = $request->sumber_biaya_nama;
        $research->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $research->save();

        //Jumlah SKS
        $sks_ketua      = floatval($request->sks_penelitian)*setting('research_ratio_chief')/100;
        $sks_anggota    = floatval($request->sks_penelitian)*setting('research_ratio_members')/100;

        //Tambah Ketua
        $ketua                  = new ResearchTeacher;
        $ketua->id_penelitian   = $research->id;
        $ketua->nidn            = $request->ketua_nidn;
        $ketua->status          = 'Ketua';
        $ketua->sks             = $sks_ketua;
        $ketua->save();

        //Tambah Anggota Dosen
        if($request->anggota_nidn) {
            $hitungDsn = count($request->anggota_nidn);
            for($i=0;$i<$hitungDsn;$i++) {
                ResearchTeacher::updateOrCreate(
                    [
                        'id_penelitian' => $research->id,
                        'nidn'          => $request->anggota_nidn[$i],
                    ],
                    [
                        'status'     => 'Anggota',
                        'sks'        => $sks_anggota,
                    ]
                );
            }
        }


        //Tambah Mahasiswa
        if($request->mahasiswa_nim) {
            $hitungMhs = count($request->mahasiswa_nim);
            for($i=0;$i<$hitungMhs;$i++) {
                ResearchStudent::updateOrCreate(
                    [
                        'id_penelitian' => $research->id,
                        'nim'           => $request->mahasiswa_nim[$i],
                    ]
                );
            }
        }

        return redirect()->route('profile.research')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function research_update(Request $request)
    {
        $id = decrypt($request->id);

        // dd($request->all());

        $request->validate([
            'id_ta'             => 'required',
            // 'ketua_nidn'        => 'required|unique:research_teachers,nidn,'.$id.',id_penelitian',
            'judul_penelitian'  => 'required',
            'tema_penelitian'   => 'required',
            'sks_penelitian'    => 'required|numeric',
            'sesuai_prodi'      => 'nullable',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
            'jumlah_biaya'      => 'required',
        ]);

        //Simpan Data Penelitian
        $research                    = Research::find($id);
        $research->id_ta             = $request->id_ta;
        $research->judul_penelitian  = $request->judul_penelitian;
        $research->tema_penelitian   = $request->tema_penelitian;
        $research->sks_penelitian    = $request->sks_penelitian;
        $research->sesuai_prodi      = $request->sesuai_prodi;
        $research->sumber_biaya      = $request->sumber_biaya;
        $research->sumber_biaya_nama = $request->sumber_biaya_nama;
        $research->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $research->save();

        //Jumlah SKS
        $sks_ketua      = floatval($request->sks_penelitian)*setting('research_ratio_chief')/100;
        $sks_anggota    = floatval($request->sks_penelitian)*setting('research_ratio_members')/100;

        //Update Ketua
        $ketua = ResearchTeacher::where('id_penelitian',$id)->where('status','Ketua');
        if($ketua != $request->ketua_nidn) {
            $ketua->delete();

            $new_ketua                  = new ResearchTeacher;
            $new_ketua->id_penelitian   = $id;
            $new_ketua->nidn            = $request->ketua_nidn;
            $new_ketua->status          = 'Ketua';
            $new_ketua->sks             = $sks_ketua;
            $new_ketua->save();
        } else {

            $ketua->id_penelitian = $id;
            $ketua->nidn          = $request->ketua_nidn;
            $ketua->sks           = $sks_ketua;
            $ketua->save();
        }

        //Update Anggota
        if($request->anggota_nidn) {
            $hitungDsn = count($request->anggota_nidn);
            for($i=0;$i<$hitungDsn;$i++) {

                ResearchTeacher::updateOrCreate(
                    [
                        'id_penelitian' => $id,
                        'nidn'          => $request->anggota_nidn[$i],
                    ],
                    [
                        'status'     => 'Anggota',
                        'sks'        => $sks_anggota,
                    ]
                );
            }
        }

        //Update Mahasiswa
        if($request->mahasiswa_nim) {
            $hitungMhs = count($request->mahasiswa_nim);
            for($i=0;$i<$hitungMhs;$i++) {

                ResearchStudent::updateOrCreate(
                    [
                        'id_penelitian' => $id,
                        'nim'           => $request->mahasiswa_nim[$i],
                    ]
                );
            }
        }

        return redirect()->route('profile.research.show',encode_id($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function research_destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decode_id($request->id);
            $q  = Research::find($id)->delete();
            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil dihapus',
                    'type'    => 'success'
                ]);
            }
        }
    }

    public function research_destroy_teacher(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = ResearchTeacher::find($id)->delete();
            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil dihapus',
                    'type'    => 'success'
                ]);
            }
        }
    }

    public function research_destroy_students(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = ResearchStudent::find($id)->delete();
            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil dihapus',
                    'type'    => 'success'
                ]);
            }
        }
    }

    public function commuService_store(Request $request)
    {
        $request->validate([
            'id_ta'             => 'required',
            'ketua_nidn'        => 'required',
            'judul_pengabdian'  => 'required',
            'tema_pengabdian'   => 'required',
            'sks_pengabdian'    => 'required|numeric',
            'sesuai_prodi'      => 'nullable',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
            'jumlah_biaya'      => 'required',
        ]);

        //Simpan Data Pengabdian
        $community                    = new CommunityService;
        $community->id_ta             = $request->id_ta;
        $community->judul_pengabdian  = $request->judul_pengabdian;
        $community->tema_pengabdian   = $request->tema_pengabdian;
        $community->sks_pengabdian    = $request->sks_pengabdian;
        $community->sesuai_prodi      = $request->sesuai_prodi;
        $community->sumber_biaya      = $request->sumber_biaya;
        $community->sumber_biaya_nama = $request->sumber_biaya_nama;
        $community->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $community->save();

        //Jumlah SKS
        $sks_ketua      = floatval($request->sks_pengabdian)*setting('service_ratio_chief')/100;
        $sks_anggota    = floatval($request->sks_pengabdian)*setting('service_ratio_members')/100;

        //Tambah Ketua
        $ketua                  = new CommunityServiceTeacher;
        $ketua->id_pengabdian   = $community->id;
        $ketua->nidn            = $request->ketua_nidn;
        $ketua->status          = 'Ketua';
        $ketua->sks             = $sks_ketua;
        $ketua->save();

        //Tambah Anggota Dosen
        if($request->anggota_nidn) {
            $hitungDsn = count($request->anggota_nidn);
            for($i=0;$i<$hitungDsn;$i++) {
                CommunityServiceTeacher::updateOrCreate(
                    [
                        'id_penelitian' => $community->id,
                        'nidn'          => $request->anggota_nidn[$i],
                    ],
                    [
                        'status'     => 'Anggota',
                        'sks'        => $sks_anggota,
                    ]
                );
            }
        }

        //Tambah Mahasiswa
        if($request->mahasiswa_nim) {
            $hitungMhs = count($request->mahasiswa_nim);
            for($i=0;$i<$hitungMhs;$i++) {
                CommunityServiceStudent::updateOrCreate(
                    [
                        'id_pengabdian' => $community->id,
                        'nim'           => $request->mahasiswa_nim[$i],
                    ]
                );
            }
        }

        return redirect()->route('profile.community-service')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function commuService_update(Request $request)
    {
        $id = decrypt($request->id);

        $request->validate([
            'id_ta'             => 'required',
            // 'ketua_nidn'        => 'required',
            'judul_pengabdian'  => 'required',
            'tema_pengabdian'   => 'required',
            'sks_pengabdian'    => 'required|numeric',
            'sesuai_prodi'      => 'nullable',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
            'jumlah_biaya'      => 'required',
        ]);

        //Update Data Pengabdian
        $community                    = CommunityService::find($id);
        $community->id_ta             = $request->id_ta;
        $community->judul_pengabdian  = $request->judul_pengabdian;
        $community->tema_pengabdian   = $request->tema_pengabdian;
        $community->sks_pengabdian    = $request->sks_pengabdian;
        $community->sesuai_prodi      = $request->sesuai_prodi;
        $community->sumber_biaya      = $request->sumber_biaya;
        $community->sumber_biaya_nama = $request->sumber_biaya_nama;
        $community->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $community->save();

        //Jumlah SKS
        $sks_ketua      = floatval($request->sks_pengabdian)*setting('service_ratio_chief')/100;
        $sks_anggota    = floatval($request->sks_pengabdian)*setting('service_ratio_members')/100;

        //Update Ketua
        $ketua = CommunityServiceTeacher::where('id_pengabdian',$id)->where('status','Ketua');
        if($ketua != $request->ketua_nidn) {
            $ketua->delete();

            $new_ketua                  = new CommunityServiceTeacher;
            $new_ketua->id_pengabdian   = $id;
            $new_ketua->nidn            = $request->ketua_nidn;
            $new_ketua->status          = 'Ketua';
            $new_ketua->sks             = $sks_ketua;
            $new_ketua->save();
        } else {
            $ketua->id_pengabdian = $id;
            $ketua->nidn          = $request->ketua_nidn;
            $ketua->sks           = $sks_ketua;
            $ketua->save();
        }

        //Update Anggota
        if($request->anggota_nidn) {
            $hitungDsn = count($request->anggota_nidn);
            for($i=0;$i<$hitungDsn;$i++) {

                CommunityServiceTeacher::updateOrCreate(
                    [
                        'id_pengabdian' => $id,
                        'nidn'          => $request->anggota_nidn[$i],
                    ],
                    [
                        'status'     => 'Anggota',
                        'sks'        => $sks_anggota,
                    ]
                );
            }
        }


        //Update Anggota Mahasiswa
        if($request->mahasiswa_nim) {
            $hitungMhs = count($request->mahasiswa_nim);
            for($i=0;$i<$hitungMhs;$i++) {

                CommunityServiceStudent::updateOrCreate(
                    [
                        'id_pengabdian' => $id,
                        'nim'           => $request->mahasiswa_nim[$i],
                    ]
                );
            }
        }

        return redirect()->route('profile.community-service.show',encode_id($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function commuService_destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decode_id($request->id);
            $q  = CommunityService::find($id)->delete();
            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil dihapus',
                    'type'    => 'success'
                ]);
            }
        }
    }

    public function commuService_destroy_teacher(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = CommunityServiceTeacher::find($id)->delete();
            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil dihapus',
                    'type'    => 'success'
                ]);
            }
        }
    }

    public function commuService_destroy_students(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = CommunityServiceStudent::find($id)->delete();
            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil dihapus',
                    'type'    => 'success'
                ]);
            }
        }
    }
}
