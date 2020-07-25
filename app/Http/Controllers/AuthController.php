<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class AuthController extends Controller
{
    public function login_form()
    {
        return view('auth.login');
    }

    public function login_post(Request $request)
    {
        // Validasi
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $auth = $request->only('username', 'password');

        $credential = [
            'username' => $request->username,
            'password' => $request->password,
            'is_active'=> true
        ];

        //Proses Login
        if (Auth::attempt($credential)) {
            //Redirek jika berhasil
            return redirect()->intended(route('dashboard'));
        }

        //Tampilkan pesan error jika gagal
        return redirect()->back()->with(['error' => 'Username / Kata Sandi Salah']);
    }

    public function logout()
    {
        if(Auth::check()) {
            Auth::logout();
        }

        return redirect(route('login'));
    }


    public function editpassword_form()
    {
        return view('auth.editpassword');
    }

    public function editpassword_post(Request $request)
    {
        // Validasi
        $this->validate($request, [
            'password_lama' => 'required',
            'password_baru' => 'required|string|min:6|confirmed',
        ]);

        if (!(Hash::check($request->get('password_lama'), Auth::user()->password))) {
            // Jika password lama cocok
            return redirect()->back()->with("error","Kata sandi lama Anda tidak sesuai. Mohon periksa kembali.");
        }

        if(strcmp($request->get('password_lama'), $request->get('password_baru')) == 0){
            //Jika password lama sama dengan password baru
            return redirect()->back()->with("error","Kata sandi baru Anda sama dengan kata sandi lama. Mohon diganti dengan yang lain.");
        }

        //Change Password
        $user = Auth::user();
        $user->password = Hash::make($request->get('password_baru'));
        $user->save();

        return redirect()->back()->with("success","Kata sandi berhasil diganti!");
    }

    public function editprofile_form()
    {
        if(Auth::user()->hasRole('dosen')) {
            return abort(404);
        }
        $data = Auth::user();

        switch($data->role) {
            case 'admin':
                $data->badge = 'primary';
            break;
            case 'kajur':
                $data->badge = 'success';
            break;
            case 'kaprodi':
                $data->badge = 'danger';
            break;
            default:
                $data->badge = 'secondary';
            break;
        }

        return view('auth.editprofile',compact(['data']));
    }

    public function editprofile_post(Request $request)
    {
        $user       = Auth::user();

        //Validasi
        $this->validate($request, [
            'name'  => 'required',
            'foto'  => 'dimensions:min_width=500,min_height=500'
        ]);

        //Edit Foto
        $storagePath = public_path('upload/user/'.$request->foto);
        if($file = $request->file('foto')) {
            if(File::exists($storagePath)) {
                File::delete($storagePath);
            }

            $tujuan_upload = public_path('upload/user');
            $filename = str_replace(' ', '', Auth::user()->username).'.'.$file->getClientOriginalExtension();
            $file->move($tujuan_upload,$filename);
            $user->foto = $filename;
        }

        //Edit Nama
        $user->name = $request->name;
        $user->save();

        return redirect()->back()->with("success","Profil berhasil diubah!");
    }

}
