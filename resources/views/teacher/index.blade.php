@extends('layouts.master')

@section('title', 'Data Dosen')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('teacher') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon fa fa-chalkboard-teacher"></i>
    <div>
        <h4>Dosen Program Studi</h4>
        <p class="mg-b-0">Olah Data Dosen</p>
    </div>
    <div class="ml-auto d-inline-flex">
        <a href="{{ route('teacher.add') }}" class="btn btn-teal btn-block mg-y-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Data Dosen</a>
        {{-- <a href="{{ route('teacher.import') }}" class="btn btn-primary btn-block mg-y-10" style="color:white"><i class="fa fa-file-import mg-r-10"></i> Import Data</a> --}}
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
            <form action="{{route('ajax.teacher.filter')}}" id="filter-teacher" method="POST">
                <div class="filter-box d-flex flex-row bd-highlight mg-b-10">
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
    <div class="widget-2">
        <div class="card shadow-base mb-3">
            <div class="card-header">
                <h6 class="card-title"><span class="nm_jurusan">{{ setting('app_department_name') }}</span></h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table id="table_teacher" class="table display responsive nowrap datatable" data-sort="asc">
                    <thead>
                        <tr>
                            <th class="text-center">NIDN</th>
                            <th class="text-center defaultSort">Nama</th>
                            <th class="text-center">Program Studi</th>
                            <th class="text-center">Ikatan Kerja</th>
                            <th class="text-center">Jabatan<br>Akademik</th>
                            <th class="text-center no-sort">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                        <tr>
                            <td><a href="{{ route('teacher.profile',encode_id($d->nidn)) }}">{{$d->nidn}}</a></td>
                            <td>
                                {{$d->nama}}<br>
                                <small>NIP. {{$d->nip}}</small>
                            </td>
                            <td>
                                {{$d->studyProgram->nama}}
                            </td>
                            <td>{{$d->ikatan_kerja}}</td>
                            <td>{{$d->jabatan_akademik}}</td>
                            <td class="text-center" width="50">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item" href="{{ route('teacher.edit',encode_id($d->nidn)) }}">Sunting</a>
                                        <form method="POST">
                                            <input type="hidden" value="{{encode_id($d->nidn)}}" name="id">
                                            <button class="dropdown-item btn-delete" data-dest="{{ route('teacher.delete') }}">Hapus</button>
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
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
