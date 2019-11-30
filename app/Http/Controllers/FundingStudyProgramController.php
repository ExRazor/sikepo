<?php

namespace App\Http\Controllers;

use App\FundingStudyProgram;
use App\FundingCategory;
use App\StudyProgram;
use App\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FundingStudyProgramController extends Controller
{

    public function index()
    {
        // $datas = DB::table('funding_study_programs')
        //             ->join('funding_categories', 'users.id', '=', 'contacts.user_id')
        //             ->join('orders', 'users.id', '=', 'orders.user_id')
        //             ->select(DB::raw('count(*) as user_count, status'))
        //             ->where('status', '<>', 1)
        //             ->groupBy('status')
        //             ->get();

        $data         = FundingStudyProgram::groupBy('kd_dana')
                                            ->groupBy('kd_prodi')
                                            ->groupBy('id_ta')
                                            ->orderBy('id_ta','desc')
                                            ->select('kd_dana','kd_prodi','id_ta')
                                            ->get();

        $funding      = FundingStudyProgram::groupBy('kd_dana')->get('kd_dana');

        foreach($funding as $f) {
            $dana[$f->kd_dana]  = DB::table('funding_study_programs as study')
                                    ->select(
                                        DB::raw('sum(study.nominal) as total'),
                                        'funding_categories.jenis'
                                    )
                                    ->join('funding_categories','funding_categories.id', '=', 'study.id_kategori')
                                    // ->where('funding_categories.jenis','!=',null)
                                    ->where('study.kd_dana',$f->kd_dana)
                                    ->groupBy('funding_categories.jenis')
                                    ->get();
        }

        return view('funding.study-program.index',compact(['data','dana']));
    }


    public function create()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $category     = FundingCategory::with('children')->orderBy('id','asc')->whereNull('id_parent')->get();
        $academicYear = AcademicYear::where('semester','Ganjil')->orderBy('tahun_akademik','desc')->get();

        return view('funding.study-program.form',compact(['studyProgram','category','academicYear']));
    }


    public function show($kd_dana)
    {
        $kd = decrypt($kd_dana);

        $data     = FundingStudyProgram::where('kd_dana',$kd)->first();
        $category = FundingCategory::with('children')->whereNull('id_parent')->orderBy('id','asc')->get();

        $categoryFilter = FundingCategory::all();
        foreach($categoryFilter as $c) {
            $nominal[$c->id] = FundingStudyProgram::where('kd_dana',$kd)->where('id_kategori',$c->id)->first();
        }

        $total  = DB::table('funding_study_programs as study')
                    ->select(
                        DB::raw('sum(study.nominal) as total')
                    )
                    ->where('study.kd_dana',$kd)
                    ->first()
                    ->total;

        return view('funding.study-program.show',compact(['data','nominal','category','total']));

    }


    public function edit($id)
    {
        $id = decrypt($id);

        $category     = FundingCategory::with('children')->orderBy('id','asc')->whereNull('id_parent')->get();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::where('semester','Ganjil')->orderBy('tahun_akademik','desc')->get();

        $categoryFilter = FundingCategory::with('children')->orderBy('id','asc')->get();

        foreach($categoryFilter as $c) {
            $q = FundingStudyProgram::where('kd_dana',$id)->where('id_kategori',$c->id);
            $cek = $q->exists();
            if($cek) {
                $nominal[$c->id] = $q->first()->nominal;
            }
        }

        $data  = FundingStudyProgram::with('studyProgram','academicYear')->where('kd_dana',$id)->first();

        // dd($nominal);

        return view('funding.study-program.form',compact(['studyProgram','academicYear','category','data','nominal']));

    }


    public function store(Request $request)
    {
        // dd($request->except(['_token','_method']));

        $request->validate([
            'kd_prodi'          => [
                'required',
                Rule::unique('funding_study_programs')->where(function ($query) use($request) {
                    return $query->where('id_ta', $request->id_ta);
                }),
            ],
            'id_ta'             => [
                'required',
                Rule::unique('funding_study_programs')->where(function ($query) use($request) {
                    return $query->where('kd_prodi', $request->kd_prodi);
                }),
            ],
        ]);

        $kd_dana = 'pd'.$request->kd_prodi.'_thn'.$request->id_ta;

        foreach($request->id_kategori as $index => $value) {
            $nominal = ($value) ? $value : '0';

            $query                 = new FundingStudyProgram;
            $query->kd_dana        = $kd_dana;
            $query->kd_prodi       = $request->kd_prodi;
            $query->id_ta          = $request->id_ta;
            $query->id_kategori    = $index;
            $query->nominal        = str_replace(".", "", $nominal);
            $query->save();
        }

        return redirect()->route('funding.study-program')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }


    public function update(Request $request)
    {
        $kd_dana = decrypt($request->_id);

        $request->validate([
            'kd_prodi'          => [
                'required',
                Rule::unique('funding_study_programs')->where(function ($query) use($request) {
                    return $query->where('id_ta', $request->id_ta);
                })->ignore($kd_dana,'kd_dana'),
            ],
            'id_ta'             => [
                'required',
                Rule::unique('funding_study_programs')->where(function ($query) use($request) {
                    return $query->where('kd_prodi', $request->kd_prodi);
                })->ignore($kd_dana,'kd_dana'),
            ],
        ]);

        foreach($request->id_kategori as $index => $value) {
            $nominal = ($value) ? $value : '0';

            $query                 = FundingStudyProgram::where('kd_dana',$kd_dana)->where('id_kategori',$index)->first();
            $query->kd_prodi       = $request->kd_prodi;
            $query->id_ta          = $request->id_ta;
            $query->nominal        = str_replace(".", "", $nominal);
            $query->save();
        }

        return redirect()->route('funding.study-program.show',$request->_id)->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }


    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = FundingStudyProgram::where('kd_dana',$id)->delete();
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
}
