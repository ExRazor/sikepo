<?php

namespace App\Http\Controllers\Perhitungan;

use App\AcademicYear;
use App\Student;
use App\Http\Controllers\Controller;
use App\StudentQuota;
use App\StudyProgram;
use Illuminate\Support\Facades\Auth;

class SdmController extends Controller
{
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_Id'))->get();

        return view('simulasi.sdm.index',compact('studyProgram'));
    }

    public function seleksi_mahasiswa()
    {
        $thn_aktif    = AcademicYear::where('status','Aktif')->first()->tahun_akademik;
        $id_thn_aktif = AcademicYear::where('tahun_akademik',$thn_aktif)->where('semester','Ganjil')->first()->id;

        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_Id'))->get();

        // $jumlah = array(
        //     'mhs_baru'  => Student::where('angkatan',$thn_aktif)->count(),
        //     'mhs_calon' => StudentQuota::where('id_ta',$id_thn_aktif)->first()->calon_pendaftar
        // );

        $jumlah = array(
            'mhs_calon' => 100,
            'mhs_baru'  => 80,
        );

        $rasio['baru']  = ($jumlah['mhs_baru']/$jumlah['mhs_calon'])*10;
        $rasio['calon'] = 10-$rasio['baru'];

        if($rasio['calon']>=5) {
			$skor = 4;
		} else if($rasio['calon']<5) {
			$skor = (4*$rasio['calon'])/5;
		} else {
            $skor = 0;
        }

        return view('simulasi.mahasiswa.seleksi',compact('jumlah','rasio','skor','studyProgram'));
    }
}
