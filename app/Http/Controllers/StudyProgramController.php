<?php

namespace App\Http\Controllers;

use App\StudyProgram;
use App\Department;
use App\Faculty;
use Illuminate\Http\Request;

class StudyProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data          = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $faculty       = Faculty::all();

        return view('master.study-program.index',compact(['data','faculty']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $department = "";
        $faculty = Faculty::all();

        return view('master.study-program.form',compact(['department','faculty']));
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
            'kd_prodi'      => 'required|numeric|digits:5',
            'kd_jurusan'    => 'required',
            'nama'          => 'required',
            'jenjang'       => 'required',
            'thn_menerima'  => 'required|numeric|digits:4',
        ]);

        $q = StudyProgram::create($request->all());

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

        return redirect()->route('master.study-program')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StudyProgram  $studyProgram
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if(request()->ajax()) {
            $id   = decode_url($request->id);
            $data = StudyProgram::where('kd_prodi',$id)->with('department','department.faculty')->first();

            return response()->json($data);
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StudyProgram  $studyProgram
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id         = decode_url($id);
        $data       = StudyProgram::where('kd_prodi',$id)->with('department','department.faculty')->first();
        $department = Department::where('id_fakultas',$data->department->faculty->id)->get();
        $faculty    = Faculty::all();

        return view('master.study-program.form',compact('data','department','faculty'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StudyProgram  $studyProgram
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->kd_prodi;

        // dd($request->all());

        $request->validate([
            'kd_jurusan'    => 'required',
            'nama'          => 'required',
            'jenjang'       => 'required',
            'thn_menerima'  => 'required|numeric|digits:4',
        ]);

        $studyProgram = StudyProgram::find($id);
        $studyProgram->nama           = $request->nama;
        $studyProgram->jenjang        = $request->jenjang;
        $studyProgram->no_sk          = $request->no_sk;
        $studyProgram->tgl_sk         = $request->tgl_sk;
        $studyProgram->pejabat_sk     = $request->pejabat_sk;
        $studyProgram->thn_menerima   = $request->thn_menerima;
        $studyProgram->singkatan      = $request->singkatan;
        $studyProgram->nip_kaprodi    = $request->nip_kaprodi;
        $studyProgram->nm_kaprodi     = $request->nm_kaprodi;
        $q = $studyProgram->save();

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


        return redirect()->route('master.study-program')->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StudyProgram  $studyProgram
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(request()->ajax()) {
            $id = decode_url($request->ids);

            $q = StudyProgram::destroy($id);

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
        } else {
            return redirect()->route('master.study-program');
        }
    }

    public function get_by_department(Request $request)
    {
        if($request->ajax()) {

            $kd = $request->input('kd_jurusan');

            if($kd == 0){
                $data = StudyProgram::with('department')->where('kd_jurusan','!=',setting('app_department_id'))->orderBy('created_at','desc')->get();
            } else {
                $data = StudyProgram::where('kd_jurusan',$kd)->with('department')->get();
            }
            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
