@extends('layouts.master')

@section('title', 'Data EWMP')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('profile-ewmp') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon fa fa-stopwatch"></i>
    <div>
        <h4>Ekuivalen Waktu Mengajar Dosen</h4>
        <p class="mg-b-0">Daftar EWMP Dosen</p>
    </div>
    <div class="ml-auto">
        <button class="btn btn-teal mg-b-10 btn-add text-white" data-toggle="modal" data-target="#modal-teach-ewmp"><i class="fa fa-plus mg-r-10"></i> Tambah</button>
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
            <div class="card-header nm_jurusan">
                <h6 class="card-title">
                    Ekuivalen Waktu Mengajar Penuh (EWMP)
                </h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table class="table table-bordered mb-0" id="ewmp">
                    <thead>
                        <tr>
                            <th rowspan="3" class="text-center align-middle">Tahun Akademik</th>
                            <th colspan="6" class="text-center align-middle">Ekuivalen Waktu Mengajar Penuh (EWMP)<br>dalam satuan kredit semester (sks)</th>
                            <th rowspan="3" class="text-center align-middle">Jumlah<br>(sks)</th>
                            <th rowspan="3" class="text-center align-middle">Rata-rata<br>(sks)</th>
                            <th rowspan="3" class="text-center align-middle">Aksi</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center align-middle" style="border-left-width: 1px;">Pendidikan</th>
                            <th rowspan="2" class="text-center align-middle" width="100">Penelitian</th>
                            <th rowspan="2" class="text-center align-middle">Pengabdian</th>
                            <th rowspan="2" class="text-center align-middle" width="100">Tugas Tambahan/<br>Penunjang</th>
                        </tr>
                        <tr>
                            <th class="text-center align-middle" style="border-left-width: 1px;" width="70">PS</th>
                            <th class="text-center align-middle" width="70">PS Luar</th>
                            <th class="text-center align-middle" width="70">Luar PT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ewmp as $e)
                        <tr>
                            <td class="text-center">{{ $e->academicYear->tahun_akademik.' - '.$e->academicYear->semester }}</td>
                            <td class="text-center">{{ $e->ps_intra }}</td>
                            <td class="text-center">{{ $e->ps_lain }}</td>
                            <td class="text-center">{{ $e->ps_luar }}</td>
                            <td class="text-center">{{ $e->penelitian }}</td>
                            <td class="text-center">{{ $e->pkm }}</td>
                            <td class="text-center">{{ $e->tugas_tambahan }}</td>
                            <td class="text-center">{{ $total = $e->ps_intra+$e->ps_lain+$e->ps_luar+$e->penelitian+$e->pkm+$e->tugas_tambahan}}</td>
                            <td class="text-center">{{ number_format($total/6, 1, ',', ' ') }}</td>
                            <td width="50">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <button class="dropdown-item btn-edit" data-id="{{encrypt($e->id)}}">Sunting</button>
                                        <form method="POST">
                                            <input type="hidden" value="{{encrypt($e->id)}}" name="_id">
                                            <button class="dropdown-item btn-delete" data-dest="{{ route('ajax.ewmp.delete') }}">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center align-middle">BELUM ADA DATA</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
        </div>
    </div>
</div>
@include('teacher-view.ewmp.form')
@endsection

