@extends('layouts.master')

@section('title', 'Data Kerja Sama Prodi')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('collaboration') as $breadcrumb)
        <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon ion-calendar"></i>
    <div>
        <h4>Kerja Sama Program Studi</h4>
        <p class="mg-b-0">Olah Data Kerja Sama</p>
    </div>
    <div class="ml-auto">
        <a href="{{ route('collaboration.add') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Kerja Sama</a>
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
    <div class="card bd-0">
        <div class="card-body bd bd-t-0 rounded-bottom">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Prodi</th>
                        <th>Tahun Akademik</th>
                        <th>Lembaga Mitra</th>
                        <th>Tingkat</th>
                        <th>Judul Kegiatan</th>
                        <th>Manfaat bagi PS yang Diakreditasi</th>
                        <th>Waktu</th>
                        <th>Durasi</th>
                        <th>Bukti Kerjasama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="table_studyProgram">
                    @foreach ($data as $d)
                    <tr>
                        <th scope="row" style="vertical-align:middle">{{$loop->iteration}}</td>
                        <td>{{$d->studyProgram->nama}}</td>
                        <td>{{$d->academicYear->tahun_akademik." - ".$d->academicYear->semester}}</td>
                        <td>{{$d->nama_lembaga}}</td>
                        <td class="text-capitalize">{{$d->tingkat}}</td>
                        <td>{{$d->judul_kegiatan}}</td>
                        <td>{{$d->manfaat_kegiatan}}</td>
                        <td>{{$d->waktu}}</td>
                        <td>{{$d->durasi}}</td>
                        <td style="text-align: center">
                            <a class="mg-r-5 mg-b-10" href="/download/collab/{{encrypt($d->bukti)}}" ><div><i class="fa fa-download"></i></div></a>
                        </td>
                        <td width="50">
                            <div class="btn-group" role="group">
                                <button id="btn-action" type="button" class="btn btn-teal dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div><span class="fa fa-caret-down"></span></div>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                    <a class="dropdown-item" href="#">Sunting</a>
                                    <a class="dropdown-item" href="#">Hapus</a>
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
@endsection

@section('js')
@endsection
