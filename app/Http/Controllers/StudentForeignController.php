<?php

namespace App\Http\Controllers;

use App\Models\StudentForeign;
use App\Models\Student;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StudentForeignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $studentForeign = StudentForeign::all();

        if(Auth::user()->hasRole('kaprodi')) {
            $studentForeign        = StudentForeign::whereHas('student.studyProgram', function($query){
                                                        $query->where('kd_prodi',Auth::user()->kd_prodi);
                                                    })
                                                    ->orderBy('created_at','desc')->get();
        } else {
            $studentForeign        = StudentForeign::whereHas('student.studyProgram', function($query){
                                                        $query->where('kd_jurusan',setting('app_department_id'));
                                                    })
                                                    ->orderBy('created_at','desc')->get();
        }

        return view('student.foreign.index',compact(['studentForeign','studyProgram']));
    }

    public function store(Request $request)
    {
        if(request()->ajax()) {

            $request->validate([
                'nim'           => 'required|unique:student_foreigns',
                'asal_negara'   => 'required',
                'durasi'        => 'required',
            ]);

            $data               = new StudentForeign;
            $data->nim          = $request->nim;
            $data->asal_negara  = $request->asal_negara;
            $data->durasi       = $request->durasi;
            $q = $data->save();

            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan',
                    'type'    => 'error'
                ]);
            } else {

                $student = Student::find($request->nim);
                $student->kewarganegaraan = 'WNA';
                $student->save();

                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil disimpan',
                    'type'    => 'success'
                ]);
            }
        }
    }

    public function show($id)
    {
        if(request()->ajax()) {
            // $id     = decode_id($id);
            $data   = StudentForeign::find('id',$id);
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request)
    {
        if(request()->ajax()) {

            // $id = decode_id($request->_id);
            $id = $request->_id;

            $request->validate([
                'nim'           => 'required|unique:student_foreigns,nim,'.$id,
                'asal_negara'   => 'required',
                'durasi'        => 'required',
            ]);

            $data               = StudentForeign::find($id);

            if($data->nim!=$request->nim) {
                $student = Student::find($data->nim);
                $student->kewarganegaraan = 'WNI';
                $student->save();

                $student = Student::find($request->nim);
                $student->kewarganegaraan = 'WNA';
                $student->save();
            }

            $data->nim          = $request->nim;
            $data->asal_negara  = $request->asal_negara;
            $data->durasi       = $request->durasi;
            $q = $data->save();

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
            $id   = decode_id($request->_id);
            $data = StudentForeign::find($id);
            $q    = StudentForeign::destroy($id);
            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                $student = Student::find($data->nim);
                $student->kewarganegaraan = 'WNI';
                $student->save();

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
            $data     = StudentForeign::whereHas('student.studyProgram', function($query){
                            $query->where('kd_prodi',Auth::user()->kd_prodi);
                        });
        } else {
            $data     = StudentForeign::whereHas('student.studyProgram', function($query){
                            $query->where('kd_jurusan',setting('app_department_id'));
                        });
        }

        if($request->prodi) {
            $data     = StudentForeign::whereHas('student.studyProgram', function($query) use($request){
                $query->where('kd_prodi',$request->prodi);
            });
        }

        return DataTables::of($data->get())
                            ->editColumn('nama', function($d) {
                                return '<a name="'.$d->student->nama.'" href="'.route("student.list.show",encode_id($d->student->nim)).'">'.
                                            $d->student->nama.
                                            '<br>
                                            <small>NIM. '.$d->student->nim.' / '.$d->student->studyProgram->singkatan.'</small>
                                        </a>';
                            })
                            ->addColumn('aksi', function($d) {
                                if(!Auth::user()->hasRole('kajur')) {
                                    return view('student.foreign.table-button', compact('d'))->render();
                                }
                            })
                            ->rawColumns(['nama','aksi'])
                            ->make();
    }

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {

            $q   = StudentForeign::with(['student.studyProgram'])
                            ->whereHas(
                                'student.studyProgram', function($query) {
                                    $query->where('kd_jurusan',setting('app_department_id'));
                                }
                            );

            if(Auth::user()->hasRole('kaprodi')) {
                $q->whereHas(
                    'student.studyProgram', function($query) use ($request) {
                        $query->where('kd_prodi',Auth::user()->kd_prodi);
                });
            }

            if($request->kd_prodi){
                $q->whereHas(
                    'student.studyProgram', function($query) use ($request) {
                        $query->where('kd_prodi',$request->kd_prodi);
                });
            }

            $data = $q->orderBy('nim','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
