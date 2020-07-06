@extends('layouts.master')

@section('title', 'Integrasi Kurikulum')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('academic-integration') as $breadcrumb)
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
        <i class="icon fa fa-link"></i>
        <div>
            <h4>Integrasi Kurikulum</h4>
            <p class="mg-b-0">Integrasi Kegiatan Penelitian/Pengabdian dalam Pembelajaran</p>
        </div>
    </div>
    @if (!Auth::user()->hasRole('kajur'))
    <div class="ml-auto">
        <a href="{{ route('academic.integration.add') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Integrasi</a>
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
    <div class="widget-2">
        <div class="card shadow-base mb-3">
            <div class="card-body bd-color-gray-lighter">
                <table id="table_publication" class="table display responsive datatable" data-sort="desc" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center defaultSort" width="90">Tahun Akademik</th>
                            <th class="text-center" width="300">Judul Kegiatan</th>
                            <th class="text-center none" width="150">Nama Dosen</th>
                            <th class="text-center none" width="300">Mata Kuliah</th>
                            <th class="text-center" width="125">Bentuk Integrasi</th>
                            @if (!Auth::user()->hasRole('kajur'))
                            <th class="text-center no-sort none" width="50">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($integration as $ci)
                        <tr>
                            <td class="text-center">{{ $ci->academicYear->tahun_akademik.' - '.$ci->academicYear->semester }}</td>
                            @if($ci->kegiatan=='Penelitian')
                            <td>
                                <a href="{{route('research.show',encode_id($ci->research->id))}}">
                                    {{$ci->research->judul_penelitian}}
                                </a>
                            </td>
                            @else
                            <td>
                                <a href="{{route('community-service.show',encode_id($ci->communityService->id))}}">
                                    {{$ci->communityService->judul_pengabdian}}
                                </a>
                            </td>
                            @endif
                            <td>
                                <a href="{{route('teacher.show',encode_id($ci->teacher->nip))}}#research">
                                    {{ $ci->teacher->nama }}<br>
                                    <small>NIDN.{{ $ci->teacher->nidn }} / {{ $ci->teacher->studyProgram->singkatan }}</small>
                                </a>
                            </td>
                            <td>{{$ci->curriculum->nama.' ('.$ci->curriculum->studyProgram->singkatan.')'}}</td>
                            <td>{{$ci->bentuk_integrasi}}</td>
                            @if (!Auth::user()->hasRole('kajur'))
                            <td class="text-center" width="50">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item" href="{{ route('academic.integration.edit',encrypt($ci->id)) }}">Sunting</a>
                                        <form method="POST">
                                            <input type="hidden" value="{{encrypt($ci->id)}}" name="id">
                                            <button class="dropdown-item btn-delete" data-dest="{{ route('academic.integration.delete') }}">Hapus</button>
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
