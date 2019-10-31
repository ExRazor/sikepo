<?php

namespace App\Http\Controllers;

use App\FundingStudyProgram;
use App\FundingCategory;
use App\StudyProgram;
use App\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FundingStudyProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = FundingStudyProgram::groupBy('id_ta')->get('id_ta');

        foreach($studyProgram as $sp) {
            foreach($academicYear as $ay) {

                $dana[$sp->kd_prodi][$ay->id_ta]  = DB::table('funding_study_programs as study')
                                                        ->select(
                                                            DB::raw('sum(study.nominal) as total'),
                                                            'funding_categories.jenis'
                                                        )
                                                        ->join('funding_categories','funding_categories.id', '=', 'study.id_kategori')
                                                        ->where('funding_categories.jenis','!=',null)
                                                        ->where('study.kd_prodi',$sp->kd_prodi)
                                                        ->where('study.id_ta',$ay->id_ta)
                                                        ->groupBy('funding_categories.jenis')
                                                        ->get();

            }
        }

        // dd($dana);

        return view('funding.study-program.index',compact(['data','dana']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $category     = FundingCategory::with('children')->orderBy('id','asc')->whereNull('id_parent')->get();
        $academicYear = AcademicYear::where('semester','Ganjil')->orderBy('tahun_akademik','desc')->get();

        return view('funding.study-program.form',compact(['studyProgram','category','academicYear']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FundingStudyProgram  $fundingStudyProgram
     * @return \Illuminate\Http\Response
     */
    public function show($kd_dana)
    {
        $kd = decrypt($kd_dana);

        $data = FundingStudyProgram::where('kd_dana',$kd)->get();

        dd($kd);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FundingStudyProgram  $fundingStudyProgram
     * @return \Illuminate\Http\Response
     */
    public function edit(FundingStudyProgram $fundingStudyProgram)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FundingStudyProgram  $fundingStudyProgram
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FundingStudyProgram $fundingStudyProgram)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FundingStudyProgram  $fundingStudyProgram
     * @return \Illuminate\Http\Response
     */
    public function destroy(FundingStudyProgram $fundingStudyProgram)
    {
        //
    }
}
