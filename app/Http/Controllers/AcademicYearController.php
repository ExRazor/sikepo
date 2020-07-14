<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::orderBy('tahun_akademik', 'desc')->orderBy('semester', 'desc')->get();

        return view('master.academic-year.index',compact('academicYears'));
    }

    public function store(Request $request)
    {
        if($request->ajax()) {
            $request->validate([
                'tahun_akademik' => 'required|digits:4',
                'semester'       => 'required',
            ]);

            $q = AcademicYear::create($request->all());

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

    public function edit(Request $request)
    {
        if(request()->ajax()){
            $id = decrypt($request->id);
            $data = AcademicYear::find($id);

            return response()->json($data);
        }
    }

    public function update(Request $request)
    {
        if(request()->ajax()){
            $id = decrypt($request->_id);

            $request->validate([
                'tahun_akademik' => 'required|digits:4',
                'semester'       => 'required',
            ]);

            $academic = AcademicYear::find($id);
            $academic->tahun_akademik = $request->tahun_akademik;
            $academic->semester       = $request->semester;
            $q = $academic->save();

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

    public function destroy(Request $request)
    {
        if($request->ajax()){
            $id = decrypt($request->id);
            $q  = AcademicYear::destroy($id);

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
        } else {
            return redirect()->route('master.academy-year');
        }

    }

    public function setStatus(Request $request)
    {
        if(request()->ajax()){
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
