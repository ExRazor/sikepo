@extends('layouts.master')

@section('title', 'Penilaian Kerja Sama')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
{{-- <div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('research') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div> --}}
<div class="br-pagetitle">
    <i class="icon fa fa-handshake"></i>
    <div>
        <h4>Penilaian Kerja Sama</h4>
        <p class="mg-b-0">Cek hasil perhitungan penilaian kerja sama program studi</p>
    </div>
</div>

<div class="br-pagebody">
    @if (session()->has('flash.message'))
        <div class="alert alert-{{ session('flash.class') }}" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('flash.message') }}
        </div>
    @endif
    @if(!Auth::user()->hasRole('kaprodi'))
    <div class="row">
        <div class="col-12">
            <form action="{{route('ajax.research.filter')}}" id="filter-research" data-token="{{encode_id(Auth::user()->role)}}" method="POST">
                <div class="filter-box d-flex flex-row bd-highlight mg-b-10">
                    @if(!Auth::user()->hasRole('kajur'))
                    <div class="mg-r-10">
                        <select id="fakultas" class="form-control" name="kd_jurusan" data-placeholder="Pilih Jurusan" required>
                            <option value="0">Semua Jurusan</option>
                            @foreach($faculty as $f)
                                @if($f->department->count())
                                <optgroup label="{{$f->nama}}">
                                    @foreach($f->department as $d)
                                    <option value="{{$d->kd_jurusan}}" {{ $d->kd_jurusan == setting('app_department_id') ? 'selected' : ''}}>{{$d->nama}}</option>
                                    @endforeach
                                </optgroup>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="mg-r-10">
                        <select class="form-control" name="kd_prodi">
                            <option value="">- Pilih Program Studi -</option>
                            @foreach($studyProgram as $sp)
                            <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-purple btn-block " style="color:white">Cari</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
    <div class="widget-2">
        <div class="card shadow-base mb-3">
            <div class="card-header">
                <div class="card-title">
                    <h6 class="mg-b-0">
                        Kerja Sama
                    </h6>
                </div>
                <div class="ml-auto">
                    <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-kerjasama"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
                </div>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <div class="row mg-b-20">
                    <div class="col-md-12">
                        <img class="w-100" src="{{ asset ('penilaian/img') }}/kerjasama.png" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Jumlah Dosen Tetap Program Studi (NDTPS)</label>
                                    <input type="text" class="form-control" value="{{$jumlah['dtps']}}" disabled>
                                </div>
                            </div>
                            <div class="col-md-4 ml-auto">
                                <div class="form-group">
                                    <label>Skor</label>
                                        <input type="text" class="form-control" value="{{number_format ( $skor['total'], 2 )}}" disabled>
                                    <small class="form-text text-muted">Skor = {{$rumus['total']}}</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pendidikan">Kerja Sama Pendidikan (N1)</label>
                                    <input type="text" class="form-control" value="{{$jumlah['pendidikan']}}" disabled>
                                    <small class="form-text text-muted">a * N1 = {{number_format ( $rata['pendidikan'], 2 )}}</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="penelitian">Kerja Sama Penelitian (N2)</label>
                                    <input type="text" class="form-control" value="{{$jumlah['penelitian']}}" disabled>
                                    <small class="form-text text-muted">b * N2 = {{number_format ( $rata['penelitian'], 2 )}}</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pkm">Kerja Sama PkM (N3)</label>
                                    <input type="text" class="form-control" value="{{$jumlah['pengabdian']}}" disabled>
                                    <small class="form-text text-muted">c * N3 = {{number_format ( $rata['pengabdian'], 2 )}}</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="skor_a">Skor A (RK)</label>
                                    <input type="text" class="form-control" value="{{number_format ( $skor['a'], 2 )}}" disabled>
                                    <small class="form-text text-muted">A = {{$rumus['a']}}</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="internasional">Kerjasama Internasional (NI)</label>
                                    <input type="text" class="form-control" value="{{$jumlah['internasional']}}" disabled>
                                    <!-- <small class="form-text text-muted">RI = NI/NDT = <span class="rata_inter">0</span></small> -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nasional">Kerjasama Nasional (NN)</label>
                                    <input type="text" class="form-control" value="{{$jumlah['nasional']}}" disabled>
                                    <!-- <small class="form-text text-muted">RN = NN/NDT = <span class="rata_nasional">0</span></small> -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="lokal">Kerjasama Lokal (NL)</label>
                                    <input type="text" class="form-control" value="{{$jumlah['lokal']}}" disabled>
                                    <!-- <small class="form-text text-muted">RL = NL/NDT = <span class="rata_lokal">0</span></small> -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="skor_b">Skor B</label>
                                    <input type="text" class="form-control" value="{{number_format ( $skor['b'], 2 )}}" readonly>
                                    <small class="form-text text-muted">B = {{$rumus['b']}}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-success">
                            <strong>FAKTOR JENIS KERJA SAMA</strong><br>
                            <span>
                                a = 3; b= 2; c = 1
                            </span>
                            <hr>
                            <strong>FAKTOR TINGKAT KERJA SAMA</strong><br>
                            <span>
                                a = 2; b= 6; c = 9
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
