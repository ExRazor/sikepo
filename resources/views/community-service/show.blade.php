@extends('layouts.master')

@section('title', 'Rincian Pengabdian')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('community-service-show',$data) as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon fa fa-balance-scale"></i>
    <div>
        <h4>Rincian Pengabdian</h4>
        <p class="mg-b-0">Rincian data pengabdian</p>
    </div>
    <div class="row ml-auto" style="width:300px">
        <div class="col-6 pr-1">
            <form method="POST">
                <input type="hidden" value="{{encode_id($data->id)}}" name="id">
                <button class="btn btn-danger btn-block btn-delete" data-dest="{{ route('community-service.delete') }}" data-redir="{{ route('community-service') }}"><i class="fa fa-trash mg-r-10"></i> Hapus</button>
            </form>
        </div>
        <div class="col-6">
            <a href="{{ route('community-service.edit',encode_id($data->id)) }}" class="btn btn-warning btn-block" style="color:white"><i class="fa fa-pencil-alt mg-r-10"></i>Sunting</a>
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
        <div class="card shadow-base mb-3">
            <div class="card-body bd-color-gray-lighter">
                <table id="table_teacher" class="table display responsive nowrap" data-sort="desc">
                    <tbody>
                        <tr>
                            <td>Judul Pengabdian</td>
                            <td>:</td>
                            <td>{{$data->judul_pengabdian}}</td>
                        </tr>
                        <tr>
                            <td>Tema Pengabdian</td>
                            <td>:</td>
                            <td>{{$data->tema_pengabdian}}</td>
                        </tr>
                        <tr>
                            <td>Jumlah SKS Pengabdian</td>
                            <td>:</td>
                            <td>{{$data->sks_pengabdian}}</td>
                        </tr>
                        <tr>
                            <td>Tahun Pengabdian</td>
                            <td>:</td>
                            <td>{{$data->academicYear->tahun_akademik.' - '.$data->academicYear->semester}}</td>
                        </tr>
                        <tr>
                            <td>Sumber Biaya Pengabdian</td>
                            <td>:</td>
                            <td>{{$data->sumber_biaya}}</td>
                        </tr>
                        <tr>
                            <td>Nama Lembaga Penunjang Biaya</td>
                            <td>:</td>
                            <td>{{isset($data->sumber_biaya_nama) ? $data->sumber_biaya_nama : ''}}</td>
                        </tr>
                        <tr>
                            <td>Jumlah Biaya Pengabdian</td>
                            <td>:</td>
                            <td>{{rupiah($data->jumlah_biaya)}}</td>
                        </tr>
                        <tr>
                            <td>Dosen yang Terlibat</td>
                            <td>:</td>
                            <td>
                                <table class="table table-bordered table-colored table-purple">
                                    <thead class="text-center">
                                        <tr>
                                            <td>Nama Dosen</td>
                                            <td>Asal Program Studi</td>
                                            <td>Status Anggota</td>
                                            <td>SKS</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data->serviceTeacher as $st)
                                        <tr>
                                            <td>
                                                <a href="{{route('teacher.profile',encode_id($st->teacher->nip))}}">
                                                    {{$st->teacher->nama}}<br>
                                                    <small>NIDN. {{$st->nidn}}</small>
                                                </a>
                                            </td>
                                            <td>
                                                {{$st->teacher->studyProgram->nama}}<br>
                                                <small>{{$st->teacher->studyProgram->department->nama.' / '.$st->teacher->studyProgram->department->faculty->singkatan}}</small>
                                            </td>
                                            <td class="text-center">
                                                {{$st->status}}
                                            </td>
                                            <td class="text-center">
                                                {{$st->sks}}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>Dosen yang Terlibat</td>
                            <td>:</td>
                            <td>
                                <table class="table table-bordered table-colored table-pink">
                                    <thead class="text-center">
                                        <tr>
                                            <td>Nama Mahasiswa</td>
                                            <td>Asal Program Studi</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data->serviceStudent as $ss)
                                        <tr>
                                            <td>
                                                <a href="{{route('student.profile',encode_id($ss->student->nim))}}">
                                                    {{$ss->student->nama}}<br>
                                                    <small>NIDN. {{$ss->nim}}</small>
                                                </a>
                                            </td>
                                            <td>
                                                {{$ss->student->studyProgram->nama}}<br>
                                                <small>{{$ss->student->studyProgram->department->nama.' / '.$ss->student->studyProgram->department->faculty->singkatan}}</small>
                                            </td>
                                        </tr>
                                        @endforeach
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
