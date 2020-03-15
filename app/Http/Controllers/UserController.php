<?php

namespace App\Http\Controllers;

use App\StudyProgram;
use App\User;
use App\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
                            'teacher.studyProgram', function($q) {
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
}
