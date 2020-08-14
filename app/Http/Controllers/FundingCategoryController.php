<?php

namespace App\Http\Controllers;

use App\Http\Requests\FundingCategoryRequest;
use App\Models\FundingCategory;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FundingCategoryController extends Controller
{
    use LogActivity;

    public function index()
    {
        $category = FundingCategory::with('children')->orderBy('id','asc')->whereNull('id_parent')->get();

        return view('master.funding-category.index',compact(['category']));
    }

    public function edit($id)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        $id = decrypt($id);
        $data = FundingCategory::find($id);
        return response()->json($data);
    }

    public function store(FundingCategoryRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Query
            $data               = new FundingCategory;
            $data->id_parent    = $request->id_parent;
            $data->nama         = $request->nama;
            $data->jenis        = $request->jenis;
            $data->deskripsi    = $request->deskripsi;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
                'url'   => route('master.funding-category')
            ];
            $this->log('created','Kategori Dana',$property);

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

    public function update(FundingCategoryRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($request->_id);

            //Query
            $data               = FundingCategory::find($id);
            $data->id_parent    = $request->id_parent;
            $data->nama         = $request->nama;
            $data->jenis        = $request->jenis;
            $data->deskripsi    = $request->deskripsi;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
                'url'   => route('master.funding-category')
            ];
            $this->log('updated','Kategori Dana',$property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil diubah',
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
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($request->_id);

            //Query
            $data  = FundingCategory::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
            ];
            $this->log('deleted','Kategori Dana',$property);

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


        try {

        } catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ],400);
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
