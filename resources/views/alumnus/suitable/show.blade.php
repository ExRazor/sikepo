@extends('layouts.master')

@section('title', 'Bidang Kerja Lulusan - Prodi '.$studyProgram->singkatan)

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('alumnus-idle-show',$studyProgram) as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon fa fa-building"></i>
    <div>
        <h4>Program Studi: {{$studyProgram->nama}}</h4>
        <p class="mg-b-0">Rincian Bidang Kerja Lulusan</p>
    </div>
    <div class="ml-auto d-inline-flex">
        <button class="btn btn-teal btn-block mg-y-10 text-white btn-add" data-toggle="modal" data-target="#modal-alumnus-suitable">
            <i class="fa fa-plus mg-r-10"></i> Tambah
        </button>
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
        <div class="col-9">
            <table id="table-alumnusSuitable" class="table table-colored table-dark" >
                <thead>
                    <tr>
                        <th class="text-center align-middle" rowspan="2">Tahun Lulus</th>
                        <th class="text-center align-middle" rowspan="2">Jumlah Lulusan</th>
                        <th class="text-center align-middle" rowspan="2">Jumlah Lulusan<br>Terlacak</th>
                        <th class="text-center" colspan="3">Jumlah Lulusan dengan Tingkat Kesesuaian Bidang Kerja</th>
                        <th class="text-center align-middle" rowspan="2">Aksi</th>
                    </tr>
                    <tr>
                        <th class="text-center">Rendah</th>
                        <th class="text-center">Sedang</th>
                        <th class="text-center">Tinggi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $d)
                    <tr>
                        <td class="text-center">{{$d->tahun_lulus}}</td>
                        <td class="text-center">{{$d->jumlah_lulusan}}</td>
                        <td class="text-center">{{$d->lulusan_terlacak}}</td>
                        <td class="text-center">{{$d->sesuai_rendah}}</td>
                        <td class="text-center">{{$d->sesuai_sedang}}</td>
                        <td class="text-center">{{$d->sesuai_tinggi}}</td>
                        <td class="text-center">
                            <div class="btn-group hidden-xs-down">
                                <button class="btn btn-primary btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-edit" data-id="{{encrypt($d->id)}}">
                                    <div><i class="fa fa-pencil-alt"></i></div>
                                </button>
                                <form method="POST">
                                    <input type="hidden" value="{{encrypt($d->id)}}" name="id">
                                    <button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-delete" data-dest="{{ route('alumnus.suitable.delete') }}">
                                        <div><i class="fa fa-trash"></i></div>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="6">BELUM ADA DATA</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="col-3">
            <div class="alert alert-info">
                <h5>Tingkat Kesesuaian Bidang Kerja</h5>
                <hr>
                <ol class="pd-l-15">
                    <li><strong>Rendah</strong><br>
                        Jenis pekerjaan/posisi jabatan dalam pekerjaan tidak sesuai atau kurang sesuai dengan profil lulusan yang direncanakan dalam dokumen kurikulum
                    </li>
                    <li><strong>Sedang</strong><br>
                        Jenis pekerjaan/posisi jabatan dalam pekerjaan cukup sesuai dengan profil lulusan yang direncanakan dalam dokumen kurikulum
                    </li>
                    <li><strong>Tinggi</strong><br>
                        Jenis pekerjaan/posisi jabatan dalam pekerjaan sesuai atau sangat sesuai dengan profil lulusan yang direncanakan dalam dokumen kurikulum
                    </li>
                </ol>
            </div>
        </div>
    </div>

</div>
@include('alumnus.suitable.form')
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
