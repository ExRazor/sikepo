<?php

namespace App\Http\Controllers;

use App\AcademicYear;
use App\AlumnusIdle;
use App\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlumnusIdleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('alumnus.idle.index',compact(['studyProgram']));
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
        $kd_prodi = decrypt($request->kd_prodi);

        if(request()->ajax()) {
            $request->validate([
                'tahun_lulus'             => [
                    'required',
                    Rule::unique('alumnus_idles')->where(function ($query) use($kd_prodi) {
                        return $query->where('kd_prodi', $kd_prodi);
                    }),
                    'numeric'
                ],
                'jumlah_lulusan'        => 'required|numeric',
                'lulusan_terlacak'      => 'required|numeric',
                'kriteria_1'            => 'required|numeric',
                'kriteria_2'            => 'required|numeric',
                'kriteria_3'            => 'required|numeric',
            ]);

            $data                   = new AlumnusIdle;
            $data->kd_prodi         = $kd_prodi;
            $data->tahun_lulus      = $request->tahun_lulus;
            $data->jumlah_lulusan   = $request->jumlah_lulusan;
            $data->lulusan_terlacak = $request->lulusan_terlacak;
            $data->kriteria_1       = $request->kriteria_1;
            $data->kriteria_2       = $request->kriteria_2;
            $data->kriteria_3       = $request->kriteria_3;
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

    public function show($id)
    {
        $id = decrypt($id);

        $studyProgram = StudyProgram::find($id);
        $data         = AlumnusIdle::where('kd_prodi',$id)->orderBy('tahun_lulus','desc')->get();

        $ayExist = array();
        foreach($data as $d) {
            $ayExist[] = $d->tahun_lulus;
        }

        $tahun = AcademicYear::whereNotIn('tahun_akademik',$ayExist)
                             ->groupBy('tahun_akademik')
                             ->orderBy('tahun_akademik','desc')
                             ->get('tahun_akademik');

        return view('alumnus.idle.show',compact(['studyProgram','data','tahun']));
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $data = AlumnusIdle::find($id);

        if(request()->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request)
    {
        $id       = decrypt($request->id);

        if(request()->ajax()) {
            $request->validate([
                'jumlah_lulusan'        => 'required|numeric',
                'lulusan_terlacak'      => 'required|numeric',
                'kriteria_1'            => 'required|numeric',
                'kriteria_2'            => 'required|numeric',
                'kriteria_3'            => 'required|numeric',
            ]);

            $data                   = AlumnusIdle::find($id);
            $data->jumlah_lulusan   = $request->jumlah_lulusan;
            $data->lulusan_terlacak = $request->lulusan_terlacak;
            $data->kriteria_1       = $request->kriteria_1;
            $data->kriteria_2       = $request->kriteria_2;
            $data->kriteria_3       = $request->kriteria_3;
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

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = AlumnusIdle::find($id)->delete();
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
