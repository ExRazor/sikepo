<?php

namespace App\Http\Controllers;

use App\Models\PublicationCategory;
use Illuminate\Http\Request;

class PublicationCategoryController extends Controller
{
    public function index()
    {
        $category = PublicationCategory::orderBy('id','asc')->get();

        return view('master/publication-category/index',compact(['category']));
    }

    public function store(Request $request)
    {
        if(request()->ajax()) {

            $request->validate([
                'nama'       => 'required',
                'deskripsi'  => 'nullable',
            ]);

            $data               = new PublicationCategory;
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
            $id = decrypt($id);
            $data = PublicationCategory::find($id);
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

            $data               = PublicationCategory::find($id);
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
            $q  = PublicationCategory::destroy($id);
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
