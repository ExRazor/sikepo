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

        return view('faculty.index',compact(['faculty']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(request()->ajax()) {
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
                'message' => 'Data berhasil ditambahkan.',
                'type'    => 'success'
            ]);
        }
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
    public function edit(Request $request)
    {
        if(request()->ajax()) {
            $id = decrypt($request->id);
            $data = Faculty::find($id);

            return response()->json($data);
        } else {
            abort(404);
        }
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
        if(request()->ajax()) {
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
                'message' => 'Data berhasil ditambahkan.',
                'type'    => 'success'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->_id);
            Faculty::destroy($id);
            return response()->json([
                'title' => 'Berhasil',
                'message' => 'Data berhasil dihapus',
                'type'    => 'success'
            ]);
        } else {
            return redirect()->route('master.faculty');
        }
    }
}
