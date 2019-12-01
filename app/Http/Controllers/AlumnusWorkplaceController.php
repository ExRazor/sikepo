<?php

namespace App\Http\Controllers;

use App\AlumnusWorkplace;
use App\AcademicYear;
use App\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlumnusWorkplaceController extends Controller
{
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('alumnus.workplace.index',compact(['studyProgram']));
    }

    public function store(Request $request)
    {
        $kd_prodi = decrypt($request->kd_prodi);

        if(request()->ajax()) {
            $request->validate([
                'tahun_lulus'             => [
                    'required',
                    Rule::unique('alumnus_workplaces')->where(function ($query) use($kd_prodi) {
                        return $query->where('kd_prodi', $kd_prodi);
                    }),
                    'numeric'
                ],
                'lulusan_bekerja'       => 'required|numeric',
                'jumlah_lulusan'        => 'required|numeric',
                'kerja_lokal'           => 'required|numeric',
                'kerja_nasional'        => 'required|numeric',
                'kerja_internasional'   => 'required|numeric',
            ]);

            $data                       = new AlumnusWorkplace;
            $data->kd_prodi             = $kd_prodi;
            $data->tahun_lulus          = $request->tahun_lulus;
            $data->jumlah_lulusan       = $request->jumlah_lulusan;
            $data->lulusan_bekerja      = $request->lulusan_bekerja;
            $data->kerja_lokal          = $request->kerja_lokal;
            $data->kerja_nasional       = $request->kerja_nasional;
            $data->kerja_internasional  = $request->kerja_internasional;
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
        $data         = AlumnusWorkplace::where('kd_prodi',$id)->orderBy('tahun_lulus','desc')->get();

        $ayExist = array();
        foreach($data as $d) {
            $ayExist[] = $d->tahun_lulus;
        }

        $tahun = AcademicYear::whereNotIn('tahun_akademik',$ayExist)
                             ->groupBy('tahun_akademik')
                             ->orderBy('tahun_akademik','desc')
                             ->get('tahun_akademik');

        return view('alumnus.workplace.show',compact(['studyProgram','data','tahun']));
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $data = AlumnusWorkplace::find($id);

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
                'lulusan_bekerja'       => 'required|numeric',
                'kerja_lokal'           => 'required|numeric',
                'kerja_nasional'        => 'required|numeric',
                'kerja_internasional'   => 'required|numeric',
            ]);

            $data                       = AlumnusWorkplace::find($id);
            $data->jumlah_lulusan       = $request->jumlah_lulusan;
            $data->lulusan_bekerja      = $request->lulusan_bekerja;
            $data->kerja_lokal          = $request->kerja_lokal;
            $data->kerja_nasional       = $request->kerja_nasional;
            $data->kerja_internasional  = $request->kerja_internasional;
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
            $q  = AlumnusWorkplace::find($id)->delete();
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
