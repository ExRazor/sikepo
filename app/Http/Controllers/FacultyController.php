<?php

namespace App\Http\Controllers;

use App\Http\Requests\FacultyRequest;
use App\Models\Faculty;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacultyController extends Controller
{
    use LogActivity;

    public function index()
    {
        $faculty = Faculty::all();

        return view('master.faculty.index',compact(['faculty']));
    }

    public function edit(Request $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        $id = decrypt($request->id);
        $data = Faculty::find($id);

        return response()->json($data);
    }

    public function store(FacultyRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Query
            $data             = new Faculty;
            $data->nama       = $request->nama;
            $data->singkatan  = $request->singkatan;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
                'url'   => route('master.faculty')
            ];
            $this->log('created','Fakultas',$property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil disimpan',
                'type'    => 'success'
            ]);
        } catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ],400);
        }
    }

    public function update(Request $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();

        try {
            //Decrypt ID
            $id  = decrypt($request->_id);

            //Query
            $data             = Faculty::find($id);
            $data->nama       = $request->nama;
            $data->singkatan  = $request->singkatan;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
                'url'   => route('master.faculty')
            ];
            $this->log('updated','Fakultas',$property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil disimpan',
                'type'    => 'success'
            ]);
        } catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ],400);
        }
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($request->_id);

            //Query
            $data = Faculty::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
            ];
            $this->log('deleted','Fakultas',$property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil dihapus',
                'type'    => 'success'
            ]);

        } catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ],400);
        }
    }
}
