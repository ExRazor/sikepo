<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentPublicationRequest;
use App\Models\PublicationCategory;
use App\Models\StudentPublication;
use App\Models\StudentPublicationMember;
use App\Models\StudyProgram;
use App\Models\Student;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class StudentPublicationController extends Controller
{
    use LogActivity;

    public function __construct()
    {
        $method = [
            'create',
            'edit',
            'store',
            'update',
            'destroy',
            'destroy_member',
        ];

        $this->middleware('role:admin,kaprodi', ['only' => $method]);
    }

    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('publication.student.index',compact(['studyProgram']));
    }

    public function create()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $jenis        = PublicationCategory::all();

        return view('publication.student.form',compact(['studyProgram','jenis']));
    }

    public function show($id)
    {
        $id   = decode_id($id);
        $data = StudentPublication::find($id);

        return view('publication.student.show',compact(['data']));
    }

    public function edit($id)
    {
        $id   = decode_id($id);

        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $jenis        = PublicationCategory::all();
        $data         = StudentPublication::with('student','publicationMembers')->where('id',$id)->first();
        $student      = Student::where('kd_prodi',$data->student->kd_prodi)->get();

        return view('publication.student.form',compact(['jenis','data','studyProgram','student']));
    }

    public function store(StudentPublicationRequest $request)
    {
        DB::beginTransaction();
        try {
            //Query
            $data                   = new StudentPublication;
            $data->jenis_publikasi  = $request->jenis_publikasi;
            $data->nim              = $request->nim;
            $data->judul            = $request->judul;
            $data->penerbit         = $request->penerbit;
            $data->id_ta            = $request->id_ta;
            $data->sesuai_prodi     = $request->sesuai_prodi;
            $data->jurnal           = $request->jurnal;
            $data->akreditasi       = $request->akreditasi;
            $data->sitasi           = $request->sitasi;
            $data->tautan           = $request->tautan;
            $data->save();

            //Tambah Mahasiswa
            if($request->anggota_nim) {
                $hitungMhs = count($request->anggota_nim);
                for($i=0;$i<$hitungMhs;$i++) {
                    StudentPublicationMember::updateOrCreate(
                        [
                            'id_publikasi'  => $data->id,
                            'nim'           => $request->anggota_nim[$i],
                        ],
                        [
                            'nama'          => $request->anggota_nama[$i],
                            'kd_prodi'      => $request->anggota_prodi[$i],
                        ]
                    );
                }
            }

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->judul.' ('.$data->student->nama.')',
                'url'   => route('publication.student.show',encode_id($data->id)),
            ];
            $this->log('created','Publikasi Mahasiswa',$property);

            DB::commit();
            return redirect()->route('publication.student.index')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }
    }

    public function update(StudentPublicationRequest $request)
    {
        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($request->id);

            //Query
            $data                   = StudentPublication::find($id);
            $data->jenis_publikasi  = $request->jenis_publikasi;
            $data->nim              = $request->nim;
            $data->judul            = $request->judul;
            $data->penerbit         = $request->penerbit;
            $data->id_ta            = $request->id_ta;
            $data->sesuai_prodi     = $request->sesuai_prodi;
            $data->jurnal           = $request->jurnal;
            $data->akreditasi       = $request->akreditasi;
            $data->sitasi           = $request->sitasi;
            $data->tautan           = $request->tautan;
            $data->save();

            //Update Mahasiswa
            if($request->anggota_nim) {
                $hitungMhs = count($request->anggota_nim);
                for($i=0;$i<$hitungMhs;$i++) {

                    StudentPublicationMember::updateOrCreate(
                        [
                            'id_publikasi'  => $id,
                            'nim'           => $request->anggota_nim[$i],
                        ],
                        [
                            'nama'          => $request->anggota_nama[$i],
                            'kd_prodi'      => $request->anggota_prodi[$i],
                        ]
                    );
                }
            }

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->judul.' ('.$data->student->nama.')',
                'url'   => route('publication.student.show',encode_id($data->id)),
            ];
            $this->log('updated','Publikasi Mahasiswa',$property);

            DB::commit();
            return redirect()->route('publication.student.show',encode_id($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
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
            $data  = StudentPublication::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->judul.' ('.$data->student->nama.')',
            ];
            $this->log('deleted','Publikasi Mahasiswa',$property);

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
            $q  = StudentPublicationMember::find($id)->delete();
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
            $data    = StudentPublication::whereHas(
                                            'student.studyProgram', function($query) {
                                                $query->where('kd_prodi',Auth::user()->kd_prodi);
                                            }
                                        );
        } else {
            $data    = StudentPublication::whereHas(
                                            'student.studyProgram', function($query) {
                                                $query->where('kd_jurusan',setting('app_department_id'));
                                            }
                                        );
        }

        if($request->kd_prodi_filter) {
            $data->whereHas(
                'student.studyProgram', function($q) use($request) {
                    $q->where('kd_prodi',$request->kd_prodi_filter);
                }
            );
        }

        return DataTables::of($data->get())
                            ->addColumn('publikasi', function($d) {
                                return  '<a href="'.route('publication.student.show',encode_id($d->id)).'" target="_blank">'
                                            .$d->judul.
                                        '</a>';
                            })
                            ->addColumn('milik', function($d) {
                                return  '<a href="'.route('student.list.show',$d->student->nim).'#publication">'
                                            .$d->student->nama.
                                            '<br><small>NIM.'.$d->student->nim.' / '.$d->student->studyProgram->singkatan.'</small>
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
                                    return view('publication.student.table-button', compact('d'))->render();
                                }
                            })
                            ->rawColumns(['publikasi','milik','aksi'])
                            ->make();
    }
}
