<?php

namespace App\Http\Controllers\Teacher;

use App\Ewmp;
use App\AcademicYear;
use App\CurriculumSchedule;
use App\Research;
use App\CommunityService;
use App\StudyProgram;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class EwmpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nidn = Auth::user()->username;
        $ewmp = Ewmp::where('nidn',$nidn)->orderBy('id_ta','desc')->get();

        return view('teacher-view.ewmp.index',compact(['ewmp']));
    }

    public function store(Request $request)
    {
        $nidn = Auth::user()->username;

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

            $ewmp                   = new Ewmp;
            $ewmp->nidn             = $nidn;
            $ewmp->id_ta            = $request->id_ta;
            $ewmp->ps_intra         = $sks['schedule_ps'];
            $ewmp->ps_lain          = $sks['schedule_pt'];
            $ewmp->ps_luar          = $request->ps_luar;
            $ewmp->penelitian       = $sks['penelitian'];
            $ewmp->pkm              = $sks['pengabdian'];
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

    public function update(Request $request)
    {
        if(request()->ajax()) {
            $id   = decrypt($request->_id);
            $nidn = Auth::user()->username;

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

            $ewmp                   = Ewmp::find($id);
            $ewmp->id_ta            = $request->id_ta;
            $ewmp->ps_intra         = $sks['schedule_ps'];
            $ewmp->ps_lain          = $sks['schedule_pt'];
            $ewmp->ps_luar          = $request->ps_luar;
            $ewmp->penelitian       = $sks['penelitian'];
            $ewmp->pkm              = $sks['pengabdian'];
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
