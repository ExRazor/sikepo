<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherStatus;
use App\Http\Requests\TeacherStatusRequest;

class TeacherStatusController extends Controller
{
    public function store(TeacherStatusRequest $request)
    {
        $val = $request->validated();

        try {
            $data                = new TeacherStatus;
            $data->nidn          = $request->_nidn;
            $data->id_ta         = $val['id_ta'];
            $data->jabatan       = $val['jabatan'];
            $data->kd_prodi      = $val['kd_prodi'];
            $data->save();

            $response = [
                'title'   => 'Berhasil',
                'message' => 'Data berhasil disimpan',
                'type'    => 'success'
            ];

            return response()->json($response);

        } catch(\Exception $e) {
            return $response = [
                'title'   => 'Gagal',
                'message' => $e->getMessage(),
                'type'    => 'error'
            ];
            // return false;
            return response()->json($response);
        }
    }

    public function edit($id)
    {
        if(request()->ajax()) {
            $id = decrypt($id);
            $data = TeacherStatus::find($id);
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function update(TeacherStatusRequest $request)
    {
        $val = $request->validated();

        $id = decrypt($request->_id);

        try {
            $data            = TeacherStatus::findOrFail($id);
            $data->nidn      = $request->_nidn;
            $data->id_ta     = $val['id_ta'];
            $data->kd_prodi  = $val['kd_prodi'];
            $data->save();

            $response = [
                'title'   => 'Berhasil',
                'message' => 'Data berhasil disunting',
                'type'    => 'success'
            ];

            return response()->json($response);

        } catch (\Exception $e) {
            $response = [
                'title'   => 'Gagal',
                'message' => $e->getMessage(),
                'type'    => 'error'
            ];
            return response()->json($response);
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);

            try {
                TeacherStatus::find($id)->delete();
            } catch(\Exception $e) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => $e->getMessage(),
                    'type'    => 'error'
                ]);
            }

            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil dihapus',
                'type'    => 'success'
            ]);
        }
    }
}
