<?php

namespace App\Http\Controllers;

use App\Ewmp;
use App\Teacher;
use App\AcademicYear;
use App\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EwmpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studyProgram = StudyProgram::all();
        $academicYear = AcademicYear::all();

        if(session()->has('ewmp')) {
            $ewmp         = session()->get('ewmp');
        }

        return view('admin.ewmp.index',compact(['studyProgram','academicYear','ewmp']));
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
            'id_ta'                 => 'required',
            'ps_intra'              => 'required|numeric',
            'ps_lain'               => 'required|numeric',
            'ps_luar'               => 'required|numeric',
            'penelitian'            => 'required|numeric',
            'pkm'                   => 'required|numeric',
            'tugas_tambahan'        => 'required|numeric',
        ]);

        $ewmp                   = new Ewmp;
        $ewmp->nidn             = decrypt($request->nidn);
        $ewmp->id_ta            = $request->id_ta;
        $ewmp->ps_intra         = $request->ps_intra;
        $ewmp->ps_lain          = $request->ps_lain;
        $ewmp->ps_luar          = $request->ps_luar;
        $ewmp->penelitian       = $request->penelitian;
        $ewmp->pkm              = $request->pkm;
        $ewmp->tugas_tambahan   = $request->tugas_tambahan;
        $ewmp->save();

        return response()->json([
            'title' => 'Berhasil',
            'message' => 'Data berhasil ditambahkan.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ewmp  $ewmp
     * @return \Illuminate\Http\Response
     */
    public function show(Ewmp $ewmp)
    {
        //
    }

    public function show_by_filter(Request $request)
    {
        $studyProgram = StudyProgram::all();

        $prodi = $request->program_studi;
        $ta    = $request->tahun_akademik;
        $smt   = $request->semester;

        $ewmp = Ewmp::whereHas(
                        'teacher.studyProgram', function($query) use ($prodi) {
                            $query->where('kd_prodi',$prodi);
                        })
                    ->whereHas(
                        'academicYear', function($query) use ($ta,$smt) {
                            $query->where('tahun_akademik','=',$ta)
                                  ->where('semester','=',$smt);
                    })
                    ->get();

        // $ewmp = DB::table('ewmps')
        //             ->join('academic_years as ay', 'ewmps.id_ta', '=', 'ay.id')
        //             ->join('teachers as t', 'ewmps.nidn', '=', 't.nidn')
        //             ->join('study_programs as sp', 't.dosen_ps', '=', 'sp.kd_prodi')
        //             ->where([
        //                 'kd_prodi'       => $prodi,
        //                 'tahun_akademik' => $ta,
        //                 'semester'       => $smt
        //             ])
        //             ->select('ewmps.*', 'ay.*', 't.nama as nama_dosen', 'sp.nama as nama_prodi')
        //             ->get();

        // dd($ewmp);
        if($ewmp->count() > 0) {
            return redirect()->route('teacher.ewmp')->with(['ewmp' => $ewmp]);
        } else {
            return redirect()->route('teacher.ewmp');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ewmp  $ewmp
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $data = Ewmp::find($id);

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ewmp  $ewmp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ewmp $ewmp)
    {
        $request->validate([
            'id_ta'                 => 'required',
            'ps_intra'              => 'required|numeric',
            'ps_lain'               => 'required|numeric',
            'ps_luar'               => 'required|numeric',
            'penelitian'            => 'required|numeric',
            'pkm'                   => 'required|numeric',
            'tugas_tambahan'        => 'required|numeric',
        ]);

        $id = decrypt($request->_id);

        $ewmp                   = Ewmp::find($id);
        $ewmp->nidn             = decrypt($request->nidn);
        $ewmp->id_ta            = $request->id_ta;
        $ewmp->ps_intra         = $request->ps_intra;
        $ewmp->ps_lain          = $request->ps_lain;
        $ewmp->ps_luar          = $request->ps_luar;
        $ewmp->penelitian       = $request->penelitian;
        $ewmp->pkm              = $request->pkm;
        $ewmp->tugas_tambahan   = $request->tugas_tambahan;
        $ewmp->save();

        return response()->json([
            'title' => 'Berhasil',
            'message' => 'Data berhasil diubah.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ewmp  $ewmp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = decrypt($request->_id);
        Ewmp::destroy($id);
        return response()->json([
            'title' => 'Berhasil',
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
