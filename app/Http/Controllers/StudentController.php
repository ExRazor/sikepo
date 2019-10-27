<?php

namespace App\Http\Controllers;

use App\Student;
use App\Faculty;
use App\StudyProgram;
use App\AcademicYear;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $faculty      = Faculty::all();
        $angkatan     = AcademicYear::groupBy('tahun_akademik')->orderBy('tahun_akademik','desc')->get('tahun_akademik');
        $data         = Student::whereHas(
                            'studyProgram', function($query) {
                                $query->where('kd_jurusan',setting('app_department_id'));
                            })
                        ->get();

        return view('student.index',compact(['studyProgram','faculty','angkatan','data']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::orderBy('tahun_akademik','desc')->orderBy('semester','desc')->get();

        return view('student/form',compact(['faculty','studyProgram','academicYear']));
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
            'nim'               => 'required|numeric|min:9',
            'kd_prodi'          => 'required',
            'nama'              => 'required',
            'tgl_lhr'           => 'required',
            'jk'                => 'required',
            'agama'             => 'required',
            'alamat'            => 'required',
            'kewarganegaraan'   => 'required',
            'kelas'             => 'required',
            'tipe'              => 'required',
            'seleksi_jenis'     => 'required',
            'seleksi_jalur'     => 'required',
            'masuk_status'      => 'required',
            'masuk_ta'          => 'required',
            'status'            => 'required',
        ]);

        $query                  = new Student;
        $query->kd_prodi        = $request->kd_prodi;
        $query->nim             = $request->nim;
        $query->nama            = $request->nama;
        $query->tgl_lhr         = $request->tgl_lhr;
        $query->jk              = $request->jk;
        $query->agama           = $request->agama;
        $query->alamat          = $request->alamat;
        $query->kewarganegaraan = $request->kewarganegaraan;
        $query->kelas           = $request->kelas;
        $query->tipe            = $request->tipe;
        $query->program         = $request->program;
        $query->seleksi_jenis   = $request->seleksi_jenis;
        $query->seleksi_jalur   = $request->seleksi_jalur;
        $query->masuk_status    = $request->masuk_status;
        $query->masuk_ta        = $request->masuk_ta;
        $query->angkatan        = AcademicYear::find($request->masuk_ta)->tahun_akademik;
        $query->status          = $request->status;
        $query->save();

        return redirect()->route('student')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $nim          = decode_id($id);
        $data         = Student::where('nim',$nim)->first();
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',$data->studyProgram->kd_jurusan)->get();
        $academicYear = AcademicYear::orderBy('tahun_akademik','desc')->orderBy('semester','desc')->get();

        return view('student/form',compact(['faculty','studyProgram','academicYear','data']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = decrypt($request->_id);
        $request->validate([
            'kd_prodi'          => 'required',
            'nama'              => 'required',
            'tgl_lhr'           => 'required',
            'jk'                => 'required',
            'agama'             => 'required',
            'alamat'            => 'required',
            'kewarganegaraan'   => 'required',
            'kelas'             => 'required',
            'tipe'              => 'required',
            'program'           => 'required',
            'seleksi_jenis'     => 'required',
            'seleksi_jalur'     => 'required',
            'masuk_status'      => 'required',
            'masuk_ta'          => 'required',
            'status'            => 'required',
        ]);

        $query                  = Student::find($id);
        $query->kd_prodi        = $request->kd_prodi;
        $query->nama            = $request->nama;
        $query->tgl_lhr         = $request->tgl_lhr;
        $query->jk              = $request->jk;
        $query->agama           = $request->agama;
        $query->alamat          = $request->alamat;
        $query->kewarganegaraan = $request->kewarganegaraan;
        $query->kelas           = $request->kelas;
        $query->tipe            = $request->tipe;
        $query->program         = $request->program;
        $query->seleksi_jenis   = $request->seleksi_jenis;
        $query->seleksi_jalur   = $request->seleksi_jalur;
        $query->masuk_status    = $request->masuk_status;
        $query->masuk_ta        = $request->masuk_ta;
        $query->angkatan        = AcademicYear::find($request->masuk_ta)->tahun_akademik;
        $query->status          = $request->status;
        $query->save();

        return redirect()->route('student')->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(request()->ajax()) {
            $id = decode_id($request->id);
            $q  = Student::destroy($id);
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

            $q = Student::with(['studyProgram.department.faculty']);

            if($request->kd_jurusan) {
                $q->whereHas(
                    'studyProgram', function($query) use($request) {
                        $query->where('kd_jurusan',$request->kd_jurusan);
                    });
            }

            if($request->kd_prodi){
                $q->where('kd_prodi',$request->kd_prodi);
            }

            if($request->angkatan) {
                $q->where('angkatan',$request->angkatan);
            }

            $data = $q->orderBy('created_at','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
