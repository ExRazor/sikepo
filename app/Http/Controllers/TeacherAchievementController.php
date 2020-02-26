<?php

namespace App\Http\Controllers;

use App\StudyProgram;
use App\TeacherAchievement;
use App\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class TeacherAchievementController extends Controller
{
    public function __construct()
    {
        $method = [
            'create',
            'edit',
            'store',
            'update',
            'destroy',
            'delete_file',
        ];

        $this->middleware('role:admin,kaprodi', ['only' => $method]);
    }

    public function index()
    {
        $studyProgram   = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $teacher = null;

        if(Auth::user()->role=='kaprodi') {

            $achievement    = TeacherAchievement::whereHas(
                                                    'teacher.studyProgram',function($query) {
                                                        $query->where('kd_prodi',Auth::user()->kd_prodi);
                                                    }
                                                )
                                                ->orderBy('id_ta','desc')->get();
        } else {
            $achievement    = TeacherAchievement::whereHas(
                                                    'teacher.studyProgram',function($query) {
                                                        $query->where('kd_jurusan',setting('app_department_id'));
                                                    }
                                                )
                                                ->orderBy('id_ta','desc')->get();
        }


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
                'bukti_file'        => 'required|mimes:jpeg,jpg,png,pdf,zip',

            ]);

            $acv                    = new TeacherAchievement;
            $acv->nidn              = $request->nidn;
            $acv->id_ta             = $request->id_ta;
            $acv->prestasi          = $request->prestasi;
            $acv->tingkat_prestasi  = $request->tingkat_prestasi;
            $acv->bukti_nama        = $request->bukti_nama;

            if($file = $request->file('bukti_file')) {
                $tgl_skrg = date('Y_m_d_H_i_s');
                $tujuan_upload = public_path('upload/teacher/achievement');
                $filename = $acv->nidn.'_'.str_replace(' ', '', $request->bukti_nama).'_'.$tgl_skrg.'.'.$file->getClientOriginalExtension();
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
            abort(404);
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
                'bukti_file'        => 'mimes:jpeg,jpg,png,pdf,zip',
            ]);

            $acv                    = TeacherAchievement::find($id);
            $acv->nidn              = $request->nidn;
            $acv->id_ta             = $request->id_ta;
            $acv->prestasi          = $request->prestasi;
            $acv->tingkat_prestasi  = $request->tingkat_prestasi;
            $acv->bukti_nama        = $request->bukti_nama;

            //Bukti File
            $storagePath = public_path('upload/teacher/achievement/'.$acv->bukti_file);
            $tgl_skrg = date('Y_m_d_H_i_s');
            if($file = $request->file('bukti_file')) {
                if(File::exists($storagePath)) {
                    File::delete($storagePath);
                }

                $tujuan_upload = public_path('upload/teacher/achievement');
                $filename = $request->nidn.'_'.str_replace(' ', '', $request->bukti_nama).'_'.$tgl_skrg.'.'.$file->getClientOriginalExtension();
                $file->move($tujuan_upload,$filename);
                $acv->bukti_file = $filename;
            } else {
                $ekstensi = File::extension($storagePath);
                $filename = $request->nidn.'_'.str_replace(' ', '', $request->bukti_nama).'_'.$tgl_skrg.'.'.$ekstensi;
                File::move($storagePath,public_path('upload/teacher/achievement/'.$filename));
                $acv->bukti_file = $filename;
            }

            //Simpan
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
            $id     = decode_id($request->_id);
            $data   = TeacherAchievement::find($id);
            $q      = $data->delete();

            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                $this->delete_file($data->bukti_file);
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

        $storagePath = public_path('upload/teacher/achievement/'.$file);
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

    public function delete_file($file)
    {
        $storagePath = public_path('upload/teacher/achievement/'.$file);

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
