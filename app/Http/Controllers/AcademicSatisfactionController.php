<?php

namespace App\Http\Controllers;

use App\AcademicSatisfaction;
use App\SatisfactionCategory;
use App\AcademicYear;
use App\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
class AcademicSatisfactionController extends Controller
{
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
        $academicYear = AcademicYear::has('academicSatisfaction')->groupBy('tahun_akademik')->orderBy('tahun_akademik','desc')->get('tahun_akademik');

        return view('academic.satisfaction.index',compact(['studyProgram','academicYear']));
    }

    public function create()
    {
        $category     = SatisfactionCategory::where('jenis','Akademik')->orderBy('id','asc')->get();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::where('semester','Ganjil')->orderBy('tahun_akademik','desc')->get();

        return view('academic.satisfaction.form',compact(['studyProgram','academicYear','category']));
    }

    public function show($kd_kepuasan)
    {
        $id = decrypt($kd_kepuasan);

        $data     = AcademicSatisfaction::where('kd_kepuasan',$id)->first();
        $category = SatisfactionCategory::where('jenis','Akademik')->orderBy('id','asc')->get();

        foreach($category as $c) {
            $aspek[$c->id] = AcademicSatisfaction::where('kd_kepuasan',$id)->where('id_kategori',$c->id)->first();
        }

        $jumlah  = DB::table('academic_satisfactions as satisfaction')
                        ->select(
                            DB::raw('sum(satisfaction.sangat_baik) as sangat_baik'),
                            DB::raw('sum(satisfaction.baik) as baik'),
                            DB::raw('sum(satisfaction.cukup) as cukup'),
                            DB::raw('sum(satisfaction.kurang) as kurang')
                        )
                        ->join('satisfaction_categories as category','category.id', '=', 'satisfaction.id_kategori')
                        ->where('category.jenis','Akademik')
                        ->where('satisfaction.kd_kepuasan',$data->kd_kepuasan)
                        ->first();

        return view('academic.satisfaction.show',compact(['data','aspek','jumlah']));
    }

    public function edit($id)
    {
        $id = decrypt($id);

        $category     = SatisfactionCategory::where('jenis','Akademik')->orderBy('id','asc')->get();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::where('semester','Ganjil')->orderBy('tahun_akademik','desc')->get();

        foreach($category as $c) {
            $persen[$c->id] = AcademicSatisfaction::where('kd_kepuasan',$id)->where('id_kategori',$c->id)->first();
        }

        $data  = AcademicSatisfaction::where('kd_kepuasan',$id)->first();


        return view('academic.satisfaction.form',compact(['studyProgram','academicYear','category','data','persen']));

    }

    public function store(Request $request)
    {
        $request->validate([
            'kd_prodi'          => [
                'required',
                Rule::unique('academic_satisfactions')->where(function ($query) use($request) {
                    return $query->where('id_ta', $request->id_ta);
                }),
            ],
            'id_ta'             => [
                'required',
                Rule::unique('academic_satisfactions')->where(function ($query) use($request) {
                    return $query->where('kd_prodi', $request->kd_prodi);
                }),
            ],
        ]);

        foreach($request->sangat_baik as $index => $value) {
            $query                 = new AcademicSatisfaction;
            $query->kd_kepuasan    = 'academic_'.$request->id_ta.'_'.$request->kd_prodi;
            $query->kd_prodi       = $request->kd_prodi;
            $query->id_ta          = $request->id_ta;
            $query->id_kategori    = $index;
            $query->sangat_baik    = ($request->sangat_baik[$index]) ? $request->sangat_baik[$index] : '0';
            $query->baik           = ($request->baik[$index]) ? $request->baik[$index] : '0';
            $query->cukup          = ($request->cukup[$index]) ? $request->cukup[$index]: '0';
            $query->kurang         = ($request->kurang[$index]) ? $request->kurang[$index] : '0';
            $query->tindak_lanjut  = $request->tindak_lanjut[$index];
            $query->save();
        }

        return redirect()->route('academic.satisfaction.index')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        foreach($request->sangat_baik as $index => $value) {
            $query                 = AcademicSatisfaction::where('kd_kepuasan',$id)->where('id_kategori',$index)->first();
            $query->sangat_baik    = ($request->sangat_baik[$index]) ? $request->sangat_baik[$index] : '0';
            $query->baik           = ($request->baik[$index]) ? $request->baik[$index] : '0';
            $query->cukup          = ($request->cukup[$index]) ? $request->cukup[$index]: '0';
            $query->kurang         = ($request->kurang[$index]) ? $request->kurang[$index] : '0';
            $query->tindak_lanjut  = $request->tindak_lanjut[$index];
            $query->save();
        }

        return redirect()->route('academic.satisfaction.show',$request->id)->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');

    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = AcademicSatisfaction::where('kd_kepuasan',$id)->delete();
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
            $data = \App\AcademicSatisfaction::where('kd_prodi',Auth::user()->kd_prodi)
                                        ->groupBy('kd_prodi')
                                        ->groupBy('id_ta')
                                        ->groupBy('kd_kepuasan')
                                        ->orderBy('id_ta','desc')
                                        ->select(['kd_prodi','id_ta','kd_kepuasan']);
        } else {
            $data = \App\AcademicSatisfaction::groupBy('kd_prodi')
                                        ->groupBy('id_ta')
                                        ->groupBy('kd_kepuasan')
                                        ->orderBy('id_ta','desc')
                                        ->select(['kd_prodi','id_ta','kd_kepuasan']);
        }


        foreach($data->get() as $d) {
            $persen[$d->kd_kepuasan]  = DB::table('academic_satisfactions as satisfaction')
                                            ->select(
                                                DB::raw('sum(satisfaction.sangat_baik) as sangat_baik'),
                                                DB::raw('sum(satisfaction.baik) as baik'),
                                                DB::raw('sum(satisfaction.cukup) as cukup'),
                                                DB::raw('sum(satisfaction.kurang) as kurang')
                                            )
                                            ->join('satisfaction_categories as category','category.id', '=', 'satisfaction.id_kategori')
                                            ->where('category.jenis','Akademik')
                                            ->where('satisfaction.kd_kepuasan',$d->kd_kepuasan)
                                            ->first();
        }

        if($request->kd_prodi_filter) {
            $data->where('kd_prodi',$request->kd_prodi_filter);
        }

        if($request->tahun_filter) {
            $data->whereHas('academicYear', function($q) use($request) {
                $q->where('tahun_akademik',$request->tahun_filter);
            });
        }

        return DataTables::of($data->get())
                            ->addColumn('prodi', function($d) {
                                if(!Auth::user()->hasRole('kaprodi')) {
                                    return $d->studyProgram->nama;
                                }
                            })
                            ->addColumn('tahun', function($d) {
                                return $d->academicYear->tahun_akademik;
                            })
                            ->addColumn('sangat_baik', function($d) use($persen) {
                                return $persen[$d->kd_kepuasan]->sangat_baik.'%';
                            })
                            ->addColumn('baik', function($d) use($persen) {
                                return $persen[$d->kd_kepuasan]->baik.'%';
                            })
                            ->addColumn('cukup', function($d) use($persen) {
                                return $persen[$d->kd_kepuasan]->cukup.'%';
                            })
                            ->addColumn('kurang', function($d) use($persen) {
                                return $persen[$d->kd_kepuasan]->kurang.'%';
                            })
                            ->addColumn('aksi', function($d) {
                                if(!Auth::user()->hasRole('kajur')) {
                                    return view('academic.satisfaction.table-button', compact('d'))->render();
                                }
                            })
                            ->rawColumns(['aksi'])
                            ->make();
    }
}
