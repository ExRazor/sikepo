<?php

namespace App\Http\Controllers;

use App\StudentAchievement;
use App\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAchievementController extends Controller
{
    public function index()
    {
        if(Auth::user()->role=='kaprodi') {
            $achievement    = StudentAchievement::whereHas(
                                                    'student.studyProgram',function($query) {
                                                        $query->where('kd_prodi',Auth::user()->kd_prodi);
                                                    }
                                                )
                                                ->orderBy('id_ta','desc')->get();
        } else {
            $achievement    = StudentAchievement::whereHas(
                                                    'student.studyProgram',function($query) {
                                                        $query->where('kd_jurusan',setting('app_department_id'));
                                                    }
                                                )
                                                ->orderBy('id_ta','desc')->get();
        }


        $studyProgram   = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('student.achievement.index',compact(['achievement','studyProgram']));
    }

    public function store(Request $request)
    {
        if(request()->ajax()) {
            $request->validate([
                'nim'               => 'required',
                'id_ta'             => 'required',
                'kegiatan_nama'     => 'required',
                'kegiatan_tingkat'  => 'required',
                'prestasi'          => 'required',
                'prestasi_jenis'    => 'required',
            ]);

            $q = StudentAchievement::create($request->all());

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


    public function edit($id)
    {
        if(request()->ajax()) {
            $id = decode_id($id);
            $data = StudentAchievement::where('id',$id)->with('student.studyProgram','academicYear')->first();

            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request)
    {
        if(request()->ajax()) {
            $id = decode_id($request->_id);

            $request->validate([
                'nim'               => 'required',
                'id_ta'             => 'required',
                'kegiatan_nama'     => 'required',
                'kegiatan_tingkat'  => 'required',
                'prestasi'          => 'required',
                'prestasi_jenis'    => 'required',
            ]);

            $acv                    = StudentAchievement::find($id);
            $acv->nim               = $request->nim;
            $acv->id_ta             = $request->id_ta;
            $acv->kegiatan_nama     = $request->kegiatan_nama;
            $acv->kegiatan_tingkat  = $request->kegiatan_tingkat;
            $acv->prestasi          = $request->prestasi;
            $acv->prestasi_jenis    = $request->prestasi_jenis;

            $q = $acv->save();

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
        if(request()->ajax()) {
            $id = decode_id($request->_id);
            $q = StudentAchievement::destroy($id);

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
            return redirect()->route('student.achievement');
        }
    }

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {

            $q   = StudentAchievement::with([
                                        'student.studyProgram' => function($q) {
                                            $q->where('kd_jurusan',setting('app_department_id'));
                                        },
                                        'academicYear'
                                    ])
                                    ->whereHas(
                                        'student.studyProgram', function($query) {
                                            $query->where('kd_jurusan',setting('app_department_id'));
                                        }
                                    );

            if($request->kd_prodi){
                $q->whereHas(
                    'student.studyProgram', function($query) use ($request) {
                        $query->where('kd_prodi',$request->kd_prodi);
                });
            }

            if($request->prestasi_jenis){
                $q->where('prestasi_jenis',$request->prestasi_jenis);
            }

            $data = $q->orderBy('id_ta','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
