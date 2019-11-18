<?php

namespace App\Http\Controllers;

use App\Curriculum;
use App\StudyProgram;
use App\Imports\CurriculumImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class CurriculumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studyProgram   = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $curriculum     = Curriculum::whereHas(
                            'studyProgram', function($query) {
                                $query->where('kd_jurusan',setting('app_department_id'));
                            })
                            ->with('studyProgram')
                            ->get();
        $thn_kurikulum  = Curriculum::groupBy('versi')->get('versi');

        return view('academic.curriculum.index',compact(['studyProgram','curriculum','thn_kurikulum']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('academic.curriculum.form',compact('studyProgram'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Curriculum  $curriculum
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id             = decode_id($id);
        $studyProgram   = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $data           = Curriculum::find($id);

        return view('academic.curriculum.form',compact(['studyProgram','data']));
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
            'kd_matkul'         => 'required|unique:curricula,kd_matkul',
            'kd_prodi'          => 'required',
            'versi'             => 'required|numeric|digits:4',
            'nama'              => 'required',
            'semester'          => 'required|numeric',
            'jenis'             => 'required',
            'sks_teori'         => 'nullable|numeric',
            'sks_seminar'       => 'nullable|numeric',
            'sks_praktikum'     => 'nullable|numeric',
            'capaian'           => 'required',
            'dokumen_nama'      => 'nullable',
        ]);

        $query                  = new Curriculum;
        $query->kd_matkul       = $request->kd_matkul;
        $query->kd_prodi        = $request->kd_prodi;
        $query->versi           = $request->versi;
        $query->nama            = $request->nama;
        $query->semester        = $request->semester;
        $query->jenis           = $request->jenis;
        $query->sks_teori       = intval($request->sks_teori);
        $query->sks_seminar     = intval($request->sks_seminar);
        $query->sks_praktikum   = intval($request->sks_praktikum);
        $query->capaian         = $request->capaian;
        $query->dokumen_nama    = $request->dokumen_nama;
        $query->save();

        return redirect()->route('academic.curriculum')->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function import(Request $request)
	{
		// Memvalidasi
		$this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		// Menangkap file excel
		$file = $request->file('file');

		// Mengambil nama file
        $nama_file = $file->getClientOriginalName();

		// upload ke folder khusus di dalam folder public
		$file->move('upload/curriculum/excel_import/',$nama_file);

		// import data
        $q = Excel::import(new CurriculumImport, public_path('/upload/curriculum/excel_import/'.$nama_file));

        //Validasi jika terjadi error saat mengimpor
        if(!$q) {
            return response()->json([
                'title'   => 'Gagal',
                'message' => 'Terjadi kesalahan saat mengimpor',
                'type'    => 'error'
            ]);
        } else {
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil diimpor',
                'type'    => 'success'
            ]);
        }
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Curriculum  $curriculum
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $request->validate([
            'kd_matkul'         => 'required|unique:curricula,kd_matkul,'.$id.',kd_matkul',
            'kd_prodi'          => 'required',
            'versi'             => 'required|numeric|digits:4',
            'nama'              => 'required',
            'semester'          => 'required|numeric',
            'jenis'             => 'required',
            'sks_teori'         => 'nullable|numeric',
            'sks_seminar'       => 'nullable|numeric',
            'sks_praktikum'     => 'nullable|numeric',
            'capaian'           => 'required',
            'dokumen_nama'      => 'nullable',
        ]);

        $query                  = Curriculum::find($id);
        $query->kd_matkul       = $request->kd_matkul;
        $query->kd_prodi        = $request->kd_prodi;
        $query->versi           = $request->versi;
        $query->nama            = $request->nama;
        $query->semester        = $request->semester;
        $query->jenis           = $request->jenis;
        $query->sks_teori       = intval($request->sks_teori);
        $query->sks_seminar     = intval($request->sks_seminar);
        $query->sks_praktikum   = intval($request->sks_praktikum);
        $query->capaian         = $request->capaian;
        $query->dokumen_nama    = $request->dokumen_nama;
        $query->save();

        return redirect()->route('academic.curriculum')->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Curriculum  $curriculum
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decode_id($request->id);
            $q  = Curriculum::destroy($id);
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

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {

            $q   = Curriculum::with(['studyProgram'])
                            ->whereHas(
                                'studyProgram', function($query) {
                                    $query->where('kd_jurusan',setting('app_department_id'));
                                }
                            );

            if($request->kd_prodi){
                $q->where('kd_prodi',$request->kd_prodi);
            }

            if($request->kurikulum) {
                $q->where('versi',$request->kurikulum);
            }

            if($request->semester) {
                $q->where('semester',$request->semester);
            }

            if($request->jenis) {
                $q->where('jenis',$request->jenis);
            }

            $data = $q->orderBy('semester','asc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function loadData(Request $request)
    {
        if($request->has('cari')){
            $cari = $request->cari;
            $data = Curriculum::where('kd_matkul', 'LIKE', '%'.$cari.'%')->orWhere('nama', 'LIKE', '%'.$cari.'%')->get();

            $response = array();
            foreach($data as $d){
                $response[] = array(
                    "id"    => $d->kd_matkul,
                    "text"  => $d->nama.' - '.$d->studyProgram->singkatan.' ('.$d->versi.')'
                );
            }
            return response()->json($response);
        }
    }
}
