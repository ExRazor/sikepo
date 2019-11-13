<?php

namespace App\Http\Controllers;

use App\Research;
use App\StudyProgram;
use App\Faculty;
use App\Teacher;
use App\ResearchStudents;
use Illuminate\Http\Request;

class ResearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $penelitian = Research::whereHas(
            'teacher.studyProgram.department', function($query) {
                $query->where('kd_jurusan',setting('app_department_id'));
            })
        ->with('researchStudents.studyProgram.department.faculty','teacher.studyProgram')
        ->get();

        return view('research.index',compact(['penelitian','studyProgram']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        return view('research.form',compact(['studyProgram','faculty']));
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
            'tema_penelitian'   => 'required',
            'judul_penelitian'  => 'required',
            'tahun_penelitian'  => 'required|numeric|digits:4',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
        ]);

        $research                    = new Research;
        $research->nidn              = $request->nidn;
        $research->tema_penelitian   = $request->tema_penelitian;
        $research->judul_penelitian  = $request->judul_penelitian;
        $research->tahun_penelitian  = $request->tahun_penelitian;
        $research->sumber_biaya      = $request->sumber_biaya;
        $research->sumber_biaya_nama = $request->sumber_biaya_nama;
        $research->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $research->save();


        $hitungMhs = count($request->mahasiswa_nim);
        for($i=0;$i<$hitungMhs;$i++) {

            ResearchStudents::updateOrCreate(
                [
                    'id_penelitian' => $research->id,
                    'nim'           => $request->mahasiswa_nim[$i],
                ],
                [
                    'nama'      => $request->mahasiswa_nama[$i],
                    'kd_prodi'  => $request->mahasiswa_prodi[$i],
                ]
            );
        }

        return redirect()->route('research')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id   = decode_id($id);

        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $data         = Research::with('teacher','researchStudents')->where('id',$id)->first();
        $teacher      = Teacher::where('kd_prodi',$data->teacher->kd_prodi)->get();

        return view('research.form',compact(['data','studyProgram','teacher']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $request->validate([
            'nidn'              => 'required',
            'tema_penelitian'   => 'required',
            'judul_penelitian'  => 'required',
            'tahun_penelitian'  => 'required|numeric|digits:4',
            'sumber_biaya'      => 'required',
        ]);

        $research                    = Research::find($id);
        $research->nidn              = $request->nidn;
        $research->tema_penelitian   = $request->tema_penelitian;
        $research->judul_penelitian  = $request->judul_penelitian;
        $research->tahun_penelitian  = $request->tahun_penelitian;
        $research->sumber_biaya      = $request->sumber_biaya;
        $research->sumber_biaya_nama = $request->sumber_biaya_nama;
        $research->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $research->save();


        $hitungMhs = count($request->mahasiswa_nim);
        for($i=0;$i<$hitungMhs;$i++) {

            ResearchStudents::updateOrCreate(
                [
                    'id_penelitian' => $id,
                    'nim'           => $request->mahasiswa_nim[$i],
                ],
                [
                    'nama'      => $request->mahasiswa_nama[$i],
                    'kd_prodi'  => $request->mahasiswa_prodi[$i],
                ]
            );
        }

        return redirect()->route('research')->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
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

    public function destroy_students(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = ResearchStudents::find($id)->delete();
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

            $q   = Research::with(['teacher.studyProgram','researchStudents.studyProgram.department'])
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

            $data = $q->orderBy('tahun_penelitian','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
