<?php

namespace App\Http\Controllers\Perhitungan;

use App\AcademicYear;
use App\Student;
use App\AlumnusWorkplace;
use App\StudentAchievement;
use App\Http\Controllers\Controller;
use App\StudentStatus;
use App\StudyProgram;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TridharmaController extends Controller
{
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_Id'))->get();

        return view('simulasi.tridharma.index',compact('studyProgram'));
    }

    public function capaian_ipk(Request $request)
    {
        if(Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        $tahun_akademik = AcademicYear::where('status','Aktif')->first()->tahun_akademik;

        $query = StudentStatus::whereHas(
                                    'academicYear', function($q) use($tahun_akademik) {
                                        $q->where('tahun_akademik',$tahun_akademik);
                                    }
                                )
                                ->whereHas(
                                    'student', function($q) use ($prodi) {
                                        $q->where('kd_prodi',$prodi);
                                    }
                                )
                                ->where('status','Lulus')
                                ->get();

        $lulusan  = $query->count();
        $rata_ipk = rata($query->avg('ipk_terakhir'));

        if($rata_ipk>=3.25) {
            $skor = rata(4);
        } else if($rata_ipk>=2.00 && $rata_ipk<3.25) {
            $skor = rata(((8*$rata_ipk)-6)/5);
        } else {
            $skor = "Tidak ada skor di bawah 2";
        }

        $data = compact(['lulusan','rata_ipk','skor']);

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function prestasi_mahasiswa(Request $request)
    {
        if(Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        $tahun_akademik = AcademicYear::where('status','Aktif')->first()->tahun_akademik;

        $lulusan = StudentStatus::whereHas(
                                    'academicYear', function($q) use($tahun_akademik) {
                                        $q->where('tahun_akademik',$tahun_akademik);
                                    }
                                )
                                ->whereHas(
                                    'student', function($q) use ($prodi) {
                                        $q->where('kd_prodi',$prodi);
                                    }
                                )
                                ->where('status','Lulus')
                                ->count();

        $query = StudentAchievement::whereHas(
                                        'student', function($q) use ($prodi) {
                                            $q->where('kd_prodi',$prodi);
                                        }
                                    )->whereHas(
                                        'academicYear', function($q) use ($prodi) {
                                            $q->whereBetween('tahun_akademik', [date('Y',strtotime('-3 year')),date('Y')]);
                                        }
                                    )->get();

        $jumlah = array(
            'mahasiswa'  => Student::where('kd_prodi', $prodi)
                                    ->whereHas(
                                        'latestStatus', function($q) {
                                            $q->where('status','Aktif');
                                        }
                                    )
                                    ->count(),
            'inter'     => $query->where('kegiatan_tingkat','Internasional')->count(),
            'nasional'  => $query->where('kegiatan_tingkat','Nasional')->count(),
            'lokal'     => $query->where('kegiatan_tingkat','Wilayah')->count(),
        );

        $faktor = array(
            'a' => 0.05,
            'b' => 0.5,
            'c' => 2
        );

        $rata = array(
            'inter'    => $jumlah['inter']/$jumlah['mahasiswa'],
            'nasional' => $jumlah['nasional']/$jumlah['mahasiswa'],
            'lokal'    => $jumlah['lokal']/$jumlah['mahasiswa'],
        );

        if($rata['inter']>=$faktor['a']) {
            $skor = 4;
            $rumus = "4";
        } else if($rata['inter']<$faktor['a'] && $rata['nasional']>=$faktor['b']) {
            $skor = 3+($rata['inter']/$faktor['a']);
            $rumus = "3 + (RI / a)";
        } else if(($rata['inter']>0 && $rata['inter']<$faktor['a']) || ($rata['nasional']>0 && $rata['nasional']<$faktor['b'])) {
            $skor = 2 + (2*($rata['inter']/$faktor['a']))+($rata['nasional']/$faktor['b'])-(($rata['inter']*$rata['nasional'])/($faktor['a']*$faktor['b']));
            $rumus = "2 + (2 * (RI / a)) + (RN / b) - ((RI * RN) / (a * b))";
        } else if($rata['inter']==0 && $rata['nasional']==0 && ($rata['lokal']<=$faktor['c'] && $rata['lokal']>=0)) {
            $skor = 1+($rata['lokal']/$faktor['c']);
            $rumus = "1 + (RW / c)";
        } else if($rata['inter']==0 && $rata['nasional']==0 && $rata['lokal']>$faktor['c']) {
            $skor = 2;
            $rumus = "2";
        } else {
            $skor = 0;
            $rumus = "Tidak ada skor di bawah 1";
        }

        $data = compact(['jumlah','rata','skor','rumus']);

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function tempat_kerja_lulusan(Request $request)
    {
        if(Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        $tahun_akademik = AcademicYear::where('status','Aktif')->first()->tahun_akademik;

        $lulusan = StudentStatus::whereHas(
                                    'academicYear', function($q) use($tahun_akademik) {
                                        $q->where('tahun_akademik',$tahun_akademik);
                                    }
                                )
                                ->whereHas(
                                    'student', function($q) use ($prodi) {
                                        $q->where('kd_prodi',$prodi);
                                    }
                                )
                                ->where('status','Lulus')
                                ->count();

        $query = AlumnusWorkplace::where('kd_prodi',$prodi)->where('tahun_lulus',$tahun_akademik)->first();

        $jumlah = array(
            'lulusan'   => $lulusan,
            'inter'     => $query->kerja_internasional,
            'nasional'  => $query->kerja_nasional,
            'lokal'     => $query->kerja_lokal,
        );

        $faktor = array(
            'a' => 5,
            'b' => 20,
            'c' => 90
        );

        $rata = array(
            'inter'    => ($jumlah['inter']/$jumlah['lulusan'])*100,
            'nasional' => ($jumlah['nasional']/$jumlah['lulusan'])*100,
            'lokal'    => ($jumlah['lokal']/$jumlah['lulusan'])*100,
        );


        if($rata['inter']>=$faktor['a']) {
            $skor = 4;
            $rumus = "4";
        } else if($rata['inter']<$faktor['a'] && $rata['nasional']>=$faktor['b']) {
            $skor = 3+($rata['inter']/$faktor['a']);
            $rumus = "3 + (RI / a)";
        } else if(($rata['inter']>0 && $rata['inter']<$faktor['a']) || ($rata['nasional']>0 && $rata['nasional']<$faktor['b'])) {
            $skor = 2 + (2*($rata['inter']/$faktor['a']))+($rata['nasional']/$faktor['b'])-(($rata['inter']*$rata['nasional'])/($faktor['a']*$faktor['b']));
            $rumus = "2 + (2 * (RI / a)) + (RN / b) - ((RI * RN) / (a * b))";
        } else if($rata['inter']==0 && $rata['nasional']==0 && $rata['lokal']>=$faktor['c']) {
            $skor = 2;
            $rumus = "2";
        } else if($rata['inter']==0 && $rata['nasional']==0 && $rata['lokal']<$faktor['c']) {
            $skor = (2*$rata['lokal'])/$faktor['c'];
            $rumus = "(2 * RL) / c";
        } else {
            $skor  = 0;
            $rumus = null;
        }

        $data = compact(['jumlah','rata','skor','rumus']);

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

}
