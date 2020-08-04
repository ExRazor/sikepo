<?php

namespace App\Http\Controllers;

use App\Exports\CurriculumExport;
use App\Models\Curriculum;
use App\Models\StudyProgram;
use App\Imports\CurriculumImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CurriculumController extends Controller
{

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

        if(Auth::user()->hasRole('kaprodi')) {
            $curriculum     = Curriculum::whereHas(
                                            'studyProgram', function($query) {
                                                $query->where('kd_prodi',Auth::user()->kd_prodi);
                                            }
                                        )
                                        ->get();
        } else {
            $curriculum     = Curriculum::whereHas(
                                            'studyProgram', function($query) {
                                                $query->where('kd_jurusan',setting('app_department_id'));
                                            }
                                        )
                                        ->get();
        }
        $thn_kurikulum  = Curriculum::groupBy('versi')->get('versi');

        return view('academic.curriculum.index',compact(['studyProgram','curriculum','thn_kurikulum']));
    }

    public function create()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('academic.curriculum.form',compact('studyProgram'));
    }

    public function show($id)
    {
        $data = Curriculum::where('id',$id)->first();

        return view('academic.curriculum.show',compact(['data']));
    }

    public function edit($id)
    {
        // $id             = decode_id($id);
        $studyProgram   = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $data           = Curriculum::find($id);

        return view('academic.curriculum.form',compact(['studyProgram','data']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kd_matkul'         => 'required|unique:curricula,kd_matkul',
            'kd_prodi'          => 'required',
            'versi'             => 'required|numeric|digits:4',
            'nama'              => 'required',
            'semester'          => 'required|numeric',
            'jenis'             => 'required',
            'sks_teori'         => 'nullable|numeric',
            'sks_seminar'       => 'nullable|numeric',
            'sks_praktikum'     => 'nullable|numeric',
            'capaian'           => 'required',
            'kompetensi_prodi'  => 'nullable',
            'dokumen_nama'      => 'nullable',
            'unit_penyelenggara'=> 'required',
        ]);

        $query                  = new Curriculum;
        $query->kd_matkul       = $request->kd_matkul;
        $query->kd_prodi        = $request->kd_prodi;
        $query->versi           = $request->versi;
        $query->nama            = $request->nama;
        $query->semester        = $request->semester;
        $query->jenis           = $request->jenis;
        $query->sks_teori       = intval($request->sks_teori);
        $query->sks_seminar     = intval($request->sks_seminar);
        $query->sks_praktikum   = intval($request->sks_praktikum);
        $query->capaian         = $request->capaian;
        $query->kompetensi_prodi= $request->kompetensi_prodi;
        $query->dokumen_nama    = $request->dokumen_nama;
        $query->unit_penyelenggara = $request->unit_penyelenggara;
        $query->save();

        return redirect()->route('academic.curriculum.index',$query->id)->with('flash.message', 'Data berhasil disimpan!')->with('flash.class', 'success');
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
        $file_dir   = storage_path('app/temp/excel/curriculum/'.$nama_file);

		// upload ke folder khusus di dalam folder public
		$file->move($file_dir);

		// import data
        $q = Excel::import(new CurriculumImport, $file_dir);

        //Validasi jika terjadi error saat mengimpor
        if(!$q) {
            return response()->json([
                'title'   => 'Gagal',
                'message' => 'Terjadi kesalahan saat mengimpor',
                'type'    => 'error'
            ]);
        } else {
            File::delete($file_dir);
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

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $request->validate([
            'kd_matkul'         => 'required|unique:curricula,kd_matkul,'.$request->kd_matkul.',kd_matkul',
            'kd_prodi'          => 'required',
            'versi'             => 'required|numeric|digits:4',
            'nama'              => 'required',
            'semester'          => 'required|numeric',
            'jenis'             => 'required',
            'sks_teori'         => 'nullable|numeric',
            'sks_seminar'       => 'nullable|numeric',
            'sks_praktikum'     => 'nullable|numeric',
            'capaian'           => 'required',
            'kompetensi_prodi'  => 'nullable',
            'dokumen_nama'      => 'nullable',
            'unit_penyelenggara'=> 'required',
        ]);

        $query                  = Curriculum::find($id);
        $query->kd_matkul       = $request->kd_matkul;
        $query->kd_prodi        = $request->kd_prodi;
        $query->versi           = $request->versi;
        $query->nama            = $request->nama;
        $query->semester        = $request->semester;
        $query->jenis           = $request->jenis;
        $query->sks_teori       = intval($request->sks_teori);
        $query->sks_seminar     = intval($request->sks_seminar);
        $query->sks_praktikum   = intval($request->sks_praktikum);
        $query->capaian         = $request->capaian;
        $query->kompetensi_prodi= $request->kompetensi_prodi;
        $query->dokumen_nama    = $request->dokumen_nama;
        $query->unit_penyelenggara = $request->unit_penyelenggara;
        $query->save();

        return redirect()->route('academic.curriculum.show',$query->id)->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decode_id($request->id);
            $q  = Curriculum::destroy($id);
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
