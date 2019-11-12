<?php

namespace App\Http\Controllers;

use App\Publication;
use App\PublicationCategory;
use App\PublicationStudents;
use App\StudyProgram;
use App\Teacher;
use Illuminate\Http\Request;

class PublicationController extends Controller
{

    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $publikasi    = Publication::whereHas(
                            'teacher.studyProgram', function($query) {
                                $query->where('kd_jurusan',setting('app_department_id'));
                            }
                        )
                        ->with('publicationStudents.studyProgram.department.faculty','teacher.studyProgram')
                        ->get();

        return view('publication.index',compact(['publikasi','studyProgram']));
    }

    public function create()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $jenis        = PublicationCategory::all();

        return view('publication.form',compact(['studyProgram','jenis']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_publikasi' => 'required',
            'nidn'            => 'required',
            'judul'           => 'required',
            'penerbit'        => 'required',
            'tahun'           => 'required|numeric|digits:4',
            'jurnal'          => 'nullable',
            'sitasi'          => 'nullable|numeric',
            'akreditasi'      => 'nullable',
            'tautan'          => 'nullable',
        ]);

        $data                   = new Publication;
        $data->jenis_publikasi  = $request->jenis_publikasi;
        $data->nidn             = $request->nidn;
        $data->judul            = $request->judul;
        $data->penerbit         = $request->penerbit;
        $data->tahun            = $request->tahun;
        $data->jurnal           = $request->jurnal;
        $data->sitasi           = $request->sitasi;
        $data->sitasi           = $request->sitasi;
        $data->akreditasi       = $request->akreditasi;
        $data->tautan           = $request->tautan;
        $data->save();


        $hitungMhs = count($request->mahasiswa_nim);
        for($i=0;$i<$hitungMhs;$i++) {

            PublicationStudents::updateOrCreate(
                [
                    'id_publikasi'  => $data->id,
                    'nim'           => $request->mahasiswa_nim[$i],
                ],
                [
                    'nama'          => $request->mahasiswa_nama[$i],
                    'kd_prodi'      => $request->mahasiswa_prodi[$i],
                ]
            );
        }

        return redirect()->route('publication')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function edit($id)
    {
        $id   = decode_id($id);

        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $jenis        = PublicationCategory::all();
        $data         = Publication::with('teacher','publicationStudents')->where('id',$id)->first();
        $teacher      = Teacher::where('kd_prodi',$data->teacher->kd_prodi)->get();

        return view('publication.form',compact(['jenis','data','studyProgram','teacher']));
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $request->validate([
            'jenis_publikasi' => 'required',
            'nidn'            => 'required',
            'judul'           => 'required',
            'penerbit'        => 'required',
            'tahun'           => 'required|numeric|digits:4',
            'jurnal'          => 'nullable',
            'sitasi'          => 'nullable|numeric',
            'akreditasi'      => 'nullable',
            'tautan'          => 'nullable',
        ]);

        $data                   = Publication::find($id);
        $data->jenis_publikasi  = $request->jenis_publikasi;
        $data->nidn             = $request->nidn;
        $data->judul            = $request->judul;
        $data->penerbit         = $request->penerbit  ;
        $data->tahun            = $request->tahun;
        $data->jurnal           = $request->jurnal;
        $data->sitasi           = $request->sitasi;
        $data->sitasi           = $request->sitasi;
        $data->akreditasi       = $request->akreditasi;
        $data->tautan           = $request->tautan;
        $data->save();


        $hitungMhs = count($request->mahasiswa_nim);
        for($i=0;$i<$hitungMhs;$i++) {

            PublicationStudents::updateOrCreate(
                [
                    'id_publikasi'  => $id,
                    'nim'           => $request->mahasiswa_nim[$i],
                ],
                [
                    'nama'          => $request->mahasiswa_nama[$i],
                    'kd_prodi'      => $request->mahasiswa_prodi[$i],
                ]
            );
        }

        return redirect()->route('publication')->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decode_id($request->id);
            $q  = Publication::find($id)->delete();
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
            $q  = PublicationStudents::find($id)->delete();
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

            $q   = Publication::with(['teacher.studyProgram','publicationStudents.studyProgram.department','publicationCategory'])
                            ->whereHas(
                                'teacher.studyProgram.department', function($query) {
                                    $query->where('kd_jurusan',setting('app_department_id'));
                                }
                            );

            if($request->kd_prodi){
                $q->whereHas(
                    'teacher.studyProgram', function($query) use ($request) {
                        $query->where('kd_prodi',$request->kd_prodi);
                });
            }

            $data = $q->orderBy('tahun','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
