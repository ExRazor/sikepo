<?php

namespace App\Http\Controllers;

use App\StudyProgram;
use App\TeacherAchievement;
use Illuminate\Http\Request;
use File;

class TeacherAchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $achievement    = TeacherAchievement::whereHas(
                                                'teacher.studyProgram',function($query) {
                                                    $query->where('kd_jurusan',setting('app_department_id'));
                                                }
                                            )
                                            ->orderBy('tanggal','desc')->get();
        $studyProgram   = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('teacher-achievement.index',compact(['achievement','studyProgram']));
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
        if(request()->ajax()) {
            $request->validate([
                'nidn'                  => 'required',
                'prestasi'              => 'required',
                'tingkat_prestasi'      => 'required',
                'tanggal_dicapai'       => 'required|date',
                'bukti_pendukung'       => 'required',
            ]);

            $acv                    = new TeacherAchievement;
            $acv->nidn              = $request->nidn;
            $acv->prestasi          = $request->prestasi;
            $acv->tingkat_prestasi  = $request->tingkat_prestasi;
            $acv->tanggal           = $request->tanggal_dicapai;

            if($file = $request->file('bukti_pendukung')) {
                $tgl_skrg = date('Y_m_d_H_i_s');
                $tujuan_upload = 'upload/teacher-achievement';
                $filename = $acv->nidn.'_'.str_replace(' ', '', $request->prestasi).'_'.$tgl_skrg.'.'.$file->getClientOriginalExtension();
                $file->move($tujuan_upload,$filename);
                $acv->bukti_pendukung = $filename;
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

    /**
     * Display the specified resource.
     *
     * @param  \App\TeacherAchievement  $teacherAchievement
     * @return \Illuminate\Http\Response
     */
    public function show(TeacherAchievement $teacherAchievement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TeacherAchievement  $teacherAchievement
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(request()->ajax()) {
            $id = decrypt($id);
            $data = TeacherAchievement::where('id',$id)->with('teacher.studyProgram')->first();

            return response()->json($data);
        } else {
            return "Hehe";
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TeacherAchievement  $teacherAchievement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(request()->ajax()) {
            $id  = decrypt($request->_id);

            $request->validate([
                'prestasi'              => 'required',
                'tingkat_prestasi'      => 'required',
                'tanggal_dicapai'       => 'required|date',
            ]);

            $acv                    = TeacherAchievement::find($id);
            $acv->nidn              = $request->nidn;
            $acv->prestasi          = $request->prestasi;
            $acv->tingkat_prestasi  = $request->tingkat_prestasi;
            $acv->tanggal           = $request->tanggal_dicapai;

            $storagePath = 'upload/teacher-achievement/'.$acv->bukti_pendukung;
            if($file = $request->file('bukti_pendukung')) {

                if(File::exists($storagePath)) {
                    File::delete($storagePath);
                }

                $tgl_skrg = date('Y_m_d_H_i_s');
                $tujuan_upload = 'upload/teacher-achievement';
                $filename = $acv->nidn.'_'.str_replace(' ', '', $request->prestasi).'_'.$tgl_skrg.'.'.$file->getClientOriginalExtension();
                $file->move($tujuan_upload,$filename);
                $acv->bukti_pendukung = $filename;
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TeacherAchievement  $teacherAchievement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(request()->ajax()) {
            $id = decrypt($request->_id);

            $q = TeacherAchievement::destroy($id);
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
            return redirect()->route('teacher.achievement');
        }
    }

    public function download($filename)
    {
        $file = decrypt($filename);

        $storagePath = 'upload/teacher-achievement/'.$file;
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
}
