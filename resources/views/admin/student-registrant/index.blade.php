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
        <h4>Calon Mahasiswa</h4>
        <p class="mg-b-0">Olah Data Calon Mahasiswa</p>
    </div>
    <div class="ml-auto">
        <div class="row">
            <div class="col-6 pr-1">
                <a href="{{ route('teacher.add') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Data Calon</a>
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
                <table class="table display responsive nowrap datatable" data-sort="asc">
                    <thead>
                        <tr>
                            <th>NISN</th>
                            <th class="defaultSort">Nama Siswa</th>
                            <th>Jenis Kelamin</th>
                            <th>Asal Sekolah</th>
                            <th>Jalur Masuk</th>
                            <th>Status</th>
                            <th class="no-sort">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registrant[$sp->kd_prodi] as $r)
                        <tr>
                            <td><a href="{{ route('teacher.show',encrypt($r->nisn)) }}">{{$r->nisn}}</a></td>
                            <td>{{$r->nama}}</td>
                            <td class="text-capitalize">{{$r->jk}}</td>
                            <td>{{$r->asal_sekolah}}</td>
                            <td>{{$r->jalur_masuk}}</td>
                            <td>{{$r->status_lulus}}</td>
                            <td width="50">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-teal dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item" href="{{ route('teacher.edit',encrypt($r->nisn)) }}">Sunting</a>
                                        <form method="POST">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" value="{{encrypt($r->nisn)}}" name="id">
                                            <a href="{{ route('teacher.delete') }}" class="dropdown-item btn-delete">Hapus</a>
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
