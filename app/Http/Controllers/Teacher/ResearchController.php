<?php

namespace App\Http\Controllers\Teacher;

use App\Research;
use App\ResearchTeacher;
use App\ResearchStudent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResearchController extends Controller
{
    public function index()
    {
        $penelitianKetua    = Research::whereHas(
                                            'researchTeacher', function($q) {
                                                $q->where('nidn',Auth::user()->username)->where('status','Ketua');
                                            }
                                        )
                                        ->get();

        $penelitianAnggota   = Research::whereHas(
                                            'researchTeacher', function($q) {
                                                $q->where('nidn',Auth::user()->username)->where('status','Anggota');
                                            }
                                        )
                                        ->get();

        return view('teacher-view.research.index',compact(['penelitianKetua','penelitianAnggota']));
    }

    public function create()
    {
        return view('teacher-view.research.form');
    }

    public function show($id)
    {
        $id     = decode_id($id);
        $nidn   = Auth::user()->username;
        $data   = Research::where('id',$id)->first();
        $status = ResearchTeacher::where('id_penelitian',$id)->where('nidn',$nidn)->first()->status;

        return view('teacher-view.research.show',compact(['data','status']));
    }

    public function edit($id)
    {
        $id     = decode_id($id);
        $nidn   = Auth::user()->username;
        $data   = Research::where('id',$id)->first();
        $status = ResearchTeacher::where('id_penelitian',$id)->where('nidn',$nidn)->first()->status;

        if($status=='Ketua') {
            return view('teacher-view.research.form',compact(['data']));
        } else {
            return abort(404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_ta'             => 'required',
            'ketua_nidn'        => 'required',
            'judul_penelitian'  => 'required',
            'tema_penelitian'   => 'required',
            'tingkat_penelitian'=> 'required',
            'sks_penelitian'    => 'required|numeric',
            'sesuai_prodi'      => 'nullable',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
            'jumlah_biaya'      => 'required',
        ]);

        //Simpan Data Penelitian
        $research                    = new Research;
        $research->id_ta             = $request->id_ta;
        $research->judul_penelitian  = $request->judul_penelitian;
        $research->tema_penelitian   = $request->tema_penelitian;
        $research->tingkat_penelitian= $request->tingkat_penelitian;
        $research->sks_penelitian    = $request->sks_penelitian;
        $research->sesuai_prodi      = $request->sesuai_prodi;
        $research->sumber_biaya      = $request->sumber_biaya;
        $research->sumber_biaya_nama = $request->sumber_biaya_nama;
        $research->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $research->save();

        //Jumlah SKS
        $sks_ketua      = floatval($request->sks_penelitian)*setting('research_ratio_chief')/100;
        $sks_anggota    = floatval($request->sks_penelitian)*setting('research_ratio_members')/100;

        //Tambah Ketua
        $ketua                  = new ResearchTeacher;
        $ketua->id_penelitian   = $research->id;
        $ketua->nidn            = $request->ketua_nidn;
        $ketua->status          = 'Ketua';
        $ketua->sks             = $sks_ketua;
        $ketua->save();

        //Tambah Anggota Dosen
        if($request->anggota_nidn) {
            $hitungDsn = count($request->anggota_nidn);
            for($i=0;$i<$hitungDsn;$i++) {
                ResearchTeacher::updateOrCreate(
                    [
                        'id_penelitian' => $research->id,
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
                ResearchStudent::updateOrCreate(
                    [
                        'id_penelitian' => $research->id,
                        'nim'           => $request->mahasiswa_nim[$i],
                    ]
                );
            }
        }

        return redirect()->route('profile.research')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        // dd($request->all());

        $request->validate([
            'id_ta'             => 'required',
            // 'ketua_nidn'        => 'required|unique:research_teachers,nidn,'.$id.',id_penelitian',
            'judul_penelitian'  => 'required',
            'tema_penelitian'   => 'required',
            'tingkat_penelitian'   => 'required',
            'sks_penelitian'    => 'required|numeric',
            'sesuai_prodi'      => 'nullable',
            'sumber_biaya'      => 'required',
            'sumber_biaya_nama' => 'nullable',
            'jumlah_biaya'      => 'required',
        ]);

        //Simpan Data Penelitian
        $research                    = Research::find($id);
        $research->id_ta             = $request->id_ta;
        $research->judul_penelitian  = $request->judul_penelitian;
        $research->tema_penelitian   = $request->tema_penelitian;
        $research->tingkat_penelitian= $request->tingkat_penelitian;
        $research->sks_penelitian    = $request->sks_penelitian;
        $research->sesuai_prodi      = $request->sesuai_prodi;
        $research->sumber_biaya      = $request->sumber_biaya;
        $research->sumber_biaya_nama = $request->sumber_biaya_nama;
        $research->jumlah_biaya      = str_replace(".", "", $request->jumlah_biaya);
        $research->save();

        //Jumlah SKS
        $sks_ketua      = floatval($request->sks_penelitian)*setting('research_ratio_chief')/100;
        $sks_anggota    = floatval($request->sks_penelitian)*setting('research_ratio_members')/100;

        //Update Ketua
        $ketua = ResearchTeacher::where('id_penelitian',$id)->where('status','Ketua');
        if($ketua != $request->ketua_nidn) {
            $ketua->delete();

            $new_ketua                  = new ResearchTeacher;
            $new_ketua->id_penelitian   = $id;
            $new_ketua->nidn            = $request->ketua_nidn;
            $new_ketua->status          = 'Ketua';
            $new_ketua->sks             = $sks_ketua;
            $new_ketua->save();
        } else {

            $ketua->id_penelitian = $id;
            $ketua->nidn          = $request->ketua_nidn;
            $ketua->sks           = $sks_ketua;
            $ketua->save();
        }

        //Update Anggota
        if($request->anggota_nidn) {
            $hitungDsn = count($request->anggota_nidn);
            for($i=0;$i<$hitungDsn;$i++) {

                ResearchTeacher::updateOrCreate(
                    [
                        'id_penelitian' => $id,
                        'nidn'          => $request->anggota_nidn[$i],
                    ],
                    [
                        'status'     => 'Anggota',
                        'sks'        => $sks_anggota,
                    ]
                );
            }
        }

        //Update Mahasiswa
        if($request->mahasiswa_nim) {
            $hitungMhs = count($request->mahasiswa_nim);
            for($i=0;$i<$hitungMhs;$i++) {

                ResearchStudent::updateOrCreate(
                    [
                        'id_penelitian' => $id,
                        'nim'           => $request->mahasiswa_nim[$i],
                    ]
                );
            }
        }

        return redirect()->route('profile.research.show',encode_id($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decode_id($request->id);
            $q  = Research::find($id)->delete();
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
            $q  = ResearchTeacher::find($id)->delete();
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
            $q  = ResearchStudent::find($id)->delete();
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
