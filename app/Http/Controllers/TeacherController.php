<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\StudyProgram;
use App\Models\AcademicYear;
use App\Models\Ewmp;
use App\Models\Faculty;
use App\Models\CurriculumSchedule;
use App\Models\TeacherAchievement;
use App\Models\Research;
use App\Models\CommunityService;
use App\Models\Minithesis;
use App\Models\User;
use App\Imports\TeacherImport;
use App\Exports\TeacherExport;
use App\Http\Requests\TeacherRequest;
use App\Models\TeacherPublication;
use App\Models\TeacherStatus;
use App\Traits\LogActivity;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class TeacherController extends Controller
{
    use LogActivity;

    public function __construct()
    {
        $method = [
            'create',
            'edit',
            'import'
        ];

        $this->middleware('role:admin,kaprodi', ['only' => $method]);
    }

    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan', setting('app_department_id'))->get();
        $faculty      = Faculty::all();

        if (Auth::user()->hasRole('kaprodi')) {
            $data         = Teacher::whereHas(
                'latestStatus.studyProgram',
                function ($query) {
                    $query->where('kd_prodi', Auth::user()->kd_prodi);
                }
            )
                ->get();
        } else {
            $data         = Teacher::whereHas(
                'latestStatus.studyProgram',
                function ($query) {
                    $query->where('kd_jurusan', setting('app_department_id'));
                }
            )
                ->get();
        }

        return view('teacher/index', compact(['studyProgram', 'faculty', 'data']));
    }

    public function index_teacher()
    {
        $nidn         = Auth::user()->username;
        $data         = Teacher::where('nidn', $nidn)->first();
        $studyProgram = StudyProgram::where('kd_jurusan', setting('app_department_id'))->get();


        $bidang       = json_decode($data->bidang_ahli);
        $data->bidang_ahli   = implode(', ', $bidang);

        return view('teacher-view.profile', compact(['data', 'studyProgram']));
    }

    public function show($nidn)
    {
        // $nidn = decode_id($nidn);
        $data = Teacher::where('nidn', $nidn)->first();

        if (!isset($data) || (Auth::user()->hasRole('kaprodi') && Auth::user()->kd_prodi != $data->latestStatus->studyProgram->kd_prodi)) {
            return redirect(route('teacher.list.index'));
        }

        $data->bidang_ahli = json_decode($data->bidang_ahli);

        $academicYear   = AcademicYear::orderBy('tahun_akademik', 'desc')->orderBy('semester', 'desc')->get();
        $tahun          = AcademicYear::where('semester', 'Ganjil')->orderBy('tahun_akademik', 'desc')->get();
        $studyProgram   = StudyProgram::where('kd_jurusan', setting('app_department_id'))->get();
        $status         = TeacherStatus::where('nidn', $data->nidn)->get();
        $schedule       = CurriculumSchedule::where('nidn', $data->nidn)->orderBy('kd_matkul', 'asc')->get();
        $minithesis     = Minithesis::where('pembimbing_utama', $data->nidn)->orWhere('pembimbing_pendamping', $data->nidn)->orderBy('id_ta', 'desc')->get();
        $ewmp           = Ewmp::where('nidn', $data->nidn)->orderBy('id_ta', 'desc')->get();
        $achievement    = TeacherAchievement::where('nidn', $data->nidn)->orderBy('id_ta', 'desc')->get();

        $research       = Research::with([
            'researchTeacher' => function ($q1) use ($data) {
                $q1->where('nidn', $data->nidn);
            }
        ])
            ->whereHas(
                'researchTeacher',
                function ($q1) use ($data) {
                    $q1->where('nidn', $data->nidn);
                }
            )
            ->orderBy('id_ta', 'desc')
            ->get();

        $service        = CommunityService::with([
            'serviceTeacher' => function ($q1) use ($data) {
                $q1->where('nidn', $data->nidn);
            }
        ])
            ->whereHas(
                'serviceTeacher',
                function ($q1) use ($data) {
                    $q1->where('nidn', $data->nidn);
                }
            )
            ->orderBy('id_ta', 'desc')
            ->get();

        $publication    = TeacherPublication::whereHas(
            'teacher',
            function ($q1) use ($data) {
                $q1->where('nidn', $data->nidn);
            }
        )
            ->orderBy('id_ta', 'desc')
            ->get();

        return view('teacher/profile', compact(['data', 'academicYear', 'tahun', 'studyProgram', 'status', 'schedule', 'ewmp', 'achievement', 'minithesis', 'research', 'service', 'publication']));
    }

    public function create()
    {
        $faculty = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan', setting('app_department_id'))->get();

        return view('teacher/form', compact(['faculty', 'studyProgram']));
    }

    public function edit($nidn)
    {
        // $nidn          = decode_id($nidn);
        $data         = Teacher::where('nidn', $nidn)->first();
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan', $data->latestStatus->studyProgram->kd_jurusan)->get();

        $bidang = json_decode($data->bidang_ahli);
        $data->bidang_ahli   = implode(', ', $bidang);

        return view('teacher/form', compact(['data', 'faculty', 'studyProgram']));
    }

    public function store(TeacherRequest $request)
    {
        DB::beginTransaction();
        try {
            //Bidang Keahlian
            $bidang_ahli = explode(", ", $request->bidang_ahli);

            //Query Dosen
            $Teacher                            = new Teacher;
            $Teacher->nidn                      = $request->nidn;
            $Teacher->nip                       = $request->nip;
            $Teacher->nama                      = $request->nama;
            $Teacher->jk                        = $request->jk;
            $Teacher->agama                     = $request->agama;
            $Teacher->tpt_lhr                   = $request->tpt_lhr;
            $Teacher->tgl_lhr                   = $request->tgl_lhr;
            $Teacher->alamat                    = $request->alamat;
            $Teacher->no_telp                   = $request->no_telp;
            $Teacher->email                     = $request->email;
            $Teacher->pend_terakhir_jenjang     = $request->pend_terakhir_jenjang;
            $Teacher->pend_terakhir_jurusan     = $request->pend_terakhir_jurusan;
            $Teacher->bidang_ahli               = json_encode($bidang_ahli);
            $Teacher->status_kerja              = $request->status_kerja;
            $Teacher->jabatan_akademik          = $request->jabatan_akademik;
            $Teacher->sertifikat_pendidik       = $request->sertifikat_pendidik;
            $Teacher->sesuai_bidang_ps          = $request->sesuai_bidang_ps;

            //Upload Foto
            if ($request->file('foto')) {
                $file = $request->file('foto');
                $tujuan_upload = storage_path('app/upload/teacher');
                $filename = $request->nidn . '_' . str_replace(' ', '', $request->nama) . '.' . $file->getClientOriginalExtension();
                $file->move($tujuan_upload, $filename);
                $Teacher->foto = $filename;
            }

            //Simpan Data Dosen
            $Teacher->save();

            //Status Dosen
            $status             = new TeacherStatus;
            $status->nidn       = $Teacher->nidn;
            $status->kd_prodi   = $request->kd_prodi;
            $status->periode    = $request->periode_prodi;
            $status->jabatan    = 'Dosen';
            $status->is_active  = false;
            $status->save();
            $this->setStatus($Teacher->nidn);

            //Buat User Dosen
            $user               = new User;
            $user->username     = $Teacher->nidn;
            $user->password     = Hash::make($Teacher->nidn);
            $user->role         = 'dosen';
            $user->defaultPass  = 1;
            $user->name         = $Teacher->nama;
            $user->foto         = $Teacher->foto;
            $user->save();

            //Activity Log
            $property = [
                'id'    => $Teacher->nidn,
                'name'  => $Teacher->nama . ' (' . $Teacher->nidn . ')',
                'url'   => route('teacher.list.show', $Teacher->nidn),
            ];
            $this->log('created', 'Dosen', $property);

            DB::commit();
            return redirect()->route('teacher.list.index')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }
    }

    public function update(TeacherRequest $request)
    {
        DB::beginTransaction();
        try {
            //Decrypt ID
            $id  = decrypt($request->_id);

            //Bidang Keahlian
            $bidang_ahli = explode(", ", $request->bidang_ahli);

            //Query Dosen
            $Teacher                            = Teacher::find($id);
            $Teacher->nip                       = $request->nip;
            $Teacher->nama                      = $request->nama;
            $Teacher->jk                        = $request->jk;
            $Teacher->agama                     = $request->agama;
            $Teacher->tpt_lhr                   = $request->tpt_lhr;
            $Teacher->tgl_lhr                   = $request->tgl_lhr;
            $Teacher->alamat                    = $request->alamat;
            $Teacher->no_telp                   = $request->no_telp;
            $Teacher->email                     = $request->email;
            $Teacher->pend_terakhir_jenjang     = $request->pend_terakhir_jenjang;
            $Teacher->pend_terakhir_jurusan     = $request->pend_terakhir_jurusan;
            $Teacher->bidang_ahli               = json_encode($bidang_ahli);
            $Teacher->status_kerja              = $request->status_kerja;
            $Teacher->jabatan_akademik          = $request->jabatan_akademik;
            $Teacher->sertifikat_pendidik       = $request->sertifikat_pendidik;
            $Teacher->sesuai_bidang_ps          = $request->sesuai_bidang_ps;

            //Upload Foto
            $storagePath = storage_path('app/upload/teacher/' . $Teacher->foto);
            if ($request->file('foto')) {
                if (File::exists($storagePath)) {
                    File::delete($storagePath);
                }
                $file = $request->file('foto');
                $tujuan_upload = storage_path('app/upload/teacher');
                $filename = $Teacher->nidn . '_' . str_replace(' ', '', $Teacher->nama) . '.' . $file->getClientOriginalExtension();
                $file->move($tujuan_upload, $filename);
                $Teacher->foto = $filename;
            }

            //Simpan Data Dosen
            $Teacher->save();

            //Status Dosen
            TeacherStatus::where('nidn', $id)->update(['nidn' => $Teacher->nidn]);
            $status             = TeacherStatus::where('nidn', $Teacher->nidn)->orderBy('periode', 'desc')->first();
            $status->nidn       = $Teacher->nidn;
            $status->kd_prodi   = $request->kd_prodi;
            $status->periode    = $request->periode_prodi;
            $status->jabatan    = 'Dosen';
            $status->is_active  = false;
            $status->save();
            $this->setStatus($Teacher->nidn);

            //Update User Dosen
            $user          = User::where('username', $id)->first();
            $user->name    = $Teacher->nama;
            $user->foto    = $Teacher->foto;
            $user->save();

            //Activity Log
            $property = [
                'id'    => $Teacher->nidn,
                'name'  => $Teacher->nama . ' (' . $Teacher->nidn . ')',
                'url'   => route('teacher.list.show', $Teacher->nidn),
            ];
            $this->log('updated', 'Dosen', $property);

            DB::commit();

            if (Auth::user()->hasRole('dosen')) {
                return redirect()->route('profile.biodata')->with('flash.message', 'Biodata berhasil diperbarui!')->with('flash.class', 'success');
            } else {
                return redirect()->route('teacher.list.show', $Teacher->nidn)->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }
    }

    public function destroy(Request $request)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Decrypt ID
            $id     = decrypt($request->id);

            //Query Hapus Dosen
            $data   = Teacher::find($id);
            $data->delete();

            //Hapus Foto
            $this->delete_file($data->foto);
            $this->delete_user($id);

            //Activity Log
            $property = [
                'id'    => $data->nidn,
                'name'  => $data->nama . ' (' . $data->nidn . ')',
            ];
            $this->log('deleted', 'Dosen', $property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil dihapus',
                'type'    => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function download($filename)
    {
        $file = decrypt($filename);

        $storagePath = storage_path('app/upload/teacher/' . $file);
        if (!File::exists($storagePath)) {
            abort(404);
        } else {
            $mimeType = File::mimeType($storagePath);
            $headers = array(
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $file . '"'
            );

            return response(file_get_contents($storagePath), 200, $headers);
        }
    }

    public function delete_file($file)
    {
        $storagePath = storage_path('app/upload/teacher/' . $file);
        if (File::exists($storagePath)) {
            File::delete($storagePath);
        }
    }

    public function delete_user($id)
    {
        $cek = User::where('username', $id)->count();

        if ($cek) {
            User::where('username', $id)->delete();
        }
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
        $tgl = date('Y-m-d');
        $nama_file  = "Data_Dosen_Import_" . $tgl . '.' . $file->getClientOriginalExtension();
        $dir_path   = storage_path('app/temp/excel/');
        $file_path  = $dir_path . $nama_file;

        // upload ke folder khusus di dalam folder public
        $file->move($dir_path, $nama_file);

        // import data
        $q = Excel::import(new TeacherImport, $file_path);

        //Validasi jika terjadi error saat mengimpor
        if (!$q) {
            return response()->json([
                'title'   => 'Gagal',
                'message' => 'Terjadi kesalahan saat mengimpor',
                'type'    => 'error'
            ]);
        } else {
            File::delete($file_path);
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil diimpor',
                'type'    => 'success'
            ]);
        }
    }

    public function export(Request $request)
    {
        // Request
        $tgl         = date('d-m-Y_h_i_s');
        $prodi       = ($request->kd_prodi ? $request->kd_prodi . '_' : null);
        $nama_file   = 'Data_Dosen_' . $prodi . $tgl . '.xlsx';
        $lokasi_file = storage_path('app/upload/' . $nama_file);

        // Ekspor data
        // return (new TeacherExport($request))->store($nama_file,storage_path('app/upload/teacher/excel_export'));
        return Excel::download(new TeacherExport($request), $nama_file);
        // Excel::download(new TeacherExport($request),$nama_file,'upload');

        // return response()->json($lokasi_file);
    }

    private function setStatus($nidn)
    {
        $status_terbaru = TeacherStatus::where('nidn', $nidn)->latest('periode')->first()->id;

        TeacherStatus::where('nidn', $nidn)->where('is_active', true)->update(['is_active' => false]);
        TeacherStatus::where('id', $status_terbaru)->update(['is_active' => true]);
    }

    public function show_by_prodi(Request $request)
    {
        $data = Teacher::where('kd_prodi', $request->kd_prodi)->get();

        return response()->json($data);
    }

    public function get_by_department(Request $request)
    {
        if ($request->ajax()) {

            $kd = $request->input('kd_jurusan');

            if ($kd == 0) {
                $data = Teacher::with(['latestStatus.studyProgram.department.faculty'])->orderBy('created_at', 'desc')->get();
            } else {
                $data = Teacher::whereHas(
                    'latestStatus.studyProgram',
                    function ($query) use ($kd) {
                        $query->where('kd_jurusan', $kd);
                    }
                )
                    ->with(['latestStatus.studyProgram.department.faculty'])
                    ->get();
            }

            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function get_by_studyProgram(Request $request)
    {
        if ($request->ajax()) {

            $q = Teacher::where('kd_prodi', $request->kd_prodi);

            if ($request->prodi) {
                $q->where('kd_prodi', $request->prodi);
            }

            if ($request->cari) {
                $q->where(function ($query) use ($request) {
                    $query->where('nidn', 'LIKE', '%' . $request->cari . '%')->orWhere('nama', 'LIKE', '%' . $request->cari . '%');
                });
            }

            $data = $q->get();

            $response = array();
            foreach ($data as $d) {
                $response[] = array(
                    "id"    => $d->nidn,
                    "text"  => $d->nama . ' (' . $d->nidn . ')'
                );
            }
            return response()->json($response);
        } else {
            abort(404);
        }
    }

    public function datatable(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        if (Auth::user()->hasRole('kaprodi')) {
            $data         = Teacher::whereHas(
                'latestStatus.studyProgram',
                function ($query) {
                    $query->where('kd_prodi', Auth::user()->kd_prodi);
                }
            );
        } else {
            $data         = Teacher::whereHas(
                'latestStatus.studyProgram',
                function ($query) {
                    $query->where('kd_jurusan', setting('app_department_id'));
                }
            );
        }

        // dd($data->get());

        if ($request->prodi) {
            $data->whereHas('latestStatus.studyProgram', function ($q) use ($request) {
                $q->where('kd_prodi', $request->prodi);
            });
        }

        return DataTables::of($data->get())
            ->editColumn('nama', function ($d) {
                return '<a name="' . $d->nama . '" href="' . route("teacher.list.show", $d->nidn) . '">' .
                    $d->nama .
                    '<br><small>NIDN. ' . $d->nidn . '</small></a>';
            })
            ->editColumn('study_program', function ($d) {
                return  $d->latestStatus->studyProgram->nama .
                    '<br>
                                        <small>' . $d->latestStatus->studyProgram->department->faculty->singkatan . ' - ' . $d->latestStatus->studyProgram->department->nama . '</small>';
            })
            ->addColumn('aksi', function ($d) {
                if (!Auth::user()->hasRole('kajur')) {
                    return view('teacher.table-button', compact('d'))->render();
                }
            })
            ->rawColumns(['nama', 'study_program', 'aksi'])
            ->make();
    }

    public function loadData(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $cari  = $request->cari;
        $prodi = $request->prodi;

        $q = Teacher::select('nidn', 'nama');

        if ($prodi) {
            $q->whereHas('latestStatus', function ($q) use ($prodi) {
                $q->where('kd_prodi', $prodi);
            });
        }

        if ($cari) {
            $q->where(function ($query) use ($request) {
                $query->where('nidn', 'LIKE', '%' . $request->cari . '%')->orWhere('nama', 'LIKE', '%' . $request->cari . '%');
            });
        }

        $data = $q->orderBy('nama', 'asc')->get();

        $response = array();
        foreach ($data as $d) {
            $response[] = array(
                "id"    => $d->nidn,
                "text"  => $d->nama . ' (' . $d->nidn . ')'
            );
        }
        return response()->json($response);
    }
}
