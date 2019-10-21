<?php

namespace App\Http\Controllers;

use App\StudyProgram;
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
        $data = StudyProgram::all();
        return view('study-program.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('study-program.form');
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
            'nama'          => 'required',
            'singkatan'     => 'required',
            'jenjang'       => 'required',
            'no_sk'         => 'required',
            'tgl_sk'        => 'required',
            'pejabat_sk'    => 'required',
            'thn_menerima'  => 'required|numeric|digits:4',
        ]);

        StudyProgram::create($request->all());

        return redirect()->route('master.study-program')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StudyProgram  $studyProgram
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(request()->ajax()) {
            $id = decrypt($id);
            $data = StudyProgram::find($id);

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
        $id = decrypt($id);
        $data = StudyProgram::find($id);

        return view('study-program.form',compact('data'));
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
            'nama'          => 'required',
            'singkatan'     => 'required',
            'jenjang'       => 'required',
            'no_sk'         => 'required',
            'tgl_sk'        => 'required',
            'pejabat_sk'    => 'required',
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
        $studyProgram->save();


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
            $id = decrypt($request->id);

            StudyProgram::destroy($id);
            return response()->json([
                'title' => 'Berhasil',
                'message' => 'Data berhasil dihapus',
                'type'    => 'success'
            ]);
        } else {
            return redirect()->route('master.study-program');
        }
    }
}
