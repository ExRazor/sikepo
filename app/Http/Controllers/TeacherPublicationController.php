<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherPublicationRequest;
use App\Models\PublicationCategory;
use App\Models\TeacherPublication;
use App\Models\TeacherPublicationMember;
use App\Models\TeacherPublicationStudent;
use App\Models\StudyProgram;
use App\Models\Teacher;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TeacherPublicationController extends Controller
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
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('publication.teacher.index',compact(['studyProgram']));
    }

    public function index_teacher()
    {
        $publikasiKetua    = TeacherPublication::where('nidn',Auth::user()->username)->get();

        $publikasiAnggota  = TeacherPublication::whereHas(
                                                    'publicationMembers', function($query) {
                                                        $query->where('nidn',Auth::user()->username);
                                                    }
                                                )
                                                ->get();

        return view('teacher-view.publication.index',compact(['publikasiKetua','publikasiAnggota']));
    }

    public function create()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $jenis        = PublicationCategory::all();

        return view('publication.teacher.form',compact(['studyProgram','jenis']));
    }

    public function create_teacher()
    {
        $jenis        = PublicationCategory::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('teacher-view.publication.form',compact(['studyProgram','jenis']));
    }

    public function show($id)
    {
        $id   = decode_id($id);
        $data = TeacherPublication::find($id);

        return view('publication.teacher.show',compact(['data']));
    }

    public function show_teacher($id)
    {
        $id   = decode_id($id);
        $data = TeacherPublication::find($id);

        return view('teacher-view.publication.show',compact(['data']));
    }

    public function edit($id)
    {
        $id   = decode_id($id);

        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $jenis        = PublicationCategory::all();
        $data         = TeacherPublication::with('teacher','publicationStudents')->where('id',$id)->first();
        $teacher      = Teacher::whereHas('latestStatus.studyProgram', function($q) use($data) {
                            $q->where('kd_prodi',$data->teacher->latestStatus->studyProgram->kd_prodi);
                        })->get();

        return view('publication.teacher.form',compact(['jenis','data','studyProgram','teacher']));
    }

    public function edit_teacher($id)
    {
        $id   = decode_id($id);

        $jenis        = PublicationCategory::all();
        $data         = TeacherPublication::with('teacher','publicationStudents')->where('id',$id)->first();
        $teacher      = Teacher::whereHas('latestStatus.studyProgram', function($q) use($data) {
                            $q->where('kd_prodi',$data->teacher->latestStatus->studyProgram->kd_prodi);
                        })->get();

        return view('teacher-view.publication.form',compact(['jenis','data','teacher']));
    }

    public function store(TeacherPublicationRequest $request)
    {
        DB::beginTransaction();
        try {
            if(Auth::user()->hasRole('dosen')) {
                $nidn = Auth::user()->username;
            } else {
                $nidn = $request->nidn;
            }

            //Query
            $data                   = new TeacherPublication;
            $data->jenis_publikasi  = $request->jenis_publikasi;
            $data->nidn             = $nidn;
            $data->judul            = $request->judul;
            $data->penerbit         = $request->penerbit;
            $data->id_ta            = $request->id_ta;
            $data->sesuai_prodi     = $request->sesuai_prodi;
            $data->jurnal           = $request->jurnal;
            $data->akreditasi       = $request->akreditasi;
            $data->sitasi           = $request->sitasi;
            $data->tautan           = $request->tautan;
            $data->save();

            //Tambah Anggota Dosen
            if($request->anggota_nidn) {
                $hitungDsn = count($request->anggota_nidn);
                for($i=0;$i<$hitungDsn;$i++) {
                    TeacherPublicationMember::updateOrCreate(
                        [
                            'id_publikasi' => $data->id,
                            'nidn'         => $request->anggota_nidn[$i],
                        ],
                        [
                            'nama'         => $request->anggota_nama[$i],
                            'kd_prodi'     => $request->anggota_prodi[$i],
                        ]
                    );
                }
            }

            //Tambah Mahasiswa
            if($request->mahasiswa_nim) {
                $hitungMhs = count($request->mahasiswa_nim);
                for($i=0;$i<$hitungMhs;$i++) {
                    TeacherPublicationStudent::updateOrCreate(
                        [
                            'id_publikasi'  => $data->id,
                            'nim'           => $request->mahasiswa_nim[$i],
                        ],
                        [
                            'nama'          => $request->mahasiswa_nama[$i],
                            'kd_prodi'      => $request->mahasiswa_prodi[$i],
                        ]
                    );
                }
            }

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->judul.' ('.$data->teacher->nama.')',
                'url'   => route('publication.teacher.show',encode_id($data->id)),
            ];
            $this->log('created','Publikasi Dosen',$property);

            DB::commit();
            if(Auth::user()->hasRole('dosen')) {
                return redirect()->route('profile.publication')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
            } else {
                return redirect()->route('publication.teacher.index')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
            }
        } catch(\Exception $e) {
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

            if(Auth::user()->hasRole('dosen')) {
                $nidn = Auth::user()->username;
            } else {
                $nidn = $request->nidn;
            }

            //Query
            $data                   = TeacherPublication::find($id);
            $data->jenis_publikasi  = $request->jenis_publikasi;
            $data->nidn             = $nidn;
            $data->judul            = $request->judul;
            $data->penerbit         = $request->penerbit;
            $data->id_ta            = $request->id_ta;
            $data->sesuai_prodi     = $request->sesuai_prodi;
            $data->jurnal           = $request->jurnal;
            $data->akreditasi       = $request->akreditasi;
            $data->sitasi           = $request->sitasi;
            $data->tautan           = $request->tautan;
            $data->save();

            //Update Anggota Dosen
            if($request->anggota_nidn) {
                $hitungDsn = count($request->anggota_nidn);
                for($i=0;$i<$hitungDsn;$i++) {

                    TeacherPublicationMember::updateOrCreate(
                        [
                            'id_publikasi'  => $id,
                            'nidn'           => $request->anggota_nidn[$i],
                        ],
                        [
                            'nama'          => $request->anggota_nama[$i],
                            'kd_prodi'      => $request->anggota_prodi[$i],
                        ]
                    );
                }
            }

            //Update Mahasiswa
            if($request->mahasiswa_nim) {
                $hitungMhs = count($request->mahasiswa_nim);
                for($i=0;$i<$hitungMhs;$i++) {

                    TeacherPublicationStudent::updateOrCreate(
                        [
                            'id_publikasi'  => $id,
                            'nim'           => $request->mahasiswa_nim[$i],
                        ],
                        [
                            'nama'          => $request->mahasiswa_nama[$i],
                            'kd_prodi'      => $request->mahasiswa_prodi[$i],
                        ]
                    );
                }
            }

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->judul.' ('.$data->teacher->nama.')',
                'url'   => route('publication.teacher.show',encode_id($data->id)),
            ];
            $this->log('updated','Publikasi Dosen',$property);

            DB::commit();

            if(Auth::user()->hasRole('dosen')) {
                return redirect()->route('profile.publication.show',encode_id($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
            } else {
                return redirect()->route('publication.teacher.show',encode_id($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
            }
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }

    }

    public function destroy(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decode_id($request->id);

            //Query
            $data = TeacherPublication::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->judul.' ('.$data->teacher->nama.')',
            ];
            $this->log('deleted','Publikasi Dosen',$property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil dihapus',
                'type'    => 'success'
            ]);
        } catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ],400);
        }

    }

    public function destroy_member(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = TeacherPublicationMember::find($id)->delete();
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

    public function destroy_student(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = TeacherPublicationStudent::find($id)->delete();
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

    public function datatable(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        if(Auth::user()->hasRole('kaprodi')) {
            $data    = TeacherPublication::whereHas(
                                            'teacher.latestStatus.studyProgram', function($query) {
                                                $query->where('kd_prodi',Auth::user()->kd_prodi);
                                            }
                                        );
        } else {
            $data    = TeacherPublication::whereHas(
                                            'teacher.latestStatus.studyProgram', function($query) {
                                                $query->where('kd_jurusan',setting('app_department_id'));
                                            }
                                        );
        }

        if($request->kd_prodi_filter) {
            $data->whereHas(
                'teacher.latestStatus.studyProgram', function($q) use($request) {
                    $q->where('kd_prodi',$request->kd_prodi_filter);
                }
            );
        }

        return DataTables::of($data->get())
                            ->addColumn('publikasi', function($d) {
                                return  '<a href="'.route('publication.teacher.show',encode_id($d->id)).'" target="_blank">'
                                            .$d->judul.
                                        '</a>';
                            })
                            ->addColumn('milik', function($d) {
                                return  '<a href="'.route('teacher.list.show',$d->teacher->nidn).'#publication">'
                                            .$d->teacher->nama.
                                            '<br><small>NIDN.'.$d->teacher->nidn.' / '.$d->teacher->latestStatus->studyProgram->singkatan.'</small>
                                        </a>';
                            })
                            ->addColumn('tahun', function($d) {
                                return  $d->academicYear->tahun_akademik.' - '.$d->academicYear->semester;
                            })
                            ->addColumn('kategori', function($d) {
                                return  $d->publicationCategory->nama;
                            })
                            ->editColumn('sesuai_prodi', function($d) {
                                if($d->sesuai_prodi) {
                                    return '<i class="fa fa-check"></i>';
                                }
                            })
                            ->addColumn('aksi', function($d) {
                                if(!Auth::user()->hasRole('kajur')) {
                                    return view('publication.teacher.table-button', compact('d'))->render();
                                }
                            })
                            ->rawColumns(['publikasi','milik','aksi'])
                            ->make();
    }
}
