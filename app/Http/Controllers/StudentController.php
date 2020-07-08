<?php

namespace App\Http\Controllers;

use App\Student;
use App\StudentStatus;
use App\Faculty;
use App\StudyProgram;
use App\AcademicYear;
use App\Research;
use App\CommunityService;
use App\StudentAchievement;
use App\Minithesis;
use App\Imports\StudentImport;
use App\StudentPublication;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StudentController extends Controller
{
    public function __construct()
    {
        $method = [
            'create',
            'edit',
            'store',
            'import',
            'update',
            'destroy',
            'upload_photo',
            'delete_photo',
        ];

        $this->middleware('role:admin,kaprodi', ['only' => $method]);
    }

    public function index()
    {
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $angkatan     = AcademicYear::groupBy('tahun_akademik')->orderBy('tahun_akademik','desc')->get('tahun_akademik');
        $status       = StudentStatus::groupBy('status')->get('status');

        // $data = datatables()->of($students)->make(true);
        return view('student.index',compact(['studyProgram','faculty','angkatan','status']));
    }

    public function show($id)
    {
        $id = decode_id($id);

        $academicYear = AcademicYear::orderBy('tahun_akademik','desc')->orderBy('semester','desc')->get();
        $data       = Student::with('studyProgram','studentForeign')->where('nim',$id)->first();
        $status     = StudentStatus::where('nim',$data->nim)->orderBy('id_ta','desc')->orderBy('id','desc')->first();
        $statusList = StudentStatus::where('nim',$data->nim)->orderBy('id','asc')->get();
        $achievement = StudentAchievement::where('nim',$data->nim)->orderBy('id_ta','desc')->get();
        $minithesis = Minithesis::where('nim',$data->nim)->orderBy('id_ta','desc')->get();

        if($status){
            if($status->status == 'Aktif') {
                $status->setAttribute('status_button','btn-success');
            } else if($status->status=='Lulus') {
                $status->setAttribute('status_button','btn-pink');
            } else {
                $status->setAttribute('status_button','btn-danger');
            }
        }

        $research       = Research::whereHas(
                                        'researchStudent', function($q1) use ($data) {
                                            $q1->where('nim',$data->nim);
                                        }
                                    )
                                    ->orderBy('id_ta','desc')
                                    ->get();

        $service        = CommunityService::whereHas(
                                        'serviceStudent', function($q1) use ($data) {
                                            $q1->where('nim',$data->nim);
                                        }
                                    )
                                    ->orderBy('id_ta','desc')
                                    ->get();

        $publication        = StudentPublication::whereHas(
                                                'student', function($q1) use ($data) {
                                                    $q1->where('nim',$data->nim);
                                                }
                                            )
                                            ->orderBy('tahun','desc')
                                            ->get();

        return view('student.profile',compact(['data','status','statusList','academicYear','achievement','minithesis','research','service','publication']));
    }

    public function create()
    {
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::orderBy('tahun_akademik','desc')->orderBy('semester','desc')->get();

        return view('student/form',compact(['faculty','studyProgram','academicYear']));
    }

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

    public function store(Request $request)
    {
        $request->validate([
            'nim'               => 'required|numeric|min:9',
            'kd_prodi'          => 'required',
            'nama'              => 'required',
            'tpt_lhr'           => 'nullable',
            'tgl_lhr'           => 'nullable',
            'jk'                => 'required',
            'agama'             => 'nullable',
            'alamat'            => 'nullable',
            'kewarganegaraan'   => 'required',
            'kelas'             => 'nullable',
            'tipe'              => 'nullable',
            'program'           => 'nullable',
            'seleksi_jenis'     => 'nullable',
            'seleksi_jalur'     => 'nullable',
            'status_masuk'      => 'nullable',
            'tahun_masuk'       => 'required',
        ]);

        $query                  = new Student;
        $query->kd_prodi        = $request->kd_prodi;
        $query->nim             = $request->nim;
        $query->nama            = $request->nama;
        $query->tpt_lhr         = $request->tpt_lhr;
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

        $status         = new StudentStatus;
        $status->nim    = $request->nim;
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
		$file->move(public_path('upload/student/excel_import/',$nama_file));

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
            File::delete(public_path('/upload/student/excel_import/'.$nama_file));
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil diimpor',
                'type'    => 'success'
            ]);
        }
	}

    public function update(Request $request)
    {
        $id = decrypt($request->_id);

        $request->validate([
            'kd_prodi'          => 'required',
            'nama'              => 'required',
            'tpt_lhr'           => 'nullable',
            'tgl_lhr'           => 'nullable',
            'jk'                => 'required',
            'agama'             => 'required',
            'alamat'            => 'nullable',
            'kewarganegaraan'   => 'required',
            'kelas'             => 'nullable',
            'tipe'              => 'nullable',
            'program'           => 'nullable',
            'seleksi_jenis'     => 'nullable',
            'seleksi_jalur'     => 'nullable',
            'status_masuk'      => 'nullable',
            'tahun_masuk'       => 'required',
        ]);

        $query                  = Student::find($id);
        $query->kd_prodi        = $request->kd_prodi;
        $query->nama            = $request->nama;
        $query->tpt_lhr         = $request->tpt_lhr;
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

        return redirect()->route('student.list.show',encode_id($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if(request()->ajax()) {
            $id     = decode_id($request->id);
            $data   = Student::find($id);
            $q      = $data->delete();
            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                $this->delete_photo($data->foto);
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
                'foto'        => 'required|mimes:jpeg,jpg,png',
            ]);

            $data = Student::find($id);

            $storagePath = public_path('upload/student/'.$data->foto);
            if($storagePath || $request->file('foto')) {
                if(File::exists($storagePath)) {
                    File::delete($storagePath);
                }
                $file = $request->file('foto');
                $tgl_skrg = date('Y_m_d_H_i_s');
                $tujuan_upload = public_path('upload/student');
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

    public function delete_photo($file)
    {
        $storagePath = public_path('upload/student/'.$file);
        if(File::exists($storagePath)) {
            File::delete($storagePath);
        }

    }

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {

            $q  = Student::with('studyProgram.department.faculty','latestStatus');

            if($request->kd_jurusan) {
                $q->whereHas(
                    'studyProgram', function($query) use($request) {
                        $query->where('kd_jurusan',$request->kd_jurusan);
                    });
            }

            if(Auth::user()->hasRole('kaprodi')) {
                $q->where('kd_prodi',Auth::user()->kd_prodi);
            }

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

            $data = $q->orderBy('nim','asc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function datatable(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        if(Auth::user()->hasRole('kaprodi')) {
            $data     = Student::whereHas(
                            'studyProgram', function($query) {
                                $query->where('kd_prodi',Auth::user()->kd_prodi);
                            }
                        );
        } else {
            $data     = Student::whereHas(
                            'studyProgram', function($query) {
                                $query->where('kd_jurusan',setting('app_department_id'));
                            }
                        );
        }

        if($request->prodi) {
            $data->where('kd_prodi',$request->prodi);
        }

        if($request->angkatan) {
            $data->where('angkatan',$request->angkatan);
        }

        if($request->status) {
            $data->whereHas('latestStatus', function($q) use($request) {
                $q->where('status','LIKE',$request->status);
            });
        }

        return DataTables::of($data->get())
                            ->editColumn('nama', function($d) {
                                return '<a href="'.route("student.list.show",encode_id($d->nim)).'">'.$d->nama.'<br><small>NIM. '.$d->nim.'</small></a>';
                            })
                            ->editColumn('study_program', function($d){
                                return $d->studyProgram->nama.'<br><small>'.$d->studyProgram->department->faculty->singkatan.' - '.$d->studyProgram->department->nama.'</small>';
                            })
                            ->addColumn('status', function($d) {
                                return $d->latestStatus->status;
                            })
                            ->addColumn('aksi', function($d) {
                                if(!Auth::user()->hasRole('kajur')) {
                                    return view('student.table-button', compact('d'))->render();
                                }
                            })
                            ->rawColumns(['nama','study_program','aksi'])
                            ->make();
    }

    public function loadData(Request $request)
    {
        if($request->ajax()) {
            if($request->has('cari')){
                $prodi = $request->prodi;
                $cari = $request->cari;

                $q = Student::select('nim','nama');

                if($prodi) {
                    $q->where('kd_prodi',$prodi);
                }

                if($cari) {
                    $q->where(function($query) use($cari) {
                        $query->where('nim', 'LIKE', '%'.$cari.'%')->orWhere('nama','LIKE','%'.$cari.'%');
                    });
                }

                $data = $q->get();

                $response = array();
                foreach($data as $d){
                    $response[] = array(
                        "id"    => $d->nim,
                        "text"  => $d->nama.' ('.$d->nim.')'
                    );
                }
                return response()->json($response);
            }
        } else {
            abort(404);
        }
    }

    public function select_by_studyProgram(Request $request)
    {
        if($request->ajax()) {
            $prodi  = $request->prodi;
            $cari   = $request->cari;

            $query  = Student::where('kd_prodi',$prodi);

            if($cari) {
                $query->where(function($q) use ($cari) {
                    $q->where('nama', 'LIKE', '%'.$cari.'%')->orWhere('nim', 'LIKE', '%'.$cari.'%');
                });
            }

            $data = $query->get();

            $response = array();
            foreach($data as $d){
                $response[] = array(
                    "id"    => $d->nim,
                    "text"  => $d->nama.' ('.$d->nim.')'
                );
            }

            return response()->json($response);
        } else {
            abort(404);
        }
    }

    public function get_by_studyProgram(Request $request)
    {
        if($request->ajax()) {

            $data = Student::where('kd_prodi',$request->kd_prodi)->select('nim','nama')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
