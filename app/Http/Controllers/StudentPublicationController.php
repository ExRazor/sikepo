<?php

namespace App\Http\Controllers;

use App\Models\PublicationCategory;
use App\Models\StudentPublication;
use App\Models\StudentPublicationMember;
use App\Models\StudyProgram;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StudentPublicationController extends Controller
{
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

        if(Auth::user()->hasRole('kaprodi')) {
            $publikasi    = StudentPublication::whereHas(
                                                    'student.studyProgram', function($query) {
                                                        $query->where('kd_prodi',Auth::user()->kd_prodi);
                                                    }
                                                )->get();
        } else {
            $publikasi    = StudentPublication::whereHas(
                                                    'student.studyProgram', function($query) {
                                                        $query->where('kd_jurusan',setting('app_department_id'));
                                                    }
                                                )->get();
        }

        return view('publication.student.index',compact(['publikasi','studyProgram']));
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

    public function store(Request $request)
    {
        $request->validate([
            'jenis_publikasi' => 'required',
            'nim'             => 'required',
            'judul'           => 'required',
            'penerbit'        => 'required',
            'tahun'           => 'required|numeric|digits:4',
            'jurnal'          => 'nullable',
            'sesuai_prodi'    => 'nullable',
            'akreditasi'      => 'nullable',
            'sitasi'          => 'nullable|numeric',
            'tautan'          => 'nullable|url',
        ]);

        $data                   = new StudentPublication;
        $data->jenis_publikasi  = $request->jenis_publikasi;
        $data->nim              = $request->nim;
        $data->judul            = $request->judul;
        $data->penerbit         = $request->penerbit;
        $data->tahun            = $request->tahun;
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

        return redirect()->route('publication.student.index')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $request->validate([
            'jenis_publikasi' => 'required',
            'nim'             => 'required',
            'judul'           => 'required',
            'penerbit'        => 'required',
            'tahun'           => 'required|numeric|digits:4',
            'jurnal'          => 'nullable',
            'sesuai_prodi'    => 'nullable',
            'akreditasi'      => 'nullable',
            'sitasi'          => 'nullable|numeric',
            'tautan'          => 'nullable|url',
        ]);

        $data                   = StudentPublication::find($id);
        $data->jenis_publikasi  = $request->jenis_publikasi;
        $data->nim              = $request->nim;
        $data->judul            = $request->judul;
        $data->penerbit         = $request->penerbit;
        $data->tahun            = $request->tahun;
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

        return redirect()->route('publication.student.show',encode_id($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decode_id($request->id);
            $q  = StudentPublication::find($id)->delete();
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
