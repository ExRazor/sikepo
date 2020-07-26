<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudyProgram;
use App\Models\Research;
use App\Models\AcademicYear;
use App\Models\CommunityService;
use App\Models\TeacherPublication;
use App\Models\TeacherOutputActivity;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function dashboard(Request $request)
    {
        if(Auth::user()->hasRole('dosen') || Auth::user()->hasRole('Dosen')) {
            return redirect()->route('profile.biodata');
        }

        /*********** QUERY ***********/
        $prodi = StudyProgram::all();

        $penelitian = Research::whereHas(
                        'researchTeacher', function($q) {
                            if(Auth::user()->hasRole('kaprodi')) {
                                $q->prodiKetua(Auth::user()->kd_prodi);
                            } else {
                                $q->jurusanKetua(setting('app_department_id'));
                            }
                        }
                    );

        $pengabdian = CommunityService::whereHas(
                        'serviceTeacher', function($q) {
                            if(Auth::user()->hasRole('kaprodi')) {
                                $q->prodiKetua(Auth::user()->kd_prodi);
                            } else {
                                $q->jurusanKetua(setting('app_department_id'));
                            }
                        }
                    );

        $publikasi = TeacherPublication::whereHas(
                        'teacher.latestStatus.studyProgram', function($query) {
                            if(Auth::user()->hasRole('kaprodi')) {
                                $query->where('kd_prodi',Auth::user()->kd_prodi);
                            } else {
                                $query->where('kd_jurusan',setting('app_department_id'));
                            }
                        }
                    );

        $luaran = TeacherOutputActivity::whereHas(
                    'teacher.latestStatus.studyProgram', function($query) {
                        if(Auth::user()->hasRole('kaprodi')) {
                            $query->where('kd_prodi',Auth::user()->kd_prodi);
                        } else {
                            $query->where('kd_jurusan',setting('app_department_id'));
                        }
                    }
                );

        $count_card = array(
            'penelitian'    => $penelitian->where('id_ta',current_academic()->id)->count(),
            'pengabdian'    => $pengabdian->where('id_ta',current_academic()->id)->count(),
            'publikasi'     => $publikasi->where('tahun',current_academic()->tahun_akademik)->count(),
            'luaran'        => $luaran->where('thn_luaran',current_academic()->tahun_akademik)->count(),
        );

        return view('home.index',compact('prodi','count_card'));
    }

    public function chart(Request $request)
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

        $thn = current_academic()->tahun_akademik;
        $academicYear = AcademicYear::whereBetween('tahun_akademik',[$thn-5,$thn])->get();

        foreach($academicYear as $ay) {
            $result['Penelitian'][$ay->tahun_akademik] = $penelitian->where('id_ta',$ay->id)->count();
            $result['Pengabdian'][$ay->tahun_akademik] = $pengabdian->where('id_ta',$ay->id)->count();
            $result['Publikasi'][$ay->tahun_akademik]  = $publikasi->where('tahun',$ay->tahun_akademik)->count();
            $result['Luaran'][$ay->tahun_akademik]     = $luaran->where('thn_luaran',$ay->tahun_akademik)->count();
        }

        return response()->json($result);
    }

    public function set_prodi($kd_prodi)
    {
        $kd_prodi = decrypt($kd_prodi);
        Session::put('prodi_aktif', $kd_prodi);
        $prodi = StudyProgram::find($kd_prodi);

        return redirect()->route('dashboard')->with('flash.message', 'Selamat datang di panel admin Program Studi '.$prodi->nama.'!');
    }

    public function tes()
    {
        // $hehe = \App\Models\Teacher::find(269685517)->teacherStatus;
        // dd($hehe);

        // $data         = \App\Models\Teacher::whereHas(
        //     'latestStatus', function($query) {
        //         $query->where('kd_prodi',57201);
        // })->get();

        // $data = \App\Models\Teacher::select('teachers.*')
        // ->join('teacher_statuses', 'teachers.nidn', 'teacher_statuses.nidn')
        // ->where(
        //     'teacher_statuses.kd_prodi', function($query) {
        //         $query->select('kd_prodi')
        //               ->from('teacher_statuses')
        //               ->latest()
        //               ->limit(1);
        // })->get();

        $data = \App\Models\Teacher::find(467391983)->latestStatus;

        dd($data);
    }
}
