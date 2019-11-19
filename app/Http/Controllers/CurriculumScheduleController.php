<?php

namespace App\Http\Controllers;

use App\CurriculumSchedule;
use App\Curriculum;
use App\Teacher;
use App\AcademicYear;
use App\Faculty;
use App\StudyProgram;
use Illuminate\Http\Request;

class CurriculumScheduleController extends Controller
{
    public function index()
    {
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::has('curriculumSchedule')->orderBy('tahun_akademik','desc')->orderBy('semester','desc')->get();

        return view('academic.schedule.index',compact(['faculty','studyProgram','academicYear']));
    }

    public function create()
    {
        $faculty      = Faculty::all();

        return view('academic.schedule.form',compact(['faculty']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_ta'          => 'required',
            'nidn'           => 'required',
            'kd_matkul'      => 'required',
            'sesuai_bidang'  => 'nullable',
        ]);

        $dosen  = Teacher::find($request->nidn)->kd_prodi;
        $matkul = Curriculum::find($request->kd_matkul)->kd_prodi;

        if($dosen==$matkul){
            $sesuai_prodi = '1';
        } else {
            $sesuai_prodi = null;
        }

        $query                  = new CurriculumSchedule;
        $query->id_ta           = $request->id_ta;
        $query->nidn            = $request->nidn;
        $query->kd_matkul       = $request->kd_matkul;
        $query->sesuai_prodi    = $sesuai_prodi;
        $query->sesuai_bidang   = $request->has('sesuai_bidang') ? $request->sesuai_bidang : null;
        $query->save();

        return redirect()->route('academic.schedule')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function edit($id)
    {
        $id             = decode_id($id);
        $faculty        = Faculty::all();
        $data           = CurriculumSchedule::find($id);
        $studyProgram   = StudyProgram::where('kd_jurusan',$data->teacher->studyProgram->kd_jurusan)->get();
        $teacher        = Teacher::where('kd_prodi',$data->teacher->kd_prodi)->get();

        return view('academic.schedule.form',compact(['faculty','data','studyProgram','teacher']));
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $request->validate([
            'id_ta'          => 'required',
            'nidn'           => 'required',
            'kd_matkul'      => 'required',
            'sesuai_bidang'  => 'nullable',
        ]);

        $dosen  = Teacher::find($request->nidn)->kd_prodi;
        $matkul = Curriculum::find($request->kd_matkul)->kd_prodi;

        if($dosen==$matkul){
            $sesuai_prodi = '1';
        } else {
            $sesuai_prodi = null;
        }

        $query                  = CurriculumSchedule::find($id);
        $query->id_ta           = $request->id_ta;
        $query->nidn            = $request->nidn;
        $query->kd_matkul       = $request->kd_matkul;
        $query->sesuai_prodi    = $sesuai_prodi;
        $query->sesuai_bidang   = $request->has('sesuai_bidang') ? $request->sesuai_bidang : null;
        $query->save();

        return redirect()->route('academic.schedule')->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if(request()->ajax()) {
            $id = decode_id($request->id);
            dd($id);
            $q  = CurriculumSchedule::destroy($id);
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
            $q  = AcademicYear::with(
                                    'curriculumSchedule.academicYear',
                                    'curriculumSchedule.curriculum.studyProgram.department',
                                    'curriculumSchedule.teacher.studyProgram.department'
                                );

            if($request->kd_jurusan) {
                $callback = function($query) use($request) {
                    $query->where('kd_jurusan',$request->kd_jurusan);
                };

                $q->whereHas(
                    'curriculumSchedule.curriculum.studyProgram', $callback);
            }

            if($request->kd_prodi){

                $callback = function ($query) use ($request) {
                    $query->curriculumProdi($request->kd_prodi);
                };

                $q->with(['curriculumSchedule' => $callback]);

                // $q->scheduleCurriculumProdi($request->kd_prodi);

            }

            $data = $q->get();

            return response()->json($data);

        } else {
            abort(404);
        }
    }
}
