<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportTridharmaRequest;
use App\Models\StudyProgram;
use App\Models\AcademicYear;
use App\Models\CommunityService;
use App\Models\Research;
use App\Models\Teacher;
use App\Models\TeacherPublication;
use App\Models\TeacherOutputActivity;
use App\Models\TeacherStatus;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use stdClass;
use PDF;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class ReportController extends Controller
{
    public function tridharma_index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $periodeTahun = AcademicYear::groupBy('tahun_akademik')->orderBy('tahun_akademik','desc')->select('tahun_akademik')->get();

        return view('report.tridharma.index',compact(['studyProgram','periodeTahun']));
    }

    public function tridharma_chart(Request $request)
    {
        //Validasi ajax
        if(!request()->ajax()) {
            abort(404);
        }

        //Query Guru
        $teacher      = Teacher::whereHas(
            'latestStatus.studyProgram', function($q) use($request){
                if($request->prodi) {
                    $q->where('kd_prodi',$request->prodi);
                } else {
                    $q->where('kd_jurusan',setting('app_department_id'));
                }
            }
        )

        ->orderBy('nama','asc')
        ->get();

        //Batas
        $batas = [$request->periode_awal,$request->periode_akhir];

        foreach($teacher as $t)
        {
            $penelitian[$t->nidn] = array(
                'nama'      => $t->nama,
                'jumlah'    => Research::with([
                                    'researchTeacher.teacher' => function($q1) use ($t) {
                                        $q1->where('nidn',$t->nidn)->orderBy('nama','asc');
                                    }
                                ])
                                ->whereHas(
                                    'researchTeacher.teacher', function($q1) use ($t) {
                                        $q1->where('nidn',$t->nidn);
                                    }
                                )
                                ->whereHas(
                                    'academicYear', function($q) use($batas) {
                                        $q->whereBetween('tahun_akademik',$batas);
                                    }
                                )
                                ->count(),
            );

            $pengabdian[$t->nidn]   = array(
                'nama'      => $t->nama,
                'jumlah'    => CommunityService::with([
                                    'serviceTeacher' => function($q1) use ($t) {
                                        $q1->where('nidn',$t->nidn);
                                    }
                                ])
                                ->whereHas(
                                    'serviceTeacher', function($q1) use ($t) {
                                        $q1->where('nidn',$t->nidn);
                                    }
                                )
                                ->whereHas(
                                    'academicYear', function($q) use($batas) {
                                        $q->whereBetween('tahun_akademik',$batas);
                                    }
                                )
                                ->count()
            );

            $publikasi[$t->nidn]    = array(
                'nama'      => $t->nama,
                'jumlah'    => TeacherPublication::whereHas(
                                    'teacher', function($q1) use ($t) {
                                        $q1->where('nidn',$t->nidn);
                                    }
                                )
                                ->whereHas(
                                    'academicYear', function($q) use($batas) {
                                        $q->whereBetween('tahun_akademik',$batas);
                                    }
                                )
                                ->count()
            );

            $luaran[$t->nidn]    = array(
                'nama'      => $t->nama,
                'jumlah'    => TeacherOutputActivity::whereHas(
                                    'teacher', function($q1) use ($t) {
                                        $q1->where('nidn',$t->nidn);
                                    }
                                )
                                ->whereHas(
                                    'academicYear', function($q) use($batas) {
                                        $q->whereBetween('tahun_akademik',$batas);
                                    }
                                )
                                ->count()
            );
        }

        $result = [
            'penelitian' => $penelitian,
            'pengabdian' => $pengabdian,
            'publikasi'  => $publikasi,
            'luaran'     => $luaran
        ];

        return response()->json($result);
    }

    public function tridharma_pdf(ReportTridharmaRequest $request)
    {
        /* Ambil kode prodi dari session jika aksesnya Kaprodi */
        if(Auth::user()->hasRole('kaprodi')) {
            $request->kd_prodi   = Auth::user()->kd_prodi;
        }

        /* DATA & TANDA TANGAN */
        $data           = $this->getTridharmaData($request);
        $ttd['kajur']   = get_structural($request->disahkan,'Kajur');
        $ttd['kaprodi'] = get_structural($request->disahkan,'Kaprodi',$request->kd_prodi);

        /* KETERANGAN */
        if($request->periode_awal == $request->periode_akhir || empty($request->periode_akhir)) {
            $keterangan['periode'] = $request->periode_awal;
        } else {
            $keterangan['periode'] = $request->periode_awal.' - '.$request->periode_akhir;
        }
        $keterangan['jenis']    = ucfirst($request->jenis);
        $keterangan['kelompok'] = ucfirst($request->tampil_kelompok);
        $keterangan['disahkan'] = Carbon::make($request->disahkan)->translatedFormat('d F Y');
        $keterangan['fakultas'] = setting('app_faculty_name');
        $keterangan['jurusan']  = setting('app_department_name');

        /* KETERANGAN - PRODI */
        if($request->tampil_tipe == 'prodi') {
            if($request->kd_prodi) {
                $query_prodi = StudyProgram::find($request->kd_prodi);
                $keterangan['prodi'] = $query_prodi->nama;
            } else {
                $keterangan['prodi'] = null;
            }
        } else {
            $teacher = Teacher::find($request->nidn);
            $keterangan['nama_dosen'] = $teacher->nama;
            $keterangan['nidn_dosen'] = $teacher->nidn;
            $keterangan['prodi']      = $teacher->latestStatus->studyProgram->nama;
        }

        /* FILTER TAMPILAN KOLOM */
        $tampil['tipe']     = $request->tampil_tipe;
        $tampil['ketua']    = $request->tampil_ketua ?? 0;
        $tampil['anggota']  = $request->tampil_anggota ?? 0;

        return view('report.pdf.tridharma_dsn',compact('data','ttd','keterangan','tampil'));

        /* RETURN PDF TAPI GAGAL + JELEK */
        // $html = PDF::loadView('report.pdf.penelitian',compact('data','ttd','keterangan'))->setPaper('A4', 'landscape');
        // return $html->stream('hehe.pdf');

        // $html = view('report.pdf.penelitian',compact('data','ttd','keterangan'))->render();
        // return response()->json($html);
    }

    private function getTridharmaData($request)
    {
        //Batas
        if(empty($request->periode_akhir)) {
            $batas = [$request->periode_awal,$request->periode_awal];
        } else {
            $batas = [$request->periode_awal,$request->periode_akhir];
        }

        //Query berdasarkan jenis
        switch($request->jenis) {
            case 'penelitian':
                //Mulai query dengan filter tahun
                $query = Research::whereHas(
                            'academicYear', function($q) use($batas) {
                                $q->whereBetween('tahun_akademik',$batas);
                            }
                        );

                //Filter penelitian kelompok atau tunggal
                if($request->tampil_kelompok == 'kelompok') {
                    $query->has('researchTeacher', '>', 1);
                } else if($request->tampil_kelompok == 'tunggal') {
                    $query->has('researchTeacher', '=', 1);
                }

                //Filter tampil sesuai prodi atau individu
                if($request->tampil_tipe == 'prodi') {
                    $query->whereHas(
                        'researchTeacher', function($q) use($request) {
                            if(!empty($request->kd_prodi) || $request->kd_prodi != null) {
                                $q->prodiKetua($request->kd_prodi);
                            } else {
                                $q->jurusanKetua(setting('app_department_id'));
                            }
                        }
                    );
                } else {
                    $query->whereHas(
                        'researchTeacher', function($q1) use ($request) {
                            $q1->where('nidn',$request->nidn);
                        }
                    );
                }

                //Tampung ke variabel data
                $data = $query->orderBy('id_ta','desc')->get();
            break;
            case 'pengabdian':
                //Mulai query dengan filter tahun
                $query = CommunityService::whereHas(
                            'academicYear', function($q) use($batas) {
                                $q->whereBetween('tahun_akademik',$batas);
                            }
                        );

                //Filter pengabdian kelompok atau tunggal
                if($request->tampil_kelompok == 'kelompok') {
                    $query->has('serviceTeacher', '>', 1);
                } else if($request->tampil_kelompok == 'tunggal') {
                    $query->has('serviceTeacher', '=', 1);
                }

                //Filter tampil sesuai prodi atau individu
                if($request->tampil_tipe == 'prodi') {
                    $query->whereHas(
                        'serviceTeacher', function($q) use($request) {
                            if(!empty($request->kd_prodi) || $request->kd_prodi != null) {
                                $q->prodiKetua($request->kd_prodi);
                            } else {
                                $q->jurusanKetua(setting('app_department_id'));
                            }
                        }
                    );
                } else {
                    $query->whereHas(
                        'serviceTeacher', function($q1) use ($request) {
                            $q1->where('nidn',$request->nidn);
                        }
                    );
                }

                //Tampung ke variabel data
                $data = $query->orderBy('id_ta','desc')->get();
            break;
            case 'publikasi':
                //Mulai query dengan filter tahun
                $query = TeacherPublication::whereHas(
                    'academicYear', function($q) use($batas) {
                        $q->whereBetween('tahun_akademik',$batas);
                    }
                );

                //Filter pengabdian kelompok atau tunggal
                if($request->tampil_kelompok == 'kelompok') {
                    $query->has('publicationMembers');
                }

                //Filter tampil sesuai prodi atau individu
                if($request->tampil_tipe == 'prodi') {
                    $query->whereHas(
                        'teacher.latestStatus.studyProgram', function($q) use($request) {
                            if(!empty($request->kd_prodi) || $request->kd_prodi != null) {
                                $q->where('kd_prodi',$request->kd_prodi);
                            } else {
                                $q->where('kd_jurusan',setting('app_department_id'));
                            }
                        }
                    );
                } else {
                    $query->where('nidn',$request->nidn);
                }

                //Tampung ke variabel data
                $data = $query->orderBy('id_ta','desc')->get();
            break;
            case 'luaran':
                $data   = TeacherOutputActivity::whereHas(
                                'teacher.latestStatus.studyProgram', function($q) use($request) {
                                    if(!empty($request->kd_prodi) || $request->kd_prodi != null) {
                                        $q->where('kd_prodi',$request->kd_prodi);
                                    } else {
                                        $q->where('kd_jurusan',setting('app_department_id'));
                                    }
                                }
                            )
                            ->whereHas(
                                'academicYear', function($q) use($batas) {
                                    $q->whereBetween('tahun_akademik',$batas);
                                }
                            )
                            ->get();
            break;
            default:
                $data = false;
        }

        return $data;
    }
}
