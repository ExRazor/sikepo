<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicationMemberRequest;
use App\Http\Requests\PublicationRequest;
use App\Models\PublicationCategory;
use App\Models\Publication;
use App\Models\PublicationMember;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\Teacher;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PublicationController extends Controller
{
    use LogActivity;

    public function __construct()
    {
        $method = [
            'index',
            'create',
            'edit',
        ];

        $this->middleware('role:admin,kaprodi', ['only' => $method]);
    }

    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan', setting('app_department_id'))->get();

        return view('publication.index', compact(['studyProgram']));
    }

    public function index_teacher()
    {
        $publikasiKetua    = Publication::whereHas(
            'publicationMembers',
            function ($query) {
                $query->dosen(Auth::user()->username)->where('penulis_utama', true);
            }
        )->get();

        $publikasiAnggota  = Publication::whereHas(
            'publicationMembers',
            function ($query) {
                $query->dosen(Auth::user()->username)->where('penulis_utama', false);
            }
        )->get();

        return view('teacher-view.publication.index', compact(['publikasiKetua', 'publikasiAnggota']));
    }

    public function create()
    {
        $studyProgram = StudyProgram::where('kd_jurusan', setting('app_department_id'))->get();
        $jenis        = PublicationCategory::all();

        return view('publication.form', compact(['studyProgram', 'jenis']));
    }

    public function create_teacher()
    {
        $jenis        = PublicationCategory::all();
        $studyProgram = StudyProgram::where('kd_jurusan', setting('app_department_id'))->get();

        return view('teacher-view.publication.form', compact(['studyProgram', 'jenis']));
    }

    public function show($id)
    {
        $id   = decrypt($id);
        $data = Publication::find($id);

        return view('publication.show', compact(['data']));
    }

    public function show_teacher($id)
    {
        $id   = decrypt($id);
        $data = Publication::find($id);

        return view('teacher-view.publication.show', compact(['data']));
    }

    public function edit($id)
    {
        $id   = decrypt($id);

        $studyProgram = StudyProgram::where('kd_jurusan', setting('app_department_id'))->get();
        $jenis        = PublicationCategory::all();
        $data         = Publication::with('publicationMembers')->where('id', $id)->first();
        // $teacher      = Teacher::whereHas('latestStatus.studyProgram', function ($q) use ($data) {
        //     $q->where('kd_prodi', $data->teacher->latestStatus->studyProgram->kd_prodi);
        // })->get();

        return view('publication.form', compact(['jenis', 'data', 'studyProgram']));
    }

    public function edit_teacher($id)
    {
        $id   = decrypt($id);

        $jenis        = PublicationCategory::all();
        $data         = Publication::where('id', $id)->first();

        if (auth()->user()->username != $data->penulisUtama->nidn) {
            // abort(404);
        }

        return view('teacher-view.publication.form', compact(['jenis', 'data']));
    }

    public function store(PublicationRequest $request)
    {
        DB::beginTransaction();
        try {

            //Query
            $data                   = new Publication;
            $data->jenis_publikasi  = $request->jenis_publikasi;
            $data->judul            = $request->judul;
            $data->penerbit         = $request->penerbit;
            $data->id_ta            = $request->id_ta;
            $data->sesuai_prodi     = $request->sesuai_prodi;
            $data->jurnal           = $request->jurnal;
            $data->akreditasi       = $request->akreditasi;
            $data->sitasi           = $request->sitasi;
            $data->tautan           = $request->tautan;
            $data->save();

            //Penulis Utama
            $validated_member = $this->validate_member($data->id, $request);

            $utama                = new PublicationMember;
            $utama->id_publikasi  = $data->id;
            $utama->nidn          = $validated_member['nidn'];
            $utama->nim           = $validated_member['nim'];
            $utama->nama          = $validated_member['nama'];
            $utama->asal          = $validated_member['asal'];
            $utama->status        = $validated_member['status'];
            $utama->penulis_utama = true;
            $utama->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->judul . ' (' . $data->academicYear->tahun_akademik . ' - ' . $data->academicYear->semester . ')',
                'url'   => route('publication.show', encrypt($data->id)),
            ];
            $this->log('created', 'Publikasi Dosen', $property);

            DB::commit();
            if (Auth::user()->hasRole('dosen')) {
                return redirect()->route('profile.publication.show', encrypt($data->id))->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
            } else {
                return redirect()->route('publication.show', encrypt($data->id))->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($request->id);

            //Query
            $data                   = Publication::find($id);
            $data->jenis_publikasi  = $request->jenis_publikasi;
            $data->judul            = $request->judul;
            $data->penerbit         = $request->penerbit;
            $data->id_ta            = $request->id_ta;
            $data->sesuai_prodi     = $request->sesuai_prodi;
            $data->jurnal           = $request->jurnal;
            $data->akreditasi       = $request->akreditasi;
            $data->sitasi           = $request->sitasi;
            $data->tautan           = $request->tautan;
            $data->save();

            //Update Penulis Utama
            PublicationMember::where('id_publikasi', $data->id)->where('penulis_utama', true)->delete();
            $validated_member = $this->validate_member($data->id, $request);

            $utama                = new PublicationMember;
            $utama->id_publikasi  = $data->id;
            $utama->nidn          = $validated_member['nidn'];
            $utama->nim           = $validated_member['nim'];
            $utama->nama          = $validated_member['nama'];
            $utama->asal          = $validated_member['asal'];
            $utama->status        = $validated_member['status'];
            $utama->penulis_utama = true;
            $utama->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->judul . ' (' . $data->academicYear->tahun_akademik . ' - ' . $data->academicYear->semester . ')',
                'url'   => route('publication.show', encrypt($data->id)),
            ];
            $this->log('updated', 'Publikasi Dosen', $property);

            DB::commit();

            if (Auth::user()->hasRole('dosen')) {
                return redirect()->route('profile.publication.show', encrypt($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
            } else {
                return redirect()->route('publication.show', encrypt($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }
    }

    public function destroy($id)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($id);

            //Query
            $data = Publication::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->judul,
            ];
            $this->log('deleted', 'Publikasi Dosen', $property);

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

    public function store_member(PublicationMemberRequest $request)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {

            $id_publikasi = decrypt($request->publikasi_id);
            //Publikasi
            $publikasi = Publication::find($id_publikasi);

            //Start Query
            $validated_member = $this->validate_member($publikasi->id, $request);

            $penulis                = new PublicationMember;
            $penulis->id_publikasi  = $publikasi->id;
            $penulis->nidn          = $validated_member['nidn'];
            $penulis->nim           = $validated_member['nim'];
            $penulis->nama          = $validated_member['nama'];
            $penulis->asal          = $validated_member['asal'];
            $penulis->status        = $validated_member['status'];
            $penulis->penulis_utama = false;
            $penulis->save();

            //Activity Log
            $property = [
                'id'    => $penulis->id,
                'name'  => $publikasi->judul,
                'url'   => route('publication.show', encrypt($id_publikasi))
            ];
            $this->log('created', 'Penulis publikasi', $property);

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

    public function destroy_member(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($request->id);

            //Query
            $data = PublicationMember::find($id);
            $data_id = $data->id;
            $publikasi = Publication::find($data->id_publikasi);

            //Hapus
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data_id,
                'name'  => $publikasi->judul,
                'url'   => route('publication.show', encrypt($publikasi->id))
            ];
            $this->log('deleted', 'Penulis publikasi', $property);

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

    public function validate_member($id_publikasi, $request)
    {
        if ($request->status_penulis == 'Sendiri') {
            $nidn = auth()->user()->username;
            $nim  = null;
            $nama = null;
            $asal = null;
            $status = 'Dosen';
        } else if ($request->status_penulis == 'Dosen') {
            if (!Teacher::find($request->penulis_nidn))
                throw new \Exception('NIDN Dosen tidak ditemukan. Periksa kembali.');

            $cekPenulisLain = PublicationMember::where('id_publikasi', $id_publikasi)->where('nim', $request->penulis_nidn)->exists();
            if ($cekPenulisLain)
                throw new \Exception('NIDN sudah ada. Periksa kembali.');

            $nidn = $request->penulis_nidn;
            $nim  = null;
            $nama = null;
            $asal = null;
            $status = $request->status_penulis;
        } else if ($request->status_penulis == 'Mahasiswa') {
            if (!Student::find($request->penulis_nim))
                throw new \Exception('NIM Mahasiswa tidak ditemukan. Periksa kembali.');

            $cekPenulisLain = PublicationMember::where('id_publikasi', $id_publikasi)->where('nim', $request->penulis_nim)->exists();
            if ($cekPenulisLain)
                throw new \Exception('NIM sudah ada. Periksa kembali.');

            $nidn  = null;
            $nim   = $request->penulis_nim;
            $nama  = null;
            $asal  = null;
            $status = $request->status_penulis;
        } else {
            $nidn   = null;
            $nim    = null;
            $nama   = $request->penulis_nama;
            $asal   = $request->penulis_asal;
            $status = $request->status_penulis;
        }

        if (auth()->user()->hasRole('dosen')) {
            if ($request->status_penulis != 'Sendiri') {
                PublicationMember::updateOrCreate(
                    [
                        'id_publikasi' => $id_publikasi,
                        'nidn'         => auth()->user()->username,
                    ],
                    [
                        'nim'           => null,
                        'nama'          => null,
                        'asal'          => null,
                        'status'        => 'Dosen',
                    ]
                );
            } else {
                PublicationMember::where('id_publikasi', $id_publikasi)->where('nidn', auth()->user()->username)->delete();
            }
        }

        return [
            'nidn' => $nidn,
            'nim'  => $nim,
            'nama' => $nama,
            'asal' => $asal,
            'status' => $status,
        ];
    }

    public function datatable(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        if (Auth::user()->hasRole('kaprodi')) {
            $data    = Publication::whereHas(
                'publikasiNotLainnya',
                function ($query) use ($request) {
                    $query->prodi(Auth::user()->kd_prodi, $request->type_filter);
                }
            );
        } else {
            $data    = Publication::whereHas(
                'publikasiNotLainnya',
                function ($query) use ($request) {
                    $query->jurusan(setting('app_department_id'), $request->type_filter);
                }
            );
        }

        if ($request->kd_prodi_filter) {
            $data->whereHas(
                'publikasiNotLainnya',
                function ($q) use ($request) {
                    $q->prodi($request->kd_prodi_filter, $request->type_filter);
                }
            );
        }

        return DataTables::of($data->get())
            ->addColumn('publikasi', function ($d) {
                return  '<a href="' . route('publication.show', encrypt($d->id)) . '" target="_blank">'
                    . $d->judul .
                    '</a>';
            })
            // ->addColumn('milik', function ($d) {
            //     return  '<a href="' . route('teacher.list.show', $d->teacher->nidn) . '#publication">'
            //         . $d->teacher->nama .
            //         '<br><small>NIDN.' . $d->teacher->nidn . ' / ' . $d->teacher->latestStatus->studyProgram->singkatan . '</small>
            //                             </a>';
            // })
            ->addColumn('tahun', function ($d) {
                return  $d->academicYear->tahun_akademik . ' - ' . $d->academicYear->semester;
            })
            ->addColumn('kategori', function ($d) {
                return  $d->publicationCategory->nama;
            })
            ->editColumn('sesuai_prodi', function ($d) {
                if ($d->sesuai_prodi) {
                    return '<i class="fa fa-check"></i>';
                }
            })
            ->addColumn('aksi', function ($d) {
                if (!Auth::user()->hasRole('kajur')) {
                    return view('publication.table-button', compact('d'))->render();
                }
            })
            ->rawColumns(['publikasi', 'milik', 'aksi'])
            ->make();
    }
}
