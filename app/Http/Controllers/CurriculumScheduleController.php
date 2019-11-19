<?php

namespace App\Http\Controllers;

use App\CurriculumSchedule;
use App\Teacher;
use App\AcademicYear;
use App\Faculty;
use App\StudyProgram;
use Illuminate\Http\Request;

class CurriculumScheduleController extends Controller
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
        $academicYear = AcademicYear::has('curriculumSchedule')->orderBy('tahun_akademik','desc')->orderBy('semester','desc')->get();

        return view('academic.schedule.index',compact(['faculty','studyProgram','academicYear']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $faculty      = Faculty::all();
        $academicYear = AcademicYear::orderBy('tahun_akademik','desc')->orderBy('semester','desc')->get();

        return view('academic.schedule.form',compact(['faculty','academicYear']));
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
     * @param  \App\CurriculumSchedule  $curriculumSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(CurriculumSchedule $curriculumSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CurriculumSchedule  $curriculumSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(CurriculumSchedule $curriculumSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CurriculumSchedule  $curriculumSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CurriculumSchedule $curriculumSchedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CurriculumSchedule  $curriculumSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(request()->ajax()) {
            $id = decode_id($request->id);
            dd($id);
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

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {
            $q  = AcademicYear::with(
                                    'curriculumSchedule.academicYear',
                                    'curriculumSchedule.curriculum.studyProgram.department',
                                    'curriculumSchedule.teacher.studyProgram.department'
                                );

            if($request->kd_jurusan) {
                $callback = function($query) use($request) {
                    $query->where('kd_jurusan',$request->kd_jurusan);
                };

                $q->whereHas(
                    'curriculumSchedule.curriculum.studyProgram', $callback);
            }

            if($request->kd_prodi){

                $callback = function ($query) use ($request) {
                    $query->curriculumProdi($request->kd_prodi);
                };

                $q->with(['curriculumSchedule' => $callback]);

                // $q->scheduleCurriculumProdi($request->kd_prodi);

            }

            $data = $q->get();

            return response()->json($data);

        } else {
            abort(404);
        }
    }
}
