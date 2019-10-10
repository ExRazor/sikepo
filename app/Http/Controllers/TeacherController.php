<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Teacher;
use App\StudyProgram;
use App\AcademicYear;
use App\Ewmp;
use App\TeacherAchievement;
use File;

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

        $Teacher            = new Teacher;
        $Teacher->nidn      = $request->nidn;
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

        if($request->file('foto')) {
            $file = $request->file('foto');
            $tgl_skrg = date('Y_m_d_H_i_s');
            $tujuan_upload = 'upload/teacher';
            $filename = $request->nidn.'_'.str_replace(' ', '', $request->nama).'_'.$tgl_skrg.'.'.$file->getClientOriginalExtension();
            $file->move($tujuan_upload,$filename);
            $Teacher->foto = $filename;
        }

        $Teacher->save();

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
    public function show($id)
    {
        $id             = decrypt($id);
        $data           = Teacher::find($id);
        $academicYear   = AcademicYear::orderBy('tahun_akademik','desc')->orderBy('semester','desc')->get();
        $ewmp           = Ewmp::where('nidn',$id)->orderBy('id_ta','desc')->get();
        $achievement    = TeacherAchievement::where('nidn',$id)->orderBy('tanggal','desc')->get();

        // dd($achievement);
        return view('admin/teacher/profile',compact(['data','academicYear','ewmp','achievement']));
    }

    public function show_by_prodi(Request $request)
    {
        $data = Teacher::where('dosen_ps',$request->kd_prodi)->get();

        return response()->json($data);
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
        $id  = decrypt($request->_token_id);
        $url = $request->_url;

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

        $storagePath = 'upload/teacher/'.$Teacher->foto;
        if($request->file('foto')) {
            if(File::exists($storagePath)) {
                File::delete($storagePath);
            }

            $file = $request->file('foto');
            $tgl_skrg = date('Y_m_d_H_i_s');
            $tujuan_upload = 'upload/teacher';
            $filename = $request->nidn.'_'.str_replace(' ', '', $request->nama).'_'.$tgl_skrg.'.'.$file->getClientOriginalExtension();
            $file->move($tujuan_upload,$filename);
            $Teacher->foto = $filename;
        }

        $Teacher->save();


        return redirect($url)->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
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

    public function download($filename)
    {
        $file = decrypt($filename);

        $storagePath = 'upload/teacher/'.$file;
        if( ! File::exists($storagePath)) {
            abort(404);
        } else {
            $mimeType = File::mimeType($storagePath);
            $headers = array(
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="'.$file.'"'
            );

            return response(file_get_contents($storagePath), 200, $headers);
        }
    }
}
