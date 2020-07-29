<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherStatus;
use App\Http\Requests\TeacherStatusRequest;
use App\Models\Teacher;
use App\Models\User;
use App\Models\StudyProgram;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class TeacherStatusController extends Controller
{
    public function index_structural()
    {
        $studyProgram   = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        return view('setting.structural.index',compact(['studyProgram']));
    }

    public function store(TeacherStatusRequest $request)
    {
        $val = $request->validated();

        try {
            $data                = new TeacherStatus;
            $data->nidn          = $request->_nidn;
            $data->periode       = date("Y-m-d", strtotime($val['periode']) );
            $data->jabatan       = $val['jabatan'];
            $data->kd_prodi      = $val['kd_prodi'];
            $data->is_active     = false;
            $data->save();

            //Update status aktif jabatan
            $this->setStatus($request->_nidn);

            //Create User
            // $this->addUser($request);

            $response = [
                'title'   => 'Berhasil',
                'message' => 'Data berhasil disimpan',
                'type'    => 'success'
            ];

            return response()->json($response);

        } catch(\Exception $e) {
            return $response = [
                'title'   => 'Gagal',
                'message' => $e->getMessage(),
                'type'    => 'error'
            ];

            return response()->json($response);
        }
    }

    public function edit($id)
    {
        if(request()->ajax()) {
            $id = decrypt($id);
            $data = TeacherStatus::with('teacher')->find($id);
            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function update(TeacherStatusRequest $request)
    {
        $val = $request->validated();

        $id = decrypt($request->_id);

        try {
            $data            = TeacherStatus::findOrFail($id);
            $data->nidn      = $request->_nidn;
            $data->periode   = date("Y-m-d", strtotime($val['periode']) );
            $data->kd_prodi  = $val['kd_prodi'];
            $data->is_active = false;
            $data->save();

            //Update status aktif jabatan
            $this->setStatus($request->_nidn);

            $response = [
                'title'   => 'Berhasil',
                'message' => 'Data berhasil disunting',
                'type'    => 'success'
            ];

            return response()->json($response);

        } catch (\Exception $e) {
            $response = [
                'title'   => 'Gagal',
                'message' => $e->getMessage(),
                'type'    => 'error'
            ];

            return response()->json($response);
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);

            try {
                $data = TeacherStatus::find($id);
                $data->delete();

                // $this->deleteUser($data);

                //Update status aktif jabatan
                $this->setStatus($data->nidn);

            } catch(\Exception $e) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => $e->getMessage(),
                    'type'    => 'error'
                ]);
            }

            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'Data berhasil dihapus',
                'type'    => 'success'
            ]);
        }
    }

    private function setStatus($nidn)
    {
        $status_terbaru = TeacherStatus::where('nidn',$nidn)->latest('periode')->first()->id;

        TeacherStatus::where('nidn',$nidn)->where('is_active',true)->update(['is_active'=>false]);
        TeacherStatus::where('id',$status_terbaru)->update(['is_active'=>true]);
    }

    public function dt_structural(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        $type = $request->type;

        $data           = TeacherStatus::where('jabatan',$type)->orderBy('periode','asc')->get();
        $latestJabatan  = TeacherStatus::where('jabatan',$type)
                                        ->orderBy('periode','desc')
                                        ->first();

        return DataTables::of($data)
                            ->editColumn('nama', function($d) {
                                return '<a name="'.$d->teacher->nama.'" href="'.route("teacher.list.show",$d->teacher->nidn).'">'.
                                            $d->teacher->nama.
                                        '<br><small>NIDN. '.$d->teacher->nidn.'</small></a>';
                            })
                            ->editColumn('periode', function($d) use($latestJabatan){
                                if($d->id == $latestJabatan->id && $d->jabatan!='Kaprodi') {
                                    $status = '<span class="badge badge-success ml-1">Aktif</span>';
                                } else {
                                    $status = null;
                                }
                                return $d->periode.' '.$status;
                            })
                            ->editColumn('study_program', function($d){
                                return  $d->studyProgram->nama;
                            })
                            ->addColumn('aksi', function($d) {
                                return view('setting.structural.table-btn', compact('d'))->render();
                            })
                            ->rawColumns(['nama','periode','study_program','aksi'])
                            ->make();
    }

    private function addUser($request)
    {
        $val = $request->validated();

        try {
            $jabatan = 'Dosen';

            if($val['jabatan'] != 'Dosen') {
                $jabatan = $val['jabatan'];
            }

            User::updateOrCreate(
                [
                    'username' => $request->_nidn,
                    'role'     => $jabatan,
                ],
                [
                    'password'   => Hash::make($request->_nidn),
                    'kd_prodi'   => $val['kd_prodi'],
                    'name'       => Teacher::find($request->_nidn)->nama,
                    'foto'       => Teacher::find($request->_nidn)->foto ?? null,
                    'is_active'  => true,
                ]
            );

            return true;

        } catch(\Exception $e) {
            return false;
        }
    }

    private function deleteUser($data)
    {
        try {
            User::where('username',$data->nidn)->where('role',$data->jabatan)->delete();
            return true;

        } catch(\Exception $e) {
            return false;
        }
    }
}
