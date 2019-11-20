<?php

namespace App\Http\Controllers;

use App\CommunityService;
use App\CommunityServiceTeacher;
use App\CommunityServiceStudent;
use App\StudyProgram;
use App\Faculty;
use App\Teacher;
use Illuminate\Http\Request;

class CommunityServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faculty = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $pengabdian   = CommunityService::with([
                                        'serviceKetua',
                                        'serviceAnggota',
                                        'serviceStudent'
                                    ])
                                    ->whereHas(
                                        'serviceTeacher', function($q) {
                                            $q->jurusanKetua(setting('app_department_id'));
                                        }
                                    )
                                    ->get();

        // return response()->json($pengabdian);die;

        return view('community-service.index',compact(['pengabdian','studyProgram','faculty']));
    }

    public function create()
    {
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        return view('community-service.form',compact(['studyProgram','faculty']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_ta'             => 'required',
            'ketua_nidn'        => 'required',
            'judul_pengabdian'  => 'required',
            'tema_pengabdian'   => 'required',
            'sks_pengabdian'    => 'required|numeric',
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
        $community->sumber_biaya      = $request->sumber_biaya;
        $community->sumber_biaya_nama = $request->sumber_biaya_nama;
        $community->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $community->save();

        //Jumlah SKS
        $sks_ketua = floatval($request->sks_pengabdian)*60/100;
        $sks_anggota = floatval($request->sks_pengabdian)*40/100;

        //Tambah Ketua
        $ketua                  = new CommunityServiceTeacher;
        $ketua->id_pengabdian   = $community->id;
        $ketua->nidn            = $request->ketua_nidn;
        $ketua->status          = 'Ketua';
        $ketua->sks             = $sks_ketua;
        $ketua->save();

        //Tambah Anggota Dosen
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

        //Tambah Mahasiswa
        $hitungMhs = count($request->mahasiswa_nim);
        for($i=0;$i<$hitungMhs;$i++) {
            CommunityServiceStudent::updateOrCreate(
                [
                    'id_pengabdian' => $community->id,
                    'nim'           => $request->mahasiswa_nim[$i],
                ]
            );
        }

        return redirect()->route('community-service')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CommunityService  $communityService
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id   = decode_id($id);

        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $data         = CommunityService::where('id',$id)->first();

        return view('community-service.form',compact(['data','studyProgram']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CommunityService  $communityService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $request->validate([
            'id_ta'             => 'required',
            // 'ketua_nidn'        => 'required',
            'judul_pengabdian'  => 'required',
            'tema_pengabdian'   => 'required',
            'sks_pengabdian'    => 'required|numeric',
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
        $community->sumber_biaya      = $request->sumber_biaya;
        $community->sumber_biaya_nama = $request->sumber_biaya_nama;
        $community->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $community->save();

        //Jumlah SKS
        $sks_ketua   = floatval($request->sks_pengabdian)*60/100;
        $sks_anggota = floatval($request->sks_pengabdian)*40/100;

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


        $hitungMhs = count($request->mahasiswa_nim);
        for($i=0;$i<$hitungMhs;$i++) {

            CommunityServiceStudent::updateOrCreate(
                [
                    'id_pengabdian' => $id,
                    'nim'           => $request->mahasiswa_nim[$i],
                ]
            );
        }

        return redirect()->route('community-service')->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CommunityService  $communityService
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
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

    public function destroy_teacher(Request $request)
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

    public function destroy_students(Request $request)
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

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {

            $q   = CommunityService::with([
                                    'academicYear',
                                    'serviceKetua.teacher.studyProgram',
                                    'serviceAnggota.teacher.studyProgram.department',
                                    'serviceStudent.student.studyProgram.department'
                                ]);

            if($request->kd_jurusan){
                $q->whereHas(
                    'serviceTeacher', function($q) use($request) {
                        $q->jurusanKetua($request->kd_jurusan);
                    }
                );
            }

            if($request->kd_prodi){
                $q->whereHas(
                    'serviceTeacher', function($q) use ($request) {
                        $q->prodiKetua($request->kd_prodi);
                    }
                );
            }

            $data = $q->orderBy('id_ta','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
