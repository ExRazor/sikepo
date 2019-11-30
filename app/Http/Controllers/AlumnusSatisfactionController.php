<?php

namespace App\Http\Controllers;

use App\AlumnusSatisfaction;
use App\SatisfactionCategory;
use App\AcademicYear;
use App\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AlumnusSatisfactionController extends Controller
{
    public function index()
    {
        $satisfaction = AlumnusSatisfaction::groupBy('kd_prodi')
                                            ->groupBy('id_ta')
                                            ->groupBy('kd_kepuasan')
                                            ->orderBy('id_ta','desc')
                                            ->get(['kd_prodi','id_ta','kd_kepuasan']);

                                            // dd($satisfaction);

        foreach($satisfaction as $s) {
            $persen[$s->kd_kepuasan]  = DB::table('alumnus_satisfactions as satisfaction')
                                            ->select(
                                                DB::raw('sum(satisfaction.sangat_baik) as sangat_baik'),
                                                DB::raw('sum(satisfaction.baik) as baik'),
                                                DB::raw('sum(satisfaction.cukup) as cukup'),
                                                DB::raw('sum(satisfaction.kurang) as kurang')
                                            )
                                            ->join('satisfaction_categories as category','category.id', '=', 'satisfaction.id_kategori')
                                            ->where('category.jenis','Alumni')
                                            ->where('satisfaction.kd_kepuasan',$s->kd_kepuasan)
                                            ->first();
        }

        return view('alumnus.satisfaction.index',compact(['satisfaction','persen']));
    }

    public function create()
    {
        $category     = SatisfactionCategory::where('jenis','Akademik')->orderBy('id','asc')->get();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::where('semester','Ganjil')->orderBy('tahun_akademik','desc')->get();

        return view('alumnus.satisfaction.form',compact(['studyProgram','academicYear','category']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kd_prodi'          => [
                'required',
                Rule::unique('alumnus_satisfactions')->where(function ($query) use($request) {
                    return $query->where('id_ta', $request->id_ta);
                }),
            ],
            'id_ta'             => [
                'required',
                Rule::unique('alumnus_satisfactions')->where(function ($query) use($request) {
                    return $query->where('kd_prodi', $request->kd_prodi);
                }),
            ],
        ]);

        foreach($request->sangat_baik as $index => $value) {
            $query                 = new AlumnusSatisfaction;
            $query->kd_kepuasan    = 'alumnus_'.$request->id_ta.'_'.$request->kd_prodi;
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

        return redirect()->route('alumnus.satisfaction')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function show($kd_kepuasan)
    {
        $id = decrypt($kd_kepuasan);

        $data     = AlumnusSatisfaction::where('kd_kepuasan',$id)->first();
        $category = SatisfactionCategory::where('jenis','Alumni')->orderBy('id','asc')->get();

        foreach($category as $c) {
            $aspek[$c->id] = AlumnusSatisfaction::where('kd_kepuasan',$id)->where('id_kategori',$c->id)->first();
        }

        $jumlah  = DB::table('alumnus_satisfactions as satisfaction')
                        ->select(
                            DB::raw('sum(satisfaction.sangat_baik) as sangat_baik'),
                            DB::raw('sum(satisfaction.baik) as baik'),
                            DB::raw('sum(satisfaction.cukup) as cukup'),
                            DB::raw('sum(satisfaction.kurang) as kurang')
                        )
                        ->join('satisfaction_categories as category','category.id', '=', 'satisfaction.id_kategori')
                        ->where('category.jenis','Alumni')
                        ->where('satisfaction.kd_kepuasan',$data->kd_kepuasan)
                        ->first();

        return view('alumnus.satisfaction.show',compact(['data','aspek','jumlah']));
    }

    public function edit($id)
    {
        $id = decrypt($id);

        $category     = SatisfactionCategory::where('jenis','Alumni')->orderBy('id','asc')->get();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::where('semester','Ganjil')->orderBy('tahun_akademik','desc')->get();

        foreach($category as $c) {
            $persen[$c->id] = AlumnusSatisfaction::where('kd_kepuasan',$id)->where('id_kategori',$c->id)->first();
        }

        $data  = AlumnusSatisfaction::where('kd_kepuasan',$id)->first();

        return view('alumnus.satisfaction.form',compact(['studyProgram','academicYear','category','data','persen']));

    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        foreach($request->sangat_baik as $index => $value) {
            $query                 = AlumnusSatisfaction::where('kd_kepuasan',$id)->where('id_kategori',$index)->first();
            $query->sangat_baik    = ($request->sangat_baik[$index]) ? $request->sangat_baik[$index] : '0';
            $query->baik           = ($request->baik[$index]) ? $request->baik[$index] : '0';
            $query->cukup          = ($request->cukup[$index]) ? $request->cukup[$index]: '0';
            $query->kurang         = ($request->kurang[$index]) ? $request->kurang[$index] : '0';
            $query->tindak_lanjut  = $request->tindak_lanjut[$index];
            $query->save();
        }

        return redirect()->route('alumnus.satisfaction.show',$request->id)->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');

    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = AlumnusSatisfaction::where('kd_kepuasan',$id)->delete();
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
