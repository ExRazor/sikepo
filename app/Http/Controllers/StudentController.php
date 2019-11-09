<?php

namespace App\Http\Controllers;

use App\Student;
use App\StudentStatus;
use App\Faculty;
use App\StudyProgram;
use App\AcademicYear;
use App\Imports\StudentImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use File;
use DataTables;

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
        $angkatan     = AcademicYear::groupBy('tahun_akademik')->orderBy('tahun_akademik','desc')->get('tahun_akademik');
        $status       = StudentStatus::groupBy('status')->get('status');
        $students     = Student::whereHas(
                            'studyProgram', function($query) {
                                $query->where('kd_jurusan',setting('app_department_id'));
                            })
                        ->get();

        $data = datatables()->of($students)->make(true);
        return view('student.index',compact(['studyProgram','faculty','angkatan','status','data']));
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
            'tpt_lhr'           => 'required',
            'tgl_lhr'           => 'required',
            'jk'                => 'required',
            'agama'             => 'required',
            'alamat'            => 'required',
            'kewarganegaraan'   => 'required',
            'kelas'             => 'required',
            'tipe'              => 'required',
            'seleksi_jenis'     => 'required',
            'seleksi_jalur'     => 'required',
            'status_masuk'      => 'required',
            'tahun_masuk'       => 'required',
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
        $query->status_masuk    = $request->status_masuk;
        $query->angkatan        = AcademicYear::find($request->tahun_masuk)->tahun_akademik;
        $query->status          = $request->status;
        $query->save();

        $status         = new StudentStatus;
        $status->id_ta  = $request->tahun_masuk;
        $status->status = 'Aktif';
        $status->save();

        return redirect()->route('student')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
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
		$file->move('upload/student/excel_import/',$nama_file);

		// import data
        $q = Excel::import(new StudentImport, public_path('/upload/student/excel_import/'.$nama_file));

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
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function profile($id)
    {
        $id = decode_id($id);

        $data       = Student::with('studyProgram','academicYear')->where('nim',$id)->first();
        $status     = StudentStatus::where('nim',$data->nim)->orderBy('id_ta','desc')->orderBy('id','desc')->first();
        $statusList = StudentStatus::where('nim',$data->nim)->orderBy('id','asc')->get();
        $academicYear = AcademicYear::orderBy('tahun_akademik','desc')->orderBy('semester','desc')->get();

        if($status->status == 'Aktif') {
            $status->setAttribute('status_button','btn-success');
        } else if($status->status=='Lulus') {
            $status->setAttribute('status_button','btn-pink');
        } else {
            $status->setAttribute('status_button','btn-danger');
        }

        return view('student.profile',compact(['data','status','statusList','academicYear']));
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
        $status       = StudentStatus::where('nim',$nim)->orderBy('id','asc')->first();

        return view('student/form',compact(['faculty','studyProgram','academicYear','data','status']));
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
            'tpt_lhr'           => 'required',
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
            'status_masuk'      => 'required',
            'tahun_masuk'       => 'required',
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
        $query->status_masuk    = $request->status_masuk;
        $query->angkatan        = AcademicYear::find($request->tahun_masuk)->tahun_akademik;
        $query->save();

        $status        = StudentStatus::where('nim',$id)->orderBy('id','asc')->first();
        $status->id_ta = $request->tahun_masuk;
        $status->save();

        return redirect()->route('student.profile',encode_id($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
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

    public function upload_photo(Request $request)
    {
        if(request()->ajax()) {
            $id = decrypt($request->_id);

            $request->validate([
                'foto'        => 'required',
            ]);

            $data = Student::find($id);

            $storagePath = 'upload/student/'.$data->foto;
            if($storagePath || $request->file('foto')) {
                if(File::exists($storagePath)) {
                    File::delete($storagePath);
                }
                $file = $request->file('foto');
                $tgl_skrg = date('Y_m_d_H_i_s');
                $tujuan_upload = 'upload/student';
                $filename = $id.'_'.str_replace(' ', '', $data->nama).'_'.$tgl_skrg.'.'.$file->getClientOriginalExtension();
                $file->move($tujuan_upload,$filename);
                $data->foto = $filename;
            }
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
                    'message' => 'Foto profil berhasil diubah',
                    'type'    => 'success'
                ]);
            }
        }
    }

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {

            $q  = Student::with('studyProgram.department.faculty','latestStatus')
                        ->whereHas(
                            'studyProgram.department', function($query) {
                                $query->where('kd_jurusan',setting('app_department_id'));
                            }
                        );

            if($request->kd_prodi){
                $q->where('kd_prodi',$request->kd_prodi);
            }

            if($request->angkatan) {
                $q->where('angkatan',$request->angkatan);
            }

            if($request->status) {
                $q->whereHas(
                    'latestStatus', function($query) use ($request) {
                        $query->where('status',$request->status);
                });
            }

            $data = $q->orderBy('created_at','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function datatable(Request $request)
    {
        if($request->ajax()) {
            $students     = Student::whereHas(
                            'studyProgram', function($query) {
                                $query->where('kd_jurusan',setting('app_department_id'));
                            })
                            ->get();

            return DataTables::of($students)
                                ->editColumn('nama', function($d) {
                                    return '<a href="'.route("student.profile",encode_id($d->nim)).'">'.$d->nama.'<br><small>NIM. '.$d->nim.'</small></a>';
                                })
                                ->editColumn('study_program', function($d){
                                    return '<td>'.$d->studyProgram->nama.'<br><small>'.$d->studyProgram->department->faculty->singkatan.' - '.$d->studyProgram->department->nama.'</small></td>';
                                })
                                ->addColumn('status', function($d) {
                                    return $d->latestStatus->status;
                                })
                                ->addColumn('aksi', function($d) {
                                    return view('student.table-button', compact('d'))->render();
                                })
                                ->escapeColumns([])
                                ->make(true);
        }
    }
}
