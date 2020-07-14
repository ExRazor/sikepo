<?php

namespace App\Http\Controllers\Perhitungan;

use App\Models\AcademicYear;
use App\Models\Http\Controllers\Controller;
use App\Models\Research;
use App\Models\StudyProgram;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenelitianController extends Controller
{
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_Id'))->get();

        return view('simulasi.penelitian.index',compact('studyProgram'));
    }

    public function penelitian(Request $request)
    {
        if(Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        $query = Research::whereHas(
                                'academicYear', function ($q) {
                                    $q->whereBetween('tahun_akademik', [date('Y',strtotime('-3 year')),date('Y')]);
                                }
                            )
                            ->whereHas(
                                'researchTeacher', function($q) use($prodi) {
                                    $q->prodiKetua($prodi);
                                }
                            )
                            ->get();

        $jumlah = array(
            'dtps'  => Teacher::where('kd_prodi',$prodi)->where('ikatan_kerja','Dosen Tetap PS')->count(),
            'ni'    => $query->where('tingkat_penelitian','Internasional')->count(),
            'nn'    => $query->where('tingkat_penelitian','Nasional')->count(),
            'nl'    => $query->where('tingkat_penelitian','Lokal')->count()
        );

        $faktor = array(
            'a' => 0.05,
            'b' => 0.3,
            'c' => 1
        );

        $rata = array(
            'inter'     => $jumlah['ni']/3/$jumlah['dtps'],
            'nasional'  => $jumlah['nn']/3/$jumlah['dtps'],
            'lokal'     => $jumlah['nl']/3/$jumlah['dtps']
        );

        if($rata['inter']>=$faktor['a']) {
            $skor = 4;
            $rumus = "4";
        } else if($rata['inter']<$faktor['a'] && $rata['nasional']>=$faktor['b']) {
            $skor = 3+($rata['inter']/$faktor['a']);
            $rumus = "3 + (RI / a)";
        } else if(($rata['inter'] > 0 && $rata['inter'] < $faktor['a']) || ($rata['nasional'] < 0 && $rata['nasional'] > $faktor['b'])) {
            $skor = 2 + (2*($rata['inter']/$faktor['a']))+($rata['nasional']/$faktor['b'])-(($rata['inter']*$rata['nasional'])/($faktor['a']*$faktor['b']));
            $rumus = "2 + (2 * (RI / a)) + (RN / b) - ((RI * RN) / (a * b))";
        } else if($rata['inter']==0 && $rata['nasional']==0 && $rata['lokal']>=$faktor['c']) {
            $skor = 2;
            $rumus = "2";
        } else if($rata['inter']==0 && $rata['nasional']==0 && $rata['lokal']<$faktor['c']) {
            $skor = (2*$rata['lokal'])/$faktor['c'];
            $rumus = "(2 * RL) / c";
        } else {
            $skor = 0;
        }

        $data = compact(['jumlah','rata','skor','rumus']);

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }

    }
}
