<?php

namespace App\Http\Controllers;

use App\Http\Requests\FundingStudyProgramRequest;
use App\Models\FundingStudyProgram;
use App\Models\FundingCategory;
use App\Models\StudyProgram;
use App\Models\AcademicYear;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FundingStudyProgramController extends Controller
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
        // $datas = DB::table('funding_study_programs')
        //             ->join('funding_categories', 'users.id', '=', 'contacts.user_id')
        //             ->join('orders', 'users.id', '=', 'orders.user_id')
        //             ->select(DB::raw('count(*) as user_count, status'))
        //             ->where('status', '<>', 1)
        //             ->groupBy('status')
        //             ->get();

        if(Auth::user()->hasRole('kaprodi')) {
            $data         = FundingStudyProgram::groupBy('kd_dana')
                                                ->groupBy('kd_prodi')
                                                ->groupBy('id_ta')
                                                ->where('kd_prodi',Auth::user()->kd_prodi)
                                                ->orderBy('id_ta','desc')
                                                ->select('kd_dana','kd_prodi','id_ta')
                                                ->get();
        } else {
            $data         = FundingStudyProgram::groupBy('kd_dana')
                                                ->groupBy('kd_prodi')
                                                ->groupBy('id_ta')
                                                ->orderBy('id_ta','desc')
                                                ->select('kd_dana','kd_prodi','id_ta')
                                                ->get();
        }

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

    public function create()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $category     = FundingCategory::with('children')->orderBy('id','asc')->whereNull('id_parent')->get();
        $academicYear = AcademicYear::where('semester','Ganjil')->orderBy('tahun_akademik','desc')->get();

        return view('funding.study-program.form',compact(['studyProgram','category','academicYear']));
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


    public function store(FundingStudyProgramRequest $request)
    {
        DB::beginTransaction();
        try {
            //Init Kode Dana
            $kd_dana = 'pd'.$request->kd_prodi.'_thn'.$request->id_ta;

            //Query
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

            //Activity Log
            $property = [
                'id'    => $query->kd_dana,
                'name'  => $query->studyProgram->nama.' - '.$query->academicYear->tahun_akademik,
                'url'   => route('funding.study-program.show',encrypt($query->kd_dana))
            ];
            $this->log('created','Keuangan Program Studi',$property);

            DB::commit();
            return redirect()->route('funding.study-program')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }

    }


    public function update(FundingStudyProgramRequest $request)
    {
        DB::beginTransaction();
        try {
            //Decrypt ID
            $kd_dana = decrypt($request->_id);

            //Query
            foreach($request->id_kategori as $index => $value) {
                $nominal = ($value) ? $value : '0';

                $query                 = FundingStudyProgram::where('kd_dana',$kd_dana)->where('id_kategori',$index)->first();
                $query->kd_prodi       = $request->kd_prodi;
                $query->id_ta          = $request->id_ta;
                $query->nominal        = str_replace(".", "", $nominal);
                $query->save();
            }

            //Activity Log
            $property = [
                'id'    => $query->kd_dana,
                'name'  => $query->studyProgram->nama.' - '.$query->academicYear->tahun_akademik,
                'url'   => route('funding.study-program.show',encrypt($query->kd_dana))
            ];
            $this->log('updated','Keuangan Program Studi',$property);

            DB::commit();
            return redirect()->route('funding.study-program.show',$request->_id)->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
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
            $data = FundingStudyProgram::where('kd_dana',$id)->first();
            FundingStudyProgram::where('kd_dana',$id)->delete();

            //Activity Log
            $property = [
                'id'    => $data->kd_dana,
                'name'  => $data->faculty->nama.' - '.$data->academicYear->tahun_akademik,
            ];
            $this->log('deleted','Keuangan Fakultas',$property);

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
