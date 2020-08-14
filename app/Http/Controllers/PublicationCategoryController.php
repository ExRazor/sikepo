<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicationCategoryRequest;
use App\Models\PublicationCategory;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicationCategoryController extends Controller
{
    use LogActivity;

    public function index()
    {
        $category = PublicationCategory::orderBy('id','asc')->get();

        return view('master/publication-category/index',compact(['category']));
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

    public function store(PublicationCategoryRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Query
            $data               = new PublicationCategory;
            $data->nama         = $request->nama;
            $data->deskripsi    = $request->deskripsi;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
            ];
            $this->log('created','Kategori Publikasi',$property);

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

    public function update(PublicationCategoryRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($request->_id);

            //Query
            $data               = PublicationCategory::find($id);
            $data->nama         = $request->nama;
            $data->deskripsi    = $request->deskripsi;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
            ];
            $this->log('updated','Kategori Publikasi',$property);

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

        try {
            //Decrypt ID
            $id = decrypt($request->_id);

            //Query
            $data  = PublicationCategory::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
            ];
            $this->log('deleted','Kategori Publikasi',$property);

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
