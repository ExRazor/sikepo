<?php

namespace App\Http\Controllers;

use App\StudentRegistrant;
use App\StudyProgram;
use Illuminate\Http\Request;

class StudentRegistrantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studyProgram = StudyProgram::all();
        $registrant   = array();

        foreach($studyProgram as $sp) {
            $registrant[$sp->kd_prodi] = StudentRegistrant::where('kd_prodi',$sp->kd_prodi)->get();
        }

        return view('admin/student-registrant/index',compact(['studyProgram','registrant']));
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
     * @param  \App\StudentRegistrant  $studentRegistrant
     * @return \Illuminate\Http\Response
     */
    public function show(StudentRegistrant $studentRegistrant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StudentRegistrant  $studentRegistrant
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentRegistrant $studentRegistrant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StudentRegistrant  $studentRegistrant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentRegistrant $studentRegistrant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StudentRegistrant  $studentRegistrant
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentRegistrant $studentRegistrant)
    {
        //
    }
}
