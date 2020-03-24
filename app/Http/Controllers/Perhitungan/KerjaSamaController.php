<?php

namespace App\Http\Controllers\Perhitungan;

use App\Collaboration;
use App\Http\Controllers\Controller;
use App\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KerjaSamaController extends Controller
{
    public function kerjasama()
    {
        $dtps       = Teacher::where('kd_prodi',Auth::user()->kd_prodi)->where('ikatan_kerja','Dosen Tetap PS')->count();
        $kerjasama  = Collaboration::whereHas(
                                        'academicYear', function($q) {
                                            $q->whereBetween('tahun_akademik', [date('Y',strtotime('-3 year')),date('Y')]);
                                        }
                                    )
                                    ->where('kd_prodi',Auth::user()->kd_prodi)
                                    ->get();

        $faktor_jenis = array(
            'a' => 3,
            'b' => 2,
            'c' => 1,
        );

        $faktor_tingkat = array(
            'a' => 2,
            'b' => 6,
            'c' => 9
        );

        $jumlah = array(
            'dtps'          => $dtps,
            'pendidikan'    => $kerjasama->where('jenis','Pendidikan')->count(),
            'penelitian'    => $kerjasama->where('jenis','Penelitian')->count(),
            'pengabdian'    => $kerjasama->where('jenis','Pengabdian')->count(),
            'internasional' => $kerjasama->where('tingkat','Internasional')->count(),
            'nasional'      => $kerjasama->where('tingkat','Nasional')->count(),
            'lokal'         => $kerjasama->where('tingkat','Lokal')->count(),
        );

        //RUMUS A
        $rata = array(
            'pendidikan'    => $faktor_jenis['a'] * $jumlah['pendidikan'],
            'penelitian'    => $faktor_jenis['b'] * $jumlah['penelitian'],
            'pengabdian'    => $faktor_jenis['c'] * $jumlah['pengabdian']
        );

        $rata_a = ($rata['pendidikan']+$rata['penelitian']+$rata['pengabdian'])/$jumlah['dtps'];

        if($rata_a >= 4) {
            $skor['a']  = 4;
            $rumus['a'] = "4";
        } else if($rata_a < 4) {
            $skor['a']  = $rata_a;
            $rumus['a'] = "((a x N1) + (b x N2) + (c x N3)) / NDTPS";
        } else {
            $skor['a']  = 0;
            $rumus['a'] = null;
        }


        //RUMUS B
        if($jumlah['internasional'] >= $faktor_tingkat['a']) {
            $skor['b'] = 4;
            $rumus['b'] = "4";
        } else if($jumlah['internasional'] < $faktor_tingkat['a'] && $jumlah['nasional'] >= $faktor_tingkat['b']) {
            $skor['b']  = 3 + ($jumlah['internasional']/$faktor_tingkat['a']);
            $rumus['b'] = "3 + (NI / a)";
        } else if(($jumlah['internasional'] > 0 && $jumlah['internasional'] < $faktor_tingkat['a']) || ($jumlah['nasional'] > 0 && $jumlah['nasional'] < $faktor_tingkat['b'])) {
            $skor['b']  = 2 + (2 * ($jumlah['internasional']/$faktor_tingkat['a'])) + ($jumlah['nasional']/$faktor_tingkat['b']) - (($jumlah['internasional'] * $jumlah['nasional'])/($faktor_tingkat['a'] * $faktor_tingkat['b']));
            $rumus['b'] = "2 + (2 x (NI/a)) + (NN/b) - ((NI x NN)/(a x b))";
        } else if ($jumlah['internasional'] == 0 && $jumlah['nasional'] == 0 && $jumlah['lokal'] >= $faktor_tingkat['c']) {
            $skor['b']  = 2;
            $rumus['b'] = "2";
        } else if ($jumlah['internasional'] == 0 && $jumlah['nasional'] == 0 && $jumlah['lokal'] < $faktor_tingkat['c']) {
            $skor['b']  = (2 * $jumlah['lokal']) / $faktor_tingkat['c'];
            $rumus['b'] = "(2 x NL) / c";
        } else {
            $skor['b'] = 0;
            $rumus['b'] = null;
        }

        //SKOR
        $skor['total']  = ((2*$skor['a'])+$skor['b'])/3;
        $rumus['total'] = "((2 x A) + B) / 3";

        return view('simulasi.kerjasama.index',compact('jumlah','rata','skor','rumus'));
    }
}
