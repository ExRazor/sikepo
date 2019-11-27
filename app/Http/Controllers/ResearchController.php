<?php

namespace App\Http\Controllers;

use App\Research;
use App\StudyProgram;
use App\Faculty;
use App\Teacher;
use App\ResearchStudent;
use App\ResearchTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ResearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faculty = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        $penelitian   = Research::whereHas(
                                        'researchTeacher', function($q) {
                                            $q->jurusanKetua(setting('app_department_id'));
                                        }
                                    )
                                    ->get();

        return view('research.index',compact(['penelitian','studyProgram','faculty']));
    }

    public function show($id)
    {
        $id   = decode_id($id);
        $data         = Research::where('id',$id)->first();

        return view('research.show',compact(['data']));
    }

    public function create()
    {
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        return view('research.form',compact(['studyProgram','faculty']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_ta'             => 'required',
            'ketua_nidn'        => 'required',
            'judul_penelitian'  => 'required',
            'tema_penelitian'   => 'required',
            'sks_penelitian'    => 'required|numeric',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
            'jumlah_biaya'      => 'required',
        ]);

        //Simpan Data Penelitian
        $research                    = new Research;
        $research->id_ta             = $request->id_ta;
        $research->judul_penelitian  = $request->judul_penelitian;
        $research->tema_penelitian   = $request->tema_penelitian;
        $research->sks_penelitian    = $request->sks_penelitian;
        $research->sumber_biaya      = $request->sumber_biaya;
        $research->sumber_biaya_nama = $request->sumber_biaya_nama;
        $research->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $research->save();

        //Jumlah SKS
        $sks_ketua      = floatval($request->sks_penelitian)*setting('research_ratio_chief')/100;
        $sks_anggota    = floatval($request->sks_penelitian)*setting('research_ratio_members')/100;

        //Tambah Ketua
        $ketua                  = new ResearchTeacher;
        $ketua->id_penelitian   = $research->id;
        $ketua->nidn            = $request->ketua_nidn;
        $ketua->status          = 'Ketua';
        $ketua->sks             = $sks_ketua;
        $ketua->save();

        //Tambah Anggota Dosen
        $hitungDsn = count($request->anggota_nidn);
        for($i=0;$i<$hitungDsn;$i++) {
            ResearchTeacher::updateOrCreate(
                [
                    'id_penelitian' => $research->id,
                    'nidn'          => $request->anggota_nidn[$i],
                ],
                [
                    'status'     => 'Anggota',
                    'sks'        => $sks_anggota,
                ]
            );
        }

        //Tambah Mahasiswa
        $hitungMhs = count($request->mahasiswa_nim);
        for($i=0;$i<$hitungMhs;$i++) {
            ResearchStudent::updateOrCreate(
                [
                    'id_penelitian' => $research->id,
                    'nim'           => $request->mahasiswa_nim[$i],
                ]
            );
        }

        return redirect()->route('research')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function edit($id)
    {
        $id   = decode_id($id);

        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $data         = Research::where('id',$id)->first();

        return view('research.form',compact(['data','studyProgram']));
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        // dd($request->all());

        $request->validate([
            'id_ta'             => 'required',
            // 'ketua_nidn'        => 'required|unique:research_teachers,nidn,'.$id.',id_penelitian',
            'judul_penelitian'  => 'required',
            'tema_penelitian'   => 'required',
            'sks_penelitian'    => 'required|numeric',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
            'jumlah_biaya'      => 'required',
        ]);

        //Simpan Data Penelitian
        $research                    = Research::find($id);
        $research->id_ta             = $request->id_ta;
        $research->judul_penelitian  = $request->judul_penelitian;
        $research->tema_penelitian   = $request->tema_penelitian;
        $research->sks_penelitian    = $request->sks_penelitian;
        $research->sumber_biaya      = $request->sumber_biaya;
        $research->sumber_biaya_nama = $request->sumber_biaya_nama;
        $research->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $research->save();

        //Jumlah SKS
        $sks_ketua      = floatval($request->sks_penelitian)*setting('research_ratio_chief')/100;
        $sks_anggota    = floatval($request->sks_penelitian)*setting('research_ratio_members')/100;

        //Update Ketua
        $ketua = ResearchTeacher::where('id_penelitian',$id)->where('status','Ketua');
        if($ketua != $request->ketua_nidn) {
            $ketua->delete();

            $new_ketua                  = new ResearchTeacher;
            $new_ketua->id_penelitian   = $id;
            $new_ketua->nidn            = $request->ketua_nidn;
            $new_ketua->status          = 'Ketua';
            $new_ketua->sks             = $sks_ketua;
            $new_ketua->save();
        } else {

            $ketua->id_penelitian = $id;
            $ketua->nidn          = $request->ketua_nidn;
            $ketua->sks           = $sks_ketua;
            $ketua->save();
        }

        //Update Anggota
        $hitungDsn = count($request->anggota_nidn);
        for($i=0;$i<$hitungDsn;$i++) {

            ResearchTeacher::updateOrCreate(
                [
                    'id_penelitian' => $id,
                    'nidn'          => $request->anggota_nidn[$i],
                ],
                [
                    'status'     => 'Anggota',
                    'sks'        => $sks_anggota,
                ]
            );
        }

        //Update Mahasiswa
        $hitungMhs = count($request->mahasiswa_nim);
        for($i=0;$i<$hitungMhs;$i++) {

            ResearchStudent::updateOrCreate(
                [
                    'id_penelitian' => $id,
                    'nim'           => $request->mahasiswa_nim[$i],
                ]
            );
        }

        return redirect()->route('research')->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decode_id($request->id);
            $q  = Research::find($id)->delete();
            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil dihapus',
                    'type'    => 'success'
                ]);
            }
        }
    }

    public function destroy_teacher(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = ResearchTeacher::find($id)->delete();
            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil dihapus',
                    'type'    => 'success'
                ]);
            }
        }
    }

    public function destroy_students(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = ResearchStudent::find($id)->delete();
            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil dihapus',
                    'type'    => 'success'
                ]);
            }
        }
    }

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {

            $q   = Research::with([
                                    'academicYear',
                                    'researchKetua.teacher.studyProgram',
                                    'researchAnggota.teacher.studyProgram.department',
                                    'researchStudent.student.studyProgram.department'
                                ]);

            if($request->kd_jurusan){
                $q->whereHas(
                    'researchTeacher', function($q) use($request) {
                        $q->jurusanKetua($request->kd_jurusan);
                    }
                );
            }

            if($request->kd_prodi){
                $q->whereHas(
                    'researchTeacher', function($q) use ($request) {
                        $q->prodiKetua($request->kd_prodi);
                    }
                );
            }

            $data = $q->orderBy('id_ta','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function get_by_department(Request $request)
    {
        if($request->has('cari')){
            $cari = $request->cari;
            $data = Research::whereHas(
                                'researchTeacher', function($q) {
                                    $q->jurusanKetua(setting('app_department_id'));
                                }
                            )
                            ->where('judul_penelitian', 'LIKE', '%'.$cari.'%')
                            ->orWhere('id','LIKE','%'.$cari.'%')
                            ->get();

            $response = array();
            foreach($data as $d){
                $response[] = array(
                    "id"    => $d->id,
                    "text"  => $d->judul_penelitian.' ('.$d->academicYear->tahun_akademik.')'
                );
            }
            return response()->json($response);
        }
    }
}
