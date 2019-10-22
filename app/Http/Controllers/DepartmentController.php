<?php

namespace App\Http\Controllers;

use App\Department;
use App\Faculty;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faculty    = Faculty::all();
        $department = Department::all();

        return view('master.department.index',compact(['faculty','department']));
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
                'id_fakultas' => 'required',
                'kd_jurusan'  => 'required|numeric|digits:5',
                'nama'        => 'required',
                'nip_kajur'   => 'numeric|digits:18|nullable',
            ]);

            $data              = new Department;
            $data->kd_jurusan  = $request->kd_jurusan;
            $data->id_fakultas = $request->id_fakultas;
            $data->nama        = $request->nama;
            $data->nip_kajur   = $request->nip_kajur;
            $data->nm_kajur    = $request->nm_kajur;
            $q = $data->save();

            if($q) {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil ditambahkan.',
                    'type'    => 'success'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi masalah saat menambah data.',
                    'type'    => 'danger'
                ]);
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if($request->ajax()) {

            $fakultas = $request->input('id_fakultas');

            if($fakultas == 0){
                $data = Department::with('faculty')->orderBy('id_fakultas','asc')->get();
            } else {
                $data = Department::where('id_fakultas',$fakultas)->with('faculty')->get();
            }
            return response()->json($data);
        } else {
            abort(404);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if(request()->ajax()) {
            $data = Department::find($request->id);
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(request()->ajax()) {
            $request->validate([
                'id_fakultas' => 'required',
                'nama'        => 'required',
                'nip_kajur'   => 'numeric|digits:18|nullable',
            ]);

            $data              = Department::find($request->_id);
            $data->id_fakultas = $request->id_fakultas;
            $data->nama        = $request->nama;
            $data->nip_kajur   = $request->nip_kajur;
            $data->nm_kajur    = $request->nm_kajur;
            $q = $data->save();

            if($q) {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil diubah.',
                    'type'    => 'success'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi masalah saat menambah data.',
                    'type'    => 'danger'
                ]);
            }

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($request->ajax()) {

            $q = Department::destroy($request->_id);

            if($q) {
                return response()->json([
                    'title' => 'Berhasil',
                    'message' => 'Data berhasil dihapus',
                    'type'    => 'success'
                ]);
            } else {
                return response()->json([
                    'title' => 'Gagal',
                    'message' => 'Data tidak berhasil dihapus',
                    'type'    => 'danger'
                ]);
            }
        } else {
            return redirect()->route('master.department');
        }
    }

    public function get_by_faculty(Request $request)
    {
        if(request()->ajax()) {

            $data = Department::where('id_fakultas',$request->id)->get();

            return response()->json($data);

        } else {
            abort(404);
        }
    }
}
