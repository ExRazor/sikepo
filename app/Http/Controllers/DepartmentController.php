<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Models\Faculty;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    use LogActivity;

    public function index()
    {
        $faculty    = Faculty::all();
        $department = Department::all();

        return view('master.department.index',compact(['faculty','department']));
    }

    public function show(Request $request)
    {
        if($request->ajax()) {

            $fakultas = decode_id($request->input('id_fakultas'));

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

    public function edit(Request $request)
    {
        if(request()->ajax()) {
            $id = decode_id($request->id);
            $data = Department::find($id);
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function store(DepartmentRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Query
            $data              = new Department;
            $data->kd_jurusan  = $request->kd_jurusan;
            $data->id_fakultas = $request->id_fakultas;
            $data->nama        = $request->nama;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->kd_jurusan,
                'name'  => $data->nama,
                'url'   => route('master.department')
            ];
            $this->log('created','Jurusan',$property);

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

    public function update(DepartmentRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Query
            $data              = Department::find($request->_id);
            $data->id_fakultas = $request->id_fakultas;
            $data->nama        = $request->nama;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->kd_jurusan,
                'name'  => $data->nama,
                'url'   => route('master.department')
            ];
            $this->log('updated','Jurusan',$property);

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
            $id = decode_id($request->id);

            //Query
            $data = Department::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->kd_jurusan,
                'name'  => $data->nama,
            ];
            $this->log('deleted','Jurusan',$property);

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

    public function get_by_faculty(Request $request)
    {
        if(request()->ajax()) {

            $data = Department::where('id_fakultas',$request->id)->get();

            return response()->json($data);

        } else {
            abort(404);
        }
    }

    public function get_faculty(Request $request)
    {
        if($request->ajax()) {

            $kd = $request->input('kd_jurusan');

            $data = Department::with('faculty')->where('kd_jurusan',$kd)->first();

            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
