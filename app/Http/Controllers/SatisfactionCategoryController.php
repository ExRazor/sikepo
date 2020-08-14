<?php

namespace App\Http\Controllers;

use App\Http\Requests\SatisfactionCategoryRequest;
use App\Models\SatisfactionCategory;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatisfactionCategoryController extends Controller
{
    use LogActivity;

    public function index()
    {
        $category = SatisfactionCategory::orderBy('jenis','desc')->orderBy('id','asc')->get();

        return view('master/satisfaction-category/index',compact(['category']));
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

    public function store(SatisfactionCategoryRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Query
            $data               = new SatisfactionCategory;
            $data->jenis        = $request->jenis;
            $data->nama         = $request->nama;
            $data->alias        = $request->alias;
            $data->deskripsi    = $request->deskripsi;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
            ];
            $this->log('created','Kategori Kepuasan',$property);

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

    public function update(SatisfactionCategoryRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        try {
            //Decrypt ID
            $id = decrypt($request->id);

            //Query
            $data               = SatisfactionCategory::find($id);
            $data->jenis        = $request->jenis;
            $data->nama         = $request->nama;
            $data->alias        = $request->alias;
            $data->deskripsi    = $request->deskripsi;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
            ];
            $this->log('updated','Kategori Kepuasan',$property);

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
            $data = SatisfactionCategory::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
            ];
            $this->log('deleted','Kategori Kepuasan',$property);

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
