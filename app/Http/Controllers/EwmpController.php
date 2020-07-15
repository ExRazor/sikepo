<?php

namespace App\Http\Controllers;

use App\Models\Ewmp;
use App\Models\AcademicYear;
use App\Models\CurriculumSchedule;
use App\Models\Research;
use App\Models\CommunityService;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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

    public function edit($id)
    {
        if(request()->ajax()) {
            $id = decrypt($id);
            $data = Ewmp::where('id',$id)->with('teacher.latestStatus.studyProgram','academicYear')->first();

            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function store(Request $request)
    {
        $nidn = decrypt($request->nidn);

        if(request()->ajax()) {
            $request->validate([
                'id_ta'             => [
                    'required',
                    Rule::unique('ewmps')->where(function ($query) use($nidn) {
                        return $query->where('nidn', $nidn);
                    }),
                ],
                'ps_intra'              => 'nullable|numeric',
                'ps_lain'               => 'nullable|numeric',
                'ps_luar'               => 'required|numeric',
                'penelitian'            => 'nullable|numeric',
                'pkm'                   => 'nullable|numeric',
                'tugas_tambahan'        => 'required|numeric',
            ]);

            $sks = $this->countSKS_manual($nidn,$request->id_ta);

            $total_sks = $sks['schedule_ps']+$request->ps_lain+$request->ps_luar+$sks['penelitian']+$sks['pengabdian']+$request->tugas_tambahan;
            $rata_sks  = $total_sks/6;

            $ewmp                   = new Ewmp;
            $ewmp->nidn             = $nidn;
            $ewmp->id_ta            = $request->id_ta;
            $ewmp->ps_intra         = $sks['schedule_ps'];
            $ewmp->ps_lain          = $request->ps_lain;
            $ewmp->ps_luar          = $request->ps_luar;
            $ewmp->penelitian       = $sks['penelitian'];
            $ewmp->pkm              = $sks['pengabdian'];
            $ewmp->tugas_tambahan   = $request->tugas_tambahan;
            $ewmp->total_sks        = $total_sks;
            $ewmp->rata_sks         = $rata_sks;

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

    public function update(Request $request)
    {
        if(request()->ajax()) {
            $id   = decrypt($request->_id);
            $nidn = decrypt($request->nidn);

            $request->validate([
                'id_ta'             => [
                    'required',
                    Rule::unique('ewmps')->where(function ($query) use($nidn) {
                        return $query->where('nidn', $nidn);
                    })->ignore($id,'id'),
                ],
                'ps_intra'              => 'nullable|numeric',
                'ps_lain'               => 'nullable|numeric',
                'ps_luar'               => 'required|numeric',
                'penelitian'            => 'nullable|numeric',
                'pkm'                   => 'nullable|numeric',
                'tugas_tambahan'        => 'required|numeric',
            ]);

            $sks = $this->countSKS_manual($nidn,$request->id_ta);

            $total_sks = $sks['schedule_ps']+$request->ps_lain+$request->ps_luar+$sks['penelitian']+$sks['pengabdian']+$request->tugas_tambahan;
            $rata_sks  = $total_sks/6;

            $ewmp                   = Ewmp::find($id);
            $ewmp->nidn             = $nidn;
            $ewmp->id_ta            = $request->id_ta;
            $ewmp->ps_intra         = $sks['schedule_ps'];
            $ewmp->ps_lain          = $request->ps_lain;
            $ewmp->ps_luar          = $request->ps_luar;
            $ewmp->penelitian       = $sks['penelitian'];
            $ewmp->pkm              = $sks['pengabdian'];
            $ewmp->tugas_tambahan   = $request->tugas_tambahan;
            $ewmp->total_sks        = $total_sks;
            $ewmp->rata_sks         = $rata_sks;

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
                                'teacher.latestStatus.studyProgram', function($query) use ($prodi) {
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
                                'teacher.latestStatus.studyProgram', function($query) use ($prodi) {
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

    public function countSKS(Request $request)
    {
        $nidn           = decrypt($request->nidn);
        $curriculum_ps  = CurriculumSchedule::where('nidn',$nidn)->where('id_ta',$request->id_ta)->whereNotNull('sesuai_prodi')->get();
        $curriculum_pt  = CurriculumSchedule::where('nidn',$nidn)->where('id_ta',$request->id_ta)->whereNull('sesuai_prodi')->get();

        $penelitian     = Research::where('id_ta',$request->id_ta)
                                    ->with([
                                        'researchTeacher' => function($q1) use ($nidn) {
                                            $q1->where('nidn',$nidn);
                                        }
                                    ])
                                    ->whereHas(
                                        'researchTeacher', function($q1) use ($nidn) {
                                            $q1->where('nidn',$nidn);
                                        }
                                    )
                                    ->get();

        $pengabdian     = CommunityService::where('id_ta',$request->id_ta)
                                    ->with([
                                        'serviceTeacher' => function($q1) use ($nidn) {
                                            $q1->where('nidn',$nidn);
                                        }
                                    ])
                                    ->whereHas(
                                        'serviceTeacher', function($q1) use ($nidn) {
                                            $q1->where('nidn',$nidn);
                                        }
                                    )
                                    ->get();

        $count_ps = array(0);
        $count_pt = array(0);
        $count_penelitian = array(0);
        $count_pengabdian = array(0);

        foreach($curriculum_ps as $ps) {
            $count_ps[] = $ps->curriculum->sks_teori + $ps->curriculum->sks_seminar + $ps->curriculum->sks_praktikum;
        }
        foreach($curriculum_pt as $pt) {
            $count_pt[] = $pt->curriculum->sks_teori + $pt->curriculum->sks_seminar + $pt->curriculum->sks_praktikum;
        }

        foreach($penelitian as $p) {
            foreach($p->researchTeacher as $pt) {
                $count_penelitian[] = $pt->sks;
            }
        }

        foreach($pengabdian as $p) {
            foreach($p->serviceTeacher as $st) {
                $count_pengabdian[] = $st->sks;
            }
        }

        $data = array(
            'schedule_ps'   => array_sum($count_ps),
            'schedule_pt'   => array_sum($count_pt),
            'penelitian'    => array_sum($count_penelitian),
            'pengabdian'    => array_sum($count_pengabdian)
        );

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function countSKS_manual($nidn,$id_ta)
    {
        $curriculum_ps  = CurriculumSchedule::where('nidn',$nidn)->where('id_ta',$id_ta)->whereNotNull('sesuai_prodi')->get();
        $curriculum_pt  = CurriculumSchedule::where('nidn',$nidn)->where('id_ta',$id_ta)->whereNull('sesuai_prodi')->get();

        $penelitian     = Research::where('id_ta',$id_ta)
                                    ->with([
                                        'researchTeacher' => function($q1) use ($nidn) {
                                            $q1->where('nidn',$nidn);
                                        }
                                    ])
                                    ->whereHas(
                                        'researchTeacher', function($q1) use ($nidn) {
                                            $q1->where('nidn',$nidn);
                                        }
                                    )
                                    ->get();

        $pengabdian     = CommunityService::where('id_ta',$id_ta)
                                    ->with([
                                        'serviceTeacher' => function($q1) use ($nidn) {
                                            $q1->where('nidn',$nidn);
                                        }
                                    ])
                                    ->whereHas(
                                        'serviceTeacher', function($q1) use ($nidn) {
                                            $q1->where('nidn',$nidn);
                                        }
                                    )
                                    ->get();

        $count_ps = array(0);
        $count_pt = array(0);
        $count_penelitian = array(0);
        $count_pengabdian = array(0);

        foreach($curriculum_ps as $ps) {
            $count_ps[] = $ps->curriculum->sks_teori + $ps->curriculum->sks_seminar + $ps->curriculum->sks_praktikum;
        }
        foreach($curriculum_pt as $pt) {
            $count_pt[] = $pt->curriculum->sks_teori + $pt->curriculum->sks_seminar + $pt->curriculum->sks_praktikum;
        }

        foreach($penelitian as $p) {
            foreach($p->researchTeacher as $pt) {
                $count_penelitian[] = $pt->sks;
            }
        }

        foreach($pengabdian as $p) {
            foreach($p->serviceTeacher as $st) {
                $count_pengabdian[] = $st->sks;
            }
        }

        $data = array(
            'schedule_ps'   => array_sum($count_ps),
            'schedule_pt'   => array_sum($count_pt),
            'penelitian'    => array_sum($count_penelitian),
            'pengabdian'    => array_sum($count_pengabdian)
        );

        return $data;
    }
}
