<?php

namespace App\Http\Controllers;

use App\Exports\CollaborationExport;
use App\Http\Requests\CollaborationRequest;
use App\Models\Collaboration;
use App\Models\StudyProgram;
use App\Models\AcademicYear;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CollaborationController extends Controller
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
                    'delete_file'
                ];
        $this->middleware('role:admin,kaprodi', ['only' => $method]);
    }

    public function index()
    {
        // $data = Collaboration::all();

        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $periodeTahun = AcademicYear::groupBy('tahun_akademik')->orderBy('tahun_akademik','desc')->select('tahun_akademik')->get();

        return view('collaboration/index',compact(['studyProgram','periodeTahun']));
    }

    public function create()
    {
        $academicYear = AcademicYear::orderBy('tahun_akademik','desc')->orderBy('semester','desc')->get();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        return view('collaboration/form',compact(['academicYear','studyProgram']));
    }

    public function show($id)
    {
        $data = Collaboration::find($id);

        return view('collaboration.show',compact(['data']));
    }

    public function edit($id)
    {
        // $id = decode_id($id);
        $data = Collaboration::find($id);

        $academicYear = AcademicYear::all();
        $studyProgram = StudyProgram::all();
        return view('collaboration/form',compact(['academicYear','studyProgram','data']));

    }

    public function store(CollaborationRequest $request)
    {
        DB::beginTransaction();
        try {
            $collaboration = new Collaboration;
            $collaboration->kd_prodi         = $request->kd_prodi;
            $collaboration->id_ta            = $request->id_ta;
            $collaboration->jenis            = $request->jenis;
            $collaboration->nama_lembaga     = $request->nama_lembaga;
            $collaboration->tingkat          = $request->tingkat;
            $collaboration->judul_kegiatan   = $request->judul_kegiatan;
            $collaboration->manfaat_kegiatan = $request->manfaat_kegiatan;
            $collaboration->waktu            = $request->waktu;
            $collaboration->durasi           = $request->durasi;
            $collaboration->bukti_nama       = $request->bukti_nama;

            if($request->file('bukti_file')) {
                $file = $request->file('bukti_file');
                $tgl_skrg = date('Y_m_d_H_i_s');
                $tujuan_upload = public_path('upload/collaboration');
                $filename = $request->kd_prodi.'_'.$request->nama_lembaga.'_'.$request->tingkat.'_'.$request->judul_kegiatan.'_'.$tgl_skrg.'.'.$file->getClientOriginalExtension();
                $file->move($tujuan_upload,$filename);
                $collaboration->bukti_file = $filename;
            }

            $collaboration->save();

            //Activity Log
            $property = [
                'id'    => $collaboration->id,
                'name'  => $collaboration->nama_lembaga,
                'url'   => route('collaboration.show',$collaboration->id)
            ];
            $this->log('created','Kerja Sama',$property);

            DB::commit();
            return redirect()->route('collaboration.index')->with('flash.message', 'Data berhasil ditambahkan.')->with('flash.class', 'success');

        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }


    }

    public function update(CollaborationRequest $request)
    {
        DB::beginTransaction();
        try {
            $id = decrypt($request->id);

            $collaboration = Collaboration::find($id);
            $collaboration->kd_prodi         = $request->kd_prodi;
            $collaboration->id_ta            = $request->id_ta;
            $collaboration->jenis            = $request->jenis;
            $collaboration->nama_lembaga     = $request->nama_lembaga;
            $collaboration->tingkat          = $request->tingkat;
            $collaboration->judul_kegiatan   = $request->judul_kegiatan;
            $collaboration->manfaat_kegiatan = $request->manfaat_kegiatan;
            $collaboration->waktu            = $request->waktu;
            $collaboration->durasi           = $request->durasi;
            $collaboration->bukti_nama       = $request->bukti_nama;

            //Upload File
            $storagePath = public_path('upload/collaboration/'.$collaboration->bukti_file);
            $tgl_skrg = date('Y_m_d_H_i_s');
            if($request->file('bukti_file')) {
                if(File::exists($storagePath)) {
                    File::delete($storagePath);
                }

                $file = $request->file('bukti_file');
                $tujuan_upload = public_path('upload/collaboration');
                $filename = $request->kd_prodi.'_'.$request->nama_lembaga.'_'.$request->tingkat.'_'.$request->judul_kegiatan.'_'.$request->waktu.'_'.$tgl_skrg.'.'.$file->getClientOriginalExtension();
                $file->move($tujuan_upload,$filename);
                $collaboration->bukti_file = $filename;
            } else {
                $ekstensi = File::extension($storagePath);
                $filename = $request->kd_prodi.'_'.$request->nama_lembaga.'_'.$request->tingkat.'_'.$request->judul_kegiatan.'_'.$request->waktu.'_'.$tgl_skrg.'.'.$ekstensi;
                File::move($storagePath,public_path('upload/collaboration/'.$filename));
                $collaboration->bukti_file = $filename;
            }

            $collaboration->save();

            //Activity Log
            $property = [
                'id'    => $collaboration->id,
                'name'  => $collaboration->nama_lembaga,
                'url'   => route('collaboration.show',$collaboration->id)
            ];
            $this->log('updated','Kerja Sama',$property);

            DB::commit();
            return redirect()->route('collaboration.show',$collaboration->id)->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }

    }

    public function destroy(Request $request)
    {
        if(!request()->ajax()){
            abort(404);
        }

        DB::beginTransaction();
        try {
            $id     = decrypt($request->id);
            $data   = Collaboration::find($id);
            $data->delete();
            $this->delete_file($data->bukti_file);

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama_lembaga,
            ];
            $this->log('deleted','Kepuasan Akademik',$property);

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

    public function download($filename)
    {
        $file = decode_id($filename);
        $storagePath = public_path('upload/collaboration/'.$file);
        if( ! File::exists($storagePath)) {
            abort(404);
        } else {
            $mimeType = File::mimeType($storagePath);
            $headers = array(
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="'.$file.'"'
            );

            return response(file_get_contents($storagePath), 200, $headers);
        }
    }

    public function delete_file($file)
    {
        $storagePath = public_path('upload/collaboration/'.$file);
        if(File::exists($storagePath)) {
            File::delete($storagePath);
        }
    }

    public function export(Request $request)
	{
		// Request
        $tgl       = date('dmYhis');
        $idn       = ($request->kd_prodi ? $request->kd_prodi.'_' : null);

        if(empty($request->periode_akhir)) {
            $periode = $request->periode_awal.'_';
        } else {
            $periode = $request->periode_awal.'-'.$request->periode_akhir.'_';
        }

        $nama_file   = 'Data_Kerja_Sama_'.$idn.$periode.$tgl.'.xlsx';
        $lokasi_file = storage_path('app/upload/temp/'.$nama_file);

		// Ekspor data
        return (new CollaborationExport($request))->download($nama_file);
    }

    public function datatable(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        if(Auth::user()->hasRole('kaprodi'))
        {
            $data = Collaboration::whereHas(
                                        'studyProgram', function($query) {
                                            $query->where('kd_prodi',Auth::user()->kd_prodi);
                                        }
                                    );
        }
        else
        {
            $data = Collaboration::whereHas(
                                        'studyProgram', function($query) {
                                            $query->where('kd_jurusan',setting('app_department_id'));
                                        }
                                    );
        }

        if($request->kd_prodi_filter) {
            $data->where('kd_prodi',$request->kd_prodi_filter);
        }

        return DataTables::of($data->get())
                            ->addColumn('tahun', function($d) {
                                return $d->academicYear->tahun_akademik." - ".$d->academicYear->semester;
                            })
                            ->addColumn('prodi', function($d) {
                                if(!Auth::user()->hasRole('kaprodi')) {
                                    return $d->studyProgram->nama;
                                }
                            })
                            ->addColumn('lembaga', function($d) {
                                return '<a href="'.route('collaboration.show',$d->id).'" target="_blank">'
                                            .$d->nama_lembaga.
                                        '</a>';
                            })
                            ->addColumn('download', function($d) {
                                return  '<a href="'.route('collaboration.download',encode_id($d->bukti_file)).'" target="_blank">'
                                            .$d->bukti_nama.
                                        '</a>';
                            })
                            ->addColumn('aksi', function($d) {
                                if(!Auth::user()->hasRole('kajur')) {
                                    return view('collaboration.table-button', compact('d'))->render();
                                }
                            })
                            ->rawColumns(['lembaga','aksi'])
                            ->make();
    }

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {

            $q   = Collaboration::with('studyProgram','academicYear')
                            ->whereHas(
                                'studyProgram', function($query) {
                                    $query->where('kd_jurusan',setting('app_department_id'));
                                }
                            );

            if(Auth::user()->hasRole('kaprodi')) {
                $q->whereHas(
                    'studyProgram', function($query) use ($request) {
                        $query->where('kd_prodi',Auth::user()->kd_prodi);
                });
            }

            if($request->kd_prodi){
                $q->whereHas(
                    'studyProgram', function($query) use ($request) {
                        $query->where('kd_prodi',$request->kd_prodi);
                });
            }

            $data = $q->orderBy('waktu','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
