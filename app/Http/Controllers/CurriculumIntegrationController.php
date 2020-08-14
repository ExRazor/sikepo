<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurriculumIntegrationRequest;
use App\Models\CurriculumIntegration;
use App\Models\StudyProgram;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CurriculumIntegrationController extends Controller
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
        ];

        $this->middleware('role:admin,kaprodi', ['only' => $method]);
    }

    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('academic.integration.index',compact(['studyProgram']));
    }

    public function create()
    {
        return view('academic.integration.form');
    }

    public function edit($id)
    {
        // $id = decrypt($id);
        $data       = CurriculumIntegration::find($id);

        return view('academic.integration.form',compact(['data']));
    }

    public function store(CurriculumIntegrationRequest $request)
    {
        DB::beginTransaction();
        try {
            //Query
            $data                   = new CurriculumIntegration;
            $data->id_ta            = $request->id_ta;
            $data->id_penelitian    = $request->has('id_penelitian') ? $request->id_penelitian : null;
            $data->id_pengabdian    = $request->has('id_pengabdian') ? $request->id_pengabdian : null;
            $data->kegiatan         = $request->kegiatan;
            $data->nidn             = $request->nidn;
            $data->kd_matkul        = $request->kd_matkul;
            $data->bentuk_integrasi = $request->bentuk_integrasi;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nidn.' - '.$data->kegiatan,
                'url'   => route('academic.integration.index')
            ];
            $this->log('created','Integrasi Kurikulum',$property);

            DB::commit();
            return redirect()->route('academic.integration.index')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }
    }

    public function update(CurriculumIntegrationRequest $request)
    {
        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($request->id);

            //Query
            $data                   = CurriculumIntegration::find($id);
            $data->id_ta            = $request->id_ta;
            $data->id_penelitian    = $request->has('id_penelitian') ? $request->id_penelitian : null;
            $data->id_pengabdian    = $request->has('id_pengabdian') ? $request->id_pengabdian : null;
            $data->kegiatan         = $request->kegiatan;
            $data->nidn             = $request->nidn;
            $data->kd_matkul        = $request->kd_matkul;
            $data->bentuk_integrasi = $request->bentuk_integrasi;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nidn.' - '.$data->kegiatan,
                'url'   => route('academic.integration.index')
            ];
            $this->log('updated','Integrasi Kurikulum',$property);

            DB::commit();
            return redirect()->route('academic.integration.index')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
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
            $id = decrypt($request->id);
            $data  = CurriculumIntegration::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nidn.' - '.$data->kegiatan,
            ];
            $this->log('deleted','Integrasi Kurikulum',$property);

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

    public function datatable(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        // if(Auth::user()->hasRole('kaprodi')) {
        //     $data = CurriculumIntegration::whereHas(
        //         'curriculum.studyProgram', function($query) {
        //             $query->where('kd_prodi',Auth::user()->kd_prodi);
        //         }
        //     );
        // } else {
        //     $data = CurriculumIntegration::whereHas(
        //         'curriculum.studyProgram', function($query) {
        //             $query->where('kd_jurusan',setting('app_department_id'));
        //         }
        //     );
        // }

        $data = CurriculumIntegration::query();

        if($request->kd_prodi_dosen) {
            $data->whereHas('teacher.latestStatus.studyProgram', function($q) use($request) {
                $q->where('kd_prodi',$request->kd_prodi_dosen);
            });
        }

        if($request->kd_prodi_matkul) {
            $data->whereHas('curriculum', function($q) use($request) {
                $q->where('kd_prodi',$request->kd_prodi_matkul);
            });
        }

        return DataTables::of($data->get())
                            ->addColumn('akademik', function($d) {
                                return $d->academicYear->tahun_akademik.' - '.$d->academicYear->semester;
                            })
                            ->addColumn('matkul', function($d) {
                                return '<a name="'.$d->curriculum->nama.'" href='.route("academic.curriculum.show",$d->id).'>'.
                                            $d->curriculum->nama.
                                            '<br><small>'.$d->curriculum->studyProgram->singkatan.' / '.$d->kd_matkul.'</small>
                                        </a>';
                            })
                            ->addColumn('dosen', function($d) {
                                return '<a name="'.$d->teacher->nama.'" href='.route('teacher.list.show',$d->teacher->nidn).'>'.
                                            $d->teacher->nama.
                                            '<br>
                                            <small>NIDN. '.$d->teacher->nidn.' / '.$d->teacher->latestStatus->studyProgram->singkatan.'</small>
                                        </a>';
                            })
                            ->addColumn('kegiatan', function($d) {
                                if($d->kegiatan=='Penelitian') {
                                    $output =  '<a href="'.route('research.show',($d->research->id)).'">
                                                    '.$d->research->judul_penelitian.' ('.$d->research->academicYear->tahun_akademik.')
                                                </a>';
                                } else {
                                    $output =   '<a href="'.route('community-service.show',($d->communityService->id)).'">
                                                    '.$d->communityService->judul_pengabdian.' ('.$d->communityService->academicYear->tahun_akademik.')
                                                </a>';
                                }

                                return $output;
                            })
                            ->addColumn('aksi', function($d) {
                                if(!Auth::user()->hasRole('kajur')) {
                                    return view('academic.integration.table-button', compact('d'))->render();
                                }
                            })
                            ->rawColumns(['matkul','dosen','kegiatan','aksi'])
                            ->make();
    }
}
