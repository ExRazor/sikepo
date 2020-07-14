<?php

namespace App\Http\Controllers;

use App\Models\FundingFaculty;
use App\Models\FundingCategory;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FundingFacultyController extends Controller
{

    public function index()
    {
        $data         = FundingFaculty::where('id_fakultas',setting('app_faculty_id'))
                                        ->groupBy('kd_dana')
                                        ->groupBy('id_fakultas')
                                        ->groupBy('id_ta')
                                        ->orderBy('id_ta','desc')
                                        ->select('kd_dana','id_fakultas','id_ta')
                                        ->get();

        $funding      = FundingFaculty::where('id_fakultas',setting('app_faculty_id'))
                                        ->groupBy('kd_dana')
                                        ->get('kd_dana');

        foreach($funding as $f) {
            $dana[$f->kd_dana]  = DB::table('funding_faculties as faculty')
                                    ->select(
                                        DB::raw('sum(faculty.nominal) as total'),
                                        'funding_categories.jenis'
                                    )
                                    ->join('funding_categories','funding_categories.id', '=', 'faculty.id_kategori')
                                    ->where('faculty.kd_dana',$f->kd_dana)
                                    ->groupBy('funding_categories.jenis')
                                    ->get();
        }

        return view('funding.faculty.index',compact(['data','dana']));
    }

    public function create()
    {
        $category     = FundingCategory::with('children')->orderBy('id','asc')->whereNull('id_parent')->get();

        $funding      = FundingFaculty::groupBy('id_ta')->get('id_ta');
        $cekTahun     = array();

        foreach($funding as $f) {
            $cekTahun[] = $f->id_ta;
        }
        $academicYear = AcademicYear::whereNotIn('id',$cekTahun)->where('semester','Ganjil')->orderBy('tahun_akademik','desc')->get();

        return view('funding.faculty.form',compact(['category','academicYear']));
    }

    public function show($kd_dana)
    {
        $kd = decrypt($kd_dana);

        $data     = FundingFaculty::where('kd_dana',$kd)->first();
        $category = FundingCategory::with('children')->whereNull('id_parent')->orderBy('id','asc')->get();

        $categoryFilter = FundingCategory::all();
        foreach($categoryFilter as $c) {
            $nominal[$c->id] = FundingFaculty::where('kd_dana',$kd)->where('id_kategori',$c->id)->first();
        }

        $total  = DB::table('funding_faculties as faculty')
                    ->select(
                        DB::raw('sum(faculty.nominal) as total')
                    )
                    ->where('faculty.kd_dana',$kd)
                    ->first()
                    ->total;

        return view('funding.faculty.show',compact(['data','nominal','category','total']));

    }

    public function edit($id)
    {
        $id = decrypt($id);

        $category     = FundingCategory::with('children')->orderBy('id','asc')->whereNull('id_parent')->get();
        $academicYear = AcademicYear::where('semester','Ganjil')->orderBy('tahun_akademik','desc')->get();

        $categoryFilter = FundingCategory::with('children')->orderBy('id','asc')->get();

        foreach($categoryFilter as $c) {
            $q = FundingFaculty::where('kd_dana',$id)->where('id_kategori',$c->id);
            $cek = $q->exists();
            if($cek) {
                $nominal[$c->id] = $q->first()->nominal;
            }
        }

        $data  = FundingFaculty::with('academicYear')->where('kd_dana',$id)->first();

        return view('funding.faculty.form',compact(['academicYear','category','data','nominal']));

    }

    public function store(Request $request)
    {
        $id_fakultas = decrypt($request->id_fakultas);

        $request->validate([
            'id_ta'             => [
                'required',
                Rule::unique('funding_faculties')->where(function ($query) use($id_fakultas) {
                    return $query->where('id_fakultas', $id_fakultas);
                }),
            ],
        ]);

        $kd_dana     = 'pd'.$id_fakultas.'_thn'.$request->id_ta;

        foreach($request->id_kategori as $index => $value) {
            $nominal = ($value) ? $value : '0';

            $query                 = new FundingFaculty;
            $query->kd_dana        = $kd_dana;
            $query->id_fakultas    = $id_fakultas;
            $query->id_ta          = $request->id_ta;
            $query->id_kategori    = $index;
            $query->nominal        = str_replace(".", "", $nominal);
            $query->save();
        }

        return redirect()->route('funding.faculty')->with('flash.message', 'Data berhasil ditambahkan!')->with('flash.class', 'success');
    }

    public function update(Request $request)
    {
        $kd_dana     = decrypt($request->_id);
        $id_fakultas = decrypt($request->id_fakultas);

        $request->validate([
            'id_ta'             => [
                'required',
                Rule::unique('funding_faculties')->where(function ($query) use($id_fakultas) {
                    return $query->where('id_fakultas', $id_fakultas);
                })->ignore($kd_dana,'kd_dana'),
            ],
        ]);

        foreach($request->id_kategori as $index => $value) {
            $nominal = ($value) ? $value : '0';

            $query                 = FundingFaculty::where('kd_dana',$kd_dana)->where('id_kategori',$index)->first();
            $query->id_fakultas    = $id_fakultas;
            $query->id_ta          = $request->id_ta;
            $query->nominal        = str_replace(".", "", $nominal);
            $query->save();
        }

        return redirect()->route('funding.faculty.show',$request->_id)->with('flash.message', 'Data berhasil disunting!')->with('flash.class', 'success');
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $id = decrypt($request->id);
            $q  = FundingFaculty::where('kd_dana',$id)->delete();
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
