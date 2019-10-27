<?php

namespace App\Http\Controllers;

use App\Student;
use App\Faculty;
use App\StudyProgram;
use App\AcademicYear;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $faculty      = Faculty::all();
        $angkatan     = AcademicYear::groupBy('tahun_akademik')->orderBy('tahun_akademik','desc')->get('tahun_akademik');
        $data         = Student::whereHas(
                            'studyProgram', function($query) {
                                $query->where('kd_jurusan',setting('app_department_id'));
                            })
                        ->get();

        return view('student.index',compact(['studyProgram','faculty','angkatan','data']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(request()->ajax()) {
            $id = decode_id($request->id);
            $q  = Student::destroy($id);
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

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {

            $q = Student::with(['studyProgram.department.faculty']);

            if($request->kd_jurusan) {
                $q->whereHas(
                    'studyProgram', function($query) use($request) {
                        $query->where('kd_jurusan',$request->kd_jurusan);
                    });
            }

            if($request->kd_prodi){
                $q->where('kd_prodi',$request->kd_prodi);
            }

            if($request->angkatan) {
                $q->where('angkatan',$request->angkatan);
            }

            $data = $q->orderBy('created_at','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
