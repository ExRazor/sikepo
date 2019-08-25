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

        return view('admin/academic-year/index',compact('academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required|size:4',
            'semester'       => 'required',
        ]);

        AcademicYear::create($request->all());

        return response()->json([
            'success' => 'Data berhasil ditambahkan.'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AcademicYear  $academicYear
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $data = AcademicYear::find($id);

        return response()->json($data);
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
        $id = decrypt($request->id);

        $request->validate([
            'tahun_akademik' => 'required|size:4',
            'semester'       => 'required',
        ]);

        $academic = AcademicYear::find($id);
        $academic->tahun_akademik = $request->tahun_akademik;
        $academic->semester       = $request->semester;
        $academic->save();

        return response()->json([
            'success' => 'Data berhasil disunting.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AcademicYear  $academicYear
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = decrypt($request->id);
        AcademicYear::destroy($id);
        return response()->json(['success' => 'Data berhasil dihapus']);
    }

    public function setStatus(Request $request)
    {
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

            return response()->json(['success' => 'Status berhasil diubah']);
        }
    }

    public function get_all() {
        $academicYears = AcademicYear::get();

        return response()->json($academicYears, 200);
    }
}
