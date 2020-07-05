@extends('layouts.master')

@section('title', 'Prestasi Mahasiswa')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('student-achievement') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>

<div class="br-pagetitle">
    <div class="d-flex pl-0 mb-3">
        <i class="icon fa fa-medal"></i>
        <div>
            <h4>Prestasi Mahasiswa</h4>
            <p class="mg-b-0">Daftar Prestasi Mahasiswa</p>
        </div>
    </div>
    @if (!Auth::user()->hasRole('kajur'))
    <div class="ml-auto">
        <button class="btn btn-teal btn-block mg-b-10 btn-add" data-toggle="modal" data-target="#modal-student-acv" style="color:white"><i class="fa fa-plus mg-r-10"></i> Prestasi</button>
    </div>
    @endif
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
            <form action="{{route('ajax.student.achievement.filter')}}" id="filter-studentAcv" data-token="{{encode_id(Auth::user()->role)}}" method="POST">
                <div class="row">
                    <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
                        <input id="nm_jurusan" type="hidden" value="{{setting('app_department_name')}}">
                        <select class="form-control" name="kd_prodi">
                            <option value="">- Pilih Program Studi -</option>
                            @foreach($studyProgram as $sp)
                            <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
                        <div class="input-group">
                            <select class="form-control mr-3" name="prestasi_jenis">
                                <option value="">- Pilih Jenis Prestasi -</option>
                                <option value="Akademik">Akademik</option>
                                <option value="Non Akademik">Non Akademik</option>
                            </select>
                            <div>
                                <button type="submit" class="btn btn-purple btn-block " style="color:white">Cari</a>
                            </div>
                        </div>
                    </div>
                    <div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="widget-2">
        <div class="card shadow-base mb-3">
            <div class="card-header">
                <h6 class="card-title">
                    <span class="nm_jurusan">
                    @if(Auth::user()->hasRole('kaprodi'))
                    {{ Auth::user()->studyProgram->nama }}

                    @else
                    {{ setting('app_department_name') }}
                    @endif
                     </span>
                </h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table id="table-studentAcv" class="table table-bordered mb-0 datatable" data-sort="desc">
                    <thead>
                        <tr>
                            <th class="text-center align-middle defaultSort" width="100">Tahun Diperoleh</th>
                            <th class="text-center align-middle">Nama Mahasiswa</th>
                            <th class="text-center align-middle">Nama Kegiatan</th>
                            <th class="text-center align-middle">Tingkat Kegiatan</th>
                            <th class="text-center align-middle no-sort">Prestasi yang Diraih</th>
                            <th class="text-center align-middle no-sort">Jenis Prestasi</th>
                            @if (!Auth::user()->hasRole('kajur'))
                            <th class="text-center align-middle no-sort">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($achievement as $acv)
                        <tr>
                            <td class="text-center">
                                {{ $acv->academicYear->tahun_akademik.' - '.$acv->academicYear->semester }}
                            </td>
                            <td>
                                <a href="{{route('student.profile',encode_id($acv->nim))}}">
                                    {{ $acv->student->nama }}<br>
                                    <small>NIM.{{$acv->student->nim}} / {{$acv->student->studyProgram->singkatan}}</small>
                                </a>
                            </td>
                            <td>{{ $acv->kegiatan_nama }}</td>
                            <td>{{ $acv->kegiatan_tingkat }}</td>
                            <td>{{ $acv->prestasi }}</td>
                            <td>{{ $acv->prestasi_jenis }}</td>
                            @if (!Auth::user()->hasRole('kajur'))
                            <td width="50" class="text-center">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <button class="dropdown-item btn-edit" data-id="{{ encode_id($acv->id) }}">Sunting</button>
                                        <form method="POST">
                                            <input type="hidden" value="{{encode_id($acv->id)}}" name="_id">
                                            <button class="dropdown-item btn-delete" data-dest="{{ route('student.achievement.delete') }}">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            @endif
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
@if (!Auth::user()->hasRole('kajur'))
    @include('student.achievement.form')
@endif
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection

@section('custom-js')
@endsection
