<?php

namespace App\Http\Controllers;

use App\AlumnusSuitable;
use App\AcademicYear;
use App\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AlumnusSuitableController extends Controller
{
    public function __construct()
    {
        $method = [
            'edit',
            'store',
            'update',
            'destroy',
        ];

        $this->middleware('role:admin,kaprodi', ['only' => $method]);
    }

    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        if(Auth::user()->hasRole('kaprodi')) {
            return redirect()->route('alumnus.suitable.show',encrypt(Auth::user()->kd_prodi));
        }

        return view('alumnus.suitable.index',compact(['studyProgram']));
    }

    public function show($id)
    {
        $id = decrypt($id);

        $studyProgram = StudyProgram::find($id);
        $data         = AlumnusSuitable::where('kd_prodi',$id)->orderBy('tahun_lulus','desc')->get();

        $ayExist = array();
        foreach($data as $d) {
            $ayExist[] = $d->tahun_lulus;
        }

        $tahun = AcademicYear::whereNotIn('tahun_akademik',$ayExist)
                             ->groupBy('tahun_akademik')
                             ->orderBy('tahun_akademik','desc')
                             ->get('tahun_akademik');

        return view('alumnus.suitable.show',compact(['studyProgram','data','tahun']));
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $data = AlumnusSuitable::find($id);

        if(request()->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function store(Request $request)
    {
        $kd_prodi = decrypt($request->kd_prodi);

        if(request()->ajax()) {
            $request->validate([
                'tahun_lulus'             => [
                    'required',
                    Rule::unique('alumnus_suitables')->where(function ($query) use($kd_prodi) {
                        return $query->where('kd_prodi', $kd_prodi);
                    }),
                    'numeric'
                ],
                'jumlah_lulusan'        => 'required|numeric',
                'lulusan_terlacak'      => 'required|numeric',
                'sesuai_rendah'         => 'required|numeric',
                'sesuai_sedang'         => 'required|numeric',
                'sesuai_tinggi'         => 'required|numeric',
            ]);

            $data                   = new AlumnusSuitable;
            $data->kd_prodi         = $kd_prodi;
            $data->tahun_lulus      = $request->tahun_lulus;
            $data->jumlah_lulusan   = $request->jumlah_lulusan;
            $data->lulusan_terlacak = $request->lulusan_terlacak;
            $data->sesuai_rendah    = $request->sesuai_rendah;
            $data->sesuai_sedang    = $request->sesuai_sedang;
            $data->sesuai_tinggi    = $request->sesuai_tinggi;
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

    public function update(Request $request)
    {
        $id       = decrypt($request->id);

        if(request()->ajax()) {
            $request->validate([
                'jumlah_lulusan'        => 'required|numeric',
                'lulusan_terlacak'      => 'required|numeric',
                'sesuai_rendah'         => 'required|numeric',
                'sesuai_sedang'         => 'required|numeric',
                'sesuai_tinggi'         => 'required|numeric',
            ]);

            $data                   = AlumnusSuitable::find($id);
            $data->jumlah_lulusan   = $request->jumlah_lulusan;
            $data->lulusan_terlacak = $request->lulusan_terlacak;
            $data->sesuai_rendah    = $request->sesuai_rendah;
            $data->sesuai_sedang    = $request->sesuai_sedang;
            $data->sesuai_tinggi    = $request->sesuai_tinggi;
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
            $q  = AlumnusSuitable::find($id)->delete();
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
