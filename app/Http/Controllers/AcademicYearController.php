<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademicYearRequest;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use Illuminate\Support\Facades\DB;

class AcademicYearController extends Controller
{
    use LogActivity;

    public function index()
    {
        $academicYears = AcademicYear::orderBy('tahun_akademik', 'desc')->orderBy('semester', 'desc')->get();

        return view('master.academic-year.index',compact('academicYears'));
    }

    public function store(AcademicYearRequest $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $academic = new AcademicYear;
            $academic->tahun_akademik = $request->tahun_akademik;
            $academic->semester       = $request->semester;
            $academic->status         = false;
            $academic->save();

            //Activity Log
            $property = [
                'id'    => $academic->id,
                'name'  => $academic->tahun_akademik. ' - '.$academic->semester,
                'url'   => route('master.academic-year')
            ];
            $this->log('created','Tahun Akademik',$property);

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

    public function edit(Request $request)
    {
        if(!request()->ajax()){
            abort(404);
        }

        $id = decrypt($request->id);
        $data = AcademicYear::find($id);

        return response()->json($data);
    }

    public function update(AcademicYearRequest $request)
    {
        if(!request()->ajax()){
            abort(404);
        }

        DB::beginTransaction();
        try {
            $id = decrypt($request->_id);

            $academic = AcademicYear::find($id);
            $academic->tahun_akademik = $request->tahun_akademik;
            $academic->semester       = $request->semester;
            $q = $academic->save();

            //Activity Log
            $property = [
                'id'    => $id,
                'name'  => $academic->tahun_akademik. ' - '.$academic->semester,
                'url'   => route('master.academic-year')
            ];
            $this->log('updated','Tahun Akademik',$property);

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

    public function destroy(Request $request)
    {
        if(!$request->ajax()){
            abort(404);
        }

        DB::beginTransaction();
        try {
            $id = decrypt($request->id);
            $data  = AcademicYear::find($id);
            $q     = $data->delete();

            //Activity Log
            $property = [
                'id'    => $id,
                'name'  => $data->tahun_akademik.' - '.$data->semester,
            ];
            $this->log('deleted','Tahun Akademik',$property);

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

    public function setStatus(Request $request)
    {
        if(!request()->ajax()){
            abort(404);
        }

        $academicYear = AcademicYear::find($request->id);

        if($academicYear->status == true) {
            return response()->json(['warning' => 'Status sudah aktif']);
        } else {
            AcademicYear::where('status',true)
                        ->update([
                            'status' => false
                        ]);

            $academicYear->status = true;
            $academicYear->save();

            return response()->json([
                'title' => 'Berhasil',
                'message' => 'Status berhasil diubah',
                'type'    => 'success'
            ]);
        }
    }

    public function loadData(Request $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        $query = AcademicYear::query();

        if($request->cari) {
            $query->where('tahun_akademik', 'LIKE', '%'.$request->cari.'%')
                 ->orWhere('semester','LIKE','%'.$request->cari.'%');
        }

        $data = $query->orderBy('tahun_akademik','desc')
                      ->orderBy('semester','desc')
                      ->get();

        $response = array();
        foreach($data as $d){
            $response[] = array(
                "id"    => $d->id,
                "text"  => $d->tahun_akademik.' - '.$d->semester
            );
        }
        return response()->json($response);
    }
}
