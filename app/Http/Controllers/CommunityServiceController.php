<?php

namespace App\Http\Controllers;

use App\Exports\CommunityServiceExport;
use App\Http\Requests\CommunityServiceRequest;
use App\Models\AcademicYear;
use App\Models\CommunityService;
use App\Models\CommunityServiceTeacher;
use App\Models\CommunityServiceStudent;
use App\Models\StudyProgram;
use App\Models\Faculty;
use App\Models\Teacher;
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
        $periodeTahun = AcademicYear::groupBy('tahun_akademik')->orderBy('tahun_akademik','desc')->select('tahun_akademik')->get();

        // return response()->json($pengabdian);die;

        return view('community-service.index',compact(['studyProgram','faculty','periodeTahun']));
    }

    public function show($id)
    {
        $id   = decrypt($id);
        $data = CommunityService::where('id',$id)->first();

        return view('community-service.show',compact(['data']));
    }

    public function create()
    {
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        return view('community-service.form',compact(['studyProgram','faculty']));
    }

    public function edit($id)
    {
        $id   = decrypt($id);

        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $data         = CommunityService::where('id',$id)->first();

        return view('community-service.form',compact(['data','studyProgram']));
    }

    public function store(CommunityServiceRequest $request)
    {
        DB::beginTransaction();
        try{
            //Simpan Data Pengabdian
            $community                    = new CommunityService;
            $community->id_ta             = $request->id_ta;
            $community->judul_pengabdian  = $request->judul_pengabdian;
            $community->tema_pengabdian   = $request->tema_pengabdian;
            $community->sks_pengabdian    = $request->sks_pengabdian;
            $community->sesuai_prodi      = $request->sesuai_prodi;
            $community->sumber_biaya      = $request->sumber_biaya;
            $community->sumber_biaya_nama = $request->sumber_biaya_nama;
            $community->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
            $community->save();

            //Jumlah SKS
            $sks_ketua      = floatval($request->sks_pengabdian)*setting('service_ratio_chief')/100;
            $sks_anggota    = floatval($request->sks_pengabdian)*setting('service_ratio_members')/100;

            //Tambah Ketua
            $ketua                  = new CommunityServiceTeacher;
            $ketua->id_pengabdian   = $community->id;
            $ketua->nidn            = $request->ketua_nidn;
            $ketua->status          = 'Ketua';
            $ketua->sks             = $sks_ketua;
            $ketua->save();

            //Tambah Anggota Dosen
            if($request->anggota_nidn) {
                $hitungDsn = count($request->anggota_nidn);
                for($i=0;$i<$hitungDsn;$i++) {
                    CommunityServiceTeacher::updateOrCreate(
                        [
                            'id_penelitian' => $community->id,
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
                    CommunityServiceStudent::updateOrCreate(
                        [
                            'id_pengabdian' => $community->id,
                            'nim'           => $request->mahasiswa_nim[$i],
                        ]
                    );
                }
            }

            //Activity Log
            $property = [
                'id'    => $community->id,
                'name'  => $community->judul_pengabdian,
                'url'   => route('community-service.show',encrypt($community->id))
            ];
            $this->log('created','Pengabdian',$property);

            DB::commit();
            return redirect()->route('community-service.index')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
        } catch(\Exception $e) {
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
            $community->sks_pengabdian    = $request->sks_pengabdian;
            $community->sesuai_prodi      = $request->sesuai_prodi;
            $community->sumber_biaya      = $request->sumber_biaya;
            $community->sumber_biaya_nama = $request->sumber_biaya_nama;
            $community->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
            $community->save();

            //Jumlah SKS
            $sks_ketua      = floatval($request->sks_pengabdian)*setting('service_ratio_chief')/100;
            $sks_anggota    = floatval($request->sks_pengabdian)*setting('service_ratio_members')/100;

            //Update Ketua
            $ketua = CommunityServiceTeacher::where('id_pengabdian',$id)->where('status','Ketua');
            if($ketua != $request->ketua_nidn) {
                $ketua->delete();

                $new_ketua                  = new CommunityServiceTeacher;
                $new_ketua->id_pengabdian   = $id;
                $new_ketua->nidn            = $request->ketua_nidn;
                $new_ketua->status          = 'Ketua';
                $new_ketua->sks             = $sks_ketua;
                $new_ketua->save();
            } else {
                $ketua->id_pengabdian = $id;
                $ketua->nidn          = $request->ketua_nidn;
                $ketua->sks           = $sks_ketua;
                $ketua->save();
            }

            //Update Anggota
            if($request->anggota_nidn) {
                $hitungDsn = count($request->anggota_nidn);
                for($i=0;$i<$hitungDsn;$i++) {

                    CommunityServiceTeacher::updateOrCreate(
                        [
                            'id_pengabdian' => $id,
                            'nidn'          => $request->anggota_nidn[$i],
                        ],
                        [
                            'status'     => 'Anggota',
                            'sks'        => $sks_anggota,
                        ]
                    );
                }
            }

            //Update Anggota Mahasiswa
            if($request->mahasiswa_nim) {
                $hitungMhs = count($request->mahasiswa_nim);
                for($i=0;$i<$hitungMhs;$i++) {

                    CommunityServiceStudent::updateOrCreate(
                        [
                            'id_pengabdian' => $id,
                            'nim'           => $request->mahasiswa_nim[$i],
                        ]
                    );
                }
            }

            //Activity Log
            $property = [
                'id'    => $community->id,
                'name'  => $community->judul_pengabdian,
                'url'   => route('community-service.show',encrypt($community->id))
            ];
            $this->log('updated','Pengabdian',$property);

            DB::commit();
            return redirect()->route('community-service.show',encrypt($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
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
            $id = decrypt($request->id);

            //Query
            $data = CommunityService::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->judul_pengabdian,
            ];
            $this->log('deleted','Pengabdian',$property);

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

    public function destroy_teacher(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = CommunityServiceTeacher::find($id)->delete();
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
            $q  = CommunityServiceStudent::find($id)->delete();
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

    public function export(Request $request)
	{
		// Request
        $tgl         = date('dmYhis');
        if($request->tipe == 'prodi') {
            $idn       = ($request->kd_prodi ? $request->kd_prodi.'_' : null);
        } else {
            $idn       = ($request->nidn ? $request->nidn.'_' : null);
        }

        if(empty($request->periode_akhir)) {
            $periode = $request->periode_awal.'_';
        } else {
            $periode = $request->periode_awal.'-'.$request->periode_akhir.'_';
        }

        $nama_file   = 'Data_Pengabdian_'.$idn.$periode.$tgl.'.xlsx';
        $lokasi_file = storage_path('app/upload/temp/'.$nama_file);

		// Ekspor data
        return (new CommunityServiceExport($request))->download($nama_file);
    }

    public function datatable(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        if(Auth::user()->hasRole('kaprodi')) {
            $data   = CommunityService::whereHas(
                                            'serviceTeacher', function($q) {
                                                $q->prodiKetua(Auth::user()->kd_prodi);
                                            }
                                        );
        } else {
            $data   = CommunityService::whereHas(
                                            'serviceTeacher', function($q) {
                                                $q->jurusanKetua(setting('app_department_id'));
                                            }
                                        );
        }

        if($request->kd_prodi_filter) {
            $data->whereHas(
                'serviceTeacher', function($q) use($request) {
                    $q->prodiKetua($request->kd_prodi_filter);
                }
            );
        }

        return DataTables::of($data->get())
                            ->addColumn('pengabdian', function($d) {
                                return  '<a href="'.route('community-service.show',encrypt($d->id)).'" target="_blank">'
                                            .$d->judul_pengabdian.
                                        '</a>';
                            })
                            ->addColumn('tahun', function($d) {
                                return $d->academicYear->tahun_akademik.' - '.$d->academicYear->semester;
                            })
                            ->addColumn('pelaksana', function($d) {
                                return  '<a href="'.route('teacher.list.show',$d->serviceKetua->teacher->nidn).'#community-service">'
                                            .$d->serviceKetua->teacher->nama.
                                            '<br><small>NIDN.'.$d->serviceKetua->teacher->nidn.' / '.$d->serviceKetua->teacher->latestStatus->studyProgram->singkatan.'</small>
                                        </a>';
                            })
                            ->addColumn('aksi', function($d) {
                                if(!Auth::user()->hasRole('kajur')) {
                                    return view('community-service.table-button', compact('d'))->render();
                                }
                            })
                            ->rawColumns(['pengabdian','pelaksana','aksi'])
                            ->make();
    }

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {

            $q   = CommunityService::with([
                                    'academicYear',
                                    'serviceKetua.teacher.latestStatus.studyProgram',
                                    'serviceAnggota.teacher.latestStatus.studyProgram.department',
                                    'serviceStudent.student.studyProgram.department'
                                ]);

            if($request->kd_jurusan){
                $q->whereHas(
                    'serviceTeacher', function($q) use($request) {
                        $q->jurusanKetua($request->kd_jurusan);
                    }
                );
            }

            if(Auth::user()->hasRole('kaprodi')) {
                $q->whereHas(
                    'serviceTeacher', function($q) use ($request) {
                        $q->prodiKetua(Auth::user()->kd_prodi);
                    }
                );
            }

            if($request->kd_prodi){
                $q->whereHas(
                    'serviceTeacher', function($q) use ($request) {
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
            $data = CommunityService::whereHas(
                                        'serviceTeacher', function($q) {
                                            $q->jurusanKetua(setting('app_department_id'));
                                        }
                                    )
                                    ->where('judul_pengabdian', 'LIKE', '%'.$cari.'%')
                                    ->orWhere('id','LIKE','%'.$cari.'%')
                                    ->get();

            $response = array();
            foreach($data as $d){
                $response[] = array(
                    "id"    => $d->id,
                    "text"  => $d->judul_pengabdian.' ('.$d->academicYear->tahun_akademik.')'
                );
            }
            return response()->json($response);
        }
    }
}
