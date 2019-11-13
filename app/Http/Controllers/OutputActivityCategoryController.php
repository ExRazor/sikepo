<?php

namespace App\Http\Controllers;

use App\OutputActivityCategory;
use Illuminate\Http\Request;

class OutputActivityCategoryController extends Controller
{
    public function index()
    {
        $category = OutputActivityCategory::orderBy('id','asc')->get();

        return view('output-activity/category/index',compact(['category']));
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
     * @param  \App\OutputActivityCategory  $outputActivityCategory
     * @return \Illuminate\Http\Response
     */
    public function show(OutputActivityCategory $outputActivityCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OutputActivityCategory  $outputActivityCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(OutputActivityCategory $outputActivityCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OutputActivityCategory  $outputActivityCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OutputActivityCategory $outputActivityCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OutputActivityCategory  $outputActivityCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(OutputActivityCategory $outputActivityCategory)
    {
        //
    }
}
