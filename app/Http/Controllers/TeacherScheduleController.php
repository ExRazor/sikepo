<?php

namespace App\Http\Controllers;

use App\TeacherSchedule;
use App\Teacher;
use App\AcademicYear;
use App\Faculty;
use App\StudyProgram;
use Illuminate\Http\Request;

class TeacherScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::has('teacherSchedule')->orderBy('tahun_akademik','desc')->orderBy('semester','desc')->get();

        return view('teacher.schedule.index',compact(['faculty','studyProgram','academicYear']));
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
     * @param  \App\TeacherSchedule  $teacherSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(TeacherSchedule $teacherSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TeacherSchedule  $teacherSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(TeacherSchedule $teacherSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TeacherSchedule  $teacherSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeacherSchedule $teacherSchedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TeacherSchedule  $teacherSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(request()->ajax()) {
            $id = decrypt($request->id);
            dd($id);
            $q  = TeacherSchedule::destroy($id);
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
        // if($request->ajax()) {

            $q  = AcademicYear::whereHas(
                                'teacherSchedule.curriculum', function($query) use($request) {
                                    $query->where('kd_prodi','83207');
                                })
                                ->with('teacherSchedule.curriculum')
                                ->get();

            // $data = $q->get();

            return response()->json($q);


            // ->with(
                // 'teacherSchedule.academicYear',
                // 'teacherSchedule.curriculum'
                // 'teacherSchedule.curriculum.studyProgram.department',
                // 'teacherSchedule.teacher.studyProgram.department'
            // );
            // dd($data);

            // if($request->has('kd_jurusan')) {
            //     $q->whereHas(
            //         'teacherSchedule.curriculum.studyProgram', function($query) use($request) {
            //             $query->where('kd_jurusan',$request->kd_jurusan);
            //         });
            // }

            // if($request->has('kd_prodi')){

            // }


        // } else {
        //     abort(404);
        // }
    }
}
