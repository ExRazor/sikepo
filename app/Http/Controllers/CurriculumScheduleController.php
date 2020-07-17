<?php

namespace App\Http\Controllers;

use App\Models\CurriculumSchedule;
use App\Models\Curriculum;
use App\Models\Teacher;
use App\Models\AcademicYear;
use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CurriculumScheduleController extends Controller
{
    public function __construct()
    {
        $method = [
            'create',
            'edit',
            'store',
            'update',
            'destroy',
        ];

        $this->middleware('role:admin,kaprodi', ['only' => $method]);
    }

    public function index()
    {
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::has('curriculumSchedule')->orderBy('tahun_akademik','desc')->orderBy('semester','desc')->get();
        $ay_year      = AcademicYear::has('curriculumSchedule')->groupBy('tahun_akademik')->orderBy('tahun_akademik','desc')->get('tahun_akademik');
        $ay_semester  = AcademicYear::has('curriculumSchedule')->groupBy('semester')->orderBy('semester','desc')->get('semester');

        return view('academic.schedule.index',compact(['faculty','studyProgram','academicYear','ay_year','ay_semester']));
    }

    public function create()
    {
        $studyProgram      = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('academic.schedule.form',compact(['studyProgram']));
    }

    public function edit($id)
    {
        // $id             = decode_id($id);
        $faculty        = Faculty::all();
        $data           = CurriculumSchedule::with('teacher.latestStatus.studyProgram','curriculum','academicYear')->where('id',$id)->first();
        $studyProgram   = StudyProgram::where('kd_jurusan',$data->teacher->latestStatus->studyProgram->kd_jurusan)->get();
        $teacher        = Teacher::whereHas('latestStatus.studyProgram', function($q) use($data) {
                                $q->where('kd_prodi',$data->teacher->latestStatus->kd_prodi);
                           })->get();

        if(request()->ajax()) {
            return response()->json($data);
        } else {
            return view('academic.schedule.form',compact(['faculty','data','studyProgram','teacher']));
        }
    }

    public function store(Request $request)
    {
        $url_current  = $request->url_current;
        $url_previous = $request->url_previous;

        $request->validate([
            'id_ta'      => [
                                'required',
                                Rule::unique('curriculum_schedules')->where(function ($query) use($request) {
                                    return $query->where('nidn', $request->nidn)->where('kd_matkul',$request->kd_matkul);
                                }),
                            ],
            'nidn'       => [
                                'required',
                                Rule::unique('curriculum_schedules')->where(function ($query) use($request) {
                                    return $query->where('id_ta', $request->id_ta)->where('kd_matkul',$request->kd_matkul);
                                }),
                            ],
            'kd_matkul'  => [
                                'required',
                                Rule::unique('curriculum_schedules')->where(function ($query) use($request) {
                                    return $query->where('id_ta', $request->id_ta)->where('nidn',$request->nidn);
                                }),
                            ],
            'sesuai_bidang'  => 'nullable',
        ]);

        $dosen  = Teacher::find($request->nidn)->kd_prodi;
        $matkul = Curriculum::where('kd_matkul',$request->kd_matkul)->first()->kd_prodi;

        if($dosen==$matkul){
            $sesuai_prodi = '1';
        } else {
            $sesuai_prodi = null;
        }

        $query                  = new CurriculumSchedule;
        $query->id_ta           = $request->id_ta;
        $query->nidn            = $request->nidn;
        $query->kd_matkul       = $request->kd_matkul;
        $query->sesuai_prodi    = $sesuai_prodi;
        $query->sesuai_bidang   = $request->has('sesuai_bidang') ? $request->sesuai_bidang : null;
        $query->save();

        // if($url_current != $url_previous) {
        //     $url_tujuan = $request->url_previous;
        // } else {
        //     $url_tujuan = route('academic.schedule.index');
        // }

        if(request()->ajax()) {
            if(!$query) {
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
        } else {
            return redirect()->route('academic.schedule.index')->with('flash.message', 'Data jadwal kurikulum berhasil ditambahkan!')->with('flash.class', 'success');
        }
    }

    public function update(Request $request)
    {
        $id  = decode_id($request->id);
        $url_current  = $request->url_current;
        $url_previous = $request->url_previous;

        $request->validate([
            'id_ta'      => [
                                'required',
                                Rule::unique('curriculum_schedules')->where(function ($query) use($request) {
                                    return $query->where('nidn', $request->nidn)->where('kd_matkul',$request->kd_matkul);
                                })->ignore($id,'id'),
                            ],
            'nidn'       => [
                                'required',
                                Rule::unique('curriculum_schedules')->where(function ($query) use($request) {
                                    return $query->where('id_ta', $request->id_ta)->where('kd_matkul',$request->kd_matkul);
                                })->ignore($id,'id'),
                            ],
            'kd_matkul'  => [
                                'required',
                                Rule::unique('curriculum_schedules')->where(function ($query) use($request) {
                                    return $query->where('id_ta', $request->id_ta)->where('nidn',$request->nidn);
                                })->ignore($id,'id'),
                            ],
            'sesuai_bidang'  => 'nullable',
        ]);

        $dosen  = Teacher::find($request->nidn)->kd_prodi;
        $matkul = Curriculum::where('kd_matkul',$request->kd_matkul)->first()->kd_prodi;

        if($dosen==$matkul){
            $sesuai_prodi = '1';
        } else {
            $sesuai_prodi = null;
        }

        $query                  = CurriculumSchedule::find($id);
        $query->id_ta           = $request->id_ta;
        $query->nidn            = $request->nidn;
        $query->kd_matkul       = $request->kd_matkul;
        $query->sesuai_prodi    = $sesuai_prodi;
        $query->sesuai_bidang   = $request->has('sesuai_bidang') ? $request->sesuai_bidang : null;
        $query->save();

        // if($url_current != $url_previous) {
        //     $url_tujuan = $request->url_previous;
        // } else {
        //     $url_tujuan = route('academic.schedule');
        // }

        if(request()->ajax()) {
            if(!$query) {
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
        } else {
            return redirect()->route('academic.schedule.index')->with('flash.message', 'Data jadwal kurikulum berhasil disunting!')->with('flash.class', 'success');
        }
    }

    public function destroy(Request $request)
    {
        if(request()->ajax()) {
            $id = decode_id($request->id);
            $q  = CurriculumSchedule::destroy($id);
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
        }
    }

    public function datatable(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        if(Auth::user()->hasRole('kaprodi')) {
            $data = CurriculumSchedule::whereHas(
                'curriculum.studyProgram', function($query) {
                    $query->where('kd_prodi',Auth::user()->kd_prodi);
                }
            );
        } else {
            $data = CurriculumSchedule::whereHas(
                'curriculum.studyProgram', function($query) {
                    $query->where('kd_jurusan',setting('app_department_id'));
                }
            );
        }

        if($request->kd_prodi) {
            $data->whereHas('curriculum', function($q) use($request) {
                $q->where('kd_prodi',$request->kd_prodi);
            });
        }

        if($request->tahun) {
            $data->whereHas('academicYear', function($q) use($request) {
                $q->where('tahun_akademik',$request->tahun);
            });
        }

        if($request->semester) {
            $data->whereHas('academicYear', function($q) use($request) {
                $q->where('semester',$request->semester);
            });
        }

        if($request->jenis) {
            $data->where('jenis',$request->jenis);
        }

        return DataTables::of($data->get())
                            ->addColumn('akademik', function($d) {
                                return $d->academicYear->tahun_akademik.' - '.$d->academicYear->semester;
                            })
                            ->addColumn('matkul', function($d) {
                                return '<a name="'.$d->curriculum->nama.'" href='.route("academic.curriculum.show",$d->id).'>'.
                                            $d->curriculum->nama.
                                            '<br><small>'.$d->curriculum->studyProgram->singkatan.' / '.$d->kd_matkul.'</small>
                                        </a>';
                            })
                            ->addColumn('sks', function($d) {
                                return $d->curriculum->sks_teori+$d->curriculum->sks_seminar+$d->curriculum->sks_praktikum;
                            })
                            ->addColumn('dosen', function($d) {
                                return '<a href='.route('teacher.list.show',$d->teacher->nidn).'>'.
                                            $d->teacher->nama.
                                            '<br>
                                            <small>NIDN. '.$d->teacher->nidn.' / '.$d->teacher->latestStatus->studyProgram->singkatan.'</small>
                                        </a>';
                            })
                            ->editColumn('sesuai_prodi', function($d) {
                                if($d->sesuai_prodi) {
                                    return '<i class="fa fa-check"></i>';
                                }
                            })
                            ->editColumn('sesuai_bidang', function($d) {
                                if($d->sesuai_bidang) {
                                    return '<i class="fa fa-check"></i>';
                                }
                            })
                            ->addColumn('aksi', function($d) {
                                if(!Auth::user()->hasRole('kajur')) {
                                    return view('academic.schedule.table-button', compact('d'))->render();
                                }
                            })
                            ->rawColumns(['akademik','matkul','dosen','sesuai_prodi','sesuai_bidang','aksi'])
                            ->make();
    }

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {
            $q  = AcademicYear::with(
                                    'curriculumSchedule.academicYear',
                                    'curriculumSchedule.curriculum.studyProgram.department',
                                    'curriculumSchedule.teacher.latestStatus.studyProgram.department'
                                );

            if($request->kd_jurusan) {
                $callback = function($query) use($request) {
                    $query->where('kd_jurusan',$request->kd_jurusan);
                };

                $q->whereHas('curriculumSchedule.curriculum.studyProgram', $callback);
            }

            if(Auth::user()->hasRole('kaprodi')) {
                $callback = function ($query) use ($request) {
                    $query->curriculumProdi(Auth::user()->kd_prodi);
                };

                $q->with(['curriculumSchedule' => $callback]);
            }

            if($request->kd_prodi){

                $callback = function ($query) use ($request) {
                    $query->curriculumProdi($request->kd_prodi);
                };

                $q->with(['curriculumSchedule' => $callback]);

            }

            $data = $q->get();

            return response()->json($data);

        } else {
            abort(404);
        }
    }
}
