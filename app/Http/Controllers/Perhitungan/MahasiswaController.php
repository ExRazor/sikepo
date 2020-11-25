<?php

namespace App\Http\Controllers\Perhitungan;

use App\Models\AcademicYear;
use App\Models\Student;
use App\Http\Controllers\Controller;
use App\Models\StudentQuota;
use App\Models\StudyProgram;
use App\Models\StudentForeign;
use App\Models\StudentPublication;
use App\Models\StudentOutputActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan', setting('app_department_Id'))->get();

        return view('simulasi.mahasiswa.index', compact('studyProgram'));
    }

    public function mahasiswa_seleksi(Request $request)
    {
        //Cek Auth
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            $thn_aktif    = AcademicYear::where('status', true)->first()->tahun_akademik;
            $id_thn_aktif = AcademicYear::where('tahun_akademik', $thn_aktif)->where('semester', 'Ganjil')->first()->id;

            $jumlah = array(
                'mhs_baru'  => Student::where('angkatan', $thn_aktif)->where('kd_prodi', $prodi)->count(),
                'mhs_calon' => StudentQuota::where('id_ta', $id_thn_aktif)->where('kd_prodi', $prodi)->first()->calon_pendaftar
            );
        } else {
            $jumlah = array(
                'mhs_baru'  => $request->input('mhs_baru'),
                'mhs_calon' => $request->input('mhs_calon')
            );
        }

        //Hitung
        $data = $this->hitung_mahasiswa_seleksi($jumlah);

        //Tampil
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function mahasiswa_asing(Request $request)
    {
        //Cek Auth
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            $query = StudentForeign::whereHas(
                'student',
                function ($q) use ($prodi) {
                    $q->where('kd_prodi', $prodi);
                }
            )
                ->whereHas(
                    'student.latestStatus',
                    function ($q) {
                        $q->where('status', 'Aktif');
                    }
                )->get();

            $jumlah = array(
                'mahasiswa'  => Student::where('kd_prodi', $prodi)
                    ->whereHas(
                        'latestStatus',
                        function ($q) {
                            $q->where('status', 'Aktif');
                        }
                    )
                    ->count(),
                'asing_full' => $query->where('durasi', 'Full-time')->count(),
                'asing_part' => $query->where('durasi', 'Part-time')->count(),
            );
        } else {
            $jumlah = array(
                'mahasiswa'  => $request->input('mahasiswa_aktif'),
                'asing_full' => $request->input('mahasiswa_asing_full'),
                'asing_part' => $request->input('mahasiswa_asing_part'),
            );
        }

        //Ambil Skor A (Upaya)
        $skor_a = $request->input('skor_asing_a');

        //Compact sebelum kirim ke hitungan
        $data_hitung = compact(['jumlah', 'skor_a']);

        //Hitung
        $data = $this->hitung_mahasiswa_asing($data_hitung);

        //Tampil
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_mahasiswa_seleksi($jumlah)
    {
        //Hitung Rasio
        $rasio['baru']  = rata(($jumlah['mhs_baru'] / $jumlah['mhs_calon']) * 10);
        $rasio['calon'] = 10 - $rasio['baru'];

        //Skor
        if ($rasio['calon'] >= 5) {
            $skor = 4;
        } else if ($rasio['calon'] < 5) {
            $skor = (4 * $rasio['calon']) / 5;
        } else {
            $skor = 0;
        }

        return compact(['jumlah', 'rasio', 'skor']);
    }

    public function hitung_mahasiswa_asing($data)
    {
        //Tampung data ke tiap variabel
        $jumlah = $data['jumlah'];
        $skor_a = $data['skor_a'];

        //Hitung persentase
        $persentase = array(
            'asing_full' => rata(($jumlah['asing_full'] / $jumlah['mahasiswa']) * 100),
            'asing_part' => rata(($jumlah['asing_part'] / $jumlah['mahasiswa']) * 100),
        );
        $persentase['asing'] = rata($persentase['asing_full'] + $persentase['asing_part']) . '%';

        //Hitung Skor B
        $skor['a']     = $skor_a;
        if ($persentase['asing'] >= 1) {
            $skor['b'] = 4;
        } elseif ($persentase['asing'] < 1) {
            $skor['b'] = 2 + ((200 * $persentase['asing']) / 100);
        } else {
            $skor['b'] = 0;
        }

        //Hitung Total
        $skor['total'] = rata(((2 * $skor['a']) + $skor['b']) / 3);

        return compact(['jumlah', 'persentase', 'skor']);
    }

    public function publikasi_mhs(Request $request)
    {
        //Cek auth untuk kode prodi
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            $thn_akademik = AcademicYear::where('status', true)->first();

            $mhs = Student::whereHas(
                'latestStatus',
                function ($q) {
                    $q->where('status', 'Aktif');
                }
            )
                ->where('kd_prodi', $prodi)
                ->get();

            $publikasi = StudentPublication::whereHas(
                'student.studyProgram',
                function ($q) use ($prodi) {
                    $q->where('kd_prodi', $prodi);
                }
            )
                ->whereHas(
                    'student.latestStatus',
                    function ($q) {
                        $q->where('status', 'Aktif');
                    }
                )
                ->whereHas(
                    'academicYear',
                    function ($q) use ($thn_akademik) {
                        $q->whereBetween('tahun_akademik', [$thn_akademik->tahun_akademik - 3, $thn_akademik->tahun_akademik]);
                    }
                )
                ->get();

            $jumlah = array(
                'mhs'   => $mhs->count(),
                'na1'   => $publikasi->where('jenis_publikasi', '1')->count(),
                'na2'   => $publikasi->where('jenis_publikasi', '2')->count(),
                'na3'   => $publikasi->where('jenis_publikasi', '3')->count(),
                'na4'   => $publikasi->where('jenis_publikasi', '4')->count(),
                'nb1'   => $publikasi->where('jenis_publikasi', '5')->count(),
                'nb2'   => $publikasi->where('jenis_publikasi', '6')->count(),
                'nb3'   => $publikasi->where('jenis_publikasi', '7')->count(),
                'nc1'   => $publikasi->where('jenis_publikasi', '8')->count(),
                'nc2'   => $publikasi->where('jenis_publikasi', '9')->count(),
                'nc3'   => $publikasi->where('jenis_publikasi', '10')->count(),
            );
        } else {
            $jumlah = array(
                'mhs'   => $request->input('mhs'),
                'na1'   => $request->input('na1'),
                'na2'   => $request->input('na2'),
                'na3'   => $request->input('na3'),
                'na4'   => $request->input('na4'),
                'nb1'   => $request->input('nb1'),
                'nb2'   => $request->input('nb2'),
                'nb3'   => $request->input('nb3'),
                'nc1'   => $request->input('nc1'),
                'nc2'   => $request->input('nc2'),
                'nc3'   => $request->input('nc3'),
            );
        }

        //Hitung
        $data = $this->hitung_publikasi_mhs($jumlah);

        //Return json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_publikasi_mhs($jumlah)
    {
        $faktor = array(
            'a' => 1,
            'b' => 10,
            'c' => 50
        );

        $rata = array(
            'rl' => (($jumlah['na1'] + $jumlah['nb1'] + $jumlah['nc1']) / $jumlah['mhs']) * 100,
            'rn' => (($jumlah['na2'] + $jumlah['na3'] + $jumlah['nb2'] + $jumlah['nc2']) / $jumlah['mhs']) * 100,
            'ri' => (($jumlah['na4'] + $jumlah['nb3'] + $jumlah['nc3']) / $jumlah['mhs']) * 100
        );

        if ($rata['ri'] >= $faktor['a']) {
            $skor = 4;
            $rumus = "4";
        } else if ($rata['ri'] < $faktor['a'] && $rata['rn'] >= $faktor['b']) {
            $skor = 3 + ($rata['ri'] / $faktor['a']);
            $rumus = "3 + (RI / faktor a)";
        } else if (($rata['ri'] > 0 && $rata['ri'] < $faktor['a']) || ($rata['rn'] > 0 && $rata['rn'] < $faktor['b'])) {
            $skor = 2 + (2 * ($rata['ri'] / $faktor['a'])) + ($rata['rn'] / $faktor['b']) - (($rata['ri'] * $rata['rn']) / ($faktor['a'] * $faktor['b']));
            $rumus = "2 + (2 * (RI / a)) + (RN / b) 0 ((RI * RN) / ( a * b))";
        } else if ($rata['ri'] == 0 && $rata['rn'] == 0 && $rata['rl'] >= $faktor['c']) {
            $skor = 2;
            $rumus = " 2";
        } else if ($rata['ri'] == 0 && $rata['rn'] == 0 && $rata['rl'] < $faktor['c']) {
            $skor = (2 * $rata['rl']) / $faktor['c'];
            $rumus = "(2*RL)/faktor c";
        } else {
            $skor = 0;
            $rumus = 0;
        }

        return compact(['jumlah', 'faktor', 'rata', 'skor', 'rumus']);
    }

    public function luaran_mhs(Request $request)
    {
        //Cek auth untuk kode prodi
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            $thn_akademik = AcademicYear::where('status', true)->first();

            $luaran = StudentOutputActivity::whereHas(
                'student.studyProgram',
                function ($q) use ($prodi) {
                    $q->where('kd_prodi', $prodi);
                }
            )
                ->whereHas(
                    'student.latestStatus',
                    function ($q) {
                        $q->where('status', 'Aktif');
                    }
                )
                ->whereHas(
                    'academicYear',
                    function ($q) use ($thn_akademik) {
                        $q->whereBetween('tahun_akademik', [$thn_akademik->tahun_akademik - 3, $thn_akademik->tahun_akademik]);
                    }
                )
                ->get();

            $mhs = Student::whereHas(
                'latestStatus',
                function ($q) {
                    $q->where('status', 'Aktif');
                }
            )
                ->where('kd_prodi', $prodi)
                ->get();

            $jumlah = array(
                'mhs' => $mhs->count(),
                'na'   => $luaran->where('id_kategori', '1')->count(),
                'nb'   => $luaran->where('id_kategori', '2')->count(),
                'nc'   => $luaran->where('id_kategori', '3')->count(),
                'nd'   => $luaran->where('id_kategori', '4')->count(),
            );
        } else {
            $jumlah = array(
                'mhs'  => $request->input('mhs'),
                'na'   => $request->input('na'),
                'nb'   => $request->input('nb'),
                'nc'   => $request->input('nc'),
                'nd'   => $request->input('nd'),
            );
        }

        //Hitung
        $data = $this->hitung_luaran_mhs($jumlah);

        //Return json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_luaran_mhs($jumlah)
    {
        $hasil = 2 * ($jumlah['na'] + $jumlah['nb'] + $jumlah['nc']) + $jumlah['nd'];
        $rumus['nlp'] = "2 * ( NA + NB + NC) + ND";

        if ($hasil >= 1) {
            $skor = 4;
            $rumus['skor'] = 4;
        } else if ($hasil < 1) {
            $skor = 2 + (2 * $hasil);
            $rumus['skor'] = "2 + (2 * RLP)";
        } else {
            $skor = null;
            $rumus['skor'] = "Tidak ada Skor kurang dari 2";
        }

        return compact(['jumlah', 'hasil', 'skor', 'rumus']);
    }
}
