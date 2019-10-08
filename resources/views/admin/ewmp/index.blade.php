@extends('layouts.master')

@section('title', 'Data EWMP Dosen')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'teacher-edit' : 'teacher-add' ) as $breadcrumb)
            @isset($breadcrumb->url)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endisset
        @endforeach
    </nav>
</div>

<div class="br-pagetitle">
        <i class="icon ion-calendar"></i>
        <div>
            <h4>Import</h4>
            <p class="mg-b-0">Import Data Dosen</p>
        </div>
    </div>

<div class="br-pagebody">
    @if($errors->any())
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif
    <div class="widget-2">
        <div class="card mb-3">
            <div class="card-header bd rounded-top bd-color-gray-lighter">
                <h6 class="card-title">Tampilkan</h6>
            </div>
            <div class="card-body bd bd-t-0 rounded-bottom bd-color-gray-lighter">
                <div class="row">
                    <div class="col-md-10">
                        <form action="{{route('ewmp.show_filter')}}" id="filter-ewmp" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <div class="form-group row mb-3">
                                <label class="col-3 form-control-label">Program Studi:</label>
                                <div class="col-6">
                                    <div id="prodi" class="parsley-select">
                                        <select class="form-control" name="program_studi" data-placeholder="Pilih Prodi" required>
                                            @foreach ($studyProgram as $sp)
                                            <option value="{{$sp->kd_prodi}}" {{ isset($data) && ($data->kd_prodi==$sp->kd_prodi || Request::old('kd_prodi')==$sp->kd_prodi) ? 'selected' : ''}}>{{$sp->nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div><!-- row -->
                            <div class="form-group row mb-3">
                                <label class="col-3 form-control-label">Tahun Akademik:</label>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-6">
                                            <select class="form-control" name="tahun_akademik" data-placeholder="Pilih Prodi">
                                                @for ($i = date('Y'); $i >= 2010; $i--)
                                                <option value="{{$i}}">{{$i}}/{{$i+1}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select class="form-control" name="semester" data-placeholder="Pilih Prodi">
                                                <option value="Ganjil">Ganjil</option>
                                                <option value="Genap">Genap</option>
                                                <option value="Penuh">Penuh</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-6 offset-3">
                                    <button type="submit" class="btn btn-info">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- card-body -->
        </div>
        <div class="card shadow-base mb-3">
            <div class="card-header">
                <h6 class="card-title">
                    @isset($ewmp)
                    Prodi: {{ $ewmp[0]->nama_prodi }} || Tahun Akademik: {{$ewmp[0]->tahun_akademik}} - {{$ewmp[0]->semester}}
                    @else
                    Prodi: - || EWMP: -
                    @endisset
                </h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table class="table table-bordered mb-0 table-ewmp">
                    <thead>
                        <tr>
                            <th rowspan="3" class="text-center align-middle">Dosen</th>
                            <th rowspan="3" class="text-center align-middle">Tahun Akademik</th>
                            <th colspan="6" class="text-center align-middle">Ekuivalen Waktu Mengajar Penuh (EWMP)<br>dalam satuan kredit semester (sks)</th>
                            <th rowspan="3" class="text-center align-middle">Jumlah<br>(sks)</th>
                            <th rowspan="3" class="text-center align-middle">Rata-rata<br>(sks)</th>
                            <th rowspan="3" class="text-center align-middle no-sort">Aksi</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center align-middle" style="border-left-width: 1px;">Pendidikan</th>
                            <th rowspan="2" class="text-center align-middle" width="100">Penelitian</th>
                            <th rowspan="2" class="text-center align-middle">PKM</th>
                            <th rowspan="2" class="text-center align-middle" width="100">Tugas Tambahan/<br>Penunjang</th>
                        </tr>
                        <tr>
                            <th class="text-center align-middle" style="border-left-width: 1px;" width="70">PS</th>
                            <th class="text-center align-middle" width="70">PS Luar</th>
                            <th class="text-center align-middle" width="70">Luar PT</th>
                        </tr>
                    </thead>
                    <tbody class="tbody-ewmp">
                        @isset($ewmp)
                            @foreach ($ewmp as $e)
                            <tr>
                                <td>{{ $e->nama_dosen }}</td>
                                <td>{{ $e->tahun_akademik.' - '.$e->semester }}</td>
                                <td class="text-center">{{ $e->ps_intra }}</td>
                                <td class="text-center">{{ $e->ps_lain }}</td>
                                <td class="text-center">{{ $e->ps_luar }}</td>
                                <td class="text-center">{{ $e->penelitian }}</td>
                                <td class="text-center">{{ $e->pkm }}</td>
                                <td class="text-center">{{ $e->tugas_tambahan }}</td>
                                <td class="text-center">{{ $total = $e->ps_intra+$e->ps_lain+$e->ps_luar+$e->penelitian+$e->pkm+$e->tugas_tambahan}}</td>
                                <td class="text-center">{{ number_format($total/6, 1, ',', ' ') }}</td>
                                <td class="text-center" width="50">
                                    <div class="btn-group" role="group">
                                        <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <div><span class="fa fa-caret-down"></span></div>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                            <a class="dropdown-item btn-edit btn-edit-ewmp" href="#" data-id="{{encrypt($e->id)}}">Sunting</a>
                                            <form method="POST">
                                                @method('delete')
                                                @csrf
                                                <input type="hidden" value="{{encrypt($e->id)}}" name="_id">
                                                <a href="#" class="dropdown-item btn-delete" data-dest="{{ route('ewmp.delete') }}">Hapus</a>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan=11 class="text-center align-middle">BELUM ADA DATA</td>
                            </tr>
                        @endisset
                    </tbody>
                </table>
            </div><!-- card-body -->
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
