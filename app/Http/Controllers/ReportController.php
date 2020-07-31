<?php

namespace App\Http\Controllers;

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
use stdClass;
use PDF;

class ReportController extends Controller
{
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $periodeTahun = AcademicYear::groupBy('tahun_akademik')->orderBy('tahun_akademik','desc')->select('tahun_akademik')->get();

        return view('report.index',compact(['studyProgram','periodeTahun']));
    }

    public function chart(Request $request)
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

    public function pdf_tridharma()
    {
        $request = new stdClass;
        $request->jenis         = 'Penelitian';
        $request->periode_awal  = 2020;
        $request->periode_akhir = 2020;
        $request->kd_prodi      = null;
        $request->disahkan      = "2019-08-01";

        $data           = $this->getTridharmaData($request);
        $ttd['kajur']   = $this->getStructural($request,'Kajur');
        $ttd['kaprodi'] = $this->getStructural($request,'Kaprodi');

        if($request->periode_awal == $request->periode_akhir || empty($request->periode_akhir)) {
            $keterangan['periode'] = $request->periode_awal;
        } else {
            $keterangan['periode'] = $request->periode_awal.' - '.$request->periode_akhir;
        }

        $keterangan['jenis'] = $request->jenis;

        $keterangan['fakultas'] = setting('app_faculty_name');
        $keterangan['jurusan']  = setting('app_department_name');

        if($request->kd_prodi) {
            $query_prodi = StudyProgram::find($request->kd_prodi);
            $keterangan['prodi']    = $query_prodi->nama;
        } else {
            $keterangan['prodi'] = null;
        }

        $keterangan['disahkan'] = Carbon::make($request->disahkan)->translatedFormat('d F Y');

        // $html = PDF::loadView('report.pdf.penelitian',compact('data','ttd','keterangan'))->setPaper('A4', 'landscape');
        // return PDF::loadHTML($html)->stream('mypdf.pdf');
        // return $html->stream('hehe.pdf');

        return view('report.pdf.penelitian',compact('data','ttd','keterangan'));

    }

    private function getTridharmaData($request)
    {
        //Batas
        $batas = [$request->periode_awal,$request->periode_akhir];

        switch($request->jenis) {
            case 'Penelitian':
                $data = Research::whereHas(
                            'researchTeacher', function($q) use($request) {
                                if(!empty($request->kd_prodi) || $request->kd_prodi != null) {
                                    $q->prodiKetua($request->kd_prodi);
                                } else {
                                    $q->jurusanKetua(setting('app_department_id'));
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
            case 'Pengabdian':
                $data   = CommunityService::whereHas(
                                    'serviceTeacher', function($q) use($request) {
                                        if(!empty($request->kd_prodi) || $request->kd_prodi != null) {
                                            $q->prodiKetua($request->kd_prodi);
                                        } else {
                                            $q->jurusanKetua(setting('app_department_id'));
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
            case 'Publikasi':
                $data   = TeacherPublication::whereHas(
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
            case 'Luaran':
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

    private function getStructural($request,$jabatan)
    {
        $query = TeacherStatus::where('jabatan',$jabatan)->where('periode','<=',$request->disahkan)->first();

        $data = array(
            'nama'  => $query->teacher->nama,
            'nip'   => $query->teacher->nip
        );

        return $data;
    }
}
