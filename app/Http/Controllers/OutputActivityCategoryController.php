<?php

namespace App\Http\Controllers;

use App\Models\OutputActivityCategory;
use Illuminate\Http\Request;

class OutputActivityCategoryController extends Controller
{
    public function index()
    {
        $category = OutputActivityCategory::orderBy('id','asc')->get();

        return view('master/outputactivity-category/index',compact(['category']));
    }

    public function store(Request $request)
    {
        if(request()->ajax()) {

            $request->validate([
                'nama'       => 'required',
                'deskripsi'  => 'nullable',
            ]);

            $data               = new OutputActivityCategory;
            $data->nama         = $request->nama;
            $data->deskripsi    = $request->deskripsi;
            $q = $data->save();

            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil disimpan',
                    'type'    => 'success'
                ]);
            }
        }
    }

    public function edit($id)
    {
        if(request()->ajax()) {
            $id   = decrypt($id);
            $data = OutputActivityCategory::find($id);
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request)
    {
        if(request()->ajax()) {

            $id = decrypt($request->_id);

            $request->validate([
                'nama'       => 'required',
                'deskripsi'  => 'nullable',
            ]);

            $data               = OutputActivityCategory::find($id);
            $data->nama         = $request->nama;
            $data->deskripsi    = $request->deskripsi;
            $q = $data->save();

            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menyimpan',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil diubah',
                    'type'    => 'success'
                ]);
            }
        }
    }

    public function destroy(Request $request)
    {
        if(request()->ajax()) {
            $id = decrypt($request->_id);
            $q  = OutputActivityCategory::destroy($id);
            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil dihapus',
                    'type'    => 'success'
                ]);
            }
        }
    }
}
