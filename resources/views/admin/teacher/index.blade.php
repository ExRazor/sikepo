@extends('layouts.master')

@section('title', 'Data Kerja Sama Prodi')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('teacher') as $breadcrumb)
        <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon ion-calendar"></i>
    <div>
        <h4>Dosen Program Studi</h4>
        <p class="mg-b-0">Olah Data Dosen</p>
    </div>
    <div class="ml-auto">
        <div class="row">
            <div class="col-6 pr-1">
                <a href="{{ route('teacher.add') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Data Dosen</a>
            </div>
            <div class="col-6 pl-1">
                    <a href="{{ route('teacher.import') }}" class="btn btn-primary btn-block mg-b-10" style="color:white"><i class="fa fa-file-import mg-r-10"></i> Import Data</a>
                </div>
        </div>
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
    <div class="widget-2">
        @foreach($studyProgram as $sp)
        <div class="card shadow-base mb-3">
            <div class="card-header">
                <h6 class="card-title">Program Studi: {{$sp->nama}}</h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table class="table display responsive nowrap table-kerjasama">
                    <thead>
                        <tr>
                            <th>NIDN</th>
                            <th>Nama Dosen</th>
                            <th>Jenis Kelamin</th>
                            <th>Pend Terakhir</th>
                            <th>Bidang Ahli</th>
                            <th>Status</th>
                            <th>Jabatan</th>
                            <th class="no-sort">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teacher[$sp->kd_prodi] as $d)
                        <tr>
                            <td>{{$d->nidn}}</td>
                            <td>{{$d->nama}}</td>
                            <td class="text-capitalize">{{$d->jk}}</td>
                            <td>{{$d->pend_terakhir_jenjang}} - {{$d->pend_terakhir_jurusan}}</td>
                            <td>{{$d->bidang_ahli}}</td>
                            <td>{{$d->status_pengajar==='DT' ? 'Dosen Tetap':'Dosen Tidak Tetap'}}</td>
                            <td>{{$d->jabatan_akademik}}</td>
                            <td width="50">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-teal dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item" href="/teacher/{{encrypt($d->nidn)}}/edit">Sunting</a>
                                        <form method="POST">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" value="{{encrypt($d->nidn)}}" name="id">
                                            <a href="#" class="dropdown-item btn-delete" data-dest="/teacher">Hapus</a>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!-- card-body -->
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
