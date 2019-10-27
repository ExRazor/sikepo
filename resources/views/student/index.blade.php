@extends('layouts.master')

@section('title', 'Data Mahasiswa')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('student') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon fa fa-user-graduate"></i>
    <div>
        <h4>Data Mahasiswa</h4>
        <p class="mg-b-0">Olah Data Mahasiswa</p>
    </div>
    <div class="ml-auto">
        <div class="row">
            <div class="col-6 pr-1">
                <a href="{{ route('student.add') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Mahasiswa</a>
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
    <div class="row">
        <div class="col-12">
            <form action="{{route('ajax.student.filter')}}" id="filter-student" method="POST">
                <div class="filter-box d-flex flex-row bd-highlight mg-b-10">
                    <div class="mg-r-10">
                        <select id="kd_jurusan" class="form-control" name="kd_jurusan">
                            <option value="">Semua Jurusan</option>
                            @foreach($faculty as $f)
                                @if($f->department->count())
                                <optgroup label="{{$f->nama}}">
                                    @foreach($f->department as $d)
                                    <option value="{{$d->kd_jurusan}}" {{ ($d->kd_jurusan == setting('app_department_id')) ? 'selected' : ''}}>{{$d->nama}}</option>
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
                    <div class="mg-r-10">
                        <select class="form-control" name="angkatan" style="width:200px">
                            <option value="">- Pilih Angkatan -</option>
                            @foreach($angkatan as $a)
                            <option value="{{$a->tahun_akademik}}">{{$a->tahun_akademik}}</option>
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
            <div class="card-header nm_jurusan">
                <h6 class="card-title">{{ setting('app_department_name') }}</h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table id="table_teacher" class="table display responsive nowrap datatable" data-sort="desc">
                    <thead>
                        <tr>
                            <th class="text-center">Nama / NIM</th>
                            <th class="text-center">Tanggal Lahir</th>
                            <th class="text-center">Program Studi</th>
                            <th class="text-center defaultSort">Angkatan</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Program</th>
                            <th class="text-center no-sort">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                        <tr>
                            <td><a href="{{ route('teacher.show',encode_id($d->nim)) }}">{{$d->nama}}<br><small>NIM. {{$d->nim}}</small></a></td>
                            <td>{{$d->tgl_lhr}}</td>
                            <td>
                                {{$d->studyProgram->nama}}
                            </td>
                            <td>{{$d->angkatan}}</td>
                            <td>{{$d->kelas}}</td>
                            <td class="text-center">{{$d->program}}</td>
                            <td class="text-center" width="50">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item" href="{{ route('student.edit',encode_id($d->nim)) }}">Sunting</a>
                                        <form method="POST">
                                            <input type="hidden" value="{{encode_id($d->nim)}}" name="id">
                                            <button class="dropdown-item btn-delete" data-dest="{{ route('student.delete') }}">Hapus</button>
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
