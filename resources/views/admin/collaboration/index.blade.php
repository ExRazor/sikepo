@extends('layouts.master')

@section('title', 'Data Kerja Sama Prodi')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

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
    <div class="widget-2">
        @foreach($studyProgram as $sp)
        <div class="card shadow-base overflow-hidden mb-3">
            <div class="card-header">
                <h6 class="card-title">Program Studi: {{$sp->nama}}</h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table class="table datatable display responsive nowrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tahun Akademik</th>
                            <th>Lembaga Mitra</th>
                            <th>Tingkat</th>
                            <th class="no-sort">Judul Kegiatan</th>
                            <th class="no-sort">Manfaat bagi PS yang Diakreditasi</th>
                            <th class="no-sort">Waktu</th>
                            <th class="no-sort">Durasi</th>
                            <th class="no-sort">Bukti Kerjasama</th>
                            <th class="no-sort">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collab[$sp->kd_prodi] as $d)
                        <tr>
                            <th scope="row" style="vertical-align:middle">{{$loop->iteration}}</td>
                            <td>{{$d->academicYear->tahun_akademik." - ".$d->academicYear->semester}}</td>
                            <td>{{$d->nama_lembaga}}</td>
                            <td class="text-capitalize">{{$d->tingkat}}</td>
                            <td>{{$d->judul_kegiatan}}</td>
                            <td>{{$d->manfaat_kegiatan}}</td>
                            <td>{{$d->waktu}}</td>
                            <td>{{$d->durasi}}</td>
                            <td style="text-align: center">
                                <a class="mg-r-5 mg-b-10" href="/download/collab/{{encrypt($d->bukti)}}" target="_blank"><div><i class="fa fa-download"></i></div></a>
                            </td>
                            <td width="50">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-teal dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item" href="/collaboration/{{encrypt($d->id)}}/edit">Sunting</a>
                                        <form method="POST">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" value="{{encrypt($d->id)}}" name="id">
                                            <a href="#" class="dropdown-item btn-delete" data-dest="/collaboration">Hapus</a>
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
        @endforeach
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
