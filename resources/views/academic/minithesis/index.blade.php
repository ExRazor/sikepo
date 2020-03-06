@extends('layouts.master')

@section('title', 'Tugas Akhir')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('academic-minithesis') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon fa fa-journal-whills"></i>
    <div>
        <h4>Tugas Akhir</h4>
        <p class="mg-b-0">Olah Data Tugas Akhir atau Skripsi Mahasiswa</p>
    </div>
    @if (!Auth::user()->hasRole('kajur'))
    <div class="ml-auto">
        <a href="{{ route('academic.minithesis.add') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Tugas Akhir</a>
    </div>
    @endif
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
            <form action="{{route('ajax.minithesis.filter')}}" id="filter-minithesis" data-token="{{encode_id(Auth::user()->role)}}" method="POST">
                <div class="filter-box d-flex flex-row bd-highlight mg-b-10">
                    @if(!Auth::user()->hasRole('kaprodi'))
                    <div class="mg-r-10">
                        <select class="form-control" name="prodi_mahasiswa">
                            <option value="">- Prodi Mahasiswa -</option>
                            @foreach($studyProgram as $sp)
                            <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="mg-r-10">
                        <select class="form-control" name="prodi_pembimbing">
                            <option value="">- Prodi Pembimbing Utama -</option>
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
                <table id="table-minithesis" class="table display responsive datatable" data-sort="desc" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="500">Judul Tugas Akhir</th>
                            <th class="text-center" width="300">Nama Mahasiswa</th>
                            <th class="text-center defaultSort" width="150">Tahun Diangkat</th>
                            <th class="text-center none">Pembimbing Utama</th>
                            <th class="text-center none">Pembimbing Pendamping</th>
                            @if (!Auth::user()->hasRole('kajur'))
                            <th class="text-center no-sort all" width="50">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($minithesis as $m)
                        <tr>
                            <td>
                                {{$m->judul}}
                            </td>
                            <td>
                                <a href="{{route('student.profile',encode_id($m->student->nim))}}">
                                    {{$m->student->nama}}<br>
                                    <small>NIM. {{$m->nim}} / {{$m->student->studyProgram->singkatan}}</small>
                                </a>
                            </td>
                            <td class="text-center">{{$m->academicYear->tahun_akademik.' - '.$m->academicYear->semester}}</td>
                            <td>
                                <a href="{{route('teacher.show',encode_id($m->pembimbingUtama->nidn))}}">
                                    {{$m->pembimbingUtama->nama}} ({{$m->pembimbingUtama->nidn}})
                                </a>
                            </td>
                            <td>
                                <a href="{{route('teacher.show',encode_id($m->pembimbingPendamping->nidn))}}">
                                    {{$m->pembimbingPendamping->nama}} ({{$m->pembimbingPendamping->nidn}})
                                </a>
                            </td>
                            @if (!Auth::user()->hasRole('kajur'))
                            <td class="text-center" width="50">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item" href="{{ route('academic.minithesis.edit',encode_id($m->id)) }}">Sunting</a>
                                        <form method="POST">
                                            <input type="hidden" value="{{encode_id($m->id)}}" name="id">
                                            <button class="dropdown-item btn-delete" data-dest="{{ route('academic.minithesis.delete') }}">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            @endif
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
