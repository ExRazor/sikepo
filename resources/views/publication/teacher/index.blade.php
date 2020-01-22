@extends('layouts.master')

@section('title', 'Data Publikasi')

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
    <i class="icon fa fa-newspaper"></i>
    <div>
        <h4>Data Publikasi</h4>
        <p class="mg-b-0">Olah Data Publikasi</p>
    </div>
    <div class="ml-auto">
        <a href="{{ route('publication.teacher.add') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Publikasi</a>
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
            <form action="{{route('ajax.publication.teacher.filter')}}" id="filter-publication" method="POST">
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
                            <th class="text-center all" width="250">Nama Dosen</th>
                            <th class="text-center all" width="600">Judul Publikasi</th>
                            <th class="text-center all" width="250">Jenis Publikasi</th>
                            <th class="text-center defaultSort all" width="100">Tahun Terbit</th>
                            <th class="text-center all" width="150">Sesuai Bidang<br>Prodi</th>
                            <th class="text-center none" width="250">Penerbit</th>
                            <th class="text-center none">Nama Jurnal</th>
                            <th class="text-center none">Akreditasi</th>
                            <th class="text-center none">Jumlah Sitasi</th>
                            <th class="text-center none">Dosen Terlibat</th>
                            <th class="text-center none">Mahasiswa Terlibat</th>
                            <th class="text-center none">Tautan</th>
                            <th class="text-center no-sort all" width="50">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($publikasi as $p)
                        <tr>
                            <td>
                                {{ $p->teacher->nama }}<br>
                                <small>NIDN.{{ $p->teacher->nidn }} / {{ $p->teacher->studyProgram->singkatan }}</small>
                            </td>
                            <td>
                                <a href="{{route('publication.teacher.show',encode_id($p->id))}}">{{ $p->judul }}</a>
                            </td>
                            <td>{{ $p->publicationCategory->nama }}</td>
                            <td class="text-center">{{ $p->tahun }}</td>
                            <td class="text-center">
                                @isset($p->sesuai_prodi)
                                    <i class="fa fa-check"></i>
                                @endisset
                            </td>
                            <td>{{ $p->penerbit }}</td>
                            <td>{{ $p->jurnal }}</td>
                            <td>{{ $p->akreditasi }}</td>
                            <td>{{ $p->sitasi }}</td>
                            <td>
                                @if($p->publicationMembers->count())
                                <ol>
                                    @foreach ($p->publicationMembers as $ps)
                                    <li>{{$ps->nama}} - NIDN.{{$ps->nidn}} ({{$ps->studyProgram->department->nama }} - {{$ps->studyProgram->nama }})</li>
                                    @endforeach
                                </ol>
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                @if($p->publicationStudents->count())
                                <ol>
                                    @foreach ($p->publicationStudents as $ps)
                                    <li>{{$ps->nama}} - NIM.{{$ps->nim}} ({{$ps->studyProgram->department->nama }} - {{$ps->studyProgram->nama }})</li>
                                    @endforeach
                                </ol>
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                @isset($p->tautan)
                                    <a href="{{ $p->tautan }}" target="_blank"><div><i class="fa fa-download"></i></div></a>
                                @else
                                    -
                                @endisset
                            </td>
                            <td class="text-center" width="50">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item" href="{{ route('publication.teacher.edit',encode_id($p->id)) }}">Sunting</a>
                                        <form method="POST">
                                            <input type="hidden" value="{{encode_id($p->id)}}" name="id">
                                            <button class="dropdown-item btn-delete" data-dest="{{ route('publication.teacher.delete') }}">Hapus</button>
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
