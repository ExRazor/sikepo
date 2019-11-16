<?php

namespace App\Http\Controllers;

use App\StudentForeign;
use App\Student;
use App\StudyProgram;
use Illuminate\Http\Request;

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

    public function edit($id)
    {
        if(request()->ajax()) {
            $id     = decode_id($id);
            $data   = StudentForeign::where('id',$id)->with('student')->first();
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request)
    {
        if(request()->ajax()) {

            $id = decode_id($request->_id);

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StudentForeign  $studentForeign
     * @return \Illuminate\Http\Response
     */
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

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {

            $q   = StudentForeign::with(['student.studyProgram'])
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

            $data = $q->orderBy('nim','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
