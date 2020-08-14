<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlumnusIdleRequest;
use App\Models\AlumnusIdle;
use App\Models\AcademicYear;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogActivity;
use Illuminate\Support\Facades\DB;

class AlumnusIdleController extends Controller
{
    use LogActivity;

    public function __construct()
    {
        $method = [
            'edit',
            'store',
            'update',
            'destroy',
        ];

        $this->middleware('role:admin,kaprodi', ['only' => $method]);
    }

    public function index()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        if(Auth::user()->hasRole('kaprodi')) {
            return redirect()->route('alumnus.idle.show',encrypt(Auth::user()->kd_prodi));
        }

        return view('alumnus.idle.index',compact(['studyProgram']));
    }

    public function show($id)
    {
        $id = decrypt($id);

        $studyProgram = StudyProgram::find($id);
        $data         = AlumnusIdle::where('kd_prodi',$id)->orderBy('tahun_lulus','desc')->get();

        $ayExist = array();
        foreach($data as $d) {
            $ayExist[] = $d->tahun_lulus;
        }

        $tahun = AcademicYear::whereNotIn('tahun_akademik',$ayExist)
                             ->groupBy('tahun_akademik')
                             ->orderBy('tahun_akademik','desc')
                             ->get('tahun_akademik');

        return view('alumnus.idle.show',compact(['studyProgram','data','tahun']));
    }

    public function edit($id)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        $id = decrypt($id);
        $data = AlumnusIdle::find($id);

        return response()->json($data);
    }

    public function store(AlumnusIdleRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $kd_prodi = decrypt($request->kd_prodi);

            $data                   = new AlumnusIdle;
            $data->kd_prodi         = $kd_prodi;
            $data->tahun_lulus      = $request->tahun_lulus;
            $data->jumlah_lulusan   = $request->jumlah_lulusan;
            $data->lulusan_terlacak = $request->lulusan_terlacak;
            $data->kriteria_1       = $request->kriteria_1;
            $data->kriteria_2       = $request->kriteria_2;
            $data->kriteria_3       = $request->kriteria_3;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->tahun_lulus.' - '.$data->studyProgram->nama,
                'url'   => route('alumnus.idle.show',encrypt($data->kd_prodi))
            ];
            $this->log('created','Waktu Tunggu Lulusan',$property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil disimpan',
                'type'    => 'success'
            ]);

        } catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ],400);
        }
    }

    public function update(AlumnusIdleRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $id = decrypt($request->id);

            $data                   = AlumnusIdle::find($id);
            $data->jumlah_lulusan   = $request->jumlah_lulusan;
            $data->lulusan_terlacak = $request->lulusan_terlacak;
            $data->kriteria_1       = $request->kriteria_1;
            $data->kriteria_2       = $request->kriteria_2;
            $data->kriteria_3       = $request->kriteria_3;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $id,
                'name'  => $data->tahun_lulus.' - '.$data->studyProgram->nama,
                'url'   => route('alumnus.idle.show',encrypt($data->kd_prodi))
            ];
            $this->log('updated','Waktu Tunggu Lulusan',$property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil disimpan',
                'type'    => 'success'
            ]);

        } catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ],400);
        }
    }

    public function destroy(Request $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $id = decrypt($request->id);

            $data = AlumnusIdle::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $id,
                'name'  => $data->tahun_lulus.' - '.$data->studyProgram->nama,
            ];
            $this->log('deleted','Waktu Tunggu Lulusan',$property);

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
}
