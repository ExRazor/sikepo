@extends('layouts.master')

@section('title', 'Rincian Penelitian')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('research-show',$data) as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon fa fa-book-reader"></i>
    <div>
        <h4>Rincian Penelitian</h4>
        <p class="mg-b-0">Rincian data penelitian</p>
    </div>
    @if(!Auth::user()->hasRole('kajur'))
    <div class="row ml-auto" style="width:300px">
        <div class="col-6 pr-1">
            <form method="POST">
                <input type="hidden" value="{{encode_id($data->id)}}" name="id">
                <button class="btn btn-danger btn-block btn-delete" data-dest="{{ route('research.delete') }}" data-redir="{{ route('research') }}"><i class="fa fa-trash mg-r-10"></i> Hapus</button>
            </form>
        </div>
        <div class="col-6">
            <a href="{{ route('research.edit',encode_id($data->id)) }}" class="btn btn-warning btn-block" style="color:white"><i class="fa fa-pencil-alt mg-r-10"></i>Sunting</a>
        </div>
    </div>
    @endif
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
        <div class="card shadow-base mb-3">
            <div class="card-body bd-color-gray-lighter">
                <table id="table_teacher" class="table display responsive nowrap" data-sort="desc">
                    <tbody>
                        <tr>
                            <td>Judul Penelitian</td>
                            <td>:</td>
                            <td>{{$data->judul_penelitian}}</td>
                        </tr>
                        <tr>
                            <td>Tema Penelitian</td>
                            <td>:</td>
                            <td>{{$data->tema_penelitian}}</td>
                        </tr>
                        <tr>
                            <td>Bidang Program Studi</td>
                            <td>:</td>
                            <td>{{isset($data->sesuai_prodi) ? 'Sesuai' : 'Tidak Sesuai'}}</td>
                        </tr>
                        <tr>
                            <td>Jumlah SKS Penelitian</td>
                            <td>:</td>
                            <td>{{$data->sks_penelitian}}</td>
                        </tr>
                        <tr>
                            <td>Tahun Penelitian</td>
                            <td>:</td>
                            <td>{{$data->academicYear->tahun_akademik.' - '.$data->academicYear->semester}}</td>
                        </tr>
                        <tr>
                            <td>Sumber Biaya Penelitian</td>
                            <td>:</td>
                            <td>{{$data->sumber_biaya}}</td>
                        </tr>
                        <tr>
                            <td>Nama Lembaga Penunjang Biaya</td>
                            <td>:</td>
                            <td>{{isset($data->sumber_biaya_nama) ? $data->sumber_biaya_nama : ''}}</td>
                        </tr>
                        <tr>
                            <td>Jumlah Biaya Penelitian</td>
                            <td>:</td>
                            <td>{{rupiah($data->jumlah_biaya)}}</td>
                        </tr>
                        <tr>
                            <td>Dosen yang Terlibat</td>
                            <td>:</td>
                            <td>
                                <table class="table table-bordered table-colored table-info">
                                    <thead class="text-center">
                                        <tr>
                                            <td>Nama Dosen</td>
                                            <td>Asal/Program Studi</td>
                                            <td>Status Anggota</td>
                                            <td>SKS</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data->researchTeacher as $rt)
                                        <tr>
                                            @if(!$rt->nama_lain)
                                            <td>
                                                <a href="{{route('teacher.show',encode_id($rt->teacher->nidn))}}">
                                                    {{$rt->teacher->nama}}<br>
                                                    <small>NIDN. {{$rt->teacher->nidn}}</small>
                                                </a>
                                            </td>
                                            <td>
                                                {{$rt->teacher->studyProgram->nama}}<br>
                                                <small>{{$rt->teacher->studyProgram->department->nama.' / '.$rt->teacher->studyProgram->department->faculty->singkatan}}</small>
                                            </td>
                                            @else
                                            <td>
                                                {{$rt->nama_lain}}<br>
                                                <small>NIDN. {{$rt->nidn}}</small>
                                            </td>
                                            <td>
                                                {{$rt->asal_lain}}<br>
                                            </td>
                                            @endif
                                            <td class="text-center">
                                                {{$rt->status}}
                                            </td>
                                            <td class="text-center">
                                                {{$rt->sks}}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td class="text-center" colspan="4">
                                                BELUM ADA DATA
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>Mahasiswa yang Terlibat</td>
                            <td>:</td>
                            <td>
                                <table class="table table-bordered table-colored table-danger">
                                    <thead class="text-center">
                                        <tr>
                                            <td>Nama Mahasiswa</td>
                                            <td>Asal/Program Studi</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data->researchStudent as $rs)
                                        <tr>
                                            @if(!$rs->nama_lain)
                                            <td>
                                                <a href="{{route('student.profile',encode_id($rs->student->nim))}}">
                                                    {{$rs->student->nama}}<br>
                                                    <small>NIM. {{$rs->student->nim}}</small>
                                                </a>
                                            </td>
                                            <td>
                                                {{$rs->student->studyProgram->nama}}<br>
                                                <small>{{$rs->student->studyProgram->department->nama.' / '.$rs->student->studyProgram->department->faculty->singkatan}}</small>
                                            </td>
                                            @else
                                            <td>
                                                {{$rs->nama_lain}}<br>
                                                <small>NIM. {{$rs->nim}}</small>
                                            </td>
                                            <td>
                                                {{$rs->asal_lain}}<br>
                                            </td>
                                            @endif
                                        </tr>
                                        @empty
                                        <tr>
                                            <td class="text-center" colspan="2">BELUM ADA DATA</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </td>
                        </tr>
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
