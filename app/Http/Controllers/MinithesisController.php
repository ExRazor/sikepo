<?php

namespace App\Http\Controllers;

use App\Minithesis;
use App\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class MinithesisController extends Controller
{
    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        if(Auth::user()->hasRole('kaprodi')) {
            $minithesis = Minithesis::whereHas('student.studyProgram', function($q) {
                                            $q->where('kd_prodi',Auth::user()->kd_prodi);
                                        })
                                        ->get();
        } else {
            $minithesis = Minithesis::all();
        }

        return view('academic.minithesis.index',compact(['minithesis','studyProgram']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $minithesis = Minithesis::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('academic.minithesis.form',compact(['minithesis','studyProgram']));
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
            'nim'                   => 'required|unique:minitheses,nim',
            'judul'                 => 'required',
            'pembimbing_utama'      => 'required',
            'pembimbing_pendamping' => 'required',
            'id_ta'                 => 'required',
        ]);

        $query                          = new Minithesis;
        $query->nim                     = $request->nim;
        $query->judul                   = $request->judul;
        $query->pembimbing_utama        = $request->pembimbing_utama;
        $query->pembimbing_pendamping   = $request->pembimbing_pendamping;
        $query->id_ta                   = $request->id_ta;
        $query->save();

        return redirect()->route('academic.minithesis.index')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function edit($id)
    {
        // $id = decode_id($id);
        $data = Minithesis::find($id);

        return view('academic.minithesis.form',compact(['data']));
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $request->validate([
            'nim'                   => 'required|unique:minitheses,nim,'.$id,
            'judul'                 => 'required',
            'pembimbing_utama'      => 'required',
            'pembimbing_pendamping' => 'required',
            'id_ta'                 => 'required',
        ]);

        $query                          = Minithesis::find($id);
        $query->nim                     = $request->nim;
        $query->judul                   = $request->judul;
        $query->pembimbing_utama        = $request->pembimbing_utama;
        $query->pembimbing_pendamping   = $request->pembimbing_pendamping;
        $query->id_ta                   = $request->id_ta;
        $query->save();

        return redirect()->route('academic.minithesis.index')->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if(request()->ajax()) {
            $id = decode_id($request->id);
            $q = Minithesis::destroy($id);

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
        } else {
            return redirect()->route('academic.minithesis.index');
        }
    }

    public function datatable(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        if(Auth::user()->hasRole('kaprodi')) {
            $data = Minithesis::whereHas(
                'student.studyProgram', function($query) {
                    $query->where('kd_prodi',Auth::user()->kd_prodi);
                }
            );
        } else {
            $data = Minithesis::whereHas(
                'student.studyProgram', function($query) {
                    $query->where('kd_jurusan',setting('app_department_id'));
                }
            );
        }

        if($request->prodi_mahasiswa_filter) {
            $data->whereHas('student.studyProgram', function($q) use($request) {
                $q->where('kd_prodi',$request->prodi_mahasiswa_filter);
            });
        }

        if($request->prodi_pembimbing_filter) {
            $data->whereHas('teacher.studyProgram', function($q) use($request) {
                $q->where('kd_prodi',$request->prodi_pembimbing_filter);
            });
        }

        return DataTables::of($data->get())
                            ->addColumn('mahasiswa', function($d) {
                                return '<a name="'.$d->student->nama.'" href='.route('student.list.show',encode_id($d->student->nim)).'>'.
                                            $d->student->nama.
                                            '<br>
                                            <small>NIM. '.$d->student->nim.' / '.$d->student->studyProgram->singkatan.'</small>
                                        </a>';
                            })
                            ->addColumn('tahun', function($d) {
                                return $d->academicYear->tahun_akademik.' - '.$d->academicYear->semester;
                            })
                            ->addColumn('pembimbing_utama', function($d) {
                                return '<a name="'.$d->pembimbingUtama->nama.'" href='.route('teacher.list.show',$d->pembimbingUtama->nidn).'>'.
                                            $d->pembimbingUtama->nama.
                                            '<br>
                                            <small>NIDN. '.$d->pembimbingUtama->nidn.' / '.$d->pembimbingUtama->studyProgram->singkatan.'</small>
                                        </a>';
                            })
                            ->addColumn('pembimbing_pendamping', function($d) {
                                return '<a name="'.$d->pembimbingPendamping->nama.'" href='.route('teacher.list.show',$d->pembimbingPendamping->nidn).'>'.
                                            $d->pembimbingPendamping->nama.
                                            '<br>
                                            <small>NIDN. '.$d->pembimbingPendamping->nidn.' / '.$d->pembimbingPendamping->studyProgram->singkatan.'</small>
                                        </a>';
                            })
                            ->addColumn('aksi', function($d) {
                                if(!Auth::user()->hasRole('kajur')) {
                                    return view('academic.minithesis.table-button', compact('d'))->render();
                                }
                            })
                            ->rawColumns(['mahasiswa','pembimbing_utama','pembimbing_pendamping','aksi'])
                            ->make();
    }

    public function get_by_filter(Request $request)
    {
        if($request->ajax()) {

            $q   = Minithesis::with([
                                        'student.studyProgram',
                                        'pembimbingUtama',
                                        'pembimbingPendamping',
                                        'academicYear',
                                    ]);

            if($request->prodi_mahasiswa){
                $q->whereHas(
                    'student', function($query) use ($request) {
                        $query->where('kd_prodi',$request->prodi_mahasiswa);
                });
            }

            if($request->prodi_pembimbing){
                $q->whereHas(
                    'pembimbingUtama', function($query) use ($request) {
                        $query->where('kd_prodi',$request->prodi_pembimbing);
                });
            }

            $data = $q->orderBy('id_ta','desc')->get();

            return response()->json($data);
        } else {
            abort(404);
        }
    }
}
