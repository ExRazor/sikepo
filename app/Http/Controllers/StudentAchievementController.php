<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentAchievementRequest;
use App\Models\StudentAchievement;
use App\Models\StudyProgram;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class StudentAchievementController extends Controller
{
    use LogActivity;

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
        $studyProgram   = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('student.achievement.index',compact(['studyProgram']));
    }

    public function show($id)
    {
        if(request()->ajax()) {
            // $id = decode_id($id);
            $data = StudentAchievement::where('id',$id)->with('student.studyProgram','academicYear')->first();

            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function store(StudentAchievementRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Query
            $data = StudentAchievement::create($request->all());

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->prestasi.' ('.$data->prestasi_jenis.')',
            ];
            $this->log('created','Prestasi Mahasiswa',$property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil disimpan',
                'type'    => 'success'
            ]);
        } catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ],400);
        }
    }

    public function update(StudentAchievementRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = $request->_id;

            //Query
            $data                    = StudentAchievement::find($id);
            $data->nim               = $request->nim;
            $data->id_ta             = $request->id_ta;
            $data->kegiatan_nama     = $request->kegiatan_nama;
            $data->kegiatan_tingkat  = $request->kegiatan_tingkat;
            $data->prestasi          = $request->prestasi;
            $data->prestasi_jenis    = $request->prestasi_jenis;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->prestasi.' ('.$data->prestasi_jenis.')',
            ];
            $this->log('updated','Prestasi Mahasiswa',$property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil disimpan',
                'type'    => 'success'
            ]);
        } catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ],400);
        }
    }

    public function destroy(Request $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decode_id($request->_id);

            //Query
            $data = StudentAchievement::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->prestasi.' ('.$data->prestasi_jenis.')',
            ];
            $this->log('deleted','Prestasi Mahasiswa',$property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil dihapus',
                'type'    => 'success'
            ]);
        } catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ],400);
        }
    }

    public function datatable(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        if(Auth::user()->hasRole('kaprodi')) {
            $data   = StudentAchievement::whereHas(
                'student.studyProgram',function($query) {
                    $query->where('kd_prodi',Auth::user()->kd_prodi);
                }
            );
        } else {
            $data    = StudentAchievement::whereHas(
                'student.studyProgram',function($query) {
                    $query->where('kd_jurusan',setting('app_department_id'));
                }
            );
        }

        if($request->kd_prodi) {
            $data->whereHas('student', function($query) use($request){
                $query->where('kd_prodi',$request->kd_prodi);
            });
        }

        if($request->kegiatan_tingkat) {
            $data->where('kegiatan_tingkat',$request->kegiatan_tingkat);
        }

        if($request->prestasi_jenis) {
            $data->where('prestasi_jenis',$request->prestasi_jenis);
        }

        return DataTables::of($data->get())
                            ->addColumn('tahun', function($d) {
                                return $d->academicYear->tahun_akademik.' - '.$d->academicYear->semester;
                            })
                            ->addColumn('mahasiswa', function($d) {
                                return '<a name="'.$d->student->nama.'" href="'.route("student.list.show",encode_id($d->student->nim)).'">'.
                                            $d->student->nama.
                                            '<br>
                                            <small>NIM. '.$d->student->nim.' / '.$d->student->studyProgram->singkatan.'</small>
                                        </a>';
                            })
                            ->addColumn('aksi', function($d) {
                                if(!Auth::user()->hasRole('kajur')) {
                                    return view('student.achievement.table-button', compact('d'))->render();
                                }
                            })
                            ->rawColumns(['mahasiswa','aksi'])
                            ->make();
    }

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {

            $q   = StudentAchievement::with([
                                        'student.studyProgram' => function($q) {
                                            $q->where('kd_jurusan',setting('app_department_id'));
                                        },
                                        'academicYear'
                                    ])
                                    ->whereHas(
                                        'student.studyProgram', function($query) {
                                            $query->where('kd_jurusan',setting('app_department_id'));
                                        }
                                    );

            if($request->kd_prodi){
                $q->whereHas(
                    'student.studyProgram', function($query) use ($request) {
                        $query->where('kd_prodi',$request->kd_prodi);
                });
            }

            if($request->prestasi_jenis){
                $q->where('prestasi_jenis',$request->prestasi_jenis);
            }

            $data = $q->orderBy('id_ta','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
