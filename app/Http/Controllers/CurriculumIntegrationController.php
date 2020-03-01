<?php

namespace App\Http\Controllers;

use App\CurriculumIntegration;
use Illuminate\Http\Request;

class CurriculumIntegrationController extends Controller
{
    public function __construct()
    {
        $method = [
            'create',
            'edit',
            'store',
            'update',
            'destroy',
        ];

        $this->middleware('role:admin,kaprodi', ['only' => $method]);
    }

    public function index()
    {
        $integration = CurriculumIntegration::all();

        return view('academic.integration.index',compact(['integration']));
    }

    public function create()
    {
        return view('academic.integration.form');
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $data       = CurriculumIntegration::find($id);

        return view('academic.integration.form',compact(['data']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_ta'             => 'required',
            'kegiatan'          => 'required',
            'nidn'              => 'required',
            'kd_matkul'         => 'required',
            'bentuk_integrasi'  => 'required',
        ]);

        $data                   = new CurriculumIntegration;
        $data->id_ta            = $request->id_ta;
        $data->id_penelitian    = $request->has('id_penelitian') ? $request->id_penelitian : null;
        $data->id_pengabdian    = $request->has('id_pengabdian') ? $request->id_pengabdian : null;
        $data->kegiatan         = $request->kegiatan;
        $data->nidn             = $request->nidn;
        $data->kd_matkul        = $request->kd_matkul;
        $data->bentuk_integrasi = $request->bentuk_integrasi;
        $data->save();

        return redirect()->route('academic.integration')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $request->validate([
            'id_ta'             => 'required',
            'kegiatan'          => 'required',
            'nidn'              => 'required',
            'kd_matkul'         => 'required',
            'bentuk_integrasi'  => 'required',
        ]);

        $data                   = CurriculumIntegration::find($id);
        $data->id_ta            = $request->id_ta;
        $data->id_penelitian    = $request->has('id_penelitian') ? $request->id_penelitian : null;
        $data->id_pengabdian    = $request->has('id_pengabdian') ? $request->id_pengabdian : null;
        $data->kegiatan         = $request->kegiatan;
        $data->nidn             = $request->nidn;
        $data->kd_matkul        = $request->kd_matkul;
        $data->bentuk_integrasi = $request->bentuk_integrasi;
        $data->save();

        return redirect()->route('academic.integration')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = CurriculumIntegration::find($id)->delete();
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
