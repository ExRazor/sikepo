<?php

namespace App\Http\Controllers;

use App\Minithesis;
use App\StudyProgram;
use Illuminate\Http\Request;

class MinithesisController extends Controller
{
    public function index()
    {
        $minithesis = Minithesis::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('academic.minithesis.index',compact(['minithesis','studyProgram']));
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
     * @param  \App\Minithesis  $minithesis
     * @return \Illuminate\Http\Response
     */
    public function show(Minithesis $minithesis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Minithesis  $minithesis
     * @return \Illuminate\Http\Response
     */
    public function edit(Minithesis $minithesis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Minithesis  $minithesis
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Minithesis $minithesis)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Minithesis  $minithesis
     * @return \Illuminate\Http\Response
     */
    public function destroy(Minithesis $minithesis)
    {
        //
    }
}
