<?php

namespace App\Http\Controllers;

use App\Models\StudentQuota;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StudentQuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faculty      = Faculty::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::where('semester','Ganjil')->orderBy('tahun_akademik','desc')->get();

        return view('student.quota.index',compact(['faculty','studyProgram','academicYear']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(request()->ajax()) {
            $request->validate([
                'kd_prodi'          => [
                    'required',
                    Rule::unique('student_quotas')->where(function ($query) use($request) {
                        return $query->where('id_ta', $request->id_ta);
                    }),
                ],
                'id_ta'             => [
                    'required',
                    Rule::unique('student_quotas')->where(function ($query) use($request) {
                        return $query->where('kd_prodi', $request->kd_prodi);
                    }),
                ],
                'daya_tampung'      => 'required|numeric',
                'calon_pendaftar'   => 'numeric|nullable',
                'calon_lulus'       => 'numeric|nullable',
            ]);

            $data                   = new StudentQuota;
            $data->kd_prodi         = $request->kd_prodi;
            $data->id_ta            = $request->id_ta;
            $data->daya_tampung     = $request->daya_tampung;
            $data->calon_pendaftar  = $request->calon_pendaftar;
            $data->calon_lulus      = $request->calon_lulus;
            $q = $data->save();

            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil disimpan',
                    'type'    => 'success'
                ]);
            }
        }
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

    public function update(Request $request)
    {
        if(request()->ajax()) {
            // $id = decrypt($request->_id);
            $id = $request->_id;

            $request->validate([
                'kd_prodi'          => [
                    'required',
                    Rule::unique('student_quotas')->where(function ($query) use($request) {
                        return $query->where('id_ta', $request->id_ta);
                    })->ignore($id),
                ],
                'id_ta'             => [
                    'required',
                    Rule::unique('student_quotas')->where(function ($query) use($request) {
                        return $query->where('kd_prodi', $request->kd_prodi);
                    })->ignore($id),
                ],
                'daya_tampung'      => 'required|numeric',
                'calon_pendaftar'   => 'numeric|nullable',
                'calon_lulus'       => 'numeric|nullable',
            ]);

            $data                   = StudentQuota::find($id);
            $data->kd_prodi         = $request->kd_prodi;
            $data->id_ta            = $request->id_ta;
            $data->daya_tampung     = $request->daya_tampung;
            $data->calon_pendaftar  = $request->calon_pendaftar;
            $data->calon_lulus      = $request->calon_lulus;
            $q = $data->save();

            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil disimpan',
                    'type'    => 'success'
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StudentQuota  $studentQuota
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(request()->ajax()) {
            $id = decrypt($request->id);
            $q  = StudentQuota::destroy($id);
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
