<?php

namespace App\Http\Controllers;

use App\Exports\CommunityServiceExport;
use App\Http\Requests\CommunityServiceRequest;
use App\Http\Requests\CommunityServiceStudentRequest;
use App\Http\Requests\CommunityServiceTeacherRequest;
use App\Models\AcademicYear;
use App\Models\CommunityService;
use App\Models\CommunityServiceTeacher;
use App\Models\CommunityServiceStudent;
use App\Models\StudyProgram;
use App\Models\Faculty;
use App\Models\Teacher;
use App\Models\Student;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CommunityServiceController extends Controller
{
    use LogActivity;

    public function __construct()
    {
        $method = [
            'create',
            'edit',
        ];

        $this->middleware('role:admin,kaprodi', ['only' => $method]);
    }

    public function index()
    {
        $faculty = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan', setting('app_department_id'))->get();
        $periodeTahun = AcademicYear::groupBy('tahun_akademik')->orderBy('tahun_akademik', 'desc')->select('tahun_akademik')->get();

        // return response()->json($pengabdian);die;

        return view('community-service.index', compact(['studyProgram', 'faculty', 'periodeTahun']));
    }

    public function index_teacher()
    {
        $pengabdianKetua     = CommunityService::whereHas(
            'serviceTeacher',
            function ($q) {
                $q->where('nidn', Auth::user()->username)->where('status', 'Ketua');
            }
        )
            ->get();

        $pengabdianAnggota   = CommunityService::whereHas(
            'serviceTeacher',
            function ($q) {
                $q->where('nidn', Auth::user()->username)->where('status', 'Anggota');
            }
        )
            ->get();

        // return response()->json($pengabdian);die;

        return view('teacher-view.community-service.index', compact(['pengabdianKetua', 'pengabdianAnggota']));
    }

    public function show($id)
    {
        $id   = decrypt($id);
        $data = CommunityService::where('id', $id)->first();

        return view('community-service.show', compact(['data']));
    }

    public function show_teacher($id)
    {
        $id     = decrypt($id);
        $nidn   = Auth::user()->username;
        $data   = CommunityService::where('id', $id)->first();

        if (!$data) {
            abort(404);
        }

        $status = null;
        $cek_dosen = CommunityServiceTeacher::where('id_pengabdian', $id)->where('nidn', $nidn)->first();
        if ($cek_dosen) {
            $status = $cek_dosen->status;
        } else {
            abort(404);
        }

        return view('teacher-view.community-service.show', compact(['data', 'status']));
    }

    public function create()
    {
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan', setting('app_department_id'))->get();
        return view('community-service.form', compact(['studyProgram', 'faculty']));
    }

    public function create_teacher()
    {
        return view('teacher-view.community-service.form');
    }

    public function edit($id)
    {
        $id   = decrypt($id);

        $studyProgram = StudyProgram::where('kd_jurusan', setting('app_department_id'))->get();
        $data         = CommunityService::where('id', $id)->first();

        return view('community-service.form', compact(['data', 'studyProgram']));
    }

    public function edit_teacher($id)
    {
        $id     = decrypt($id);
        $nidn   = Auth::user()->username;
        $data   = CommunityService::where('id', $id)->first();
        $status = CommunityServiceTeacher::where('id_pengabdian', $id)->where('nidn', $nidn)->first()->status;

        if ($status != 'Ketua') {
            return abort(404);
        }

        return view('teacher-view.community-service.form', compact(['data']));
    }

    public function store(CommunityServiceRequest $request)
    {
        DB::beginTransaction();
        try {
            //Simpan Data Pengabdian
            $community                    = new CommunityService;
            $community->id_ta             = $request->id_ta;
            $community->judul_pengabdian  = $request->judul_pengabdian;
            $community->tema_pengabdian   = $request->tema_pengabdian;
            $community->tingkat_pengabdian = $request->tingkat_pengabdian;
            $community->sks_pengabdian    = $request->sks_pengabdian;
            $community->sesuai_prodi      = $request->sesuai_prodi;
            $community->sumber_biaya      = $request->sumber_biaya;
            $community->sumber_biaya_nama = $request->sumber_biaya_nama;
            $community->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);

            //Upload File
            if ($request->file('bukti_fisik')) {
                //Init file
                $file = $request->file('bukti_fisik');

                //Ambil tanggal upload
                $tgl_skrg = date('Y_m_d_H_i_s');

                //Tujuan Upload
                $tujuan_upload = storage_path('app/upload/community-service');

                //Buat nama file
                $tahun = AcademicYear::find($request->id_ta)->tahun_akademik;
                $filename = $tahun . '_' . $request->judul_pengabdian . '_' . $request->tingkat_pengabdian . '_' . $tgl_skrg . '.' . $file->getClientOriginalExtension();
                $newname = str_replace(' ', '_', $filename); //hilangkan spasi

                //Pindahkan file ke folder tujuan
                $file->move($tujuan_upload, $newname);

                //Simpan nama file ke db
                $community->bukti_fisik = $newname;
            }

            //Save Query
            $community->save();

            //Jumlah SKS
            $sks_ketua      = floatval($request->sks_pengabdian) * setting('service_ratio_chief') / 100;

            //Tambah Ketua
            $validated_teacher      = $this->validate_teacher($community->id, $request);

            $ketua                  = new CommunityServiceTeacher;
            $ketua->id_pengabdian   = $community->id;
            $ketua->status          = 'Ketua';
            $ketua->sks             = $sks_ketua;
            $ketua->nidn            = $validated_teacher['nidn'];
            $ketua->nama            = $validated_teacher['nama'];
            $ketua->asal            = $validated_teacher['asal'];
            $ketua->save();

            //Activity Log
            $property = [
                'id'    => $community->id,
                'name'  => $community->judul_pengabdian,
                'url'   => route('community-service.show', encrypt($community->id))
            ];
            $this->log('created', 'Pengabdian', $property);

            DB::commit();
            if (Auth::user()->hasRole('dosen')) {
                return redirect()->route('profile.community-service.show', encrypt($community->id))->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
            } else {
                return redirect()->route('community-service.show', encrypt($community->id))->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }
    }

    public function update(CommunityServiceRequest $request)
    {
        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($request->id);

            //Update Data Pengabdian
            $community                    = CommunityService::find($id);
            $community->id_ta             = $request->id_ta;
            $community->judul_pengabdian  = $request->judul_pengabdian;
            $community->tema_pengabdian   = $request->tema_pengabdian;
            $community->tingkat_pengabdian = $request->tingkat_pengabdian;
            $community->sks_pengabdian    = $request->sks_pengabdian;
            $community->sesuai_prodi      = $request->sesuai_prodi;
            $community->sumber_biaya      = $request->sumber_biaya;
            $community->sumber_biaya_nama = $request->sumber_biaya_nama;
            $community->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);

            //Upload File
            $storagePath = storage_path('app/upload/community-service/' . $community->bukti_fisik);
            $tgl_skrg = date('Y_m_d_H_i_s');
            $tahun = AcademicYear::find($request->id_ta)->tahun_akademik;

            if ($request->file('bukti_fisik')) {
                //Jika sudah ada file, hapus
                if (File::exists($storagePath)) {
                    File::delete($storagePath);
                }

                //Init file
                $file = $request->file('bukti_fisik');

                //Folder tujuan upload
                $tujuan_upload = storage_path('app/upload/community-service');

                //Buat nama file
                $filename = $tahun . '_' . $request->judul_pengabdian . '_' . $request->tingkat_pengabdian . '_' . $tgl_skrg . '.' . $file->getClientOriginalExtension();
                $newname = str_replace(' ', '_', $filename); //hilangkan spasi

                //Pindahkan file ke folder tujuan
                $file->move($tujuan_upload, $newname);

                //Simpan nama file ke db
                $community->bukti_fisik = $newname;
            }

            //Save Query
            $community->save();

            //Update Ketua
            CommunityServiceTeacher::where('id_pengabdian', $community->id)->where('status', 'Ketua')->delete();
            $validated_teacher      = $this->validate_teacher($community->id, $request);

            $ketua                  = new CommunityServiceTeacher;
            $ketua->id_pengabdian   = $id;
            $ketua->status          = 'Ketua';
            $ketua->sks             = 0;
            $ketua->nidn            = $validated_teacher['nidn'];
            $ketua->nama            = $validated_teacher['nama'];
            $ketua->asal            = $validated_teacher['asal'];
            $ketua->save();

            //Update SKS Pengabdian Ketua & Anggota
            $this->update_sks($community->id);

            //Activity Log
            $property = [
                'id'    => $community->id,
                'name'  => $community->judul_pengabdian,
                'url'   => route('community-service.show', encrypt($community->id))
            ];
            $this->log('updated', 'Pengabdian', $property);

            DB::commit();

            if (Auth::user()->hasRole('dosen')) {
                return redirect()->route('profile.community-service.show', encrypt($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
            } else {
                return redirect()->route('community-service.show', encrypt($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }
    }

    public function destroy(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($request->id);

            //Query
            $data = CommunityService::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->judul_pengabdian,
            ];
            $this->log('deleted', 'Pengabdian', $property);

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

    public function store_teacher(CommunityServiceTeacherRequest $request)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {

            //Pengabdian
            $id_pengabdian = decrypt($request->pengabdian_id);
            $pengabdian = CommunityService::find($id_pengabdian);

            //Start Query
            $validated_teacher      = $this->validate_teacher($pengabdian->id, $request);

            $anggota                = new CommunityServiceTeacher;
            $anggota->id_pengabdian = $id_pengabdian;
            $anggota->status        = 'Anggota';
            $anggota->sks           = 0;
            $anggota->nidn          = $validated_teacher['nidn'];
            $anggota->nama          = $validated_teacher['nama'];
            $anggota->asal          = $validated_teacher['asal'];
            $anggota->save();

            //Update SKS Penelitian Ketua & Anggota
            $this->update_sks($id_pengabdian);

            //Activity Log
            $property = [
                'id'    => $anggota->id,
                'name'  => $pengabdian->judul_pengabdian,
                'url'   => route('community-service.show', encrypt($id_pengabdian))
            ];
            $this->log('created', 'anggota dosen pengabdian', $property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil disimpan',
                'type'    => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy_teacher($id)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($id);

            //Query
            $data       = CommunityServiceTeacher::find($id);
            $data_id    = $data->id;
            $pengabdian = CommunityService::find($data->id_pengabdian);

            //Hapus
            $data->delete();

            //Update SKS Penelitian Ketua & Anggota
            $this->update_sks($pengabdian->id);

            //Activity Log
            $property = [
                'id'    => $data_id,
                'name'  => $pengabdian->judul_pengabdian,
                'url'   => route('community-service.show', encrypt($pengabdian->id))
            ];
            $this->log('deleted', 'anggota dosen pengabdian', $property);

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

    public function store_student(CommunityServiceStudentRequest $request)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {

            //Publikasi
            $id_pengabdian = decrypt($request->pengabdian_id);
            $pengabdian = CommunityService::find($id_pengabdian);

            //Start Query
            $validated_student = $this->validate_student($pengabdian->id, $request);

            $anggota                = new CommunityServiceStudent;
            $anggota->id_pengabdian = $id_pengabdian;
            $anggota->nim           = $validated_student['nim'];
            $anggota->nama          = $validated_student['nama'];
            $anggota->asal          = $validated_student['asal'];
            $anggota->save();

            //Activity Log
            $property = [
                'id'    => $anggota->id,
                'name'  => $pengabdian->judul_pengabdian,
                'url'   => route('community-service.show', encrypt($id_pengabdian))
            ];
            $this->log('created', 'anggota mahasiswa pengabdian', $property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil disimpan',
                'type'    => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy_students($id)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($id);

            //Query
            $data       = CommunityServiceStudent::find($id);
            $data_id    = $data->id;
            $pengabdian = CommunityService::find($data->id_pengabdian);

            //Hapus
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data_id,
                'name'  => $pengabdian->judul_pengabdian,
                'url'   => route('community-service.show', encrypt($pengabdian->id))
            ];
            $this->log('deleted', 'anggota mahasiswa pengabdian', $property);

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

    public function export(Request $request)
    {
        // Request
        $tgl         = date('dmYhis');
        if ($request->tipe == 'prodi') {
            $idn       = ($request->kd_prodi ? $request->kd_prodi . '_' : null);
        } else {
            $idn       = ($request->nidn ? $request->nidn . '_' : null);
        }

        if (empty($request->periode_akhir)) {
            $periode = $request->periode_awal . '_';
        } else {
            $periode = $request->periode_awal . '-' . $request->periode_akhir . '_';
        }

        $nama_file   = 'Data_Pengabdian_' . $idn . $periode . $tgl . '.xlsx';
        $lokasi_file = storage_path('app/upload/temp/' . $nama_file);

        // Ekspor data
        return (new CommunityServiceExport($request))->download($nama_file);
    }

    public function validate_teacher($id_parent, $request)
    {
        DB::beginTransaction();
        try {
            if ($request->asal_penyelenggara == 'Sendiri') {
                $nidn = auth()->user()->username;
                $nama = null;
                $asal = null;
            } else if ($request->asal_penyelenggara == 'Jurusan') {
                if (!Teacher::find($request->anggota_nidn))
                    throw new \Exception('NIDN Dosen tidak ditemukan. Periksa kembali.', 404);

                if (CommunityServiceTeacher::where('id_pengabdian', $id_parent)->where('nidn', $request->anggota_nidn)->first())
                    throw new \Exception('NIDN sudah ada. Periksa kembali.', 404);

                $nidn = $request->anggota_nidn;
                $nama = null;
                $asal = null;
            } else {
                $nidn   = null;
                $nama   = $request->anggota_nama;
                $asal   = $request->anggota_asal;
            }

            if (auth()->user()->hasRole('dosen')) {
                if ($request->asal_penyelenggara == 'Sendiri') {
                    CommunityServiceTeacher::where('id_pengabdian', $id_parent)->where('nidn', auth()->user()->username)->delete();
                }

                if ($request->asal_penyelenggara != 'Sendiri' && $request->is_ketua && $request->anggota_nidn != auth()->user()->username) {
                    CommunityServiceTeacher::updateOrCreate(
                        [
                            'id_pengabdian' => $id_parent,
                            'nidn'          => auth()->user()->username,
                        ],
                        [
                            'status'        => 'Anggota',
                            'sks'           => 0,
                            'nama'          => null,
                            'asal'          => null,
                        ]
                    );

                    //Update SKS Penelitian Ketua & Anggota
                    $this->update_sks($id_parent);
                }
            }

            DB::commit();
            return [
                'nidn' => $nidn,
                'nama' => $nama,
                'asal' => $asal,
            ];
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function validate_student($id_parent, $request)
    {
        DB::beginTransaction();
        try {
            if ($request->asal_mahasiswa == 'Luar') {
                $nim  = null;
                $nama = $request->mahasiswa_nama;
                $asal = $request->mahasiswa_asal;
            } else {
                if (!Student::find($request->mahasiswa_nim))
                    throw new \Exception('NIM Mahasiswa tidak ditemukan. Periksa kembali.');

                if (CommunityServiceStudent::where('id_pengabdian', $id_parent)->where('nim', $request->mahasiswa_nim)->first()) {
                    throw new \Exception('NIM sudah ada. Periksa kembali.');
                }

                $nim = $request->mahasiswa_nim;
                $nama = null;
                $asal = null;
            }

            DB::commit();
            return [
                'nim' => $nim,
                'nama' => $nama,
                'asal' => $asal,
            ];
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update_sks($id_pengabdian)
    {
        $pengabdian = CommunityService::find($id_pengabdian);

        //Query
        $query_ketua   = CommunityServiceTeacher::where('id_pengabdian', $id_pengabdian)->where('status', 'Ketua');
        $query_anggota = CommunityServiceTeacher::where('id_pengabdian', $id_pengabdian)->where('status', 'Anggota');

        //Hitung total anggota
        $count_anggota = $query_anggota->count();

        //Hitung dan Update SKS Ketua
        $sks_ketua     = (floatval($pengabdian->sks_pengabdian) * setting('service_ratio_chief')) / 100;
        $query_ketua->update(['sks' => $sks_ketua]);

        //Hitung dan Update SKS Anggota
        if ($count_anggota > 0) {
            $rasio_anggota = (setting('service_ratio_members') / $count_anggota) / 100;
            $sks_anggota   = floatval($pengabdian->sks_pengabdian) * $rasio_anggota;
            $query_anggota->update(['sks' => $sks_anggota]);
        }
    }

    public function datatable(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        if (Auth::user()->hasRole('kaprodi')) {
            $data   = CommunityService::whereHas(
                'serviceTeacher',
                function ($q) {
                    $q->prodi(Auth::user()->kd_prodi);
                }
            );
        } else {
            $data   = CommunityService::whereHas(
                'serviceTeacher',
                function ($q) {
                    $q->jurusan(setting('app_department_id'));
                }
            );
        }

        if ($request->kd_prodi_filter) {
            $data->whereHas(
                'serviceTeacher',
                function ($q) use ($request) {
                    $q->prodi($request->kd_prodi_filter);
                }
            );
        }

        return DataTables::of($data->get())
            ->addColumn('pengabdian', function ($d) {
                return  '<a href="' . route('community-service.show', encrypt($d->id)) . '" target="_blank">'
                    . $d->judul_pengabdian .
                    '</a>';
            })
            ->addColumn('tahun', function ($d) {
                return $d->academicYear->tahun_akademik . ' - ' . $d->academicYear->semester;
            })
            // ->addColumn('pelaksana', function ($d) {
            //     return  '<a href="' . route('teacher.list.show', $d->serviceKetua->teacher->nidn) . '#community-service">'
            //         . $d->serviceKetua->teacher->nama .
            //         '<br><small>NIDN.' . $d->serviceKetua->teacher->nidn . ' / ' . $d->serviceKetua->teacher->latestStatus->studyProgram->singkatan . '</small>
            //                             </a>';
            // })
            ->addColumn('aksi', function ($d) {
                if (!Auth::user()->hasRole('kajur')) {
                    return view('community-service.table-button', compact('d'))->render();
                }
            })
            ->rawColumns(['pengabdian', 'pelaksana', 'aksi'])
            ->make();
    }

    public function get_by_filter(Request $request)
    {
        if ($request->ajax()) {

            $q   = CommunityService::with([
                'academicYear',
                'serviceKetua.teacher.latestStatus.studyProgram',
                'serviceAnggota.teacher.latestStatus.studyProgram.department',
                'serviceStudent.student.studyProgram.department'
            ]);

            if ($request->kd_jurusan) {
                $q->whereHas(
                    'serviceTeacher',
                    function ($q) use ($request) {
                        $q->jurusanKetua($request->kd_jurusan);
                    }
                );
            }

            if (Auth::user()->hasRole('kaprodi')) {
                $q->whereHas(
                    'serviceTeacher',
                    function ($q) use ($request) {
                        $q->prodiKetua(Auth::user()->kd_prodi);
                    }
                );
            }

            if ($request->kd_prodi) {
                $q->whereHas(
                    'serviceTeacher',
                    function ($q) use ($request) {
                        $q->prodiKetua($request->kd_prodi);
                    }
                );
            }

            $data = $q->orderBy('id_ta', 'desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function get_by_department(Request $request)
    {
        if ($request->has('cari')) {
            $cari = $request->cari;
            $data = CommunityService::whereHas(
                'serviceTeacher',
                function ($q) {
                    $q->jurusanKetua(setting('app_department_id'));
                }
            )
                ->where('judul_pengabdian', 'LIKE', '%' . $cari . '%')
                ->orWhere('id', 'LIKE', '%' . $cari . '%')
                ->get();

            $response = array();
            foreach ($data as $d) {
                $response[] = array(
                    "id"    => $d->id,
                    "text"  => $d->judul_pengabdian . ' (' . $d->academicYear->tahun_akademik . ')'
                );
            }
            return response()->json($response);
        }
    }
}
