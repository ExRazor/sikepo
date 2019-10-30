<?php

namespace App\Http\Controllers;

use App\FundingStudyProgram;
use Illuminate\Http\Request;

class FundingStudyProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = FundingStudyProgram::all();

        return view('funding.study-program.index',compact(['data']));
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
     * @param  \App\FundingStudyProgram  $fundingStudyProgram
     * @return \Illuminate\Http\Response
     */
    public function show(FundingStudyProgram $fundingStudyProgram)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FundingStudyProgram  $fundingStudyProgram
     * @return \Illuminate\Http\Response
     */
    public function edit(FundingStudyProgram $fundingStudyProgram)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FundingStudyProgram  $fundingStudyProgram
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FundingStudyProgram $fundingStudyProgram)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FundingStudyProgram  $fundingStudyProgram
     * @return \Illuminate\Http\Response
     */
    public function destroy(FundingStudyProgram $fundingStudyProgram)
    {
        //
    }
}
