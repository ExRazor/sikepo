<?php

namespace App\Http\Controllers;

use App\OutputActivity;
use App\OutputActivityCategory;
use App\StudyProgram;
use Illuminate\Http\Request;

class OutputActivityController extends Controller
{
    public function index()
    {
        $studyProgram   = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $outputActivity = OutputActivity::all();

        return view('output-activity.index',compact(['outputActivity','studyProgram']));
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
     * @param  \App\OutputActivity  $outputActivity
     * @return \Illuminate\Http\Response
     */
    public function show(OutputActivity $outputActivity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OutputActivity  $outputActivity
     * @return \Illuminate\Http\Response
     */
    public function edit(OutputActivity $outputActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OutputActivity  $outputActivity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OutputActivity $outputActivity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OutputActivity  $outputActivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(OutputActivity $outputActivity)
    {
        //
    }
}
