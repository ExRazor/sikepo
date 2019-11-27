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
            @isset($breadcrumb->url)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endisset
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon ion-calendar"></i>
    <div>
        <h4>Daftar Kerja Sama</h4>
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
    <div class="row">
        <div class="col-12">
            <form action="{{route('ajax.collaboration.filter')}}" id="filter-collaboration" method="POST">
                <div class="filter-box d-flex flex-row bd-highlight mg-b-10">
                    <div class="mg-r-10">
                        <input id="nm_jurusan" type="hidden" value="{{setting('app_department_name')}}">
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
        <div class="card shadow-base overflow-hidden mb-3">
            <div class="card-header">
                <h6 class="card-title">Teknik Informatika</h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table id="table_collaboration" class="table datatable display responsive" data-sort="desc">
                    <thead>
                        <tr>
                            <th class="text-center all" width="150">Tahun Akademik</th>
                            <th class="text-center all" width="250">Program Studi</th>
                            <th class="text-center all">Lembaga Mitra</th>
                            <th class="text-center all" width="150">Tingkat</th>
                            <th class="text-center none">Judul Kegiatan</th>
                            <th class="text-center no-sort none">Manfaat</th>
                            <th class="text-center defaultSort all" width="100">Waktu</th>
                            <th class="text-center no-sort none">Durasi</th>
                            <th class="text-center no-sort all">Bukti Kerjasama</th>
                            <th class="text-center no-sort all">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collab as $d)
                        <tr>
                            <td>{{$d->academicYear->tahun_akademik." - ".$d->academicYear->semester}}</td>
                            <td>{{$d->studyProgram->nama}}</td>
                            <td>{{$d->nama_lembaga}}</td>
                            <td class="text-capitalize">{{$d->tingkat}}</td>
                            <td>{{$d->judul_kegiatan}}</td>
                            <td>{{$d->manfaat_kegiatan}}</td>
                            <td>{{$d->waktu}}</td>
                            <td>{{$d->durasi}}</td>
                            <td class="text-center" width="75">
                                <a href="{{ route('collaboration.download',encode_id($d->bukti)) }}" target="_blank">
                                    {{$d->bukti_nama}}
                                </a>
                            </td>
                            <td class="text-center" width="50">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item" href="{{ route('collaboration.edit',encode_id($d->id)) }}">Sunting</a>
                                        <form method="POST">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" value="{{encode_id($d->id)}}" name="id">
                                            <a href="#" class="dropdown-item btn-delete" data-dest={{ route('collaboration.delete') }}>Hapus</a>
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
