@extends('layouts.master')

@section('title', 'Data Kurikulum')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('academic-curriculum') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon fa fa-atom"></i>
    <div>
        <h4>Data Kurikulum</h4>
        <p class="mg-b-0">Olah Data Kurikulum atau Mata Kuliah</p>
    </div>
    <div class="ml-auto">
        <div class="row">
            <div class="col-6 pr-1">
                <a href="{{ route('academic.curriculum.add') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Matkul</a>
            </div>
            <div class="col-6 pl-1">
                <button class="btn btn-primary btn-block mg-b-10 text-white" data-toggle="modal" data-target="#modal-import-curriculum"><i class="fa fa-file-import mg-r-10"></i> Impor</button>
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
    <div class="row">
        <div class="col-12">
            <form action="{{route('ajax.curriculum.filter')}}" id="filter-curriculum" method="POST">
                <div class="filter-box d-flex flex-row bd-highlight mg-b-10">
                    <div class="mg-r-10">
                        <input id="nm_jurusan" type="hidden" value="{{setting('app_department_name')}}">
                        <select class="form-control" name="kd_prodi">
                            <option value="">- Program Studi -</option>
                            @foreach($studyProgram as $sp)
                            <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mg-r-10">
                        <select class="form-control" name="kurikulum" style="width:200px;">
                            <option value="">- Tahun Kurikulum -</option>
                            @foreach($thn_kurikulum as $tk)
                            <option>{{$tk->versi}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mg-r-10">
                        <select class="form-control" name="semester" style="width:150px;">
                            <option value="">- Semester -</option>
                            @for($i=1;$i<=8;$i++)
                            <option>{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mg-r-10">
                        <select class="form-control" name="jenis" style="width:125px;">
                            <option value="">- Jenis -</option>
                            <option>Wajib</option>
                            <option>Pilihan</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-purple btn-block " style="color:white">Cari</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="widget-2">
        <div class="card shadow-base mb-3">
            <div class="card-header nm_jurusan">
                <h6 class="card-title">{{ setting('app_department_name') }}</h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table id="table_curriculum" class="table display responsive datatable" data-sort="asc" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center all defaultSort" width="50">#</th>
                            <th class="text-center all" width="200">Program Studi</th>
                            <th class="text-center all" width="100">Kode</th>
                            <th class="text-center all" width="600">Nama</th>
                            <th class="text-center all">Semester</th>
                            <th class="text-center all" width="200">Jenis</th>
                            <th class="text-center all">Sesuai<br>Kompetensi</th>
                            <th class="text-center none">Tahun Kurikulum</th>
                            <th class="text-center none">SKS Teori</th>
                            <th class="text-center none">SKS Seminar</th>
                            <th class="text-center none">SKS Praktikum</th>
                            <th class="text-center none">Capaian Pembelajaran</th>
                            <th class="text-center none">Dokumen Rencana Pembelajaran</th>
                            <th class="text-center none">Unit Penyelenggara</th>
                            <th class="text-center no-sort all" width="50">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($curriculum as $c)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$c->studyProgram->nama}}</td>
                                <td>{{$c->kd_matkul}}</td>
                                <td>{{$c->nama}}</td>
                                <td class="text-center">{{$c->semester}}</td>
                                <td class="text-center">{{$c->jenis}}</td>
                                <td class="text-center">
                                    @isset($c->kompetensi_prodi)
                                        <i class="fa fa-check"></i>
                                    @endisset
                                </td>
                                <td>{{$c->versi}}</td>
                                <td>{{$c->sks_teori}}</td>
                                <td>{{$c->sks_seminar}}</td>
                                <td>{{$c->sks_praktikum}}</td>
                                <td>{{ implode(', ',$c->capaian) }}</td>
                                <td>{{$c->dokumen_nama}}</td>
                                <td>{{$c->unit_penyelenggara}}</td>
                                <td class="text-center" width="50">
                                    <div class="btn-group" role="group">
                                        <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <div><span class="fa fa-caret-down"></span></div>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                            <a class="dropdown-item" href="{{ route('academic.curriculum.edit',encode_id($c->kd_matkul)) }}">Sunting</a>
                                            <form method="POST">
                                                <input type="hidden" value="{{encode_id($c->kd_matkul)}}" name="id">
                                                <button class="dropdown-item btn-delete" data-dest="{{ route('academic.curriculum.delete') }}">Hapus</button>
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
    </div>
</div>
@include('academic.curriculum.form-import')
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
