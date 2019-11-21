@extends('layouts.master')

@section('title', 'Data Luaran')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('publication') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon fa fa-boxes"></i>
    <div>
        <h4>Luaran Kegiatan</h4>
        <p class="mg-b-0">Olah Data Luaran Kegiatan DTPS</p>
    </div>
    <div class="ml-auto">
        <a href="{{ route('output-activity.add') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Luaran</a>
    </div>
</div>

<div class="br-pagebody">
    <div class="alert alert-warning" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong class="d-block d-sm-inline-block-force">Hati-Hati!</strong><br>
        Data kategori luaran yang disunting atau pun dihapus akan berdampak langung pada Data Luaran.<br>
        Jika kategori dihapus, maka data luaran yang berkaitan dengan kategori tersebut akan ikut terhapus.<br>
    </div>
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
            <form action="{{route('ajax.output-activity.filter')}}" id="filter-publication" method="POST">
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
        <div class="card shadow-base mb-3">
            <div class="card-header nm_jurusan">
                <h6 class="card-title">{{ setting('app_department_name') }}</h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table id="table_publication" class="table display responsive datatable" data-sort="desc" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="500">Judul Luaran</th>
                            <th class="text-center" width="150">Jenis Kegiatan</th>
                            <th class="text-center" width="150">Kategori</th>
                            <th class="text-center" width="300">Judul Luaran</th>
                            <th class="text-center defaultSort" width="75">Tahun</th>
                            <th class="text-center no-sort all" width="50">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($outputActivity as $activity)
                        <tr>
                            @if($activity->kegiatan=='Penelitian')
                            <td>
                                <a href="{{route('research.show',encode_id($activity->research->id))}}">
                                    {{$activity->research->judul_penelitian}}
                                </a>
                            </td>
                            @else
                            <td>
                                <a href="{{route('community-service.show',encode_id($activity->communityService->id))}}">
                                    {{$activity->communityService->judul_pengabdian}}
                                </a>
                            </td>
                            @endif
                            <td class="text-center">{{$activity->kegiatan}}</td>
                            <td class="text-center">
                                {{$activity->outputActivityCategory->nama}}
                            </td>
                            <td>{{$activity->judul_luaran}}</td>
                            <td class="text-center">{{$activity->tahun_luaran}}</td>
                            <td class="text-center" width="50">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item" href="{{ route('output-activity.edit',encode_id($activity->id)) }}">Sunting</a>
                                        <form method="POST">
                                            <input type="hidden" value="{{encode_id($activity->id)}}" name="id">
                                            <button class="dropdown-item btn-delete" data-dest="{{ route('output-activity.delete') }}">Hapus</button>
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
