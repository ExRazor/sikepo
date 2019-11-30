<?php

namespace App\Http\Controllers;

use App\StudyProgram;
use App\TeacherAchievement;
use Illuminate\Http\Request;
use File;

class TeacherAchievementController extends Controller
{
    public function index()
    {
        $achievement    = TeacherAchievement::whereHas(
                                                'teacher.studyProgram',function($query) {
                                                    $query->where('kd_jurusan',setting('app_department_id'));
                                                }
                                            )
                                            ->orderBy('id_ta','desc')->get();
        $studyProgram   = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('teacher.achievement.index',compact(['achievement','studyProgram']));
    }


    public function store(Request $request)
    {
        if(request()->ajax()) {
            $request->validate([
                'nidn'              => 'required',
                'id_ta'             => 'required',
                'prestasi'          => 'required',
                'tingkat_prestasi'  => 'required',
                'bukti_nama'        => 'required',
                'bukti_file'        => 'required',
            ]);

            $acv                    = new TeacherAchievement;
            $acv->nidn              = $request->nidn;
            $acv->id_ta             = $request->id_ta;
            $acv->prestasi          = $request->prestasi;
            $acv->tingkat_prestasi  = $request->tingkat_prestasi;
            $acv->bukti_nama        = $request->bukti_nama;

            if($file = $request->file('bukti_file')) {
                $tgl_skrg = date('Y_m_d_H_i_s');
                $tujuan_upload = 'upload/teacher-achievement';
                $filename = $acv->nidn.'_'.str_replace(' ', '', $request->prestasi).'_'.$tgl_skrg.'.'.$file->getClientOriginalExtension();
                $file->move($tujuan_upload,$filename);
                $acv->bukti_file = $filename;
            }

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

    public function edit($id)
    {
        if(request()->ajax()) {
            $id = decode_id($id);
            $data = TeacherAchievement::where('id',$id)->with('teacher.studyProgram','academicYear')->first();

            return response()->json($data);
        } else {
            abot(404);
        }
    }

    public function update(Request $request)
    {
        if(request()->ajax()) {
            $id  = decode_id($request->_id);

            $request->validate([
                'id_ta'             => 'required',
                'prestasi'          => 'required',
                'tingkat_prestasi'  => 'required',
                'bukti_nama'        => 'required',
            ]);

            $acv                    = TeacherAchievement::find($id);
            $acv->nidn              = $request->nidn;
            $acv->id_ta             = $request->id_ta;
            $acv->prestasi          = $request->prestasi;
            $acv->tingkat_prestasi  = $request->tingkat_prestasi;
            $acv->bukti_nama        = $request->bukti_nama;

            $storagePath = 'upload/teacher/achievement/'.$acv->bukti_file;
            if($file = $request->file('bukti_file')) {

                if(File::exists($storagePath)) {
                    File::delete($storagePath);
                }

                $tgl_skrg = date('Y_m_d_H_i_s');
                $tujuan_upload = 'upload/teacher-achievement';
                $filename = $acv->nidn.'_'.str_replace(' ', '', $request->prestasi).'_'.$tgl_skrg.'.'.$file->getClientOriginalExtension();
                $file->move($tujuan_upload,$filename);
                $acv->bukti_file = $filename;
            }

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
            $q = TeacherAchievement::destroy($id);

            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                $this->delete_file($id);
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil dihapus',
                    'type'    => 'success'
                ]);
            }
        } else {
            return redirect()->route('teacher.achievement');
        }
    }

    public function download($filename)
    {
        $file = decode_id($filename);

        $storagePath = 'upload/teacher/achievement/'.$file;
        if( ! File::exists($storagePath)) {
            abort(404);
        } else {
            $mimeType = File::mimeType($storagePath);
            $headers = array(
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="'.$file.'"'
            );

            return response(file_get_contents($storagePath), 200, $headers);
        }
    }

    public function delete_file($id)
    {
        $data = TeacherAchievement::find($id);

        $storagePath = 'upload/teacher/achievement/'.$data->bukti_file;
        if(File::exists($storagePath)) {
            File::delete($storagePath);
        }
    }

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {

            $q   = TeacherAchievement::with([
                                        'teacher.studyProgram.department' => function($q) {
                                            $q->where('kd_jurusan',setting('app_department_id'));
                                        },
                                        'academicYear'
                                    ])
                                    ->whereHas(
                                        'teacher.studyProgram', function($query) {
                                            $query->where('kd_jurusan',setting('app_department_id'));
                                        }
                                    );

            if($request->kd_prodi){
                $q->whereHas(
                    'teacher.studyProgram', function($query) use ($request) {
                        $query->where('kd_prodi',$request->kd_prodi);
                });
            }

            $data = $q->orderBy('id_ta','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
