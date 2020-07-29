<?php

namespace App\Http\Controllers;

use App\Models\StudyProgram;
use App\Models\AcademicYear;
use App\Models\CommunityService;
use App\Models\Research;
use App\Models\Teacher;
use App\Models\TeacherPublication;
use App\Models\TeacherOutputActivity;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $periodeTahun = AcademicYear::groupBy('tahun_akademik')->orderBy('tahun_akademik','desc')->select('tahun_akademik')->get();

        return view('report.index',compact(['studyProgram','periodeTahun']));
    }

    public function chart(Request $request)
    {
        //Validasi ajax
        if(!request()->ajax()) {
            abort(404);
        }

        //Query Guru
        $teacher      = Teacher::whereHas(
            'latestStatus.studyProgram', function($q) use($request){
                if($request->prodi) {
                    $q->where('kd_prodi',$request->prodi);
                } else {
                    $q->where('kd_jurusan',setting('app_department_id'));
                }
            }
        )

        ->orderBy('nama','asc')
        ->get();

        //Batas
        $batas = [$request->periode_awal,$request->periode_akhir];

        foreach($teacher as $t)
        {
            $penelitian[$t->nidn] = array(
                'nama'      => $t->nama,
                'jumlah'    => Research::with([
                                    'researchTeacher.teacher' => function($q1) use ($t) {
                                        $q1->where('nidn',$t->nidn)->orderBy('nama','asc');
                                    }
                                ])
                                ->whereHas(
                                    'researchTeacher.teacher', function($q1) use ($t) {
                                        $q1->where('nidn',$t->nidn);
                                    }
                                )
                                ->whereHas(
                                    'academicYear', function($q) use($batas) {
                                        $q->whereBetween('tahun_akademik',$batas);
                                    }
                                )
                                ->count(),
            );

            $pengabdian[$t->nidn]   = array(
                'nama'      => $t->nama,
                'jumlah'    => CommunityService::with([
                                    'serviceTeacher' => function($q1) use ($t) {
                                        $q1->where('nidn',$t->nidn);
                                    }
                                ])
                                ->whereHas(
                                    'serviceTeacher', function($q1) use ($t) {
                                        $q1->where('nidn',$t->nidn);
                                    }
                                )
                                ->whereHas(
                                    'academicYear', function($q) use($batas) {
                                        $q->whereBetween('tahun_akademik',$batas);
                                    }
                                )
                                ->count()
            );

            $publikasi[$t->nidn]    = array(
                'nama'      => $t->nama,
                'jumlah'    => TeacherPublication::whereHas(
                                    'teacher', function($q1) use ($t) {
                                        $q1->where('nidn',$t->nidn);
                                    }
                                )
                                ->whereHas(
                                    'academicYear', function($q) use($batas) {
                                        $q->whereBetween('tahun_akademik',$batas);
                                    }
                                )
                                ->count()
            );

            $luaran[$t->nidn]    = array(
                'nama'      => $t->nama,
                'jumlah'    => TeacherOutputActivity::whereHas(
                                    'teacher', function($q1) use ($t) {
                                        $q1->where('nidn',$t->nidn);
                                    }
                                )
                                ->whereHas(
                                    'academicYear', function($q) use($batas) {
                                        $q->whereBetween('tahun_akademik',$batas);
                                    }
                                )
                                ->count()
            );
        }

        $result = [
            'penelitian' => $penelitian,
            'pengabdian' => $pengabdian,
            'publikasi'  => $publikasi,
            'luaran'     => $luaran
        ];

        return response()->json($result);
    }

    public function charts(Request $request)
    {
        $penelitian = Research::whereHas(
            'researchTeacher', function($q) {
                if(Auth::user()->hasRole('kaprodi')) {
                    $q->prodiKetua(Auth::user()->kd_prodi);
                } else {
                    $q->jurusanKetua(setting('app_department_id'));
                }
            }
        )->get();

        $pengabdian = CommunityService::whereHas(
            'serviceTeacher', function($q) {
                if(Auth::user()->hasRole('kaprodi')) {
                    $q->prodiKetua(Auth::user()->kd_prodi);
                } else {
                    $q->jurusanKetua(setting('app_department_id'));
                }
            }
        )->get();

        $publikasi = TeacherPublication::whereHas(
            'teacher.latestStatus.studyProgram', function($query) {
                if(Auth::user()->hasRole('kaprodi')) {
                    $query->where('kd_prodi',Auth::user()->kd_prodi);
                } else {
                    $query->where('kd_jurusan',setting('app_department_id'));
                }
            }
        )->get();

        $luaran = TeacherOutputActivity::whereHas(
            'teacher.latestStatus.studyProgram', function($query) {
                if(Auth::user()->hasRole('kaprodi')) {
                    $query->where('kd_prodi',Auth::user()->kd_prodi);
                } else {
                    $query->where('kd_jurusan',setting('app_department_id'));
                }
            }
        )->get();

        //Request
        $thn_awal  = $request->thn_awal;
        $thn_akhir = $request->thn_akhir;

        $academicYear = AcademicYear::whereBetween('tahun_akademik',[$thn_awal,$thn_akhir])->get();
        $teacher      = Teacher::whereHas(
            'studyProgram.department', function($q) {
                $q->where('kd_jurusan',setting('app_department_id'));
            }
        );

        foreach($academicYear as $ay) {
            $result['Penelitian'][$ay->tahun_akademik] = $penelitian->where('id_ta',$ay->id)->count();
            $result['Pengabdian'][$ay->tahun_akademik] = $pengabdian->where('id_ta',$ay->id)->count();
            $result['Publikasi'][$ay->tahun_akademik]  = $publikasi->where('tahun',$ay->tahun_akademik)->count();
            $result['Luaran'][$ay->tahun_akademik]     = $luaran->where('thn_luaran',$ay->tahun_akademik)->count();
        }

        return response()->json($result);
    }
}
