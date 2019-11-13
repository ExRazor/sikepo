<?php

namespace App\Http\Controllers;

use App\CommunityService;
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
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $pengabdian   = CommunityService::whereHas(
                            'teacher.studyProgram', function($query) {
                                $query->where('kd_jurusan',setting('app_department_id'));
                            }
                        )
                        ->with('communityServiceStudent.studyProgram.department.faculty','teacher.studyProgram')
                        ->get();

        return view('community-service.index',compact(['pengabdian','studyProgram']));
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
            'nidn'              => 'required',
            'tema_pengabdian'   => 'required',
            'judul_pengabdian'  => 'required',
            'tahun_pengabdian'  => 'required|numeric|digits:4',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
        ]);

        $community                    = new CommunityService;
        $community->nidn              = $request->nidn;
        $community->tema_pengabdian   = $request->tema_pengabdian;
        $community->judul_pengabdian  = $request->judul_pengabdian;
        $community->tahun_pengabdian  = $request->tahun_pengabdian;
        $community->sumber_biaya      = $request->sumber_biaya;
        $community->sumber_biaya_nama = $request->sumber_biaya_nama;
        $community->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $community->save();


        $hitungMhs = count($request->mahasiswa_nim);
        for($i=0;$i<$hitungMhs;$i++) {

            CommunityServiceStudent::updateOrCreate(
                [
                    'id_pengabdian' => $community->id,
                    'nim'           => $request->mahasiswa_nim[$i],
                ],
                [
                    'nama'      => $request->mahasiswa_nama[$i],
                    'kd_prodi'  => $request->mahasiswa_prodi[$i],
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
        $data         = CommunityService::with('teacher','communityServiceStudent')->where('id',$id)->first();
        $teacher      = Teacher::where('kd_prodi',$data->teacher->kd_prodi)->get();

        return view('community-service.form',compact(['data','studyProgram','teacher']));
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
            'nidn'              => 'required',
            'tema_pengabdian'   => 'required',
            'judul_pengabdian'  => 'required',
            'tahun_pengabdian'  => 'required|numeric|digits:4',
            'sumber_biaya'      => 'required',
        ]);

        $community                    = CommunityService::find($id);
        $community->nidn              = $request->nidn;
        $community->tema_pengabdian   = $request->tema_pengabdian;
        $community->judul_pengabdian  = $request->judul_pengabdian;
        $community->tahun_pengabdian  = $request->tahun_pengabdian;
        $community->sumber_biaya      = $request->sumber_biaya;
        $community->sumber_biaya_nama = $request->sumber_biaya_nama;
        $community->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $community->save();


        $hitungMhs = count($request->mahasiswa_nim);
        for($i=0;$i<$hitungMhs;$i++) {

            CommunityServiceStudent::updateOrCreate(
                [
                    'id_pengabdian' => $id,
                    'nim'           => $request->mahasiswa_nim[$i],
                ],
                [
                    'nama'      => $request->mahasiswa_nama[$i],
                    'kd_prodi'  => $request->mahasiswa_prodi[$i],
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

            $q   = CommunityService::with(['teacher.studyProgram','communityServiceStudent.studyProgram.department'])
                            ->whereHas(
                                'teacher.studyProgram.department', function($query) {
                                    $query->where('kd_jurusan',setting('app_department_id'));
                                }
                            );

            if($request->kd_prodi){
                $q->whereHas(
                    'teacher.studyProgram', function($query) use ($request) {
                        $query->where('kd_prodi',$request->kd_prodi);
                });
            }

            $data = $q->orderBy('tahun_pengabdian','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
