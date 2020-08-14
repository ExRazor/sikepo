<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudyProgramRequest;
use App\Models\StudyProgram;
use App\Models\Department;
use App\Models\Faculty;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudyProgramController extends Controller
{
    use LogActivity;

    public function index()
    {
        $data          = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $faculty       = Faculty::all();

        return view('master.study-program.index',compact(['data','faculty']));
    }

    public function create()
    {
        $department = Department::where('id_fakultas',request()->old('id_fakultas'))->get();
        $faculty = Faculty::all();

        return view('master.study-program.form',compact(['department','faculty']));
    }

    public function show(Request $request)
    {
        if(request()->ajax()) {
            $id   = decode_id($request->id);
            $data = StudyProgram::where('kd_prodi',$id)->with('department','department.faculty')->first();

            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function edit($id)
    {
        $id         = decode_id($id);
        $data       = StudyProgram::where('kd_prodi',$id)->with('department','department.faculty')->first();
        $department = Department::where('id_fakultas',$data->department->faculty->id)->get();
        $faculty    = Faculty::all();

        return view('master.study-program.form',compact('data','department','faculty'));
    }

    public function store(StudyProgramRequest $request)
    {
        DB::beginTransaction();
        try {
            //Query Program Studi
            $data = StudyProgram::create($request->all());

            //Activity Log
            $property = [
                'id'    => $data->kd_prodi,
                'name'  => $data->nama,
                'url'   => route('master.study-program')
            ];
            $this->log('created','Program Studi',$property);

            DB::commit();
            return redirect()->route('master.study-program')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash.message', $e->getMessage())->with('flash.class', 'danger')->withInput($request->input());
        }
    }

    public function update(StudyProgramRequest $request)
    {
        DB::beginTransaction();
        try {
            //Variabel ID
            $id = $request->kd_prodi;

            //Query
            $data                 = StudyProgram::find($id);
            $data->kd_jurusan     = $request->kd_jurusan;
            $data->kd_unik        = $request->kd_unik;
            $data->nama           = $request->nama;
            $data->jenjang        = $request->jenjang;
            $data->no_sk          = $request->no_sk;
            $data->tgl_sk         = $request->tgl_sk;
            $data->pejabat_sk     = $request->pejabat_sk;
            $data->thn_menerima   = $request->thn_menerima;
            $data->singkatan      = $request->singkatan;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->kd_prodi,
                'name'  => $data->nama,
                'url'   => route('master.study-program')
            ];
            $this->log('updated','Program Studi',$property);

            DB::commit();
            return redirect()->route('master.study-program')->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
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

        try {
            //Decrypt ID
            $id = decode_id($request->id);

            //Query
            $data = StudyProgram::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->kd_prodi,
                'name'  => $data->nama,
            ];
            $this->log('deleted','Program Studi',$property);

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

    public function get_by_department(Request $request)
    {
        if($request->ajax()) {

            $kd = $request->input('kd_jurusan');

            if($kd == 'all'){
                $data = StudyProgram::with('department.faculty')->where('kd_jurusan','!=',setting('app_department_id'))->orderBy('created_at','desc')->get();
            } elseif($kd == 0) {
                $data = '';
            } else {
                $data = StudyProgram::where('kd_jurusan',$kd)->with('department.faculty')->get();
            }
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function loadData(Request $request)
    {
        if($request->has('cari')){
            $cari = $request->cari;
            $data = StudyProgram::where('kd_prodi', 'LIKE', '%'.$cari.'%')->orWhere('nama', 'LIKE', '%'.$cari.'%')->get();

            $response = array();
            foreach($data as $d){
                $response[] = array(
                    "id"=>$d->kd_prodi,
                    "text"=>$d->nama
                );
            }
            return response()->json($response);
        }
    }
}
