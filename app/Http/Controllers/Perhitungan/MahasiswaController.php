<?php

namespace App\Http\Controllers\Perhitungan;

use App\AcademicYear;
use App\Student;
use App\Http\Controllers\Controller;
use App\StudentQuota;
use App\StudyProgram;
use App\StudentForeign;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_Id'))->get();

        return view('simulasi.mahasiswa.index',compact('studyProgram'));
    }

    public function mahasiswa_seleksi(Request $request)
    {
        if(Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        $thn_aktif    = AcademicYear::where('status','Aktif')->first()->tahun_akademik;
        $id_thn_aktif = AcademicYear::where('tahun_akademik',$thn_aktif)->where('semester','Ganjil')->first()->id;

        $jumlah = array(
            'mhs_baru'  => Student::where('angkatan',$thn_aktif)->where('kd_prodi',$prodi)->count(),
            'mhs_calon' => StudentQuota::where('id_ta',$id_thn_aktif)->where('kd_prodi',$prodi)->first()->calon_pendaftar
        );

        $rasio['baru']  = rata(($jumlah['mhs_baru']/$jumlah['mhs_calon'])*10);
        $rasio['calon'] = 10-$rasio['baru'];

        if($rasio['calon']>=5) {
			$skor = 4;
		} else if($rasio['calon']<5) {
			$skor = (4*$rasio['calon'])/5;
		} else {
            $skor = 0;
        }

        $data = compact(['jumlah','rasio','skor']);

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function mahasiswa_asing(Request $request)
    {
        if(Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        $query = StudentForeign::whereHas(
                                    'student', function($q) use($prodi) {
                                        $q->where('kd_prodi', $prodi);
                                    }
                                )
                                ->whereHas(
                                    'student.latestStatus', function($q) {
                                        $q->where('status','Aktif');
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
            'asing_full' => $query->where('durasi','Full-time')->count(),
            'asing_part' => $query->where('durasi','Part-time')->count(),
        );

        $persentase = array(
            'asing_full' => rata(($jumlah['asing_full']/$jumlah['mahasiswa'])*100),
            'asing_part' => rata(($jumlah['asing_part']/$jumlah['mahasiswa'])*100),
        );
        $persentase['asing'] = rata($persentase['asing_full']+$persentase['asing_part']).'%';

        $skor['a']     = $request->post('skor_asing_a');
        if($persentase['asing']>=1) {
            $skor['b'] = 4;
        } elseif ($persentase['asing']<1) {
            $skor['b'] = 2+((200*$persentase['asing'])/100);
        } else {
            $skor['b'] = 0;
        }

        $skor['total'] = rata(( (2*$skor['a']) + $skor['b'] ) / 3);

        $data = compact(['jumlah','rasio','persentase','skor']);

        if($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
