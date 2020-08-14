<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlumnusSatisfactionRequest;
use App\Models\AlumnusSatisfaction;
use App\Models\SatisfactionCategory;
use App\Models\AcademicYear;
use App\Models\StudyProgram;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AlumnusSatisfactionController extends Controller
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
        if(Auth::user()->hasRole('kaprodi')) {
            $satisfaction = AlumnusSatisfaction::where('kd_prodi',Auth::user()->kd_prodi)
                                                ->groupBy('kd_prodi')
                                                ->groupBy('id_ta')
                                                ->groupBy('kd_kepuasan')
                                                ->orderBy('id_ta','desc')
                                                ->get(['kd_prodi','id_ta','kd_kepuasan']);
        } else {
            $satisfaction = AlumnusSatisfaction::groupBy('kd_prodi')
                                                ->groupBy('id_ta')
                                                ->groupBy('kd_kepuasan')
                                                ->orderBy('id_ta','desc')
                                                ->get(['kd_prodi','id_ta','kd_kepuasan']);
        }

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
        $category     = SatisfactionCategory::where('jenis','Alumni')->orderBy('id','asc')->get();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::where('semester','Ganjil')->orderBy('tahun_akademik','desc')->get();

        return view('alumnus.satisfaction.form',compact(['studyProgram','academicYear','category']));
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

    public function store(AlumnusSatisfactionRequest $request)
    {
        DB::beginTransaction();
        try {
            $id = 'alumnus_'.$request->id_ta.'_'.$request->kd_prodi;

            foreach($request->sangat_baik as $index => $value) {
                $query                 = new AlumnusSatisfaction;
                $query->kd_kepuasan    = $id;
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

            //Activity Log
            $queryProperty = AlumnusSatisfaction::where('kd_kepuasan',$id)->first();
            $property = [
                'id'    => $id,
                'name'  => $queryProperty->academicYear->tahun_akademik.' - '.$queryProperty->studyProgram->nama,
                'url'   => route('alumnus.satisfaction.show',encrypt($id))
            ];
            $this->log('created','Kepuasan Pengguna Lulusan',$property);

            DB::commit();
            return redirect()->route('alumnus.satisfaction')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }

    }

    public function update(AlumnusSatisfactionRequest $request)
    {
        DB::beginTransaction();
        try {
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

            //Activity Log
            $queryProperty = AlumnusSatisfaction::where('kd_kepuasan',$id)->first();
            $property = [
                'id'    => $id,
                'name'  => $queryProperty->academicYear->tahun_akademik.' - '.$queryProperty->studyProgram->nama,
                'url'   => route('alumnus.satisfaction.show',encrypt($id))
            ];
            $this->log('updated','Kepuasan Pengguna Lulusan',$property);

            DB::commit();
            return redirect()->route('alumnus.satisfaction.show',$request->id)->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
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
            $data  = AlumnusSatisfaction::where('kd_kepuasan',$id)->first();
            AlumnusSatisfaction::where('kd_kepuasan',$id)->delete();

            //Activity Log
            $property = [
                'id'    => $data->kd_kepuasan,
                'name'  => $data->academicYear->tahun_akademik.' - '.$data->studyProgram->nama,
            ];
            $this->log('deleted','Kepuasan Pengguna Lulusan',$property);

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
}
