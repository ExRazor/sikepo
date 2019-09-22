<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Teacher;
use App\StudyProgram;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studyProgram = StudyProgram::all();
        $teacher = array();

        foreach($studyProgram as $sp) {
            $teacher[$sp->kd_prodi] = Teacher::where('dosen_ps',$sp->kd_prodi)->get();
        }

        // dd($teacher);

        return view('admin/teacher/index',compact(['studyProgram','teacher']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $studyProgram = StudyProgram::all();
        return view('admin/teacher/form',compact('studyProgram'));
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
            'nidn'                  => 'required|numeric|digits:9',
            'nama'                  => 'required',
            'jk'                    => 'required',
            'agama'                 => 'required',
            'tpt_lhr'               => 'required',
            'tgl_lhr'               => 'required',
            'alamat'                => 'required',
            'no_telp'               => 'required',
            'email'                 => 'required|email',
            'pend_terakhir_jenjang' => 'required',
            'pend_terakhir_jurusan' => 'required',
            'bidang_ahli'           => 'required',
            'dosen_ps'              => 'required',
            'status_pengajar'       => 'required',
            'jabatan_akademik'      => 'required',
            'sesuai_bidang_ps'      => 'required',
        ]);

        Teacher::create($request->all());

        return redirect()->route('teacher')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function import()
    {
        $studyProgram = StudyProgram::all();
        return view('admin/teacher/import',compact('studyProgram'));
    }

    public function store_import()
    {
        $studyProgram = StudyProgram::all();
        return view('admin/teacher/form',compact('studyProgram'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $studyProgram = StudyProgram::all();
        $data = Teacher::find($id);

        return view('admin/teacher/form',compact(['data','studyProgram']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = decrypt($request->_token_id);

        $request->validate([
            'nama'                  => 'required',
            'jk'                    => 'required',
            'agama'                 => 'required',
            'tpt_lhr'               => 'required',
            'tgl_lhr'               => 'required',
            'alamat'                => 'required',
            'no_telp'               => 'required',
            'email'                 => 'required|email',
            'pend_terakhir_jenjang' => 'required',
            'pend_terakhir_jurusan' => 'required',
            'bidang_ahli'           => 'required',
            'dosen_ps'              => 'required',
            'status_pengajar'       => 'required',
            'jabatan_akademik'      => 'required',
            'sesuai_bidang_ps'      => 'required',
        ]);

        $Teacher            = Teacher::find($id);
        $Teacher->nama      = $request->nama;
        $Teacher->jk        = $request->jk;
        $Teacher->agama     = $request->agama;
        $Teacher->tpt_lhr   = $request->tpt_lhr;
        $Teacher->tgl_lhr   = $request->tgl_lhr;
        $Teacher->alamat    = $request->alamat;
        $Teacher->no_telp   = $request->no_telp;
        $Teacher->email     = $request->email;
        $Teacher->pend_terakhir_jenjang     = $request->pend_terakhir_jenjang;
        $Teacher->pend_terakhir_jurusan     = $request->pend_terakhir_jurusan;
        $Teacher->bidang_ahli     = $request->bidang_ahli;
        $Teacher->dosen_ps     = $request->dosen_ps;
        $Teacher->status_pengajar     = $request->status_pengajar;
        $Teacher->jabatan_akademik     = $request->jabatan_akademik;
        $Teacher->sertifikat_pendidik     = $request->sertifikat_pendidik;
        $Teacher->sesuai_bidang_ps     = $request->sesuai_bidang_ps;
        $Teacher->save();

        return redirect()->route('teacher')->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = decrypt($request->id);

        Teacher::destroy($id);
        return response()->json([
            'title' => 'Berhasil',
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
