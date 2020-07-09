<?php

namespace App\Http\Controllers;

use App\StudentOutputActivity;
use App\OutputActivityCategory;
use App\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;

class StudentOutputActivityController extends Controller
{
    public function __construct()
    {
        $method = [
            'create',
            'edit',
            'store',
            'update',
            'destroy',
            'delete_file',
            'delete_all_file',
        ];

        $this->middleware('role:admin,kaprodi', ['only' => $method]);
    }

    public function index()
    {
        $studyProgram   = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $category       = OutputActivityCategory::all();

        if(Auth::user()->hasRole('kaprodi')) {
            $outputActivity = StudentOutputActivity::whereHas(
                                                        'student.studyProgram', function($q) {
                                                            $q->where('kd_prodi',Auth::user()->kd_prodi);
                                                        }
                                                    )->get();
        } else {
            $outputActivity = StudentOutputActivity::whereHas(
                                                        'student.studyProgram', function($q) {
                                                            $q->where('kd_jurusan',setting('app_department_id'));
                                                        }
                                                    )->get();
        }

        return view('output-activity.student.index',compact(['outputActivity','category','studyProgram']));
    }

    public function show($id)
    {
        $id         = decode_id($id);
        $data       = StudentOutputActivity::where('id',$id)->first();
        $category   = OutputActivityCategory::all();

        return view('output-activity.student.show',compact(['data','category']));
    }

    public function create()
    {
        $category   = OutputActivityCategory::all();

        return view('output-activity.student.form',compact(['category']));
    }

    public function edit($id)
    {
        $id = decode_id($id);

        $category   = OutputActivityCategory::all();
        $data       = StudentOutputActivity::find($id);
        return view('output-activity.student.form',compact(['category','data']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan'          => 'required',
            'nm_kegiatan'       => 'nullable',
            'nim'               => 'required',
            'id_kategori'       => 'required',
            'judul_luaran'      => 'required',
            'thn_luaran'        => 'required|numeric|digits:4',
            'jenis_luaran'      => 'required',
            'url'               => 'url|nullable',
            'file_karya'        => 'mimes:pdf',
        ]);

        $data                   = new StudentOutputActivity;
        $data->kegiatan         = $request->kegiatan;
        $data->nm_kegiatan      = $request->nm_kegiatan;
        $data->nim              = $request->nim;
        $data->id_kategori      = $request->id_kategori;
        $data->judul_luaran     = $request->judul_luaran;
        $data->thn_luaran       = $request->thn_luaran;
        $data->jenis_luaran     = $request->jenis_luaran;
        $data->nama_karya       = $request->nama_karya;
        $data->jenis_karya      = $request->jenis_karya;
        $data->pencipta_karya   = $request->pencipta_karya;
        $data->issn             = $request->issn;
        $data->no_paten         = $request->no_paten;
        $data->tgl_sah          = $request->tgl_sah;
        $data->no_permohonan    = $request->no_permohonan;
        $data->tgl_permohonan   = $request->tgl_permohonan;
        $data->penerbit         = $request->penerbit;
        $data->penyelenggara    = $request->penyelenggara;
        $data->url              = $request->url;
        $data->keterangan       = $request->keterangan;
        $data->save();

        if($file = $request->file('file_karya')) {
            $tujuan_upload = public_path('upload/output-activity/student');
            $filename = str_replace(' ', '', $request->jenis_luaran).'_'.$request->nim.'_'.$request->id_kategori.'_'.str_replace(' ', '', $request->kegiatan).'_'.$request->thn_luaran.'_'.$data->id.'.'.$file->getClientOriginalExtension();
            $file->move($tujuan_upload,$filename);
            $data->update([
                    'file_karya' => $filename
                ]);
        }

        return redirect()->route('output-activity.student.show',encode_id($data->id))->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');

    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $request->validate([
            'kegiatan'          => 'required',
            'nm_kegiatan'       => 'nullable',
            'nim'               => 'required',
            'id_kategori'       => 'required',
            'judul_luaran'      => 'required',
            'thn_luaran'        => 'required|numeric|digits:4',
            'jenis_luaran'      => 'required',
            'url'               => 'url|nullable',
            'file_karya'        => 'mimes:pdf',
        ]);

        $data                   = StudentOutputActivity::find($id);
        $data->kegiatan         = $request->kegiatan;
        $data->nm_kegiatan      = $request->nm_kegiatan;
        $data->nim              = $request->nim;
        $data->id_kategori      = $request->id_kategori;
        $data->judul_luaran     = $request->judul_luaran;
        $data->thn_luaran       = $request->thn_luaran;
        $data->jenis_luaran     = $request->jenis_luaran;
        $data->nama_karya       = $request->nama_karya;
        $data->jenis_karya      = $request->jenis_karya;
        $data->pencipta_karya   = $request->pencipta_karya;
        $data->issn             = $request->issn;
        $data->no_paten         = $request->no_paten;
        $data->tgl_sah          = $request->tgl_sah;
        $data->no_permohonan    = $request->no_permohonan;
        $data->tgl_permohonan   = $request->tgl_permohonan;
        $data->penerbit         = $request->penerbit;
        $data->penyelenggara    = $request->penyelenggara;
        $data->url              = $request->url;
        $data->keterangan       = $request->keterangan;
        $data->save();

        $storagePath = public_path('upload/output-activity/student'.$data->file_karya);
        if($file = $request->file('file_karya')) {
            if(File::exists($storagePath)) {
                File::delete($storagePath);
            }

            $tujuan_upload = public_path('upload/output-activity/student');
            $filename = str_replace(' ', '', $request->jenis_luaran).'_'.$request->nim.'_'.$request->id_kategori.'_'.str_replace(' ', '', $request->kegiatan).'_'.$request->thn_luaran.'_'.$data->id.'.'.$file->getClientOriginalExtension();
            $file->move($tujuan_upload,$filename);
            $data->update([
                    'file_karya' => $filename
                ]);
        }

        if(isset($data->file_karya) && File::exists($storagePath)){
            $ekstensi = File::extension($storagePath);
            $filename = str_replace(' ', '', $request->jenis_luaran).'_'.$request->nim.'_'.$request->id_kategori.'_'.str_replace(' ', '', $request->kegiatan).'_'.$request->thn_luaran.'_'.$data->id.'.'.$ekstensi;
            File::move($storagePath,public_path('upload/output-activity/student/'.$filename));
            $data->update([
                'file_karya' => $filename
            ]);
        }

        return redirect()->route('output-activity.student.show',encode_id($data->id))->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id     = decode_id($request->id);
            $data   = StudentOutputActivity::find($id);
            $q      = $data->delete();

            if(!$q) {
                return response()->json([
                    'title'   => 'Gagal',
                    'message' => 'Terjadi kesalahan saat menghapus',
                    'type'    => 'error'
                ]);
            } else {
                $this->delete_all_file($data->file_karya);
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

        $data = StudentOutputActivity::find($id);

        $storagePath = public_path('upload/output-activity/student/'.$data->file_karya);
        if( ! File::exists($storagePath)) {
            abort(404);
        } else {
            $mimeType = File::mimeType($storagePath);
            $headers = array(
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="'.$data->file_karya.'"'
            );

            return response(file_get_contents($storagePath), 200, $headers);
        }
    }

    public function delete_file(Request $request)
    {
        $id   = decrypt($request->id);
        $data = StudentOutputActivity::find($id);

        if(request()->ajax()) {

            $storagePath = public_path('upload/output-activity/student/'.$data->file_karya);
            if(File::exists($storagePath)) {
                $delete = File::delete($storagePath);

                if($delete) {
                    $data->file_karya = null;
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
            return redirect()->route('output-activity.student.show',encode_id($data->id));
        }
    }

    public function delete_all_file($file)
    {
        $storage = public_path('upload/output-activity/student/'.$file);
        if(File::exists($storage)) {
            File::delete($storage);
        }
    }

    public function datatable(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        if(Auth::user()->hasRole('kaprodi')) {
            $data    = StudentOutputActivity::whereHas(
                                            'student.studyProgram', function($query) {
                                                $query->where('kd_prodi',Auth::user()->kd_prodi);
                                            }
                                        );
        } else {
            $data   = StudentOutputActivity::whereHas(
                                            'student.studyProgram.department', function($query) {
                                                $query->where('kd_jurusan',setting('app_department_id'));
                                            }
                                        );
        }

        if($request->kd_prodi_filter) {
            $data->whereHas(
                'student.studyProgram', function($q) use($request) {
                    $q->where('kd_prodi',$request->kd_prodi_filter);
                }
            );
        }

        return DataTables::of($data->get())
                            ->addColumn('judul', function($d) {
                                return  '<a href="'.route('output-activity.student.show',encode_id($d->id)).'" target="_blank">'
                                            .$d->judul_luaran.
                                        '</a>';
                            })
                            ->addColumn('milik', function($d) {
                                return  '<a href="'.route('student.list.show',$d->student->nim).'#publication">'
                                            .$d->student->nama.
                                            '<br><small>NIM.'.$d->student->nim.' / '.$d->student->studyProgram->singkatan.'</small>
                                        </a>';
                            })
                            ->addColumn('kategori', function($d) {
                                return  $d->outputActivityCategory->nama;
                            })
                            ->addColumn('aksi', function($d) {
                                if(!Auth::user()->hasRole('kajur')) {
                                    return view('output-activity.student.table-button', compact('d'))->render();
                                }
                            })
                            ->rawColumns(['judul','milik','aksi'])
                            ->make();
    }
}
