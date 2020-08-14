<?php

namespace App\Http\Controllers;

use App\Exports\CurriculumExport;
use App\Http\Requests\CurriculumRequest;
use App\Models\Curriculum;
use App\Models\StudyProgram;
use App\Imports\CurriculumImport;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CurriculumController extends Controller
{
    use LogActivity;

    public function __construct()
    {
        $method = [
            'create',
            'edit',
            'store',
            'import',
            'update',
            'destroy',
        ];

        $this->middleware('role:admin,kaprodi', ['only' => $method]);
    }

    public function index()
    {
        $studyProgram   = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $thn_kurikulum  = Curriculum::groupBy('versi')->get('versi');

        return view('academic.curriculum.index',compact(['studyProgram','thn_kurikulum']));
    }

    public function create()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('academic.curriculum.form',compact('studyProgram'));
    }

    public function show($id)
    {
        $data = Curriculum::find($id);

        return view('academic.curriculum.show',compact(['data']));
    }

    public function edit($id)
    {
        $studyProgram   = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $data           = Curriculum::find($id);

        return view('academic.curriculum.form',compact(['studyProgram','data']));
    }

    public function store(CurriculumRequest $request)
    {
        DB::beginTransaction();
        try {
            //Query
            $curriculum                  = new Curriculum;
            $curriculum->kd_matkul       = $request->kd_matkul;
            $curriculum->kd_prodi        = $request->kd_prodi;
            $curriculum->versi           = $request->versi;
            $curriculum->nama            = $request->nama;
            $curriculum->semester        = $request->semester;
            $curriculum->jenis           = $request->jenis;
            $curriculum->sks_teori       = intval($request->sks_teori);
            $curriculum->sks_seminar     = intval($request->sks_seminar);
            $curriculum->sks_praktikum   = intval($request->sks_praktikum);
            $curriculum->capaian         = $request->capaian;
            $curriculum->kompetensi_prodi= $request->kompetensi_prodi;
            $curriculum->dokumen_nama    = $request->dokumen_nama;
            $curriculum->unit_penyelenggara = $request->unit_penyelenggara;
            $curriculum->save();

            //Activity Log
            $property = [
                'id'    => $curriculum->id,
                'name'  => $curriculum->nama,
                'url'   => route('academic.curriculum.show',$curriculum->id)
            ];
            $this->log('created','Kurikulum',$property);

            DB::commit();
            return redirect()->route('academic.curriculum.index',$curriculum->id)->with('flash.message', 'Data berhasil disimpan!')->with('flash.class', 'success');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }

    }

    public function update(CurriculumRequest $request)
    {
        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($request->id);

            //Query
            $curriculum                  = Curriculum::find($id);
            $curriculum->kd_matkul       = $request->kd_matkul;
            $curriculum->kd_prodi        = $request->kd_prodi;
            $curriculum->versi           = $request->versi;
            $curriculum->nama            = $request->nama;
            $curriculum->semester        = $request->semester;
            $curriculum->jenis           = $request->jenis;
            $curriculum->sks_teori       = intval($request->sks_teori);
            $curriculum->sks_seminar     = intval($request->sks_seminar);
            $curriculum->sks_praktikum   = intval($request->sks_praktikum);
            $curriculum->capaian         = $request->capaian;
            $curriculum->kompetensi_prodi= $request->kompetensi_prodi;
            $curriculum->dokumen_nama    = $request->dokumen_nama;
            $curriculum->unit_penyelenggara = $request->unit_penyelenggara;
            $curriculum->save();

            //Activity Log
            $property = [
                'id'    => $curriculum->id,
                'name'  => $curriculum->nama,
                'url'   => route('academic.curriculum.show',$curriculum->id)
            ];
            $this->log('updated','Kurikulum',$property);

            DB::commit();
            return redirect()->route('academic.curriculum.show',$curriculum->id)->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');

        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger');
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
            $data = Curriculum::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->nama,
            ];
            $this->log('deleted','Kurikulum',$property);

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

    public function import(Request $request)
	{
		// Memvalidasi
		$this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		// Menangkap file excel
		$file = $request->file('file');

        // Mengambil nama file
        $tgl = date('Y-m-d');
        $nama_file  = "Data_Mahasiswa_Import_".$tgl.'.'.$file->getClientOriginalExtension();
        $dir_path   = storage_path('app/temp/excel/');
        $file_path  = $dir_path.$nama_file;

		// upload ke folder khusus di dalam folder public
		$file->move($dir_path,$nama_file);

		// import data
        $q = Excel::import(new CurriculumImport, $file_path);

        //Validasi jika terjadi error saat mengimpor
        if(!$q) {
            return response()->json([
                'title'   => 'Gagal',
                'message' => 'Terjadi kesalahan saat mengimpor',
                'type'    => 'error'
            ]);
        } else {
            File::delete($file_path);
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil diimpor',
                'type'    => 'success'
            ]);
        }
    }

    public function export(Request $request)
	{
		// Request
        $tgl         = date('d-m-Y_h_i_s');
        $prodi       = ($request->kd_prodi ? $request->kd_prodi.'_' : null);
        $nama_file   = 'Data_Mata_Kuliah_'.$prodi.$tgl.'.xlsx';
        $lokasi_file = storage_path('app/upload/'.$nama_file);

		// Ekspor data
        return Excel::download(new CurriculumExport($request),$nama_file);
    }

    public function datatable(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        if(Auth::user()->hasRole('kaprodi')) {
            $data = Curriculum::whereHas(
                'studyProgram', function($query) {
                    $query->where('kd_prodi',Auth::user()->kd_prodi);
                }
            );
        } else {
            $data = Curriculum::whereHas(
                'studyProgram', function($query) {
                    $query->where('kd_jurusan',setting('app_department_id'));
                }
            );
        }

        if($request->kd_prodi) {
            $data->where('kd_prodi',$request->kd_prodi);
        }

        if($request->thn_kurikulum) {
            $data->where('thn_kurikulum',$request->thn_kurikulum);
        }

        if($request->semester) {
            $data->where('semester',$request->semester);
        }

        if($request->jenis) {
            $data->where('jenis',$request->jenis);
        }

        return DataTables::of($data->get())
                            ->addColumn('matkul', function($d) {
                                return '<a name="'.$d->kd_matkul.'" href='.route("academic.curriculum.show",$d->id).'>'.
                                            $d->nama.
                                            '<br><small>'.$d->studyProgram->singkatan.' / '.$d->kd_matkul.'</small>
                                        </a>';
                            })
                            ->addColumn('prodi', function($d) {
                                if(!Auth::user()->hasRole('kajur')) {
                                    return $d->studyProgram->nama;
                                }
                            })
                            ->addColumn('aksi', function($d) {
                                if(!Auth::user()->hasRole('kajur')) {
                                    return view('academic.curriculum.table-button', compact('d'))->render();
                                }
                            })
                            ->rawColumns(['matkul','kompetensi_prodi','sks','aksi'])
                            ->make();
    }

    public function loadData(Request $request)
    {
        if($request->has('cari')){
            $cari  = $request->cari;
            $prodi = $request->prodi;

            $q = Curriculum::select('*');

            if($prodi) {
                $q->where('kd_prodi',$prodi);
            }

            if($cari) {
                $q->where(function($query) use ($cari) {
                    $query->where('kd_matkul', 'LIKE', '%'.$cari.'%')->orWhere('nama', 'LIKE', '%'.$cari.'%');
                });
            }

            $data = $q->get();

            $response = array();
            foreach($data as $d){
                $response[] = array(
                    "id"    => $d->kd_matkul,
                    "text"  => $d->nama.' - '.$d->studyProgram->singkatan.' ('.$d->versi.')'
                );
            }
            return response()->json($response);
        }
    }
}
