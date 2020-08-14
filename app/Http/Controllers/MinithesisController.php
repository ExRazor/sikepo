<?php

namespace App\Http\Controllers;

use App\Http\Requests\MinithesisRequest;
use App\Models\Minithesis;
use App\Models\StudyProgram;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MinithesisController extends Controller
{
    use LogActivity;

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

    public function create()
    {
        $minithesis = Minithesis::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('academic.minithesis.form',compact(['minithesis','studyProgram']));
    }

    public function edit($id)
    {
        // $id = decode_id($id);
        $data = Minithesis::find($id);

        return view('academic.minithesis.form',compact(['data']));
    }

    public function store(MinithesisRequest $request)
    {
        DB::beginTransaction();
        try {
            //Query
            $query                          = new Minithesis;
            $query->nim                     = $request->nim;
            $query->judul                   = $request->judul;
            $query->pembimbing_utama        = $request->pembimbing_utama;
            $query->pembimbing_pendamping   = $request->pembimbing_pendamping;
            $query->id_ta                   = $request->id_ta;
            $query->save();

            //Activity Log
            $property = [
                'id'    => $query->id,
                'name'  => $query->judul .' ~ '.$query->student->nama.' ('.$query->student->studyProgram->singkatan.')',
            ];
            $this->log('created','Tugas Akhir',$property);

            DB::commit();
            return redirect()->route('academic.minithesis.index')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }
    }

    public function update(MinithesisRequest $request)
    {
        DB::beginTransaction();
        try {
            //Decrypt ID
            $id = decrypt($request->id);

            //Query
            $query                          = Minithesis::find($id);
            $query->nim                     = $request->nim;
            $query->judul                   = $request->judul;
            $query->pembimbing_utama        = $request->pembimbing_utama;
            $query->pembimbing_pendamping   = $request->pembimbing_pendamping;
            $query->id_ta                   = $request->id_ta;
            $query->save();

            //Activity Log
            $property = [
                'id'    => $query->id,
                'name'  => $query->judul .' ~ '.$query->student->nama.' ('.$query->student->studyProgram->singkatan.')',
            ];
            $this->log('updated','Tugas Akhir',$property);

            DB::commit();
            return redirect()->route('academic.minithesis.index')->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }


    }

    public function destroy(Request $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $id = decode_id($request->id);
            $data = Minithesis::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->judul .' ~ '.$data->student->nama.' ('.$data->student->studyProgram->singkatan.')',
            ];
            $this->log('deleted','Tugas Akhir',$property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil dihapus',
                'type'    => 'success'
            ]);
        } catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ],400);
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
            $data->whereHas('pembimbingUtama.latestStatus.studyProgram', function($q) use($request) {
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
                                            <small>NIDN. '.$d->pembimbingUtama->nidn.' / '.$d->pembimbingUtama->latestStatus->studyProgram->singkatan.'</small>
                                        </a>';
                            })
                            ->addColumn('pembimbing_pendamping', function($d) {
                                return '<a name="'.$d->pembimbingPendamping->nama.'" href='.route('teacher.list.show',$d->pembimbingPendamping->nidn).'>'.
                                            $d->pembimbingPendamping->nama.
                                            '<br>
                                            <small>NIDN. '.$d->pembimbingPendamping->nidn.' / '.$d->pembimbingPendamping->latestStatus->studyProgram->singkatan.'</small>
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
