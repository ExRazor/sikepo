<?php

namespace App\Http\Controllers;

use App\Http\Requests\OutputActivityCategoryRequest;
use App\Models\OutputActivityCategory;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutputActivityCategoryController extends Controller
{
    use LogActivity;

    public function index()
    {
        $category = OutputActivityCategory::orderBy('id','asc')->get();

        return view('master/outputactivity-category/index',compact(['category']));
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

    public function store(OutputActivityCategoryRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Query
            $data               = new OutputActivityCategory;
            $data->nama         = $request->nama;
            $data->deskripsi    = $request->deskripsi;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
            ];
            $this->log('created','Kategori Luaran Kegiatan',$property);

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

    public function update(OutputActivityCategoryRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($request->_id);

            //Query
            $data               = OutputActivityCategory::find($id);
            $data->nama         = $request->nama;
            $data->deskripsi    = $request->deskripsi;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
            ];
            $this->log('updated','Kategori Luaran Kegiatan',$property);

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
            $data  = OutputActivityCategory::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
            ];
            $this->log('deleted','Kategori Luaran Kegiatan',$property);

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
