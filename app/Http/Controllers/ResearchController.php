<?php

namespace App\Http\Controllers;

use App\Exports\ResearchExport;
use App\Http\Requests\ResearchRequest;
use App\Http\Requests\ResearchTeacherRequest;
use App\Http\Requests\ResearchStudentRequest;
use App\Models\AcademicYear;
use App\Models\Research;
use App\Models\StudyProgram;
use App\Models\Faculty;
use App\Models\Teacher;
use App\Models\ResearchStudent;
use App\Models\ResearchTeacher;
use App\Models\Student;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use File;


class ResearchController extends Controller
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

        return view('research.index', compact(['studyProgram', 'faculty', 'periodeTahun']));
    }

    public function index_teacher()
    {
        $penelitianKetua    = Research::whereHas(
            'researchTeacher',
            function ($q) {
                $q->where('nidn', Auth::user()->username)->where('status', 'Ketua');
            }
        )
            ->get();

        $penelitianAnggota   = Research::whereHas(
            'researchTeacher',
            function ($q) {
                $q->where('nidn', Auth::user()->username)->where('status', 'Anggota');
            }
        )
            ->get();

        return view('teacher-view.research.index', compact(['penelitianKetua', 'penelitianAnggota']));
    }

    public function show($id)
    {
        $id   = decrypt($id);
        $data = Research::where('id', $id)->first();

        return view('research.show', compact(['data']));
    }

    public function show_teacher($id)
    {
        $id     = decode_id($id);
        $nidn   = Auth::user()->username;
        $data   = Research::where('id', $id)->first();
        $status = ResearchTeacher::where('id_penelitian', $id)->where('nidn', $nidn)->first()->status;

        return view('teacher-view.research.show', compact(['data', 'status']));
    }

    public function create()
    {
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan', setting('app_department_id'))->get();

        return view('research.form', compact(['studyProgram', 'faculty']));
    }

    public function create_teacher()
    {
        return view('teacher-view.research.form');
    }

    public function edit($id)
    {
        $id   = decrypt($id);

        $studyProgram = StudyProgram::where('kd_jurusan', setting('app_department_id'))->get();
        $data         = Research::where('id', $id)->first();

        return view('research.form', compact(['data', 'studyProgram']));
    }

    public function edit_teacher($id)
    {
        $id     = decrypt($id);
        $nidn   = Auth::user()->username;
        $data   = Research::where('id', $id)->first();
        $status = ResearchTeacher::where('id_penelitian', $id)->where('nidn', $nidn)->first()->status;

        if ($status == 'Ketua') {
            return view('teacher-view.research.form', compact(['data']));
        } else {
            return abort(404);
        }
    }

    public function store(ResearchRequest $request)
    {
        DB::beginTransaction();
        try {
            //Simpan Data Penelitian
            $research                    = new Research;
            $research->id_ta             = $request->id_ta;
            $research->judul_penelitian  = $request->judul_penelitian;
            $research->tema_penelitian   = $request->tema_penelitian;
            $research->tingkat_penelitian = $request->tingkat_penelitian;
            $research->sks_penelitian    = $request->sks_penelitian;
            $research->sesuai_prodi      = $request->sesuai_prodi;
            $research->sumber_biaya      = $request->sumber_biaya;
            $research->sumber_biaya_nama = $request->sumber_biaya_nama;
            $research->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);

            //Upload File
            if ($request->file('bukti_fisik')) {
                //Init file
                $file = $request->file('bukti_fisik');

                //Ambil tanggal upload
                $tgl_skrg = date('Y_m_d_H_i_s');

                //Tujuan Upload
                $tujuan_upload = storage_path('app/upload/research');

                //Buat nama file
                $tahun = AcademicYear::find($request->id_ta)->tahun_akademik;
                $filename = $tahun . '_' . $request->judul_penelitian . '_' . $request->tingkat_penelitian . '_' . $tgl_skrg . '.' . $file->getClientOriginalExtension();
                $newname = str_replace(' ', '_', $filename); //hilangkan spasi

                //Pindahkan file ke folder tujuan
                $file->move($tujuan_upload, $newname);

                //Simpan nama file ke db
                $research->bukti_fisik = $newname;
            }

            //Save Query
            $research->save();

            //Jumlah SKS
            $sks_ketua      = floatval($request->sks_penelitian) * setting('research_ratio_chief') / 100;

            //Tambah Ketua
            if ($request->asal_ketua_peneliti == 'Luar') {
                $ketua                  = new ResearchTeacher;
                $ketua->id_penelitian   = $research->id;
                $ketua->status          = 'Ketua';
                $ketua->sks             = $sks_ketua;
                $ketua->nidn            = null;
                $ketua->nama            = $request->ketua_nama;
                $ketua->asal            = $request->ketua_asal;
                $ketua->save();
            } else {

                if (!Teacher::find($request->ketua_nidn))
                    throw new \Exception('NIDN Dosen tidak ditemukan. Periksa kembali.');

                $ketua                  = new ResearchTeacher;
                $ketua->id_penelitian   = $research->id;
                $ketua->status          = 'Ketua';
                $ketua->sks             = $sks_ketua;
                $ketua->nidn            = $request->ketua_nidn;
                $ketua->nama            = null;
                $ketua->asal            = null;
                $ketua->save();
            }

            //Activity Log
            $property = [
                'id'    => $research->id,
                'name'  => $research->judul_penelitian,
                'url'   => route('research.show', encrypt($research->id))
            ];
            $this->log('created', 'Penelitian', $property);

            DB::commit();

            if (Auth::user()->hasRole('dosen')) {
                return redirect()->route('profile.research')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
            } else {
                return redirect()->route('research.show', encrypt($research->id))->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }
    }

    public function update(ResearchRequest $request)
    {
        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($request->id);

            //Simpan Data Penelitian
            $research                    = Research::find($id);
            $research->id_ta             = $request->id_ta;
            $research->judul_penelitian  = $request->judul_penelitian;
            $research->tema_penelitian   = $request->tema_penelitian;
            $research->tingkat_penelitian = $request->tingkat_penelitian;
            $research->sks_penelitian    = $request->sks_penelitian;
            $research->sesuai_prodi      = $request->sesuai_prodi;
            $research->sumber_biaya      = $request->sumber_biaya;
            $research->sumber_biaya_nama = $request->sumber_biaya_nama;
            $research->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);

            //Upload File
            $storagePath = storage_path('app/upload/research/' . $research->bukti_fisik);
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
                $tujuan_upload = storage_path('app/upload/research');

                //Buat nama file
                $filename = $tahun . '_' . $request->judul_penelitian . '_' . $request->tingkat_penelitian . '_' . $tgl_skrg . '.' . $file->getClientOriginalExtension();
                $newname = str_replace(' ', '_', $filename); //hilangkan spasi

                //Pindahkan file ke folder tujuan
                $file->move($tujuan_upload, $newname);

                //Simpan nama file ke db
                $research->bukti_fisik = $newname;
            }

            //Save Query
            $research->save();

            //Update Ketua
            ResearchTeacher::where('id_penelitian', $research->id)->where('status', 'Ketua')->delete();
            if ($request->asal_ketua_peneliti == 'Luar') {
                $ketua                  = new ResearchTeacher;
                $ketua->id_penelitian   = $id;
                $ketua->status          = 'Ketua';
                $ketua->sks             = 0;
                $ketua->nidn            = null;
                $ketua->nama            = $request->ketua_nama;
                $ketua->asal            = $request->ketua_asal;
                $ketua->save();
            } else {

                if (!Teacher::find($request->ketua_nidn))
                    throw new \Exception('NIDN Dosen tidak ditemukan. Periksa kembali.');

                if (ResearchTeacher::where('id_penelitian', $research->id)->where('nidn', $request->ketua_nidn)->first()) {
                    throw new \Exception('NIDN sudah ada. Periksa kembali.');
                }

                $ketua                  = new ResearchTeacher;
                $ketua->id_penelitian   = $id;
                $ketua->status          = 'Ketua';
                $ketua->sks             = 0;
                $ketua->nidn            = $request->ketua_nidn;
                $ketua->nama            = null;
                $ketua->asal            = null;
                $ketua->save();
            }

            //Update SKS Penelitian Ketua & Anggota
            $this->update_sks($research->id);

            //Activity Log
            $property = [
                'id'    => $research->id,
                'name'  => $research->judul_penelitian,
                'url'   => route('research.show', encrypt($research->id))
            ];
            $this->log('updated', 'Penelitian', $property);

            DB::commit();
            if (Auth::user()->hasRole('dosen')) {
                return redirect()->route('profile.research.show', encode_id($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
            } else {
                return redirect()->route('research.show', encrypt($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
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
            $id     = decrypt($request->id);

            //Query
            $data   = Research::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->judul_penelitian,
            ];
            $this->log('deleted', 'Penelitian', $property);

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

    public function store_teacher(ResearchTeacherRequest $request)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {

            //Publikasi
            $id_penelitian = $request->_id;
            $penelitian = Research::find($id_penelitian);

            //Start Query
            if ($request->asal_dosen == 'Luar') {
                $anggota                = new ResearchTeacher;
                $anggota->id_penelitian = $id_penelitian;
                $anggota->status        = 'Anggota';
                $anggota->sks           = 0;
                $anggota->nidn          = null;
                $anggota->nama          = $request->anggota_nama;
                $anggota->asal          = $request->anggota_asal;
                $anggota->save();
            } else {
                if (!Teacher::find($request->anggota_nidn))
                    throw new \Exception('NIDN Dosen tidak ditemukan. Periksa kembali.');

                if (ResearchTeacher::where('id_penelitian', $penelitian->id)->where('nidn', $request->anggota_nidn)->first()) {
                    throw new \Exception('NIDN sudah ada. Periksa kembali.');
                }

                $anggota                = new ResearchTeacher;
                $anggota->id_penelitian = $id_penelitian;
                $anggota->status        = 'Anggota';
                $anggota->sks           = 0;
                $anggota->nidn          = $request->anggota_nidn;
                $anggota->nama          = null;
                $anggota->asal          = null;
                $anggota->save();
            }

            //Update SKS Penelitian Ketua & Anggota
            $this->update_sks($id_penelitian);

            //Activity Log
            $property = [
                'id'    => $anggota->id,
                'name'  => $penelitian->judul_penelitian,
                'url'   => route('research.show', encrypt($id_penelitian))
            ];
            $this->log('created', 'anggota dosen penelitian', $property);

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
            $data       = ResearchTeacher::find($id);
            $data_id    = $data->id;
            $penelitian = Research::find($data->id_penelitian);

            //Hapus
            $data->delete();

            //Update SKS Penelitian Ketua & Anggota
            $this->update_sks($penelitian->id);

            //Activity Log
            $property = [
                'id'    => $data_id,
                'name'  => $penelitian->judul_penelitian,
                'url'   => route('research.show', encrypt($penelitian->id))
            ];
            $this->log('deleted', 'anggota dosen penelitian', $property);

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

    public function store_student(ResearchStudentRequest $request)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {

            //Publikasi
            $id_penelitian = $request->_id;
            $penelitian = Research::find($id_penelitian);

            //Start Query
            if ($request->asal_mahasiswa == 'Luar') {
                $anggota                = new ResearchStudent;
                $anggota->id_penelitian = $id_penelitian;
                $anggota->nim           = null;
                $anggota->nama          = $request->anggota_nama;
                $anggota->asal          = $request->anggota_asal;
                $anggota->save();
            } else {
                if (!Student::find($request->anggota_nim))
                    throw new \Exception('NIM Mahasiswa tidak ditemukan. Periksa kembali.');

                if (ResearchStudent::where('id_penelitian', $penelitian->id)->where('nim', $request->anggota_nim)->first()) {
                    throw new \Exception('NIM sudah ada. Periksa kembali.');
                }

                $anggota                = new ResearchStudent;
                $anggota->id_penelitian = $id_penelitian;
                $anggota->nim           = $request->anggota_nim;
                $anggota->nama          = null;
                $anggota->asal          = null;
                $anggota->save();
            }

            //Activity Log
            $property = [
                'id'    => $anggota->id,
                'name'  => $penelitian->judul_penelitian,
                'url'   => route('research.show', encrypt($id_penelitian))
            ];
            $this->log('created', 'anggota mahasiswa penelitian', $property);

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
            $data       = ResearchStudent::find($id);
            $data_id    = $data->id;
            $penelitian = Research::find($data->id_penelitian);

            //Hapus
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data_id,
                'name'  => $penelitian->judul_penelitian,
                'url'   => route('research.show', encrypt($penelitian->id))
            ];
            $this->log('deleted', 'anggota mahasiswa penelitian', $property);

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

        $nama_file   = 'Data_Penelitian_' . $idn . $periode . $tgl . '.xlsx';
        $lokasi_file = storage_path('app/upload/temp/' . $nama_file);

        // Ekspor data
        return (new ResearchExport($request))->download($nama_file);
    }

    public function update_sks($id_penelitian)
    {
        $penelitian = Research::find($id_penelitian);

        //Query
        $query_ketua   = ResearchTeacher::where('id_penelitian', $id_penelitian)->where('status', 'Ketua');
        $query_anggota = ResearchTeacher::where('id_penelitian', $id_penelitian)->where('status', 'Anggota');

        //Hitung total anggota
        $count_anggota = $query_anggota->count();

        //Hitung dan update SKS Ketua
        $sks_ketua     = (floatval($penelitian->sks_penelitian) * setting('research_ratio_chief')) / 100;
        $query_ketua->update(['sks' => $sks_ketua]);

        //Hitung dan update SKS Anggota
        if ($count_anggota > 0) {
            $rasio_anggota = (setting('research_ratio_members') / $count_anggota) / 100;
            $sks_anggota   = floatval($penelitian->sks_penelitian) * $rasio_anggota;
            $query_anggota->update(['sks' => $sks_anggota]);
        }
    }

    public function datatable(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        if (Auth::user()->hasRole('kaprodi')) {
            $data   = Research::whereHas(
                'researchTeacher',
                function ($q) {
                    $q->prodi(Auth::user()->kd_prodi);
                }
            );
        } else {
            $data   = Research::whereHas(
                'researchTeacher',
                function ($q) {
                    $q->jurusan(setting('app_department_id'));
                }
            );
        }

        if ($request->kd_prodi_filter) {
            $data->whereHas(
                'researchTeacher',
                function ($q) use ($request) {
                    $q->prodiKetua($request->kd_prodi_filter);
                }
            );
        }

        return DataTables::of($data->get())
            ->addColumn('penelitian', function ($d) {
                return  '<a href="' . route('research.show', encrypt($d->id)) . '" target="_blank">'
                    . $d->judul_penelitian .
                    '</a>';
            })
            ->addColumn('tahun', function ($d) {
                return $d->academicYear->tahun_akademik . ' - ' . $d->academicYear->semester;
            })
            // ->addColumn('peneliti', function ($d) {
            //     return  '<a href="' . route('teacher.list.show', $d->researchKetua->teacher->nidn) . '#research">'
            //         . $d->researchKetua->teacher->nama .
            //         '<br><small>NIDN.' . $d->researchKetua->teacher->nidn . ' / ' . $d->researchKetua->teacher->latestStatus->studyProgram->singkatan . '</small>
            //                             </a>';
            // })
            ->addColumn('aksi', function ($d) {
                if (!Auth::user()->hasRole('kajur')) {
                    return view('research.table-button', compact('d'))->render();
                }
            })
            ->rawColumns(['penelitian', 'peneliti', 'aksi'])
            ->make();
    }

    public function chart(Request $request)
    {
        $query = Research::whereHas(
            'researchTeacher',
            function ($q) {
                if (Auth::user()->hasRole('kaprodi')) {
                    $q->prodiKetua(Auth::user()->kd_prodi);
                } else {
                    $q->jurusanKetua(setting('app_department_id'));
                }
            }
        )->get();

        $a = $request->tahun_a;
        $b = $request->tahun_b;
        $thn = current_academic()->tahun_akademik;
        $academicYear = AcademicYear::whereBetween('tahun_akademik', [$thn - 5, $thn])->get();

        // if($a != null && $b != null) {
        //     $query->whereHas(
        //         'academicYear', function($q) use($a,$b) {
        //             $q->whereBetween('tahun_akademik', [$a,$b]);
        //         }
        //     );
        // } else {
        //     $query->whereHas(
        //         'academicYear', function($q) use($a) {
        //             $q->where('status', 1);
        //         }
        //     );
        // }

        foreach ($academicYear as $ay) {
            $result[$ay->tahun_akademik] = $query->where('id_ta', $ay->id)->count();
        }

        return response()->json($result);
    }

    public function get_by_department(Request $request)
    {
        if ($request->has('cari')) {
            $cari = $request->cari;
            $data = Research::whereHas(
                'researchTeacher',
                function ($q) {
                    $q->jurusanKetua(setting('app_department_id'));
                }
            )
                ->where('judul_penelitian', 'LIKE', '%' . $cari . '%')
                ->orWhere('id', 'LIKE', '%' . $cari . '%')
                ->get();

            $response = array();
            foreach ($data as $d) {
                $response[] = array(
                    "id"    => $d->id,
                    "text"  => $d->judul_penelitian . ' (' . $d->academicYear->tahun_akademik . ')'
                );
            }
            return response()->json($response);
        }
    }
}
