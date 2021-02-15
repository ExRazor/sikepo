<?php

namespace App\Http\Controllers\Perhitungan;

use App\Models\AcademicYear;
use App\Models\Ewmp;
use App\Http\Controllers\Controller;
use App\Models\CommunityService;
use App\Models\Minithesis;
use App\Models\Research;
use App\Models\StudyProgram;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\TeacherAchievement;
use App\Models\TeacherPublication;
use App\Models\TeacherOutputActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SdmController extends Controller
{
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan', setting('app_department_Id'))->get();

        return view('simulasi.sdm.index', compact('studyProgram'));
    }

    public function kecukupan_dosen(Request $request)
    {
        //Cek Auth untuk Kode Prodi
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            $jumlah = array(
                'dtps'  => Teacher::whereHas(
                    'latestStatus.studyProgram',
                    function ($query) use ($prodi) {
                        $query->where('kd_prodi', $prodi);
                    }
                )
                    ->where('status_kerja', 'Dosen Tetap PS')->count(),
            );
        } else {
            $jumlah = array(
                'dtps' => $request->input('dtps')
            );
        }

        //Hitung
        $data = $this->hitung_kecukupan_dosen($jumlah);

        //Return json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_kecukupan_dosen($jumlah)
    {
        if ($jumlah['dtps'] >= 12) {
            $skor = 4;
        } else if ($jumlah['dtps'] >= 3 && $jumlah['dtps'] < 12) {
            $skor = ((2 * $jumlah['dtps']) + 12) / 9;
        } else {
            $skor = 0;
        }

        return compact(['jumlah', 'skor']);
    }

    public function persentase_dtps_s3(Request $request)
    {
        //Cek Auth untuk Kode Prodi
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            $dtps = Teacher::whereHas(
                'latestStatus.studyProgram',
                function ($query) use ($prodi) {
                    $query->where('kd_prodi', $prodi);
                }
            )
                ->where('status_kerja', 'Dosen Tetap PS')->get();

            $jumlah = array(
                'dtps'      => $dtps->count(),
                'dtps_s3'   => $dtps->where('pend_terakhir_jenjang', 'S3')->count(),
            );
        } else {
            $jumlah = array(
                'dtps'      => $request->input('dtps'),
                'dtps_s3'   => $request->input('dtps_s3'),
            );
        }

        //Hitung
        $data = $this->hitung_persentase_dtps_s3($jumlah);

        //Return json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_persentase_dtps_s3($jumlah)
    {
        $persentase = ($jumlah['dtps_s3'] / $jumlah['dtps']) * 100;

        if ($persentase >= 50) {
            $skor = 4;
        } else if ($persentase < 50) {
            $skor = 2 + ((4 * $persentase) / 100);
        } else {
            $skor = 0;
        }

        return compact(['jumlah', 'persentase', 'skor']);
    }

    public function persentase_dtps_jabatan(Request $request)
    {
        //Cek auth untuk Kode Prodi
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            $dtps = Teacher::whereHas(
                'latestStatus.studyProgram',
                function ($query) use ($prodi) {
                    $query->where('kd_prodi', $prodi);
                }
            )
                ->where('status_kerja', 'Dosen Tetap PS')->get();

            $jumlah = array(
                'dtps'          => $dtps->count(),
                'dtps_gubes'    => $dtps->where('jabatan_akademik', 'Guru Besar')->count(),
                'dtps_lk'       => $dtps->where('jabatan_akademik', 'Lektor Kepala')->count(),
                'dtps_lektor'   => $dtps->where('jabatan_akademik', 'Lektor')->count(),
            );
        } else {
            $jumlah = array(
                'dtps'          => $request->input('dtps'),
                'dtps_gubes'    => $request->input('dtps_gb'),
                'dtps_lk'       => $request->input('dtps_lk'),
                'dtps_lektor'   => $request->input('dtps_lektor'),
            );
        }

        //Hitung
        $data = $this->hitung_persentase_dtps_jabatan($jumlah);

        //Return json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_persentase_dtps_jabatan($jumlah)
    {
        $total = $jumlah['dtps_gubes'] + $jumlah['dtps_lk'] + $jumlah['dtps_lektor'];
        $persentase = ($total / $jumlah['dtps']) * 100;

        if ($persentase >= 70) {
            $skor = 4;
        } else if ($persentase < 70) {
            $skor = 2 + ((20 * ($persentase / 100)) / 7);
        } else {
            $skor = 0;
        }

        return compact(['jumlah', 'persentase', 'skor']);
    }

    public function rasio_mahasiswa_dtps(Request $request)
    {
        //Cek auth untuk kode prodi
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            $jumlah = array(
                'mahasiswa'  => Student::where('kd_prodi', $prodi)
                    ->whereHas(
                        'latestStatus',
                        function ($q) {
                            $q->where('status', 'Aktif');
                        }
                    )
                    ->count(),
                'dtps'       => Teacher::whereHas(
                    'latestStatus.studyProgram',
                    function ($query) use ($prodi) {
                        $query->where('kd_prodi', $prodi);
                    }
                )
                    ->where('status_kerja', 'Dosen Tetap PS')->count(),
            );
        } else {
            $jumlah = array(
                'mahasiswa'  => $request->input('mahasiswa'),
                'dtps'       => $request->input('dtps'),
            );
        }

        $data = $this->hitung_rasio_mahasiswa_dtps($jumlah);

        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_rasio_mahasiswa_dtps($jumlah)
    {
        $rasio['dtps'] = $jumlah['mahasiswa'] / $jumlah['dtps'];
        $rasio['mahasiswa'] = 100 - $rasio['dtps'];


        if ($rasio['dtps'] >= 15 && $rasio['dtps'] <= 25) {
            $skor = 4;
        } else if ($rasio['dtps'] < 15) {
            $skor = (4 * $rasio['dtps']) / 15;
        } else if ($rasio['dtps'] > 25 && $rasio['dtps'] <= 35) {
            $skor = (70 - (2 * $rasio['dtps'])) / 5;
        } else if ($rasio['dtps'] > 35) {
            $skor = 0;
        }

        return compact(['jumlah', 'rasio', 'skor']);
    }

    public function persentase_dtps_dtt(Request $request)
    {
        //Cek auth untuk kode prodi
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            $dosen = Teacher::whereHas(
                'latestStatus.studyProgram',
                function ($query) use ($prodi) {
                    $query->where('kd_prodi', $prodi);
                }
            )
                ->get();

            $jumlah = array(
                'dtps'  => $dosen->where('status_kerja', 'Dosen Tetap PS')->count(),
                'dtt'   => $dosen->where('status_kerja', 'Dosen Tidak Tetap')->count(),
            );
        } else {
            $jumlah = array(
                'dtps'  => $request->input('dtps'),
                'dtt'   => $request->input('dtt'),
            );
        }

        //Hitung
        $data = $this->hitung_persentase_dtps_dtt($jumlah);

        //Return json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_persentase_dtps_dtt($jumlah)
    {
        $jumlah['dosen'] = $jumlah['dtps'] + $jumlah['dtt'];
        $desimal = array(
            'dtps'  => $jumlah['dtps'] / $jumlah['dosen'],
            'dtt'   => $jumlah['dtt'] / $jumlah['dosen']
        );

        $persentase = array(
            'dtps'  => $desimal['dtps'] * 100,
            'dtt'   => $desimal['dtt'] * 100
        );

        if ($persentase['dtt'] <= 10) {
            $skor = 4;
        } else if ($persentase['dtt'] > 10 && $persentase['dtt'] <= 40) {
            $skor = (14 - (20 * $desimal['dtt'])) / 3;
        } else if ($persentase['dtt'] > 40) {
            $skor = 0;
        }

        return compact(['jumlah', 'persentase', 'skor']);
    }

    public function beban_bimbingan(Request $request)
    {
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            $dosen = Teacher::whereHas(
                'latestStatus.studyProgram',
                function ($q) use ($prodi) {
                    $q->where('kd_prodi', $prodi);
                }
            )
                ->has('minithesis_utama')
                ->get();

            foreach ($dosen as $d) {
                $bimbingan[] = $d->minithesis_utama->count();
            }

            $jumlah = array_sum($bimbingan) / count($bimbingan);
        } else {
            $jumlah = $request->input('rata_bimbingan');
        }

        $data = $this->hitung_beban_bimbingan($jumlah);

        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_beban_bimbingan($jumlah)
    {
        if ($jumlah <= 6) {
            $skor = 4;
        } else if ($jumlah > 6 && $jumlah <= 10) {
            $skor = 7 - ($jumlah / 2);
        } else {
            $skor = 0;
        }

        return compact(['jumlah', 'skor']);
    }

    public function waktu_mengajar(Request $request)
    {
        //Cek auth untuk kode prodi
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            $dtps      = Teacher::whereHas(
                'latestStatus.studyProgram',
                function ($query) use ($prodi) {
                    $query->where('kd_prodi', $prodi);
                }
            )
                ->where('status_kerja', 'Dosen Tetap PS')
                ->get();

            $total_sks = 0;
            $rata_sks = 0;
            foreach ($dtps as $dt) {
                $total_sks += Ewmp::where('nidn', $dt->nidn)->get()->sum('total_sks');
                $rata_sks  += Ewmp::where('nidn', $dt->nidn)->get()->sum('rata_sks');
            }

            $jumlah = array(
                'dtps'       => $dtps->count(),
                'rata_sks'   => $rata_sks,
            );
        } else {
            $jumlah = array(
                'dtps'       => $request->input('total_dtps'),
                'rata_sks'  => $request->input('total_rata_sks'),
            );
        }

        //Hitung
        $data = $this->hitung_waktu_mengajar($jumlah);

        //Return json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_waktu_mengajar($jumlah)
    {
        $rata_sks = ($jumlah['rata_sks'] / $jumlah['dtps']);

        if ($rata_sks >= 12 && $rata_sks <= 16) {
            $skor = 4;
        } else if ($rata_sks >= 6 && $rata_sks < 12) {
            $skor = ((2 * $rata_sks) - 12) / 3;
        } else if ($rata_sks > 16 && $rata_sks <= 18) {
            $skor = (36 - (2 * $rata_sks));
        } else if ($rata_sks < 6 || $rata_sks > 18) {
            $skor = 0;
        }

        return compact(['jumlah', 'rata_sks', 'skor']);
    }

    public function prestasi_dtps(Request $request)
    {
        //Cek auth untuk kode prodi
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            $dtps      = Teacher::whereHas(
                'latestStatus.studyProgram',
                function ($query) use ($prodi) {
                    $query->where('kd_prodi', $prodi);
                }
            )
                ->where('status_kerja', 'Dosen Tetap PS')
                ->get();

            foreach ($dtps as $dt) {
                $nidn[] = $dt->nidn;
            }

            $dtps_berprestasi = TeacherAchievement::whereHas(
                'academicYear',
                function ($q) {
                    $q->whereBetween('tahun_akademik', [date('Y', strtotime('-3 year')), date('Y')]);
                }
            )
                ->whereIn('nidn', $nidn)
                ->groupBy('nidn')
                ->get('nidn');

            $jumlah = array(
                'dtps'              => $dtps->count(),
                'dtps_berprestasi'  => $dtps_berprestasi->count(),
            );
        } else {
            $jumlah = array(
                'dtps'              => $request->input('dtps'),
                'dtps_berprestasi'  => $request->input('dtps_berprestasi'),
            );
        }

        $data = $this->hitung_prestasi_dtps($jumlah);

        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_prestasi_dtps($jumlah)
    {
        $rata = $jumlah['dtps_berprestasi'] / $jumlah['dtps'];

        if ($rata >= 0.5) {
            $skor = 4;
        } else if ($rata < 0.5) {
            $skor = 2 + (4 * $rata);
        } else {
            $skor = 0;
        }

        return compact(['jumlah', 'rata', 'skor']);
    }

    public function penelitian_dtps(Request $request)
    {
        //Cek auth untuk kode prodi
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            $dtps = Teacher::whereHas(
                'latestStatus.studyProgram',
                function ($query) use ($prodi) {
                    $query->where('kd_prodi', $prodi);
                }
            )
                ->where('status_kerja', 'Dosen Tetap PS')->get();

            $penelitian = Research::whereHas(
                'academicYear',
                function ($q) {
                    $q->whereBetween('tahun_akademik', [date('Y', strtotime('-3 year')), date('Y')]);
                }
            )
                ->whereHas(
                    'researchTeacher',
                    function ($q) use ($prodi) {
                        $q->prodiKetua($prodi);
                    }
                )
                ->get();

            $p_mandiri = $penelitian->where('sumber_biaya', 'Mandiri')->count();
            $p_pt = $penelitian->where('sumber_biaya', 'Perguruan Tinggi')->count();

            $jumlah = array(
                'dtps'  => $dtps->count(),
                'ni'    => $penelitian->where('sumber_biaya', 'Lembaga Luar Negeri')->count(),
                'nn'    => $penelitian->where('sumber_biaya', 'Lembaga Dalam Negeri')->count(),
                'nl'    => $p_mandiri + $p_pt
            );
        } else {
            $jumlah = array(
                'dtps'  => $request->input('dtps'),
                'ni'    => $request->input('ni'),
                'nn'    => $request->input('nn'),
                'nl'    => $request->input('nl')
            );
        }

        //Hitung
        $data = $this->hitung_penelitian_dtps($jumlah);

        //Return json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function pengabdian_dtps(Request $request)
    {
        //Cek auth untuk kode prodi
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            $dtps = Teacher::whereHas(
                'latestStatus.studyProgram',
                function ($query) use ($prodi) {
                    $query->where('kd_prodi', $prodi);
                }
            )
                ->where('status_kerja', 'Dosen Tetap PS')->get();

            $pengabdian = CommunityService::whereHas(
                'academicYear',
                function ($q) {
                    $q->whereBetween('tahun_akademik', [date('Y', strtotime('-3 year')), date('Y')]);
                }
            )
                ->whereHas(
                    'serviceTeacher',
                    function ($q) use ($prodi) {
                        $q->prodiKetua($prodi);
                    }
                )
                ->get();

            $p_mandiri = $pengabdian->where('sumber_biaya', 'Mandiri')->count();
            $p_pt = $pengabdian->where('sumber_biaya', 'Perguruan Tinggi')->count();

            $jumlah = array(
                'dtps'  => $dtps->count(),
                'ni'    => $pengabdian->where('sumber_biaya', 'Lembaga Luar Negeri')->count(),
                'nn'    => $pengabdian->where('sumber_biaya', 'Lembaga Dalam Negeri')->count(),
                'nl'    => $p_mandiri + $p_pt
            );
        } else {
            $jumlah = array(
                'dtps'  => $request->input('dtps'),
                'ni'    => $request->input('ni'),
                'nn'    => $request->input('nn'),
                'nl'    => $request->input('nl')
            );
        }

        //Hitung
        $data = $this->hitung_penelitian_dtps($jumlah);

        //Return json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_penelitian_dtps($jumlah)
    {
        $faktor = array(
            'a' => 0.05,
            'b' => 0.3,
            'c' => 1
        );

        $rata = array(
            'inter'     => $jumlah['ni'] / 3 / $jumlah['dtps'],
            'nasional'  => $jumlah['nn'] / 3 / $jumlah['dtps'],
            'lokal'     => $jumlah['nl'] / 3 / $jumlah['dtps']
        );

        if ($rata['inter'] >= $faktor['a']) {
            $skor = 4;
            $rumus = "4";
        } else if ($rata['inter'] < $faktor['a'] && $rata['nasional'] >= $faktor['b']) {
            $skor = 3 + ($rata['inter'] / $faktor['a']);
            $rumus = "3 + (RI / a)";
        } else if (($rata['inter'] > 0 && $rata['inter'] < $faktor['a']) || ($rata['nasional'] < 0 && $rata['nasional'] > $faktor['b'])) {
            $skor = 2 + (2 * ($rata['inter'] / $faktor['a'])) + ($rata['nasional'] / $faktor['b']) - (($rata['inter'] * $rata['nasional']) / ($faktor['a'] * $faktor['b']));
            $rumus = "2 + (2 * (RI / a)) + (RN / b) - ((RI * RN) / (a * b))";
        } else if ($rata['inter'] == 0 && $rata['nasional'] == 0 && $rata['lokal'] >= $faktor['c']) {
            $skor = 2;
            $rumus = "2";
        } else if ($rata['inter'] == 0 && $rata['nasional'] == 0 && $rata['lokal'] < $faktor['c']) {
            $skor = (2 * $rata['lokal']) / $faktor['c'];
            $rumus = "(2 * RL) / c";
        } else {
            $skor = 0;
        }

        return compact(['jumlah', 'faktor', 'rata', 'skor', 'rumus']);
    }

    public function publikasi_dtps(Request $request)
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

            $dtps = Teacher::whereHas(
                'latestStatus.studyProgram',
                function ($query) use ($prodi) {
                    $query->where('kd_prodi', $prodi);
                }
            )
                ->where('status_kerja', 'Dosen Tetap PS')
                ->get();

            $publikasi = TeacherPublication::whereHas(
                'teacher.latestStatus',
                function ($q) use ($prodi) {
                    $q->where('kd_prodi', $prodi);
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
                'dtps'  => $dtps->count(),
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
                'dtps'  => $request->input('dtps'),
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
        $data = $this->hitung_publikasi_dtps($jumlah);

        //Return json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_publikasi_dtps($jumlah)
    {
        $faktor = array(
            'a' => 0.1,
            'b' => 1,
            'c' => 2
        );

        $rata = array(
            'rl' => ($jumlah['na1'] + $jumlah['nb1'] + $jumlah['nc1']) / $jumlah['dtps'],
            'rn' => ($jumlah['na2'] + $jumlah['na3'] + $jumlah['nb2'] + $jumlah['nc2']) / $jumlah['dtps'],
            'ri' => ($jumlah['na4'] + $jumlah['nb3'] + $jumlah['nc3']) / $jumlah['dtps']
        );

        if ($rata['ri'] >= $faktor['a']) {
            $skor = 4;
            $rumus = "4";
        } else if ($rata['ri'] < $faktor['a'] && $rata['rn'] >= $faktor['b']) {
            $skor = 3 + ($rata['ri'] / $faktor['a']);
            $rumus = "3 + (RI / faktor a)";
        } else if (($rata['ri'] > 0 && $rata['ri'] < $faktor['a']) || ($rata['rn'] > 0 && $rata['rn'] < $faktor['b'])) {
            $skor = 2 + (2 * ($rata['ri'] / $faktor['a'])) + ($rata['rn'] / $faktor['b']) - (($rata['ri'] * $rata['rn']) / ($faktor['a'] * $faktor['b']));
            $rumus = "2 + (2 * (RI / a)) + (RN / b) 0 ((RI * RN) / ( a * b ))";
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

    public function publikasi_tersitasi(Request $request)
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
            $publikasi = TeacherPublication::whereHas(
                'teacher.latestStatus',
                function ($q) use ($prodi) {
                    $q->where('kd_prodi', $prodi);
                }
            )
                ->whereHas(
                    'academicYear',
                    function ($q) use ($thn_akademik) {
                        $q->whereBetween('tahun_akademik', [$thn_akademik->tahun_akademik - 3, $thn_akademik->tahun_akademik]);
                    }
                )
                ->get();

            $dtps = Teacher::whereHas(
                'latestStatus.studyProgram',
                function ($query) use ($prodi) {
                    $query->where('kd_prodi', $prodi);
                }
            )
                ->where('status_kerja', 'Dosen Tetap PS')
                ->get();

            $jumlah = array(
                'dtps'  => $dtps->count(),
                'nas'   => $publikasi->where('sitasi', '!=', null)->count(),
            );
        } else {
            $jumlah = array(
                'dtps'  => $request->input('dtps'),
                'nas'   => $request->input('nas'),
            );
        }

        //Hitung
        $data = $this->hitung_publikasi_tersitasi($jumlah);

        //Return json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_publikasi_tersitasi($jumlah)
    {
        $rata = array(
            'rs' => $jumlah['nas'] / $jumlah['dtps'],
        );

        if ($rata['rs'] >= 0.5) {
            $skor = 4;
            $rumus = "4";
        } else if ($rata['rs'] < 0.5) {
            $skor = 2 + (4 * $rata['rs']);
            $rumus = "2 + (4 * RS)";
        } else {
            $skor = 0;
            $rumus = "Tidak ada Skor kurang dari 2";
        }

        return compact(['jumlah', 'rata', 'skor', 'rumus']);
    }

    public function luaran_pkm(Request $request)
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

            $luaran = TeacherOutputActivity::whereHas(
                'teacher.latestStatus',
                function ($q) use ($prodi) {
                    $q->where('kd_prodi', $prodi);
                }
            )
                ->whereHas(
                    'academicYear',
                    function ($q) use ($thn_akademik) {
                        $q->whereBetween('tahun_akademik', [$thn_akademik->tahun_akademik - 3, $thn_akademik->tahun_akademik]);
                    }
                )
                ->get();

            $dtps = Teacher::whereHas(
                'latestStatus.studyProgram',
                function ($query) use ($prodi) {
                    $query->where('kd_prodi', $prodi);
                }
            )
                ->where('status_kerja', 'Dosen Tetap PS')
                ->get();

            $jumlah = array(
                'dtps' => $dtps->count(),
                'na'   => $luaran->where('id_kategori', '1')->count(),
                'nb'   => $luaran->where('id_kategori', '2')->count(),
                'nc'   => $luaran->where('id_kategori', '3')->count(),
                'nd'   => $luaran->where('id_kategori', '4')->count(),
            );
        } else {
            $jumlah = array(
                'dtps' => $request->input('dtps'),
                'na'   => $request->input('na'),
                'nb'   => $request->input('nb'),
                'nc'   => $request->input('nc'),
                'nd'   => $request->input('nd'),
            );
        }

        //Hitung
        $data = $this->hitung_luaran_pkm($jumlah);

        //Return json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_luaran_pkm($jumlah)
    {
        $rata = (2 * ($jumlah['na'] + $jumlah['nb'] + $jumlah['nc']) + $jumlah['nd']) / $jumlah['dtps'];
        $rumus['rlp'] = "(2 * ( NA + NB + NC) + ND) / NDT";

        if ($rata >= 1) {
            $skor = 4;
            $rumus['skor'] = 4;
        } else if ($rata < 1) {
            $skor = 2 + (2 * $rata);
            $rumus['skor'] = "2 + (2 * RLP)";
        } else {
            $skor = null;
            $rumus['skor'] = "Tidak ada Skor kurang dari 2";
        }

        return compact(['jumlah', 'rata', 'skor', 'rumus']);
    }
}
