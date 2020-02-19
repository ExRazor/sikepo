<?php

namespace App\Http\Controllers;

use App\Faculty;
use App\StudyProgram;
use App\OutputActivity;
use App\OutputActivityCategory;
use Illuminate\Http\Request;
use File;

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
            'id_kategori'       => 'required',
            'pembuat_luaran'    => 'required',
            'kegiatan'          => 'required',
            'judul_luaran'      => 'required',
            'tahun_luaran'      => 'required|numeric|digits:4',
            'url'               => 'url',
            'file_keterangan'   => 'mimes:pdf',
            'file_artikel'      => 'mimes:pdf',
        ]);


        //Simpan Data Penelitian
        $data                   = new OutputActivity;
        $data->id_kategori      = $request->id_kategori;
        $data->pembuat_luaran   = $request->pembuat_luaran;
        $data->kegiatan         = $request->kegiatan;
        $data->id_penelitian    = $request->has('id_penelitian') ? $request->id_penelitian : null;
        $data->id_pengabdian    = $request->has('id_pengabdian') ? $request->id_pengabdian : null;
        $data->lainnya          = $request->has('lainnya') ? $request->lainnya : null;
        $data->judul_luaran     = $request->judul_luaran;
        $data->jurnal_luaran    = $request->jurnal_luaran;
        $data->tahun_luaran     = $request->tahun_luaran;
        $data->issn             = $request->issn;
        $data->volume_hal       = $request->volume_hal;
        $data->url              = $request->url;
        $data->keterangan       = $request->keterangan;

        //Tampung variabel ID Kegiatan
        if($request->has('id_penelitian')) {
            $id_kegiatan = $request->id_penelitian;
        } elseif($request->has('id_pengabdian')) {
            $id_kegiatan = $request->id_pengabdian;
        } else {
            $id_kegiatan = $request->lainnya;
        }

        if($file = $request->file('file_keterangan')) {
            $tujuan_upload = 'upload/output-activity/keterangan';
            $filename = 'SuratAccepted_'.$request->id_kategori.'_'.str_replace(' ', '', $request->kegiatan).'_'.$id_kegiatan.'_'.$request->tahun_luaran.'.'.$file->getClientOriginalExtension();
            $file->move($tujuan_upload,$filename);
            $data->file_keterangan = $filename;
        }

        if($file = $request->file('file_artikel')) {
            $tujuan_upload = 'upload/output-activity/artikel';
            $filename = 'Artikel_'.$request->id_kategori.'_'.str_replace(' ', '', $request->kegiatan).'_'.$id_kegiatan.'_'.$request->tahun_luaran.'.'.$file->getClientOriginalExtension();
            $file->move($tujuan_upload,$filename);
            $data->file_artikel = $filename;
        }

        $data->save();

        return redirect()->route('output-activity.show',encode_id($data->id))->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');

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
            'id_kategori'       => 'required',
            'pembuat_luaran'    => 'required',
            'kegiatan'          => 'required',
            'judul_luaran'      => 'required',
            'tahun_luaran'      => 'required|numeric|digits:4',
            'url'               => 'url',
            'file_keterangan'   => 'mimes:pdf',
            'file_artikel'      => 'mimes:pdf',
        ]);

        //Simpan Data Penelitian
        $data                   = OutputActivity::find($id);
        $data->id_kategori      = $request->id_kategori;
        $data->pembuat_luaran   = $request->pembuat_luaran;
        $data->kegiatan         = $request->kegiatan;
        $data->id_penelitian    = $request->has('id_penelitian') ? $request->id_penelitian : null;
        $data->id_pengabdian    = $request->has('id_pengabdian') ? $request->id_pengabdian : null;
        $data->lainnya          = $request->has('lainnya') ? $request->lainnya : null;
        $data->judul_luaran     = $request->judul_luaran;
        $data->jurnal_luaran    = $request->jurnal_luaran;
        $data->tahun_luaran     = $request->tahun_luaran;
        $data->issn             = $request->issn;
        $data->volume_hal       = $request->volume_hal;
        $data->url              = $request->url;
        $data->keterangan       = $request->keterangan;

        //Tampung variabel ID Kegiatan
        if($request->has('id_penelitian')) {
            $id_kegiatan = $request->id_penelitian;
        } elseif($request->has('id_pengabdian')) {
            $id_kegiatan = $request->id_pengabdian;
        } else {
            $id_kegiatan = $request->lainnya;
        }

        if($file = $request->file('file_keterangan')) {
            $storagePath = 'upload/output-activity/keterangan'.$data->file_keterangan;
            if(File::exists($storagePath)) {
                File::delete($storagePath);
            }

            $tujuan_upload = 'upload/output-activity/keterangan';
            $filename = 'SuratAccepted_'.$request->id_kategori.'_'.str_replace(' ', '', $request->kegiatan).'_'.$id_kegiatan.'_'.$request->tahun_luaran.'.'.$file->getClientOriginalExtension();
            $file->move($tujuan_upload,$filename);
            $data->file_keterangan = $filename;
        }

        if($file = $request->file('file_artikel')) {
            $storagePath = 'upload/output-activity/artikel'.$data->file_artikel;
            if(File::exists($storagePath)) {
                File::delete($storagePath);
            }

            $tujuan_upload = 'upload/output-activity/artikel';
            $filename = 'Artikel_'.$request->id_kategori.'_'.str_replace(' ', '', $request->kegiatan).'_'.$id_kegiatan.'_'.$request->tahun_luaran.'.'.$file->getClientOriginalExtension();
            $file->move($tujuan_upload,$filename);
            $data->file_artikel = $filename;
        }

        $data->save();

        return redirect()->route('output-activity.show',encode_id($data->id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id     = decode_id($request->id);
            $data   = OutputActivity::find($id);

            $file['ket'] = $data->file_keterangan;
            $file['art'] = $data->file_artikel;

            $q = $data->delete();

            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                $this->delete_all_file($file['ket'],$file['art']);
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Data berhasil dihapus',
                    'type'    => 'success'
                ]);
            }
        }
    }

    public function download(Request $request)
    {
        $id   = decrypt($request->id);
        $type = $request->type;

        $data = OutputActivity::find($id);

        switch ($type) {
            case 'keterangan':
                $file = $data->file_keterangan;
                break;

            case 'artikel':
                $file = $data->file_artikel;
                break;

            default:
                $file = $data->file_keterangan;
                break;
        }

            $storagePath = 'upload/output-activity/'.$type.'/'.$file;
        if( ! File::exists($storagePath)) {
            abort(404);
        } else {
            $mimeType = File::mimeType($storagePath);
            $headers = array(
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="'.$file.'"'
            );

            return response(file_get_contents($storagePath), 200, $headers);
        }
    }

    public function delete_file(Request $request)
    {
        $id   = decrypt($request->id);
        $type = $request->type;

        $data = OutputActivity::find($id);

        if(request()->ajax()) {
            switch ($type) {
                case 'keterangan':
                    $file       = $data->file_keterangan;
                    break;

                case 'artikel':
                    $file = $data->file_artikel;
                    break;
            }

            $storagePath = 'upload/output-activity/'.$type.'/'.$file;
            if(File::exists($storagePath)) {
                $delete = File::delete($storagePath);

                if($delete) {
                    if($type=='keterangan') {
                        $data->file_keterangan = null;
                    } elseif($type=='artikel') {
                        $data->file_artikel    = null;
                    }
                    $q = $data->save();
                }
            }

            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus fail',
                    'type'    => 'error'
                ]);
            } else {
                return response()->json([
                    'title'   => 'Berhasil',
                    'message' => 'Fail berhasil dihapus',
                    'type'    => 'success'
                ]);
            }
        } else {
            return redirect()->route('output-activity.show',encode_id($data->id));
        }
    }

    public function delete_all_file($keterangan,$artikel)
    {
        $storageKeterangan = 'upload/output-activity/keterangan/'.$keterangan;
        $storageArtikel = 'upload/output-activity/artikel/'.$artikel;
        if(File::exists($storageKeterangan)) {
            File::delete($storageKeterangan);
        }

        if (File::exists($storageArtikel)) {
            File::delete($storageArtikel);
        }
    }
}
