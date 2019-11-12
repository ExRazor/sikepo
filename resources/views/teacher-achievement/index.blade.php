@extends('layouts.master')

@section('title', 'Data Prestasi Dosen')

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
    <i class="icon fa fa-medal"></i>
    <div>
        <h4>Prestasi Dosen</h4>
        <p class="mg-b-0">Daftar Prestasi Dosen di Jurusan {{setting('app_department_name')}}</p>
    </div>
    <div class="ml-auto">
        <button class="btn btn-teal btn-block mg-b-10 btn-add" data-toggle="modal" data-target="#modal-teach-acv" style="color:white"><i class="fa fa-plus mg-r-10"></i> Prestasi Dosen</button>
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
    <div class="row">
        <div class="col-12">
            <form action="{{route('ajax.teacherAcv.filter')}}" id="filter-teacherAcv" method="POST">
                <div class="filter-box d-flex flex-row bd-highlight mg-b-10">
                    <div class="mg-r-10">
                        <input id="nm_jurusan" type="hidden" value="{{setting('app_department_name')}}">
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
    <div class="widget-2">
        <div class="card shadow-base mb-3">
            <div class="card-header">
                <h6 class="card-title">
                    Prestasi/Pengakuan yang Diraih Dosen
                </h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table id="table_teacherAcv" class="table table-bordered mb-0 datatable" data-sort="desc">
                    <thead>
                        <tr>
                            <th class="text-center align-middle defaultSort" width="100">Tanggal Diperoleh</th>
                            <th class="text-center align-middle">Nama Dosen</th>
                            <th class="text-center align-middle">Prestasi</th>
                            <th class="text-center align-middle">Tingkat</th>
                            <th class="text-center align-middle no-sort">Bukti<br>Pendukung</th>
                            <th class="text-center align-middle no-sort">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($achievement as $acv)
                        <tr>
                            <td class="text-center">{{ $acv->tanggal }}</td>
                            <td>
                                {{ $acv->teacher->nama }}<br>
                                <small>NIDN.{{$acv->teacher->nidn}} / {{$acv->teacher->studyProgram->singkatan}}</small>
                            </td>
                            <td>{{ $acv->prestasi }}</td>
                            <td>{{ $acv->tingkat_prestasi }}</td>
                            <td class="text-center align-middle">
                                <a href="{{route('teacher.achievement.download',encode_id($acv->bukti_pendukung))}}" target="_blank"><div><i class="fa fa-download"></i></div></a>
                            </td>
                            <td width="50" class="text-center">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item btn-edit" href="#" data-id="{{ encode_id($acv->id) }}">Sunting</a>
                                        <form method="POST">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" value="{{encode_id($acv->id)}}" name="_id">
                                            <a href="#" class="dropdown-item btn-delete" data-dest="{{ route('teacher.achievement.delete') }}">Hapus</a>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan=5 class="text-center align-middle">BELUM ADA DATA</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div><!-- card-body -->
        </div>
    </div>
</div>
@include('teacher-achievement.form')
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection

@section('custom-js')
@endsection
