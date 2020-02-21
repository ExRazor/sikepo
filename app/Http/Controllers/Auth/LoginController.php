<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function form()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $auth = $request->only('username', 'password');

        //Proses Login
        if (Auth::attempt($auth)) {
            //Redirek jika berhasil
            return redirect()->intended(route('dashboard'));
        }

        //Tampilkan pesan error jika gagal
        return redirect()->back()->with(['error' => 'Username / Password Salah']);
    }

    public function logout()
    {
        if(Auth::check()) {
            Auth::logout();
        }

        return redirect(route('login'));

    }

}
