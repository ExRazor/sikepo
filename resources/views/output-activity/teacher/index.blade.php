@extends('layouts.master')

@section('title', 'Luaran Dosen')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('output-activity-teacher') as $breadcrumb)
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
        <p class="mg-b-0">Olah Data Luaran Kegiatan</p>
    </div>
    <div class="ml-auto">
        <a href="{{ route('output-activity.teacher.add') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Luaran</a>
    </div>
</div>

<div class="br-pagebody">
    <div class="alert alert-info">
        <h5>KATEGORI LUARAN</h5>
        <hr>
        @foreach($category as $c)
        <strong class="d-block d-sm-inline-block-force">{{$loop->iteration.'. '.$c->nama}}</strong><br>
        {{$c->deskripsi}}
        @if (!$loop->last)
            <br><br>
        @endif
        @endforeach
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
            <form action="{{route('ajax.output-activity.teacher.filter')}}" id="filter-outputActivity" data-type="teacher" method="POST">
                <input type="hidden" id="nm_jurusan" value="{{ setting('app_department_name') }}">
                <div class="filter-box d-flex flex-row bd-highlight mg-b-10">
                    <div class="mg-r-10">
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
                <table id="table_outputActivity" class="table display responsive datatable" data-sort="desc" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="500">Judul Luaran</th>
                            <th class="text-center" width="125">Jenis Luaran</th>
                            <th class="text-center" width="300">Kategori</th>
                            <th class="text-center defaultSort" width="150">Tahun</th>
                            <th class="text-center" width="125">Jenis Kegiatan</th>
                            <th class="text-center no-sort" width="50">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($outputActivity as $activity)
                        <tr>
                            <td>
                                <a href="{{route('output-activity.teacher.show',encode_id($activity->id))}}">
                                    {{$activity->judul_luaran}}
                                </a><br>
                                <small>
                                    {{$activity->teacher->nama.' ('.$activity->nidn.')'.' / '.$activity->teacher->studyProgram->singkatan}}
                                </small>
                            </td>
                            <td class="text-center">{{$activity->jenis_luaran}}</td>
                            <td class="text-center">{{$activity->outputActivityCategory->nama}}</td>
                            <td class="text-center">{{$activity->thn_luaran}}</td>
                            <td class="text-center">{{$activity->kegiatan}}</td>
                            <td class="text-center" width="50">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item" href="{{ route('output-activity.teacher.edit',encode_id($activity->id)) }}">Sunting</a>
                                        <form method="POST">
                                            <input type="hidden" value="{{encode_id($activity->id)}}" name="id">
                                            <button class="dropdown-item btn-delete" data-dest="{{ route('output-activity.teacher.delete') }}">Hapus</button>
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
