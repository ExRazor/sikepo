<?php

namespace App\Http\Controllers\Teacher;

use App\CommunityService;
use App\CommunityServiceTeacher;
use App\CommunityServiceStudent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityServiceController extends Controller
{
    public function index()
    {
        $pengabdianKetua     = CommunityService::whereHas(
                                                    'serviceTeacher', function($q) {
                                                        $q->where('nidn',Auth::user()->username)->where('status','Ketua');
                                                    }
                                                )
                                                ->get();

        $pengabdianAnggota   = CommunityService::whereHas(
                                                    'serviceTeacher', function($q) {
                                                        $q->where('nidn',Auth::user()->username)->where('status','Anggota');
                                                    }
                                                )
                                                ->get();

        // return response()->json($pengabdian);die;

        return view('teacher-view.community-service.index',compact(['pengabdianKetua','pengabdianAnggota']));
    }

    public function show($id)
    {
        $id     = decode_id($id);
        $nidn   = Auth::user()->username;
        $data   = CommunityService::where('id',$id)->first();
        $status = CommunityServiceTeacher::where('id_pengabdian',$id)->where('nidn',$nidn)->first()->status;

        return view('teacher-view.community-service.show',compact(['data','status']));
    }

    public function create()
    {
        return view('teacher-view.community-service.form');
    }

    public function edit($id)
    {
        $id     = decode_id($id);
        $nidn   = Auth::user()->username;
        $data   = CommunityService::where('id',$id)->first();
        $status = CommunityServiceTeacher::where('id_pengabdian',$id)->where('nidn',$nidn)->first()->status;

        if($status=='Ketua') {
            return view('teacher-view.community-service.form',compact(['data']));
        } else {
            return abort(404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_ta'             => 'required',
            'ketua_nidn'        => 'required',
            'judul_pengabdian'  => 'required',
            'tema_pengabdian'   => 'required',
            'sks_pengabdian'    => 'required|numeric',
            'sesuai_prodi'      => 'nullable',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
            'jumlah_biaya'      => 'required',
        ]);

        //Simpan Data Pengabdian
        $community                    = new CommunityService;
        $community->id_ta             = $request->id_ta;
        $community->judul_pengabdian  = $request->judul_pengabdian;
        $community->tema_pengabdian   = $request->tema_pengabdian;
        $community->sks_pengabdian    = $request->sks_pengabdian;
        $community->sesuai_prodi      = $request->sesuai_prodi;
        $community->sumber_biaya      = $request->sumber_biaya;
        $community->sumber_biaya_nama = $request->sumber_biaya_nama;
        $community->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $community->save();

        //Jumlah SKS
        $sks_ketua      = floatval($request->sks_pengabdian)*setting('service_ratio_chief')/100;
        $sks_anggota    = floatval($request->sks_pengabdian)*setting('service_ratio_members')/100;

        //Tambah Ketua
        $ketua                  = new CommunityServiceTeacher;
        $ketua->id_pengabdian   = $community->id;
        $ketua->nidn            = $request->ketua_nidn;
        $ketua->status          = 'Ketua';
        $ketua->sks             = $sks_ketua;
        $ketua->save();

        //Tambah Anggota Dosen
        if($request->anggota_nidn) {
            $hitungDsn = count($request->anggota_nidn);
            for($i=0;$i<$hitungDsn;$i++) {
                CommunityServiceTeacher::updateOrCreate(
                    [
                        'id_penelitian' => $community->id,
                        'nidn'          => $request->anggota_nidn[$i],
                    ],
                    [
                        'status'     => 'Anggota',
                        'sks'        => $sks_anggota,
                    ]
                );
            }
        }

        //Tambah Mahasiswa
        if($request->mahasiswa_nim) {
            $hitungMhs = count($request->mahasiswa_nim);
            for($i=0;$i<$hitungMhs;$i++) {
                CommunityServiceStudent::updateOrCreate(
                    [
                        'id_pengabdian' => $community->id,
                        'nim'           => $request->mahasiswa_nim[$i],
                    ]
                );
            }
        }

        return redirect()->route('profile.community-service')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $request->validate([
            'id_ta'             => 'required',
            // 'ketua_nidn'        => 'required',
            'judul_pengabdian'  => 'required',
            'tema_pengabdian'   => 'required',
            'sks_pengabdian'    => 'required|numeric',
            'sesuai_prodi'      => 'nullable',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
            'jumlah_biaya'      => 'required',
        ]);

        //Update Data Pengabdian
        $community                    = CommunityService::find($id);
        $community->id_ta             = $request->id_ta;
        $community->judul_pengabdian  = $request->judul_pengabdian;
        $community->tema_pengabdian   = $request->tema_pengabdian;
        $community->sks_pengabdian    = $request->sks_pengabdian;
        $community->sesuai_prodi      = $request->sesuai_prodi;
        $community->sumber_biaya      = $request->sumber_biaya;
        $community->sumber_biaya_nama = $request->sumber_biaya_nama;
        $community->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $community->save();

        //Jumlah SKS
        $sks_ketua      = floatval($request->sks_pengabdian)*setting('service_ratio_chief')/100;
        $sks_anggota    = floatval($request->sks_pengabdian)*setting('service_ratio_members')/100;

        //Update Ketua
        $ketua = CommunityServiceTeacher::where('id_pengabdian',$id)->where('status','Ketua');
        if($ketua != $request->ketua_nidn) {
            $ketua->delete();

            $new_ketua                  = new CommunityServiceTeacher;
            $new_ketua->id_pengabdian   = $id;
            $new_ketua->nidn            = $request->ketua_nidn;
            $new_ketua->status          = 'Ketua';
            $new_ketua->sks             = $sks_ketua;
            $new_ketua->save();
        } else {
            $ketua->id_pengabdian = $id;
            $ketua->nidn          = $request->ketua_nidn;
            $ketua->sks           = $sks_ketua;
            $ketua->save();
        }

        //Update Anggota
        if($request->anggota_nidn) {
            $hitungDsn = count($request->anggota_nidn);
            for($i=0;$i<$hitungDsn;$i++) {

                CommunityServiceTeacher::updateOrCreate(
                    [
                        'id_pengabdian' => $id,
                        'nidn'          => $request->anggota_nidn[$i],
                    ],
                    [
                        'status'     => 'Anggota',
                        'sks'        => $sks_anggota,
                    ]
                );
            }
        }

        //Update Anggota Mahasiswa
        if($request->mahasiswa_nim) {
            $hitungMhs = count($request->mahasiswa_nim);
            for($i=0;$i<$hitungMhs;$i++) {

                CommunityServiceStudent::updateOrCreate(
                    [
                        'id_pengabdian' => $id,
                        'nim'           => $request->mahasiswa_nim[$i],
                    ]
                );
            }
        }

        return redirect()->route('profile.community-service.show',encode_id($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decode_id($request->id);
            $q  = CommunityService::find($id)->delete();
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

    public function destroy_teacher(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = CommunityServiceTeacher::find($id)->delete();
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

    public function destroy_students(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = CommunityServiceStudent::find($id)->delete();
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
