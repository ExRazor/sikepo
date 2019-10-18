<?php

namespace App\Http\Controllers;

use App\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faculty = Faculty::all();

        return view('admin.faculty.index',compact(['faculty']));
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
        $request->validate([
            'nama'        => 'required',
            'singkatan'   => 'required',
        ]);

        $data             = new Faculty;
        $data->nama       = $request->nama;
        $data->singkatan  = $request->singkatan;
        $data->nip_dekan  = $request->nip_dekan;
        $data->nm_dekan   = $request->nm_dekan;
        $data->save();

        return response()->json([
            'title' => 'Berhasil',
            'message' => 'Data berhasil ditambahkan.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $data = Faculty::find($id);

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id  = decrypt($request->_id);

        $request->validate([
            'nama'        => 'required',
            'singkatan'   => 'required',
        ]);

        $data             = Faculty::find($id);
        $data->nama       = $request->nama;
        $data->singkatan  = $request->singkatan;
        $data->nip_dekan  = $request->nip_dekan;
        $data->nm_dekan   = $request->nm_dekan;
        $data->save();

        return response()->json([
            'title' => 'Berhasil',
            'message' => 'Data berhasil ditambahkan.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = decrypt($request->_id);
        Faculty::destroy($id);
        return response()->json([
            'title' => 'Berhasil',
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
