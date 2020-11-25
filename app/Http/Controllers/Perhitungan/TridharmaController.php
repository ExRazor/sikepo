<?php

namespace App\Http\Controllers\Perhitungan;

use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\AlumnusWorkplace;
use App\Models\StudentAchievement;
use App\Http\Controllers\Controller;
use App\Models\StudentStatus;
use App\Models\StudyProgram;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TridharmaController extends Controller
{
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan', setting('app_department_Id'))->get();

        return view('simulasi.tridharma.index', compact('studyProgram'));
    }

    public function capaian_ipk(Request $request)
    {
        //Cek Auth
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            //Query Capaian IPK Lulusan 3 Tahun Terakhir
            $query = StudentStatus::whereHas(
                'academicYear',
                function ($q) {
                    $q->whereBetween('tahun_akademik', [date('Y', strtotime('-3 year')), date('Y')]);
                }
            )
                ->whereHas(
                    'student',
                    function ($q) use ($prodi) {
                        $q->where('kd_prodi', $prodi);
                    }
                )
                ->where('status', 'Lulus')
                ->get();

            $lulusan  = $query->count();
            $rata_ipk = rata($query->avg('ipk_terakhir'));
        } else {
            $lulusan  = $request->input('total_mahasiswa');
            $rata_ipk = rata($request->input('rata_ipk'));
        }

        $data_hitung = compact(['lulusan', 'rata_ipk']);

        $data = $this->hitung_capaian_ipk($data_hitung);

        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function prestasi_akademik_mahasiswa(Request $request)
    {
        //Cek Auth
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {

            //Query Prestasi Akademik 3 Tahun Terakhir
            $query = StudentAchievement::whereHas(
                'student',
                function ($q) use ($prodi) {
                    $q->where('kd_prodi', $prodi);
                }
            )->whereHas(
                'academicYear',
                function ($q) use ($prodi) {
                    $q->whereBetween('tahun_akademik', [date('Y', strtotime('-3 year')), date('Y')]);
                }
            )
                ->where('prestasi_jenis', 'Akademik')
                ->get();

            //Jumlah
            $jumlah = array(
                'mahasiswa'  => Student::where('kd_prodi', $prodi)
                    ->whereHas(
                        'latestStatus',
                        function ($q) {
                            $q->where('status', 'Aktif');
                        }
                    )
                    ->count(),
                'inter'     => $query->where('kegiatan_tingkat', 'Internasional')->count(),
                'nasional'  => $query->where('kegiatan_tingkat', 'Nasional')->count(),
                'lokal'     => $query->where('kegiatan_tingkat', 'Wilayah')->count(),
            );
        } else {
            //Jumlah ambil dari inputan simulasi
            $jumlah = array(
                'mahasiswa' => $request->input('nm'),
                'inter'     => $request->input('ni'),
                'nasional'  => $request->input('nn'),
                'lokal'     => $request->input('nw'),
            );
        }

        //Compact variabel
        $data_hitung = compact(['jumlah']);

        //Proses hitung
        $data = $this->hitung_prestasi_akademik_mahasiswa($data_hitung);

        //Output json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function prestasi_nonakademik_mahasiswa(Request $request)
    {
        //Cek Auth
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {

            //Query Prestasi Akademik 3 Tahun Terakhir
            $query = StudentAchievement::whereHas(
                'student',
                function ($q) use ($prodi) {
                    $q->where('kd_prodi', $prodi);
                }
            )->whereHas(
                'academicYear',
                function ($q) use ($prodi) {
                    $q->whereBetween('tahun_akademik', [date('Y', strtotime('-3 year')), date('Y')]);
                }
            )
                ->where('prestasi_jenis', 'Non Akademik')
                ->get();

            //Jumlah
            $jumlah = array(
                'mahasiswa'  => Student::where('kd_prodi', $prodi)
                    ->whereHas(
                        'latestStatus',
                        function ($q) {
                            $q->where('status', 'Aktif');
                        }
                    )
                    ->count(),
                'inter'     => $query->where('kegiatan_tingkat', 'Internasional')->count(),
                'nasional'  => $query->where('kegiatan_tingkat', 'Nasional')->count(),
                'lokal'     => $query->where('kegiatan_tingkat', 'Wilayah')->count(),
            );
        } else {
            //Jumlah ambil dari inputan simulasi
            $jumlah = array(
                'mahasiswa' => $request->input('nm'),
                'inter'     => $request->input('ni'),
                'nasional'  => $request->input('nn'),
                'lokal'     => $request->input('nw'),
            );
        }

        //Compact variabel
        $data_hitung = compact(['jumlah']);

        //Proses hitung
        $data = $this->hitung_prestasi_nonakademik_mahasiswa($data_hitung);

        //Output json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function masa_studi_lulusan(Request $request)
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
            $q = StudentStatus::whereHas(
                'academicYear',
                function ($q) use ($thn_aktif) {
                    $q->where('tahun_akademik', $thn_aktif);
                }
            )
                ->whereHas(
                    'student',
                    function ($q) use ($prodi) {
                        $q->where('kd_prodi', $prodi);
                    }
                )
                ->where('status', 'Lulus')->get();

            $masa_studi = array();
            foreach ($q as $lulusan) {
                $masa_studi[$lulusan->nim] = $thn_aktif - $lulusan->student->angkatan;
            }

            $jumlah = array(
                'lulusan' => $q->count(),
                'rata_masa_studi' => count($masa_studi) > 0 ? array_sum($masa_studi) / count($masa_studi) : 0
            );
        } else {
            $jumlah = array(
                'rata_masa_studi' => $request->input('ms')
            );
        }

        //Proses hitung
        $data = $this->hitung_masa_studi_lulusan($jumlah);

        //Output json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function tempat_kerja_lulusan(Request $request)
    {
        //Cek Auth
        if (Auth::user()->hasRole('kaprodi')) {
            $prodi = Auth::user()->kd_prodi;
        } else {
            $prodi = $request->post('kd_prodi');
        }

        //Cek apakah simulasi
        if (!$request->post('simulasi')) {
            //Query Lulusan  3 Tahun Terakhir
            $lulusan = StudentStatus::whereHas(
                'academicYear',
                function ($q) {
                    $q->whereBetween('tahun_akademik', [date('Y', strtotime('-3 year')), date('Y')]);
                }
            )
                ->whereHas(
                    'student',
                    function ($q) use ($prodi) {
                        $q->where('kd_prodi', $prodi);
                    }
                )
                ->where('status', 'Lulus')
                ->count();

            //Query Tempat Kerja Lulusan 3 Tahun Terakhir
            $query = AlumnusWorkplace::where('kd_prodi', $prodi)
                ->whereBetween('tahun_lulus', [date('Y', strtotime('-3 year')), date('Y')])
                ->first();

            //Jumlah
            $jumlah = array(
                'lulusan'   => $lulusan,
                'inter'     => $query->kerja_internasional,
                'nasional'  => $query->kerja_nasional,
                'lokal'     => $query->kerja_lokal,
            );
        } else {
            //Jumlah dari input simulasi
            $jumlah = array(
                'lulusan'   => $request->input('na'),
                'inter'     => $request->input('ni'),
                'nasional'  => $request->input('nn'),
                'lokal'     => $request->input('nl'),
            );
        }

        //Compact variabel
        $data_hitung = compact(['jumlah']);

        //Hitung
        $data = $this->hitung_tempat_kerja_lulusan($data_hitung);

        //Output json
        if ($request->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hitung_capaian_ipk($data)
    {
        //Ambil data
        $lulusan  = $data['lulusan'];
        $rata_ipk = $data['rata_ipk'];

        //Hitung skor
        if ($rata_ipk >= 3.25) {
            $skor = rata(4);
        } else if ($rata_ipk >= 2.00 && $rata_ipk < 3.25) {
            $skor = rata(((8 * $rata_ipk) - 6) / 5);
        } else {
            $skor = "Tidak ada skor di bawah 2";
        }

        //Return data
        return compact(['lulusan', 'rata_ipk', 'skor']);
    }

    public function hitung_prestasi_akademik_mahasiswa($data)
    {
        //Data
        $jumlah = $data['jumlah'];

        //Faktor
        $faktor = array(
            'a' => 0.1,
            'b' => 1,
            'c' => 2
        );

        //Hitung Rata-Rata
        $rata = array(
            'inter'    => $jumlah['inter'] / $jumlah['mahasiswa'],
            'nasional' => $jumlah['nasional'] / $jumlah['mahasiswa'],
            'lokal'    => $jumlah['lokal'] / $jumlah['mahasiswa'],
        );

        //Skor
        if ($rata['inter'] >= $faktor['a']) {
            $skor = 4;
            $rumus = "4";
        } else if ($rata['inter'] < $faktor['a'] && $rata['nasional'] >= $faktor['b']) {
            $skor = 3 + ($rata['inter'] / $faktor['a']);
            $rumus = "3 + (RI / a)";
        } else if (($rata['inter'] > 0 && $rata['inter'] < $faktor['a']) && ($rata['nasional'] > 0 && $rata['nasional'] < $faktor['b'])) {
            $skor = 2 + (2 * ($rata['inter'] / $faktor['a'])) + ($rata['nasional'] / $faktor['b']) - (($rata['inter'] * $rata['nasional']) / ($faktor['a'] * $faktor['b']));
            $rumus = "2 + (2 * (RI / a)) + (RN / b) - ((RI * RN) / (a * b))";
        } else if ($rata['inter'] == 0 && $rata['nasional'] == 0 && $rata['lokal'] >= $faktor['c']) {
            $skor = 2;
            $rumus = "2";
        } else if ($rata['inter'] == 0 && $rata['nasional'] == 0 && $rata['lokal'] < $faktor['c']) {
            $skor = (2 * $rata['lokal']) / $faktor['c'];
            $rumus = "( 2 * RW) / c";
        } else {
            $skor = 0;
            $rumus = "Tidak ada skor di bawah 1";
        }

        //Return data
        return compact(['jumlah', 'faktor', 'rata', 'skor', 'rumus']);
    }

    public function hitung_prestasi_nonakademik_mahasiswa($data)
    {
        //Data
        $jumlah = $data['jumlah'];

        //Faktor
        $faktor = array(
            'a' => 0.2,
            'b' => 2,
            'c' => 4
        );

        //Hitung Rata-Rata
        $rata = array(
            'inter'    => $jumlah['inter'] / $jumlah['mahasiswa'],
            'nasional' => $jumlah['nasional'] / $jumlah['mahasiswa'],
            'lokal'    => $jumlah['lokal'] / $jumlah['mahasiswa'],
        );

        //Skor
        if ($rata['inter'] >= $faktor['a']) {
            $skor = 4;
            $rumus = "4";
        } else if ($rata['inter'] < $faktor['a'] && $rata['nasional'] >= $faktor['b']) {
            $skor = 3 + ($rata['inter'] / $faktor['a']);
            $rumus = "3 + (RI / a)";
        } else if (($rata['inter'] > 0 && $rata['inter'] < $faktor['a']) && ($rata['nasional'] > 0 && $rata['nasional'] < $faktor['b'])) {
            $skor = 2 + (2 * ($rata['inter'] / $faktor['a'])) + ($rata['nasional'] / $faktor['b']) - (($rata['inter'] * $rata['nasional']) / ($faktor['a'] * $faktor['b']));
            $rumus = "2 + (2 * (RI / a)) + (RN / b) - ((RI * RN) / (a * b))";
        } else if ($rata['inter'] == 0 && $rata['nasional'] == 0 && $rata['lokal'] >= $faktor['c']) {
            $skor = 2;
            $rumus = "2";
        } else if ($rata['inter'] == 0 && $rata['nasional'] == 0 && $rata['lokal'] < $faktor['c']) {
            $skor = (2 * $rata['lokal']) / $faktor['c'];
            $rumus = "( 2 * RW) / c";
        } else {
            $skor = 0;
            $rumus = "Tidak ada skor di bawah 1";
        }

        //Return data
        return compact(['jumlah', 'faktor', 'rata', 'skor', 'rumus']);
    }

    public function hitung_masa_studi_lulusan($data)
    {
        //Ambil data
        $masa_studi  = $data['rata_masa_studi'];

        //Hitung skor
        if ($masa_studi > 3.5 && $masa_studi < 4.5) {
            $skor  = rata(4);
            $rumus = "4";
        } else if ($masa_studi > 3 && $masa_studi <= 3.5) {
            $skor = rata(((8 * $masa_studi) - 24));
            $rumus = "( 8 * ms ) - 24";
        } else if ($masa_studi > 4.5 && $masa_studi <= 7) {
            $skor = rata((56 - (8 * $masa_studi)) / 5);
            $rumus = "( 56 -( 8 * ms ) ) / 5";
        } else {
            $skor = 0;
            $rumus = "0";
        }

        //Return data
        return compact(['masa_studi', 'skor', 'rumus']);
    }

    public function hitung_tempat_kerja_lulusan($data)
    {
        //Data
        $jumlah  = $data['jumlah'];

        //Nilai faktor
        $faktor = array(
            'a' => 5,
            'b' => 20,
            'c' => 90
        );

        //Hitung rata
        $rata = array(
            'inter'    => ($jumlah['inter'] / $jumlah['lulusan']) * 100,
            'nasional' => ($jumlah['nasional'] / $jumlah['lulusan']) * 100,
            'lokal'    => ($jumlah['lokal'] / $jumlah['lulusan']) * 100,
        );

        //Hitung skor
        if ($rata['inter'] >= $faktor['a']) {
            $skor = 4;
            $rumus = "4";
        } else if ($rata['inter'] < $faktor['a'] && $rata['nasional'] >= $faktor['b']) {
            $skor = 3 + ($rata['inter'] / $faktor['a']);
            $rumus = "3 + (RI / a)";
        } else if (($rata['inter'] > 0 && $rata['inter'] < $faktor['a']) || ($rata['nasional'] > 0 && $rata['nasional'] < $faktor['b'])) {
            $skor = 2 + (2 * ($rata['inter'] / $faktor['a'])) + ($rata['nasional'] / $faktor['b']) - (($rata['inter'] * $rata['nasional']) / ($faktor['a'] * $faktor['b']));
            $rumus = "2 + (2 * (RI / a)) + (RN / b) - ((RI * RN) / (a * b))";
        } else if ($rata['inter'] == 0 && $rata['nasional'] == 0 && $rata['lokal'] >= $faktor['c']) {
            $skor = 2;
            $rumus = "2";
        } else if ($rata['inter'] == 0 && $rata['nasional'] == 0 && $rata['lokal'] < $faktor['c']) {
            $skor = (2 * $rata['lokal']) / $faktor['c'];
            $rumus = "(2 * RL) / c";
        } else {
            $skor  = 0;
            $rumus = null;
        }

        //Return data
        return compact(['jumlah', 'faktor', 'rata', 'skor', 'rumus']);
    }
}
