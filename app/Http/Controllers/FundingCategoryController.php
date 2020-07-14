<?php

namespace App\Http\Controllers;

use App\Models\FundingCategory;
use Illuminate\Http\Request;

class FundingCategoryController extends Controller
{

    public function index()
    {
        $category = FundingCategory::with('children')->orderBy('id','asc')->whereNull('id_parent')->get();

        return view('master.funding-category.index',compact(['category']));
    }

    public function store(Request $request)
    {
        if(request()->ajax()) {

            if(!$request->id_parent) {
                $request->validate([
                    'id_parent'  => 'nullable',
                    'nama'       => 'required',
                    'deskripsi'  => 'nullable',
                    'jenis'      => 'required',
                ]);
            } else {
                $request->validate([
                    'id_parent'  => 'nullable',
                    'nama'       => 'required',
                    'deskripsi'  => 'nullable',
                ]);
            }

            $data               = new FundingCategory;
            $data->id_parent    = $request->id_parent;
            $data->nama         = $request->nama;
            $data->jenis        = $request->jenis;
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
            $data = FundingCategory::find($id);
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request)
    {
        if(request()->ajax()) {

            $id = decrypt($request->_id);

            if(!$request->id_parent) {
                $request->validate([
                    'id_parent'  => 'nullable',
                    'nama'       => 'required',
                    'deskripsi'  => 'nullable',
                    'jenis'      => 'required',
                ]);
            } else {
                $request->validate([
                    'id_parent'  => 'nullable',
                    'nama'       => 'required',
                    'deskripsi'  => 'nullable',
                ]);
            }

            $data               = FundingCategory::find($id);
            $data->id_parent    = $request->id_parent;
            $data->nama         = $request->nama;
            $data->jenis        = $request->jenis;
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
            $q  = FundingCategory::destroy($id);
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

    public function get_jenis($id)
    {
        if(request()->ajax()) {

            $data  = FundingCategory::where('id',$id)->first()->jenis;

            return response()->json($data);
        }
    }
}
