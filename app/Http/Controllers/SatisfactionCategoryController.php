<?php

namespace App\Http\Controllers;

use App\SatisfactionCategory;
use Illuminate\Http\Request;

class SatisfactionCategoryController extends Controller
{
    public function index()
    {
        $category = SatisfactionCategory::orderBy('jenis','desc')->orderBy('id','asc')->get();

        return view('master/satisfaction-category/index',compact(['category']));
    }

    public function store(Request $request)
    {
        if(request()->ajax()) {

            $request->validate([
                'jenis'      => 'required',
                'nama'       => 'required',
                'alias'      => 'nullable',
                'deskripsi'  => 'nullable',
            ]);

            $data               = new SatisfactionCategory;
            $data->jenis        = $request->jenis;
            $data->nama         = $request->nama;
            $data->alias        = $request->alias;
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
            $data = SatisfactionCategory::find($id);
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request)
    {
        if(request()->ajax()) {

            $id = decrypt($request->id);

            $request->validate([
                'jenis'      => 'required',
                'nama'       => 'required',
                'alias'      => 'nullable',
                'deskripsi'  => 'nullable',
            ]);

            $data               = SatisfactionCategory::find($id);
            $data->jenis        = $request->jenis;
            $data->nama         = $request->nama;
            $data->alias        = $request->alias;
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
            $q  = SatisfactionCategory::destroy($id);
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
