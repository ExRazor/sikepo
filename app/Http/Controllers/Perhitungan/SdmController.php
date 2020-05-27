<?php

namespace App\Http\Controllers\Perhitungan;

use App\Ewmp;
use App\Http\Controllers\Controller;
use App\Minithesis;
use App\StudyProgram;
use App\Teacher;
use App\Student;
use App\TeacherAchievement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SdmController extends Controller
{
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_Id'))->get();

        return view('simulasi.sdm.index',compact('studyProgram'));
    }

    public function kecukupan_dosen(Request $request)
    {
        if(Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        $jumlah = array(
            'dtps'  => Teacher::where('kd_prodi',$prodi)->where('ikatan_kerja','Dosen Tetap PS')->count(),
        );

        if($jumlah['dtps']>=12) {
            $skor = 4;
        } else if($jumlah['dtps']>=6 && $jumlah['dtps']<12) {
            $skor = $jumlah['dtps']/3;
        } else {
            $skor = 0;
        }

        $data = compact(['jumlah','skor']);

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function persentase_dtps_s3(Request $request)
    {
        if(Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        $dtps = Teacher::where('kd_prodi',$prodi)->where('ikatan_kerja','Dosen Tetap PS')->get();

        $jumlah = array(
            'dtps'      => $dtps->count(),
            'dtps_s3'   => $dtps->where('pend_terakhir_jenjang','S3')->count(),
        );

        $persentase = ($jumlah['dtps_s3']/$jumlah['dtps'])*100;

        if($persentase>=50) {
            $skor = 4;
        } else if ($persentase<50) {
            $skor = 2 + ((4*$persentase)/100);
        } else {
            $skor = 0;
        }

        $data = compact(['jumlah','persentase','skor']);

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function persentase_dtps_jabatan(Request $request)
    {
        if(Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        $dtps = Teacher::where('kd_prodi',$prodi)->where('ikatan_kerja','Dosen Tetap PS')->get();

        $jumlah = array(
            'dtps'      => $dtps->count(),
            'dtps_gubes'=> $dtps->where('jabatan_akademik','Guru Besar')->count(),
            'dtps_lk'   => $dtps->where('jabatan_akademik','Lektor Kepala')->count(),
        );

        $jumlah['dtps_gubes_lk'] = $jumlah['dtps_gubes'] + $jumlah['dtps_lk'];

        $persentase = ($jumlah['dtps_gubes_lk']/$jumlah['dtps'])*100;

        if($persentase>=50) {
            $skor = 4;
        } else if ($persentase<50) {
            $skor = 2 + ((4*$persentase)/100);
        } else {
            $skor = 0;
        }

        $data = compact(['jumlah','persentase','skor']);

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function persentase_dtps_sertifikat(Request $request)
    {
        if(Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        $dtps = Teacher::where('kd_prodi',$prodi)->where('ikatan_kerja','Dosen Tetap PS')->get();

        $jumlah = array(
            'dtps'           => $dtps->count(),
            'dtps_sertifikat'=> $dtps->where('sertifikat_pendidik','!=',null)->count(),
        );

        $persentase = ($jumlah['dtps_sertifikat']/$jumlah['dtps'])*100;

        if($persentase>=80) {
            $skor = 4;
        } else if ($persentase<80) {
            $skor = 1 + (((15*$persentase)/100)/4);
        } else {
            $skor = 0;
        }

        $data = compact(['jumlah','persentase','skor']);

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function persentase_dtps_dtt(Request $request)
    {
        if(Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        $dosen = Teacher::where('kd_prodi',$prodi)->get();

        $jumlah = array(
            'dosen' => $dosen->count(),
            'dtps'  => $dosen->where('ikatan_kerja','Dosen Tetap PS')->count(),
            'dtt'   => $dosen->where('ikatan_kerja','Dosen Tidak Tetap')->count(),
        );

        $desimal = array(
            'dtt'   => $jumlah['dtt']/$jumlah['dosen'],
            'dtps'  => $jumlah['dtps']/$jumlah['dosen']
        );

        $persentase = array(
            'dtt'   => $desimal['dtt']*100,
            'dtps'  => $desimal['dtps']*100
        );

        // $desimal    = $jumlah['dtt']/$jumlah['dtps'];
        // $persentase = $desimal*100;

        if($persentase['dtt']<=10) {
            $skor = 4;
        } else if ($persentase['dtt']>10 && $persentase['dtt']<=40) {
            $skor = (16 - (40*$desimal['dtt']))/3;
        } else if ($persentase['dtt'] > 40) {
            $skor = 0;
        }

        $data = compact(['jumlah','persentase','skor']);

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function rasio_mahasiswa_dtps(Request $request)
    {
        if(Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        $jumlah = array(
            'mahasiswa'  => Student::where('kd_prodi', $prodi)
                                    ->whereHas(
                                        'latestStatus', function($q) {
                                            $q->where('status','Aktif');
                                        }
                                    )
                                    ->count(),
            'dtps'       => Teacher::where('kd_prodi',$prodi)->where('ikatan_kerja','Dosen Tetap PS')->count(),
        );

        $rasio['dtps'] = ($jumlah['dtps']/$jumlah['mahasiswa'])*100;
        $rasio['mahasiswa'] = 100-$rasio['dtps'];

        if($rasio['dtps']>=15 && $rasio['dtps'] <= 25) {
            $skor=4;
        } else if($rasio['dtps']<15) {
            $skor = (4*$rasio['dtps'])/15;
        } else if($rasio['dtps']>25 && $rasio['dtps']<=35) {
            $skor = (70-(2*$rasio['dtps']))/5;
        } else if ($rasio['dtps']>35) {
            $skor=0;
        }

        $data = compact(['jumlah','rasio','skor']);

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function beban_bimbingan(Request $request)
    {
        if(Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        $pembimbing = Minithesis::whereHas(
                                    'pembimbingUtama', function($q) use($prodi) {
                                        $q->where('kd_prodi',$prodi);
                                    }
                                )
                                // ->whereHas(
                                //     'academicYear', function($q) use($prodi) {
                                //         $q->where('status','Aktif');
                                //     }
                                // )
                                ->groupBy('pembimbing_utama')
                                ->select('pembimbing_utama')
                                ->get();

        $i = 0;
        foreach($pembimbing as $p) {
            $hitung_bimbingan = Minithesis::where('pembimbing_utama',$p)->get()->count();

            if($hitung_bimbingan <= 10) {
                $i+=1;
            }
        }
        $jumlah = array(
            'pembimbing_utama' => $pembimbing->count(),
            'pembimbing_10'    => $i,
        );

        $desimal    = ($jumlah['pembimbing_10']/$jumlah['pembimbing_utama']);
        $persentase = $desimal*100;

        if($persentase>20) {
            $skor = (5*$desimal)-1;
        } else if ($persentase<=20) {
            $skor = 0;
        }

        $data = compact(['jumlah','persentase','skor']);

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function waktu_mengajar(Request $request)
    {
        if(Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        $dtps      = Teacher::where('kd_prodi',$prodi)->where('ikatan_kerja','Dosen Tetap PS')->get();
        $total_sks = 0;
        $rata_sks = 0;

        foreach($dtps as $dt) {
            $total_sks += Ewmp::where('nidn',$dt->nidn)->get()->sum('total_sks');
            $rata_sks  += Ewmp::where('nidn',$dt->nidn)->get()->sum('rata_sks');
        }

        $jumlah = array(
            'dtps'       => $dtps->count(),
            'total_sks'  => $total_sks,
            'rata_sks'   => $rata_sks,
        );

        $rata_sks = ($jumlah['rata_sks']/$jumlah['dtps']);

        if($rata_sks>=12 && $rata_sks<=13) {
            $skor = 4;
        } else if($rata_sks>=6 && $rata_sks<12) {
            $skor = ((4*$rata_sks)-24)/5;
        } else if($rata_sks>=13 && $rata_sks<=18) {
            $skor = (72-(4*$rata_sks))/5;
        } else if($rata_sks<6 || $rata_sks>18) {
            $skor = 0;
        }

        $data = compact(['jumlah','rata_sks','skor']);

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function prestasi_dtps(Request $request)
    {
        if(Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        $dtps      = Teacher::where('kd_prodi',$prodi)->where('ikatan_kerja','Dosen Tetap PS')->get();

        foreach($dtps as $dt) {
            $nidn[] = $dt->nidn;
        }

        $jumlah = array(
            'dtps'                  => $dtps->count(),
            'dtps_berprestasi'      => TeacherAchievement::whereHas(
                                                            'academicYear', function($q) {
                                                                $q->whereBetween('tahun_akademik', [date('Y',strtotime('-3 year')),date('Y')]);
                                                            }
                                                        )
                                                        ->whereIn('nidn',$nidn)
                                                        ->groupBy('nidn')
                                                        ->get('nidn')
                                                        ->count(),
            'dtps_prestasi_inter'   => TeacherAchievement::whereHas(
                                                                'academicYear', function($q) {
                                                                    $q->whereBetween('tahun_akademik', [date('Y',strtotime('-3 year')),date('Y')]);
                                                                }
                                                            )
                                                            ->whereIn('nidn',$nidn)
                                                            ->where('tingkat_prestasi','Internasional')
                                                            ->groupBy('nidn')
                                                            ->get('nidn')
                                                            ->count(),
        );

        $rata = $jumlah['dtps_berprestasi']/$jumlah['dtps'];

        if($rata>=0.5 || $jumlah['dtps_prestasi_inter']>=1) {
            $skor = 4;
        } else if($rata<=0.5) {
            $skor = 2+(4*$rata);
        } else {
            $skor = 0;
        }

        $data = compact(['jumlah','rata','skor']);

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
