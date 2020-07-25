<?php

namespace App\Http\Controllers;

use App\Models\StudyProgram;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user   = User::where('role','!=','Dosen')->orderBy('created_at','asc')->get();
        $dosen  = User::where('role','=','Dosen')
                        ->whereHas(
                            'teacher.latestStatus.studyProgram', function($q) {
                                $q->where('kd_jurusan',setting('app_department_id'));
                            }
                        )
                        ->orderBy('created_at','asc')->get();

        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        foreach($user as $u) {
            switch($u->role) {
                case 'admin':
                    $u->badge = 'primary';
                break;
                case 'kajur':
                    $u->badge = 'success';
                break;
                case 'kaprodi':
                    $u->badge = 'danger';
                break;
                default:
                    $u->badge = 'secondary';
                break;
            }
        }

        return view('setting.user.index',compact(['user','studyProgram','dosen']));
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $data = User::find($id);

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validateProdi = $request->has('kd_prodi') ? 'required' : 'nullable';

        $request->validate([
            'name'                  => 'required|min:5',
            'username'              => 'required|min:5|alpha_dash',
            'role'                  => 'required',
            'kd_prodi'              => $validateProdi,
        ]);

        $data            = new User;
        $data->name      = $request->name;
        $data->username  = $request->username;
        $data->password  = Hash::make($request->username);
        $data->role      = $request->role;
        $data->kd_prodi  = $request->kd_prodi;
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

    public function update(Request $request)
    {
        $id = decrypt($request->id);
        $validateProdi = $request->has('kd_prodi') ? 'required' : 'nullable';

        $request->validate([
            'name'                  => 'required|min:5',
            'username'              => 'required|min:5|alpha_dash',
            'role'                  => 'required',
            'kd_prodi'              => $validateProdi,
        ]);

        $data            = User::find($id);
        $data->name      = $request->name;
        $data->username  = $request->username;
        $data->role      = $request->role;
        $data->kd_prodi  = $request->kd_prodi;
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
                'message' => 'Data berhasil diubah',
                'type'    => 'success'
            ]);
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = User::destroy($id);
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

    public function reset_password(Request $request)
    {
        $id         = decrypt($request->id);
        $password   = generatePassword();

        $data              = User::find($id);
        $data->password    = Hash::make($password);
        $data->defaultPass = 0;
        $q = $data->save();

        if(!$q) {
            return response()->json([
                'title'   => 'Gagal',
                'message' => 'Mohon ulangi lagi kembali',
                'type'    => 'error'
            ]);
        } else {
            return response()->json([
                'title'     => 'Password baru Anda:',
                'password'  => $password,
                'type'      => 'success'
            ]);
        }
    }

    public function toggle_active($id)
    {
        try {
            $user = User::find($id);
            $user->is_active = !$user->is_active;
            $user->save();

            switch($user->is_active) {
                case true:
                    $pesan = 'diaktifkan';
                break;
                case false:
                    $pesan = 'dinonaktifkan';
                break;
            }

            return response()->json([
                'title'   => 'Berhasil',
                'message' => 'User berhasil '.$pesan,
                'type'    => 'success'
            ]);

        } catch(\Exception $e) {
            return false;
        }
    }

    public function datatable_user(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        $user   = User::where('role','!=','Dosen')->orderBy('created_at','asc')->get();

        foreach($user as $u) {
            switch($u->role) {
                case 'admin':
                    $u->badge = 'badge badge-primary tx-13';
                break;
                case 'kajur':
                    $u->badge = 'badge badge-success tx-13';
                break;
                case 'kaprodi':
                    $u->badge = 'badge badge-danger tx-13';
                break;
                default:
                    $u->badge = 'badge badge-secondary tx-13';
                break;
            }
        }
        return DataTables::of($user)
                            ->editColumn('role', function($d) {
                                $role = '<span class="'.$d->badge.'">'.ucfirst($d->role).(isset($d->kd_prodi) ? ' - '.$d->studyProgram->nama : '').'</span>';
                                return $role;
                            })
                            ->addColumn('status', function($d) {
                                $status =   '<div class="br-toggle br-toggle-success '.(($d->is_active) ? 'on' : '').'" onclick="setActive(this)" data-route='.route('ajax.user.toggle_active',$d->id).'>
                                                <div class="br-toggle-switch"></div>
                                            </div>';
                                return $status;
                            })
                            ->addColumn('aksi', function($d) {
                                return view('setting.user.btn_action_user', compact('d'))->render();
                            })
                            ->rawColumns(['role','status','aksi'])
                            ->make();
    }

    public function datatable_dosen(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        $data  = User::where('role','=','Dosen')
                        ->whereHas(
                            'teacher.latestStatus.studyProgram', function($q) {
                                $q->where('kd_jurusan',setting('app_department_id'));
                            }
                        )
                        ->orderBy('created_at','asc')->get();

        return DataTables::of($data)
                            ->addColumn('status', function($d) {
                                // $status = '<button class="btn btn-sm btn-success btn-active font-weight-bold" onclick="setActive(this)" data-route='.route('ajax.user.toggle_active',$d->id).'>Aktif</button>';
                                $status =   '<div class="br-toggle br-toggle-success '.(($d->is_active) ? 'on' : '').'" onclick="setActive(this)" data-route='.route('ajax.user.toggle_active',$d->id).'>
                                                <div class="br-toggle-switch"></div>
                                            </div>';

                                return $status;
                            })
                            ->addColumn('password', function($d) {
                                if($d->defaultPass) {
                                    $msg = 'Default';
                                } else {
                                    $msg = 'Diganti';
                                }
                                return $msg;
                            })
                            ->addColumn('aksi', function($d) {
                                return view('setting.user.btn_action_dosen', compact('d'))->render();
                            })
                            ->rawColumns(['status','aksi'])
                            ->make();
    }
}
