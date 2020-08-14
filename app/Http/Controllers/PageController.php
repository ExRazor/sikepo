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
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;

class PageController extends Controller
{
    public function dashboard(Request $request)
    {
        if(Auth::user()->hasRole('dosen') || Auth::user()->hasRole('Dosen')) {
            return redirect()->route('profile.biodata');
        }

        /*********** QUERY ***********/
        $prodi = StudyProgram::all();
        $count_card = $this->count_card();


        return view('home.index',compact('prodi','count_card'));
    }

    public function chart(Request $request)
    {
        //Tahun Akademik
        $thn   = current_academic()->tahun_akademik;

        //Query
        $hehe = array();
        for( $i=$thn-5; $i<=$thn; $i++ ) {

            //Penelitian
            $result['Penelitian'][$i] = Research::whereHas(
                                'researchTeacher', function($q) {
                                    if(Auth::user()->hasRole('kaprodi')) {
                                        $q->prodiKetua(Auth::user()->kd_prodi);
                                    } else {
                                        $q->jurusanKetua(setting('app_department_id'));
                                    }
                                }
                            )
                            ->whereHas(
                                'academicYear', function($q) use($i) {
                                    $q->where('tahun_akademik',$i);
                                }
                            )
                            ->count();

            //Pengabdian
            $result['Pengabdian'][$i] = CommunityService::whereHas(
                                'serviceTeacher', function($q) {
                                    if(Auth::user()->hasRole('kaprodi')) {
                                        $q->prodiKetua(Auth::user()->kd_prodi);
                                    } else {
                                        $q->jurusanKetua(setting('app_department_id'));
                                    }
                                }
                            )
                            ->whereHas(
                                'academicYear', function($q) use($i) {
                                    $q->where('tahun_akademik',$i);
                                }
                            )
                            ->count();

            //Publikasi
            $result['Publikasi'][$i] = TeacherPublication::whereHas(
                            'teacher.latestStatus.studyProgram', function($query) {
                                if(Auth::user()->hasRole('kaprodi')) {
                                    $query->where('kd_prodi',Auth::user()->kd_prodi);
                                } else {
                                    $query->where('kd_jurusan',setting('app_department_id'));
                                }
                            }
                        )
                        ->whereHas(
                            'academicYear', function($q) use($i) {
                                $q->where('tahun_akademik',$i);
                            }
                        )
                        ->count();

            //Luaran
            $result['Luaran'][$i] = TeacherOutputActivity::whereHas(
                        'teacher.latestStatus.studyProgram', function($query) {
                            if(Auth::user()->hasRole('kaprodi')) {
                                $query->where('kd_prodi',Auth::user()->kd_prodi);
                            } else {
                                $query->where('kd_jurusan',setting('app_department_id'));
                            }
                        }
                    )
                    ->whereHas(
                        'academicYear', function($q) use($i) {
                            $q->where('tahun_akademik',$i);
                        }
                    )
                    ->count();
        }

        return response()->json($result);
    }

    public function count_card()
    {
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
            'penelitian'    => $penelitian->whereHas(
                                    'academicYear', function($q) {
                                        $q->where('tahun_akademik',current_academic()->tahun_akademik);
                                    }
                                )
                                ->count(),
            'pengabdian'    => $pengabdian->whereHas(
                                    'academicYear', function($q) {
                                        $q->where('tahun_akademik',current_academic()->tahun_akademik);
                                    }
                                )
                                ->count(),
            'publikasi'     => $publikasi->whereHas(
                                    'academicYear', function($q) {
                                        $q->where('tahun_akademik',current_academic()->tahun_akademik);
                                    }
                                )
                                ->count(),
            'luaran'        => $luaran->whereHas(
                                    'academicYear', function($q) {
                                        $q->where('tahun_akademik',current_academic()->tahun_akademik);
                                    }
                                )
                                ->count(),
        );

        return $count_card;
    }

    public function activity_log(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        $data = Activity::all();

        return DataTables::of($data)
                            ->addColumn('waktu', function($d) {
                                return $d->created_at;
                            })
                            ->addColumn('aksi', function($d) {
                                return $d->causer->name.' '.$d->description;
                            })
                            ->addColumn('target', function($d) {
                                if($d->getExtraProperty('url')) {
                                    $message = '<a name="'.$d->getExtraProperty('name').'" href="'.$d->getExtraProperty('url').'">'.
                                                    $d->getExtraProperty('name').
                                                '</a>';
                                } else {
                                    $message = $d->getExtraProperty('name');
                                }

                                return $message;
                            })
                            ->rawColumns(['aksi','target'])
                            ->make();
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
