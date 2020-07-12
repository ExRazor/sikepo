@extends('layouts.master')

@section('title', 'Rincian Data Publikasi')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('profile-publication-show',$data) as $breadcrumb)
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
        <i class="icon fa fa-newspaper"></i>
        <div>
            <h4>Rincian Publikasi</h4>
            <p class="mg-b-0">Rincian data publikasi dosen</p>
        </div>
    </div>
    <div class="row ml-auto" style="width:300px">
        <div class="col-6 pr-1">
            <form method="POST">
                <input type="hidden" value="{{encode_id($data->id)}}" name="id">
                <button class="btn btn-danger btn-block btn-delete" data-dest="{{ route('profile.publication.delete') }}" data-redir="{{ route('profile.publication') }}"><i class="fa fa-trash mg-r-10"></i> Hapus</button>
            </form>
        </div>
        <div class="col-6">
            <a href="{{ route('profile.publication.edit',encode_id($data->id)) }}" class="btn btn-warning btn-block" style="color:white"><i class="fa fa-pencil-alt mg-r-10"></i>Sunting</a>
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
                <table class="table display responsive nowrap">
                    <tbody>
                        <tr>
                            <td width="350px">Judul Publikasi</td>
                            <td width="20px">:</td>
                            <td>{{$data->judul}}</td>
                        </tr>
                        <tr>
                            <td>Jenis Publikasi</td>
                            <td>:</td>
                            <td>{{$data->publicationCategory->nama}}</td>
                        </tr>
                        <tr>
                            <td>Penulis Utama</td>
                            <td>:</td>
                            <td>
                                {{$data->teacher->nama}} / NIDN. {{$data->nidn}}
                            </td>
                        </tr>
                        <tr>
                            <td>Penulis Lain</td>
                            <td>:</td>
                            <td>
                                <table class="table table-bordered table-colored table-info">
                                    <thead class="text-center">
                                        <tr>
                                            <td>Nama</td>
                                            <td>Asal Program Studi</td>
                                            <td>Status</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($data->publicationMembers->count() || $data->publicationStudents->count() )
                                            @foreach($data->publicationMembers as $pm)
                                            <tr>
                                                <td>
                                                    {{$pm->nama}}<br>
                                                    <small>NIDN. {{$pm->nidn}}</small>
                                                </td>
                                                <td>
                                                    {{$pm->studyProgram->nama}}<br>
                                                    <small>{{$pm->studyProgram->department->nama.' / '.$pm->studyProgram->department->faculty->singkatan}}</small>
                                                </td>
                                                <td class="text-center">
                                                    Dosen
                                                </td>
                                            </tr>
                                            @endforeach
                                            @foreach($data->publicationStudents as $ps)
                                            <tr>
                                                <td>
                                                    {{$ps->nama}}<br>
                                                    <small>NIM. {{$ps->nim}}</small>
                                                </td>
                                                <td>
                                                    {{$ps->studyProgram->nama}}<br>
                                                    <small>{{$ps->studyProgram->department->nama.' / '.$ps->studyProgram->department->faculty->singkatan}}</small>
                                                </td>
                                                <td class="text-center">
                                                    Mahasiswa
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-center">DATA KOSONG</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>Bidang Program Studi</td>
                            <td>:</td>
                            <td>{{isset($data->sesuai_prodi) ? 'Sesuai' : 'Tidak Sesuai'}}</td>
                        </tr>
                        <tr>
                            <td>Tahun Terbit</td>
                            <td>:</td>
                            <td>{{$data->tahun}}</td>
                        </tr>
                        <tr>
                            <td>Penerbit</td>
                            <td>:</td>
                            <td>{{$data->penerbit}}</td>
                        </tr>
                        <tr>
                            <td>Nama Terbitan</td>
                            <td>:</td>
                            <td>{{$data->jurnal}}</td>
                        </tr>
                        <tr>
                            <td>Akreditasi</td>
                            <td>:</td>
                            <td>{{$data->akreditasi}}</td>
                        </tr>
                        <tr>
                            <td>Jumlah Sitasi</td>
                            <td>:</td>
                            <td>{{$data->sitasi}}</td>
                        </tr>
                        <tr>
                            <td>Tautan Jurnal</td>
                            <td>:</td>
                            <td><a href="{{$data->tautan}}">{{$data->tautan}}</a></td>
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
