<?php

namespace App\Http\Controllers;

use App\StudyProgram;
use App\User;
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
        $user = User::orderBy('created_at','asc')->get();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        foreach($user as $u) {
            switch($u->role) {
                case 'Admin':
                    $u->badge = 'primary';
                break;
                case 'Kajur':
                    $u->badge = 'success';
                break;
                case 'Kaprodi':
                    $u->badge = 'danger';
                break;
                default:
                    $u->badge = 'secondary';
                break;
            }
        }

        return view('setting.user.index',compact(['user','studyProgram']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateProdi = $request->has('kd_prodi') ? 'required' : 'nullable';

        $request->validate([
            'name'                  => 'required|min:5',
            'username'              => 'required|min:5|alpha_dash',
            'password'              => 'required|min:8',
            'role'                  => 'required',
            'kd_prodi'              => $validateProdi,
        ]);

        $data            = new User;
        $data->name      = $request->name;
        $data->username  = $request->username;
        $data->password  = Hash::make($request->password);
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

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
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
}
