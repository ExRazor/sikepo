<?php

namespace App\Http\Controllers;

use App\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $academicYears = AcademicYear::orderBy('tahun_akademik', 'desc')->orderBy('semester', 'desc')->get();

        return view('master.academic-year.index',compact('academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AcademicYear  $academicYear
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if(request()->ajax()){
            $id = decrypt($request->id);
            $data = AcademicYear::find($id);

            return response()->json($data);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AcademicYear  $academicYear
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AcademicYear  $academicYear
     * @return \Illuminate\Http\Response
     */
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

            if($academicYear->status == 'Aktif') {
                return response()->json(['warning' => 'Status sudah aktif']);
            } else {
                AcademicYear::where('status','Aktif')
                            ->update([
                                'status' => 'Tidak Aktif'
                            ]);

                $academicYear->status = 'Aktif';
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
        if($request->has('cari')){
            $cari = $request->cari;
            $data = AcademicYear::where('tahun_akademik', 'LIKE', '%'.$cari.'%')->orWhere('semester','LIKE','%'.$cari.'%')->get();

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
}
