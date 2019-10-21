<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StudyProgram;
use Session;

class PageController extends Controller
{
    public function dashboard(Request $request)
    {
        $prodi = StudyProgram::all();
        return view('index',compact('prodi'));
        // dd($request->session());
    }

    public function set_prodi($kd_prodi)
    {
        $kd_prodi = decrypt($kd_prodi);
        Session::put('prodi_aktif', $kd_prodi);
        $prodi = StudyProgram::find($kd_prodi);

        return redirect()->route('dashboard')->with('flash.message', 'Selamat datang di panel admin Program Studi '.$prodi->nama.'!');
    }
}
