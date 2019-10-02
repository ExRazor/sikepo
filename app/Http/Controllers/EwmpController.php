<?php

namespace App\Http\Controllers;

use App\Ewmp;
use App\Teacher;
use App\AcademicYear;
use Illuminate\Http\Request;

class EwmpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            'id_ta'                 => 'required',
            'ps_intra'              => 'required|numeric',
            'ps_lain'               => 'required|numeric',
            'ps_luar'               => 'required|numeric',
            'penelitian'            => 'required|numeric',
            'pkm'                   => 'required|numeric',
            'tugas_tambahan'        => 'required|numeric',
        ]);

        $ewmp                   = new Ewmp;
        $ewmp->nidn             = decrypt($request->_nidn);
        $ewmp->id_ta            = $request->id_ta;
        $ewmp->ps_intra         = $request->ps_intra;
        $ewmp->ps_lain          = $request->ps_lain;
        $ewmp->ps_luar          = $request->ps_luar;
        $ewmp->penelitian       = $request->penelitian;
        $ewmp->pkm              = $request->pkm;
        $ewmp->tugas_tambahan   = $request->tugas_tambahan;
        $ewmp->save();

        return response()->json([
            'title' => 'Berhasil',
            'message' => 'Data berhasil ditambahkan.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ewmp  $ewmp
     * @return \Illuminate\Http\Response
     */
    public function show(Ewmp $ewmp)
    {
        //
    }

    public function show_based_nidn($nidn)
    {
        $data = Ewmp::find($nidn);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ewmp  $ewmp
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $data = Ewmp::find($id);

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ewmp  $ewmp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ewmp $ewmp)
    {
        $request->validate([
            'id_ta'                 => 'required',
            'ps_intra'              => 'required|numeric',
            'ps_lain'               => 'required|numeric',
            'ps_luar'               => 'required|numeric',
            'penelitian'            => 'required|numeric',
            'pkm'                   => 'required|numeric',
            'tugas_tambahan'        => 'required|numeric',
        ]);

        $id = decrypt($request->_id);

        $ewmp                   = Ewmp::find($id);
        $ewmp->id_ta            = $request->id_ta;
        $ewmp->ps_intra         = $request->ps_intra;
        $ewmp->ps_lain          = $request->ps_lain;
        $ewmp->ps_luar          = $request->ps_luar;
        $ewmp->penelitian       = $request->penelitian;
        $ewmp->pkm              = $request->pkm;
        $ewmp->tugas_tambahan   = $request->tugas_tambahan;
        $ewmp->save();

        return response()->json([
            'title' => 'Berhasil',
            'message' => 'Data berhasil diubah.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ewmp  $ewmp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = decrypt($request->_id);
        Ewmp::destroy($id);
        return response()->json([
            'title' => 'Berhasil',
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
