<?php

namespace App\Http\Controllers;

use App\FundingCategory;
use Illuminate\Http\Request;

class FundingCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = FundingCategory::with('children')->orderBy('id','asc')->whereNull('id_parent')->get();

        return view('funding/category/index',compact(['category']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FundingCategory  $fundingCategory
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FundingCategory  $fundingCategory
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FundingCategory  $fundingCategory
     * @return \Illuminate\Http\Response
     */
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
}
