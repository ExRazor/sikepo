<?php

namespace App\Http\Controllers;

use App\Collaboration;
use App\StudyProgram;
use App\AcademicYear;
use Illuminate\Http\Request;
use File;

class CollaborationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data = Collaboration::all();

        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        foreach($studyProgram as $sp) {
            $collab[$sp->kd_prodi] = Collaboration::where('kd_prodi',$sp->kd_prodi)->get();
        }

        return view('collaboration/index',compact(['studyProgram','collab']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $academicYear = AcademicYear::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        return view('collaboration/form',compact(['academicYear','studyProgram']));
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
            'kd_prodi'          => 'required',
            'id_ta'             => 'required',
            'nama_lembaga'      => 'required',
            'tingkat'           => 'required',
            'judul_kegiatan'    => 'required',
            'manfaat_kegiatan'  => 'required',
            'waktu'             => 'required',
            'durasi'            => 'required',
            'bukti'             => 'required',
        ]);

        $collaboration = new Collaboration;
        $collaboration->kd_prodi         = $request->kd_prodi;
        $collaboration->id_ta            = $request->id_ta;
        $collaboration->nama_lembaga     = $request->nama_lembaga;
        $collaboration->tingkat          = $request->tingkat;
        $collaboration->judul_kegiatan   = $request->judul_kegiatan;
        $collaboration->manfaat_kegiatan = $request->manfaat_kegiatan;
        $collaboration->waktu            = $request->waktu;
        $collaboration->durasi            = $request->durasi;

        if($request->file('bukti')) {
            $file = $request->file('bukti');
            $tgl_skrg = date('Y_m_d_H_i_s');
            $tujuan_upload = 'upload/collaboration';
            $filename = $request->nama_lembaga.'_'.$request->tingkat.'_'.$request->judul_kegiatan.'_'.$tgl_skrg.'.'.$file->getClientOriginalExtension();
            $file->move($tujuan_upload,$filename);
            $collaboration->bukti = $filename;
        }

        $collaboration->save();


        return redirect()->route('collaboration')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Collaboration  $collaboration
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $data = Collaboration::find($id);

        $academicYear = AcademicYear::all();
        $studyProgram = StudyProgram::all();
        return view('collaboration/form',compact(['academicYear','studyProgram','data']));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Collaboration  $collaboration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $request->validate([
            'kd_prodi'          => 'required',
            'id_ta'             => 'required',
            'nama_lembaga'      => 'required',
            'tingkat'           => 'required',
            'judul_kegiatan'    => 'required',
            'manfaat_kegiatan'  => 'required',
            'waktu'             => 'required',
            'durasi'            => 'required',
        ]);

        $collaboration = Collaboration::find($id);
        $collaboration->kd_prodi         = $request->kd_prodi;
        $collaboration->id_ta            = $request->id_ta;
        $collaboration->nama_lembaga     = $request->nama_lembaga;
        $collaboration->tingkat          = $request->tingkat;
        $collaboration->judul_kegiatan   = $request->judul_kegiatan;
        $collaboration->manfaat_kegiatan = $request->manfaat_kegiatan;
        $collaboration->waktu            = $request->waktu;
        $collaboration->durasi           = $request->durasi;

        //Upload File
        $storagePath = 'upload/collaboration/'.$collaboration->bukti;
        if($request->file('bukti')) {
            File::delete($storagePath);

            $file = $request->file('bukti');
            $tgl_skrg = date('Y_m_d_H_i_s');
            $tujuan_upload = 'upload/collaboration';
            $filename = $request->nama_lembaga.'_'.$request->tingkat.'_'.$request->judul_kegiatan.'_'.$request->waktu.'_'.$tgl_skrg.'.'.$file->getClientOriginalExtension();
            $file->move($tujuan_upload,$filename);
            $collaboration->bukti = $filename;
        }

        $collaboration->save();

        return redirect()->route('collaboration')->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Collaboration  $collaboration
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(request()->ajax()){
            $id = decrypt($request->id);

            $q = Collaboration::destroy($id);
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
            return redirect()->route('collaboration');
        }
    }

    public function download($filename)
    {
        $file = decrypt($filename);
        $storagePath = 'upload/collaboration/'.$file;
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
