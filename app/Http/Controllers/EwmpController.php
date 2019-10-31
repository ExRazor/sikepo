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
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::all();

        if(session()->has('ewmp')) {
            $ewmp         = session()->get('ewmp');
        }

        $filter = session()->get('data');

        return view('ewmp.index',compact(['studyProgram','academicYear','ewmp','filter']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(request()->ajax()) {
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
            $q = $ewmp->save();

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

    /**
     * Display the specified resource.
     *
     * @param  \App\Ewmp  $ewmp
     * @return \Illuminate\Http\Response
     */

    public function show_by_filter(Request $request)
    {
        $prodi = $request->program_studi;
        $ta    = $request->tahun_akademik;
        $smt   = $request->semester;
        $data  = array();

        if(request()->ajax()) {

            if($smt == 'Penuh') {
                $ewmp = Ewmp::with('teacher')
                            ->whereHas(
                                'teacher.studyProgram', function($query) use ($prodi) {
                                    $query->where('kd_prodi',$prodi);
                                })
                            ->whereHas(
                                'academicYear', function($query) use ($ta,$smt) {
                                    $query->where('tahun_akademik',$ta);
                            })
                            ->select([
                                'nidn',
                                DB::raw('sum(ps_intra) as ps_intra'),
                                DB::raw('sum(ps_lain) as ps_lain'),
                                DB::raw('sum(ps_luar) as ps_luar'),
                                DB::raw('sum(penelitian) as penelitian'),
                                DB::raw('sum(pkm) as pkm'),
                                DB::raw('sum(tugas_tambahan) as tugas_tambahan'),
                            ])
                            ->groupBy('nidn')
                            ->get();

                $data['tahun_akademik'] = $ta;
            } else {
                $ewmp = Ewmp::with('teacher')
                            ->whereHas(
                                'teacher.studyProgram', function($query) use ($prodi) {
                                    $query->where('kd_prodi',$prodi);
                                })
                            ->whereHas(
                                'academicYear', function($query) use ($ta,$smt) {
                                    $query->where('tahun_akademik',$ta)
                                        ->where('semester',$smt);
                            })
                            ->get();

                $data['tahun_akademik'] = $ta.' - '.$smt;
            }

            $data['ewmp']           = $ewmp;

            return response()->json($data);
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
        if(request()->ajax()) {
            $id = decrypt($id);
            $data = Ewmp::find($id);

            return response()->json($data);
        } else {
            abort(404);
        }
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
        if(request()->ajax()) {
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
            $q = $ewmp->save();

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ewmp  $ewmp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(request()->ajax()) {
            $id = decrypt($request->_id);
            $q  = Ewmp::destroy($id);

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
}
