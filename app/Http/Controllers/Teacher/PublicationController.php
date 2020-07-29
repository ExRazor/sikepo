<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Teacher;
use App\Models\PublicationCategory;
use App\Models\TeacherPublication;
use App\Models\TeacherPublicationMember;
use App\Models\TeacherPublicationStudent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PublicationController extends Controller
{
    public function index()
    {
        $publikasiKetua    = TeacherPublication::where('nidn',Auth::user()->username)->get();

        $publikasiAnggota  = TeacherPublication::whereHas(
                                                    'publicationMembers', function($query) {
                                                        $query->where('nidn',Auth::user()->username);
                                                    }
                                                )
                                                ->get();

        return view('teacher-view.publication.index',compact(['publikasiKetua','publikasiAnggota']));
    }

    public function create()
    {
        $jenis        = PublicationCategory::all();

        return view('teacher-view.publication.form',compact(['studyProgram','jenis']));
    }

    public function show($id)
    {
        $id   = decode_id($id);
        $data = TeacherPublication::find($id);

        return view('teacher-view.publication.show',compact(['data']));
    }

    public function edit($id)
    {
        $id   = decode_id($id);

        $jenis        = PublicationCategory::all();
        $data         = TeacherPublication::with('teacher','publicationStudents')->where('id',$id)->first();
        $teacher      = Teacher::where('kd_prodi',$data->teacher->kd_prodi)->get();

        return view('teacher-view.publication.form',compact(['jenis','data','teacher']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_publikasi' => 'required',
            'judul'           => 'required',
            'penerbit'        => 'required',
            'id_ta'           => 'required',
            'jurnal'          => 'nullable',
            'sesuai_prodi'    => 'nullable',
            'akreditasi'      => 'nullable',
            'sitasi'          => 'nullable|numeric',
            'tautan'          => 'nullable|url',
        ]);

        $data                   = new TeacherPublication;
        $data->jenis_publikasi  = $request->jenis_publikasi;
        $data->nidn             = Auth::user()->username;
        $data->judul            = $request->judul;
        $data->penerbit         = $request->penerbit;
        $data->id_ta            = $request->id_ta;
        $data->sesuai_prodi     = $request->sesuai_prodi;
        $data->jurnal           = $request->jurnal;
        $data->akreditasi       = $request->akreditasi;
        $data->sitasi           = $request->sitasi;
        $data->tautan           = $request->tautan;
        $data->save();

        //Tambah Anggota Dosen
        if($request->anggota_nidn) {
            $hitungDsn = count($request->anggota_nidn);
            for($i=0;$i<$hitungDsn;$i++) {
                TeacherPublicationMember::updateOrCreate(
                    [
                        'id_publikasi' => $data->id,
                        'nidn'         => $request->anggota_nidn[$i],
                    ],
                    [
                        'nama'         => $request->anggota_nama[$i],
                        'kd_prodi'     => $request->anggota_prodi[$i],
                    ]
                );
            }
        }

        //Tambah Mahasiswa
        if($request->mahasiswa_nim) {
            $hitungMhs = count($request->mahasiswa_nim);
            for($i=0;$i<$hitungMhs;$i++) {

                TeacherPublicationStudent::updateOrCreate(
                    [
                        'id_publikasi'  => $data->id,
                        'nim'           => $request->mahasiswa_nim[$i],
                    ],
                    [
                        'nama'          => $request->mahasiswa_nama[$i],
                        'kd_prodi'      => $request->mahasiswa_prodi[$i],
                    ]
                );
            }
        }

        return redirect()->route('profile.publication')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $request->validate([
            'jenis_publikasi' => 'required',
            'judul'           => 'required',
            'penerbit'        => 'required',
            'id_ta'           => 'required',
            'jurnal'          => 'nullable',
            'sesuai_prodi'    => 'nullable',
            'akreditasi'      => 'nullable',
            'sitasi'          => 'nullable|numeric',
            'tautan'          => 'nullable|url',
        ]);

        $data                   = TeacherPublication::find($id);
        $data->jenis_publikasi  = $request->jenis_publikasi;
        $data->judul            = $request->judul;
        $data->penerbit         = $request->penerbit;
        $data->id_ta            = $request->id_ta;
        $data->sesuai_prodi     = $request->sesuai_prodi;
        $data->jurnal           = $request->jurnal;
        $data->akreditasi       = $request->akreditasi;
        $data->sitasi           = $request->sitasi;
        $data->tautan           = $request->tautan;
        $data->save();

        //Update Anggota Dosen
        if($request->anggota_nidn) {
            $hitungDsn = count($request->anggota_nidn);
            for($i=0;$i<$hitungDsn;$i++) {

                TeacherPublicationMember::updateOrCreate(
                    [
                        'id_publikasi'  => $id,
                        'nidn'           => $request->anggota_nidn[$i],
                    ],
                    [
                        'nama'          => $request->anggota_nama[$i],
                        'kd_prodi'      => $request->anggota_prodi[$i],
                    ]
                );
            }
        }

        //Update Mahasiswa
        if($request->mahasiswa_nim) {
            $hitungMhs = count($request->mahasiswa_nim);
            for($i=0;$i<$hitungMhs;$i++) {

                TeacherPublicationStudent::updateOrCreate(
                    [
                        'id_publikasi'  => $id,
                        'nim'           => $request->mahasiswa_nim[$i],
                    ],
                    [
                        'nama'          => $request->mahasiswa_nama[$i],
                        'kd_prodi'      => $request->mahasiswa_prodi[$i],
                    ]
                );
            }
        }

        return redirect()->route('profile.publication.show',encode_id($id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decode_id($request->id);
            $q  = TeacherPublication::find($id)->delete();
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

    public function destroy_member(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = TeacherPublicationMember::find($id)->delete();
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

    public function destroy_student(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = TeacherPublicationStudent::find($id)->delete();
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
