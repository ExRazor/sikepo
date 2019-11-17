<?php

namespace App\Http\Controllers;

use App\TeacherSchedule;
use App\Teacher;
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
        $data = Teacher::all();

        return view('teacher.schedule.index',compact(['data']));
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
    public function destroy(TeacherSchedule $teacherSchedule)
    {
        //
    }
}
