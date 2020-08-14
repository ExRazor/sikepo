<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlumnusWorkplaceRequest;
use App\Models\AlumnusWorkplace;
use App\Models\AcademicYear;
use App\Models\StudyProgram;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlumnusWorkplaceController extends Controller
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
            return redirect()->route('alumnus.workplace.show',encrypt(Auth::user()->kd_prodi));
        }

        return view('alumnus.workplace.index',compact(['studyProgram']));
    }

    public function show($id)
    {
        $id = decrypt($id);

        $studyProgram = StudyProgram::find($id);
        $data         = AlumnusWorkplace::where('kd_prodi',$id)->orderBy('tahun_lulus','desc')->get();

        $ayExist = array();
        foreach($data as $d) {
            $ayExist[] = $d->tahun_lulus;
        }

        $tahun = AcademicYear::whereNotIn('tahun_akademik',$ayExist)
                             ->groupBy('tahun_akademik')
                             ->orderBy('tahun_akademik','desc')
                             ->get('tahun_akademik');

        return view('alumnus.workplace.show',compact(['studyProgram','data','tahun']));
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $data = AlumnusWorkplace::find($id);

        if(request()->ajax()) {
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function store(AlumnusWorkplaceRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $kd_prodi = decrypt($request->kd_prodi);

            $data                       = new AlumnusWorkplace;
            $data->kd_prodi             = $kd_prodi;
            $data->tahun_lulus          = $request->tahun_lulus;
            $data->jumlah_lulusan       = $request->jumlah_lulusan;
            $data->lulusan_bekerja      = $request->lulusan_bekerja;
            $data->kerja_lokal          = $request->kerja_lokal;
            $data->kerja_nasional       = $request->kerja_nasional;
            $data->kerja_internasional  = $request->kerja_internasional;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->tahun_lulus.' - '.$data->studyProgram->nama,
                'url'   => route('alumnus.workplace.show',encrypt($data->kd_prodi))
            ];
            $this->log('created','Tempat Kerja Lulusan',$property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil disimpan',
                'type'    => 'success'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ],400);
        }
    }

    public function update(AlumnusWorkplaceRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $id = decrypt($request->id);

            $data                       = AlumnusWorkplace::find($id);
            $data->jumlah_lulusan       = $request->jumlah_lulusan;
            $data->lulusan_bekerja      = $request->lulusan_bekerja;
            $data->kerja_lokal          = $request->kerja_lokal;
            $data->kerja_nasional       = $request->kerja_nasional;
            $data->kerja_internasional  = $request->kerja_internasional;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $id,
                'name'  => $data->tahun_lulus.' - '.$data->studyProgram->nama,
                'url'   => route('alumnus.workplace.show',encrypt($data->kd_prodi))
            ];
            $this->log('updated','Tempat Kerja Lulusan',$property);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil disimpan',
                'type'    => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ],400);
        }
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $id    = decrypt($request->id);
            $data  = AlumnusWorkplace::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $id,
                'name'  => $data->tahun_lulus.' - '.$data->studyProgram->nama,
            ];
            $this->log('deleted','Tempat Kerja Lulusan',$property);

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
