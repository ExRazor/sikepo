<?php

namespace App\Http\Controllers;

use App\Faculty;
use App\OutputActivity;
use App\OutputActivityCategory;
use App\StudyProgram;
use Illuminate\Http\Request;

class OutputActivityController extends Controller
{
    public function index()
    {
        $studyProgram   = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $outputActivity = OutputActivity::all();
        $category       = OutputActivityCategory::all();

        return view('output-activity.index',compact(['outputActivity','category','studyProgram']));
    }

    public function show($id)
    {
        $id         = decode_id($id);
        $data       = OutputActivity::where('id',$id)->first();
        $category   = OutputActivityCategory::all();

        return view('output-activity.show',compact(['data','category']));
    }

    public function create()
    {
        $category   = OutputActivityCategory::all();

        return view('output-activity.form',compact(['category']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori'   => 'required',
            'kegiatan'      => 'required',
            'judul_luaran'  => 'required',
            'tahun_luaran'  => 'required|numeric|digits:4',
            'keterangan'    => 'nullable',
        ]);

        //Simpan Data Penelitian
        $data                   = new OutputActivity;
        $data->id_kategori      = $request->id_kategori;
        $data->id_penelitian    = $request->has('id_penelitian') ? $request->id_penelitian : null;
        $data->id_pengabdian    = $request->has('id_pengabdian') ? $request->id_pengabdian : null;
        $data->judul_luaran     = $request->judul_luaran;
        $data->tahun_luaran     = $request->tahun_luaran;
        $data->kegiatan         = $request->kegiatan;
        $data->keterangan       = $request->keterangan;
        $data->save();

        return redirect()->route('output-activity')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');

    }

    public function edit($id)
    {
        $id = decode_id($id);

        $category   = OutputActivityCategory::all();
        $data       = OutputActivity::find($id);
        return view('output-activity.form',compact(['category','data']));
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $request->validate([
            'id_kategori'   => 'required',
            'kegiatan'      => 'required',
            'judul_luaran'  => 'required',
            'tahun_luaran'  => 'required|numeric|digits:4',
            'keterangan'    => 'nullable',
        ]);

        //Simpan Data Penelitian
        $data                   = OutputActivity::find($id);
        $data->id_kategori      = $request->id_kategori;
        $data->id_penelitian    = $request->has('id_penelitian') ? $request->id_penelitian : null;
        $data->id_pengabdian    = $request->has('id_pengabdian') ? $request->id_pengabdian : null;
        $data->judul_luaran     = $request->judul_luaran;
        $data->tahun_luaran     = $request->tahun_luaran;
        $data->kegiatan         = $request->kegiatan;
        $data->keterangan       = $request->keterangan;
        $data->save();

        return redirect()->route('output-activity')->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decode_id($request->id);
            $q  = OutputActivity::find($id)->delete();
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
