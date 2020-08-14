<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStatusRequest;
use App\Models\StudentStatus;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentStatusController extends Controller
{
    use LogActivity;

    public function edit($id)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        $id = decrypt($id);
        $data = StudentStatus::find($id);
        return response()->json($data);

    }

    public function store(StudentStatusRequest $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $data                = new StudentStatus;
            $data->id_ta         = $request->id_ta;
            $data->nim           = decrypt($request->_nim);
            $data->status        = $request->status;
            $data->ipk_terakhir  = $request->ipk_terakhir;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->student->nama.' ('.$data->status.')',
                'url'   => route('student.list.show',encode_id($data->nim)).'#status'
            ];
            $this->log('created','Status Mahasiswa',$property);

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

    public function update(StudentStatusRequest $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($request->_id);

            //Query
            $data                = StudentStatus::find($id);
            $data->id_ta         = $request->id_ta;
            $data->nim           = decrypt($request->_nim);
            $data->ipk_terakhir  = $request->ipk_terakhir;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->student->nama.' ('.$data->status.')',
                'url'   => route('student.list.show',encode_id($data->nim)).'#status'
            ];
            $this->log('updated','Status Mahasiswa',$property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil disunting',
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

        try {
            //Decrypt ID
            $id = decrypt($request->id);

            //Query
            $data = StudentStatus::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->student->nama.' ('.$data->status.')',
            ];
            $this->log('deleted','Status Mahasiswa',$property);

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
