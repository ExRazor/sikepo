<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentQuotaRequest;
use App\Models\StudentQuota;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\AcademicYear;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class StudentQuotaController extends Controller
{
    use LogActivity;

    public function index()
    {
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::where('semester','Ganjil')->orderBy('tahun_akademik','desc')->get();

        return view('student.quota.index',compact(['faculty','studyProgram','academicYear']));
    }

    public function show($id)
    {
        if(request()->ajax()) {
            // $id = decrypt($id);
            $data = StudentQuota::find($id);
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function store(StudentQuotaRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            //Query
            $data                   = new StudentQuota;
            $data->kd_prodi         = $request->kd_prodi;
            $data->id_ta            = $request->id_ta;
            $data->daya_tampung     = $request->daya_tampung;
            $data->calon_pendaftar  = $request->calon_pendaftar;
            $data->calon_lulus      = $request->calon_lulus;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->studyProgram->nama.' ~ '.$data->academicYear->tahun_akademik,
            ];
            $this->log('created','Kuota Mahasiswa',$property);

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

    public function update(StudentQuotaRequest $request)
    {
        if(!request()->ajax()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $id = $request->_id;

            $data                   = StudentQuota::find($id);
            $data->kd_prodi         = $request->kd_prodi;
            $data->id_ta            = $request->id_ta;
            $data->daya_tampung     = $request->daya_tampung;
            $data->calon_pendaftar  = $request->calon_pendaftar;
            $data->calon_lulus      = $request->calon_lulus;
            $data->save();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->studyProgram->nama.' ~ '.$data->academicYear->tahun_akademik,
            ];
            $this->log('updated','Kuota Mahasiswa',$property);

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
            //Decrypt ID
            $id = decrypt($request->id);

            //Query
            $data  = StudentQuota::find($id);
            $data->delete();

            //Activity Log
            $property = [
                'id'    => $data->id,
                'name'  => $data->studyProgram->nama.' ~ '.$data->academicYear->tahun_akademik,
            ];
            $this->log('deleted','Kuota Mahasiswa',$property);

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
            $data        = StudentQuota::whereHas('studyProgram', function($query){
                                $query->where('kd_prodi',Auth::user()->kd_prodi);
                            });
        } else {
            $data        = StudentQuota::whereHas('studyProgram', function($query){
                                $query->where('kd_jurusan',setting('app_department_id'));
                            });
        }

        if($request->prodi) {
            $data     = StudentQuota::whereHas('studyProgram', function($query) use($request){
                $query->where('kd_prodi',$request->prodi);
            });
        }

        return DataTables::of($data->get())
                            ->addColumn('prodi', function($d) {
                                if(!Auth::user()->hasRole('kaprodi')) {
                                    return $d->studyProgram->nama;
                                }
                            })
                            ->addColumn('tahun', function($d) {
                                return $d->academicYear->tahun_akademik;
                            })
                            ->addColumn('aksi', function($d) {
                                if(!Auth::user()->hasRole('kajur')) {
                                    return view('student.quota.table-button', compact('d'))->render();
                                }
                            })
                            ->rawColumns(['aksi'])
                            ->make();
    }
}
