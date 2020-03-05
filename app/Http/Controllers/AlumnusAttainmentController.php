<?php

namespace App\Http\Controllers;

use App\AcademicYear;
use App\StudentStatus;
use App\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumnusAttainmentController extends Controller
{
    public function attainment()
    {
        $academicYear = AcademicYear::groupBy('tahun_akademik')->orderBy('tahun_akademik','desc')->get('tahun_akademik');

        if(Auth::user()->hasRole('kaprodi')) {
            $studyProgram = StudyProgram::where('kd_prodi',Auth::user()->kd_prodi)->get();
        } else {
            $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        }

        foreach($studyProgram as $sp) {
            foreach($academicYear as $ay) {
                $q = StudentStatus::whereHas(
                                        'academicYear', function($q) use($ay) {
                                            $q->where('tahun_akademik',$ay->tahun_akademik);
                                        }
                                    )
                                    ->whereHas(
                                        'student', function($q) use ($sp) {
                                            $q->where('kd_prodi',$sp->kd_prodi);
                                        }
                                    )
                                    ->where('status','Lulus');
                $jumlah[$sp->kd_prodi][$ay->tahun_akademik]    = $q->count();
                $min_ipk[$sp->kd_prodi][$ay->tahun_akademik]   = $q->min('ipk_terakhir') ? $q->min('ipk_terakhir') : 0;
                $rata_ipk[$sp->kd_prodi][$ay->tahun_akademik]  = $q->avg('ipk_terakhir') ? $q->avg('ipk_terakhir') :0;
                $max_ipk[$sp->kd_prodi][$ay->tahun_akademik]   = $q->max('ipk_terakhir') ? $q->max('ipk_terakhir') :0;
            }
        }

        $data = array(
            'academicYear' => $academicYear,
            'studyProgram' => $studyProgram,
            'jumlah'  => $jumlah,
            'min_ipk' => $min_ipk,
            'rata_ipk'=> $rata_ipk,
            'max_ipk' => $max_ipk,
        );

        return view('alumnus.attainment.index',$data);
    }

    public function attainment_show($id)
    {
        $id = decrypt($id);

        $academicYear = AcademicYear::groupBy('tahun_akademik')->orderBy('tahun_akademik','desc')->get('tahun_akademik');
        // $academicYear = AcademicYear::groupBy('tahun_akademik')->get('tahun_akademik');
        $studyProgram = StudyProgram::find($id);

        foreach($academicYear as $ay) {
            $q = StudentStatus::whereHas(
                                    'academicYear', function($q) use($ay) {
                                        $q->where('tahun_akademik',$ay->tahun_akademik);
                                    }
                                )
                                ->whereHas(
                                    'student', function($q) use ($id) {
                                        $q->where('kd_prodi',$id);
                                    }
                                )
                                ->where('status','Lulus');
            $jumlah[$ay->tahun_akademik]    = $q->count();
            $min_ipk[$ay->tahun_akademik]   = $q->min('ipk_terakhir') ? $q->min('ipk_terakhir') : 0;
            $rata_ipk[$ay->tahun_akademik]  = $q->avg('ipk_terakhir') ? $q->avg('ipk_terakhir') :0;
            $max_ipk[$ay->tahun_akademik]   = $q->max('ipk_terakhir') ? $q->max('ipk_terakhir') :0;
        }
        $data = array(
            'studyProgram' => $studyProgram,
            'academicYear' => $academicYear,
            'jumlah'  => $jumlah,
            'min_ipk' => $min_ipk,
            'rata_ipk'=> $rata_ipk,
            'max_ipk' => $max_ipk,
            'total_lulus' => array_sum($jumlah)
        );

        return view('alumnus.attainment.show',$data);
    }

    public function get_alumnus(Request $request)
    {
        $prodi = decrypt($request->prodi);
        $query = StudentStatus::whereHas(
                                'academicYear', function($q) use($request) {
                                    $q->where('tahun_akademik',$request->tahun);
                                }
                            )
                            ->whereHas(
                                'student', function($q) use ($prodi) {
                                    $q->where('kd_prodi',$prodi);
                                }
                            )
                            ->where('status','Lulus')
                            ->count();

        if(request()->ajax()) {
            return response()->json($query);
        } else {
            abort(404);
        }

    }

    // public function attainment_show($id)
    // {
    //     $id = decrypt($id);

    //     $academicYear = AcademicYear::orderBy('tahun_akademik','desc')->orderBy('semester','desc')->get();
    //     $studyProgram = StudyProgram::find($id);

    //     foreach($academicYear as $ay) {
    //         $q = StudentStatus::where('id_ta',$ay->id)->where('status','Lulus');
    //         $jumlah[$ay->id]    = $q->count();
    //         $min_ipk[$ay->id]   = $q->min('ipk_terakhir');
    //         $rata_ipk[$ay->id]  = $q->avg('ipk_terakhir');
    //         $max_ipk[$ay->id]   = $q->max('ipk_terakhir');
    //     }
    //     $data = array(
    //         'studyProgram' => $studyProgram,
    //         'academicYear' => $academicYear,
    //         'jumlah'  => $jumlah,
    //         'min_ipk' => $min_ipk,
    //         'rata_ipk'=> $rata_ipk,
    //         'max_ipk' => $max_ipk
    //     );

    //     dd($data);

    //     return view('alumnus.attainment.show',$data);
    // }




}
