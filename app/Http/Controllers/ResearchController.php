<?php

namespace App\Http\Controllers;

use App\AcademicYear;
use App\Research;
use App\StudyProgram;
use App\Faculty;
use App\Teacher;
use App\ResearchStudent;
use App\ResearchTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class ResearchController extends Controller
{
    public function __construct()
    {
        $method = [
            'create',
            'edit',
            'store',
            'update',
            'destroy',
            'destroy_teacher',
            'destroy_students',
        ];

        $this->middleware('role:admin,kaprodi', ['only' => $method]);
    }

    public function index()
    {
        $faculty = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('research.index',compact(['studyProgram','faculty']));
    }

    public function show($id)
    {
        $id   = decode_id($id);
        $data         = Research::where('id',$id)->first();

        return view('research.show',compact(['data']));
    }

    public function create()
    {
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        return view('research.form',compact(['studyProgram','faculty']));
    }

    public function edit($id)
    {
        $id   = decode_id($id);

        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $data         = Research::where('id',$id)->first();

        return view('research.form',compact(['data','studyProgram']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_ta'             => 'required',
            'ketua_nidn'        => 'required',
            'judul_penelitian'  => 'required',
            'tema_penelitian'   => 'required',
            'tingkat_penelitian'=> 'required',
            'sks_penelitian'    => 'required|numeric',
            'sesuai_prodi'      => 'nullable',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
            'jumlah_biaya'      => 'required',
        ]);

        //Simpan Data Penelitian
        $research                    = new Research;
        $research->id_ta             = $request->id_ta;
        $research->judul_penelitian  = $request->judul_penelitian;
        $research->tema_penelitian   = $request->tema_penelitian;
        $research->tingkat_penelitian= $request->tingkat_penelitian;
        $research->sks_penelitian    = $request->sks_penelitian;
        $research->sesuai_prodi      = $request->sesuai_prodi;
        $research->sumber_biaya      = $request->sumber_biaya;
        $research->sumber_biaya_nama = $request->sumber_biaya_nama;
        $research->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $research->save();

        //Jumlah SKS
        $sks_ketua      = floatval($request->sks_penelitian)*setting('research_ratio_chief')/100;
        $sks_anggota    = floatval($request->sks_penelitian)*setting('research_ratio_members')/100;

        //Tambah Ketua
        $ketua                  = new ResearchTeacher;
        $ketua->id_penelitian   = $research->id;
        $ketua->nidn            = $request->ketua_nidn;
        $ketua->status          = 'Ketua';
        $ketua->sks             = $sks_ketua;
        $ketua->save();

        //Tambah Anggota Dosen
        if($request->anggota_nidn) {
            $hitungDsn = count($request->anggota_nidn);
            for($i=0;$i<$hitungDsn;$i++) {
                ResearchTeacher::updateOrCreate(
                    [
                        'id_penelitian' => $research->id,
                        'nidn'          => $request->anggota_nidn[$i],
                    ],
                    [
                        'status'     => 'Anggota',
                        'sks'        => $sks_anggota,
                    ]
                );
            }
        }


        //Tambah Mahasiswa
        if($request->mahasiswa_nim) {
            $hitungMhs = count($request->mahasiswa_nim);
            for($i=0;$i<$hitungMhs;$i++) {
                ResearchStudent::updateOrCreate(
                    [
                        'id_penelitian' => $research->id,
                        'nim'           => $request->mahasiswa_nim[$i],
                    ]
                );
            }
        }

        return redirect()->route('research.index')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        // dd($request->all());

        $request->validate([
            'id_ta'             => 'required',
            // 'ketua_nidn'        => 'required|unique:research_teachers,nidn,'.$id.',id_penelitian',
            'judul_penelitian'  => 'required',
            'tema_penelitian'   => 'required',
            'tingkat_penelitian'=> 'required',
            'sks_penelitian'    => 'required|numeric',
            'sesuai_prodi'      => 'nullable',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
            'jumlah_biaya'      => 'required',
        ]);

        //Simpan Data Penelitian
        $research                    = Research::find($id);
        $research->id_ta             = $request->id_ta;
        $research->judul_penelitian  = $request->judul_penelitian;
        $research->tema_penelitian   = $request->tema_penelitian;
        $research->tingkat_penelitian= $request->tingkat_penelitian;
        $research->sks_penelitian    = $request->sks_penelitian;
        $research->sesuai_prodi      = $request->sesuai_prodi;
        $research->sumber_biaya      = $request->sumber_biaya;
        $research->sumber_biaya_nama = $request->sumber_biaya_nama;
        $research->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $research->save();

        //Jumlah SKS
        $sks_ketua      = floatval($request->sks_penelitian)*setting('research_ratio_chief')/100;
        $sks_anggota    = floatval($request->sks_penelitian)*setting('research_ratio_members')/100;

        //Update Ketua
        $ketua = ResearchTeacher::where('id_penelitian',$id)->where('status','Ketua');
        if($ketua != $request->ketua_nidn) {
            $ketua->delete();

            $new_ketua                  = new ResearchTeacher;
            $new_ketua->id_penelitian   = $id;
            $new_ketua->nidn            = $request->ketua_nidn;
            $new_ketua->status          = 'Ketua';
            $new_ketua->sks             = $sks_ketua;
            $new_ketua->save();
        } else {

            $ketua->id_penelitian = $id;
            $ketua->nidn          = $request->ketua_nidn;
            $ketua->sks           = $sks_ketua;
            $ketua->save();
        }

        //Update Anggota
        if($request->anggota_nidn) {
            $hitungDsn = count($request->anggota_nidn);
            for($i=0;$i<$hitungDsn;$i++) {

                ResearchTeacher::updateOrCreate(
                    [
                        'id_penelitian' => $id,
                        'nidn'          => $request->anggota_nidn[$i],
                    ],
                    [
                        'status'     => 'Anggota',
                        'sks'        => $sks_anggota,
                    ]
                );
            }
        }

        //Update Mahasiswa
        if($request->mahasiswa_nim) {
            $hitungMhs = count($request->mahasiswa_nim);
            for($i=0;$i<$hitungMhs;$i++) {

                ResearchStudent::updateOrCreate(
                    [
                        'id_penelitian' => $id,
                        'nim'           => $request->mahasiswa_nim[$i],
                    ]
                );
            }
        }

        return redirect()->route('research.show',encode_id($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decode_id($request->id);
            $q  = Research::find($id)->delete();
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

    public function destroy_teacher(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = ResearchTeacher::find($id)->delete();
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

    public function destroy_students(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = ResearchStudent::find($id)->delete();
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
            $data   = Research::whereHas(
                                        'researchTeacher', function($q) {
                                            $q->prodiKetua(Auth::user()->kd_prodi);
                                        }
                                    );

        } else {
            $data   = Research::whereHas(
                                        'researchTeacher', function($q) {
                                            $q->jurusanKetua(setting('app_department_id'));
                                        }
                                    );
        }

        if($request->kd_prodi_filter) {
            $data->whereHas(
                'researchTeacher', function($q) use($request) {
                    $q->prodiKetua($request->kd_prodi_filter);
                }
            );
        }

        return DataTables::of($data->get())
                            ->addColumn('penelitian', function($d) {
                                return  '<a href="'.route('research.show',encode_id($d->id)).'" target="_blank">'
                                            .$d->judul_penelitian.
                                        '</a>';
                            })
                            ->addColumn('tahun', function($d) {
                                return $d->academicYear->tahun_akademik.' - '.$d->academicYear->semester;
                            })
                            ->addColumn('peneliti', function($d) {
                                return  '<a href="'.route('teacher.list.show',$d->researchKetua->teacher->nidn).'#research">'
                                            .$d->researchKetua->teacher->nama.
                                            '<br><small>NIDN.'.$d->researchKetua->teacher->nidn.' / '.$d->researchKetua->teacher->studyProgram->singkatan.'</small>
                                        </a>';
                            })
                            ->addColumn('aksi', function($d) {
                                if(!Auth::user()->hasRole('kajur')) {
                                    return view('research.table-button', compact('d'))->render();
                                }
                            })
                            ->rawColumns(['penelitian','peneliti','aksi'])
                            ->make();
    }

    public function chart(Request $request)
    {
        $query = Research::whereHas(
            'researchTeacher', function($q) {
                if(Auth::user()->hasRole('kaprodi')) {
                    $q->prodiKetua(Auth::user()->kd_prodi);
                } else {
                    $q->jurusanKetua(setting('app_department_id'));
                }
            }
        )->get();

        $a = $request->tahun_a;
        $b = $request->tahun_b;
        $thn = current_academic()->tahun_akademik;
        $academicYear = AcademicYear::whereBetween('tahun_akademik',[$thn-5,$thn])->get();

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

        foreach($academicYear as $ay) {
            $result[$ay->tahun_akademik] = $query->where('id_ta',$ay->id)->count();
        }

        return response()->json($result);
    }

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {

            $q   = Research::with([
                                    'academicYear',
                                    'researchKetua.teacher.studyProgram',
                                    'researchAnggota.teacher.studyProgram.department',
                                    'researchStudent.student.studyProgram.department'
                                ]);

            if($request->kd_jurusan){
                $q->whereHas(
                    'researchTeacher', function($q) use($request) {
                        $q->jurusanKetua($request->kd_jurusan);
                    }
                );
            }

            if($request->kd_prodi){
                $q->whereHas(
                    'researchTeacher', function($q) use ($request) {
                        $q->prodiKetua($request->kd_prodi);
                    }
                );
            }

            $data = $q->orderBy('id_ta','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function get_by_department(Request $request)
    {
        if($request->has('cari')){
            $cari = $request->cari;
            $data = Research::whereHas(
                                'researchTeacher', function($q) {
                                    $q->jurusanKetua(setting('app_department_id'));
                                }
                            )
                            ->where('judul_penelitian', 'LIKE', '%'.$cari.'%')
                            ->orWhere('id','LIKE','%'.$cari.'%')
                            ->get();

            $response = array();
            foreach($data as $d){
                $response[] = array(
                    "id"    => $d->id,
                    "text"  => $d->judul_penelitian.' ('.$d->academicYear->tahun_akademik.')'
                );
            }
            return response()->json($response);
        }
    }
}
