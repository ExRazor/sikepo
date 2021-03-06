@extends('layouts.master')

@section('title', 'Data Penelitian')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('profile-research') as $breadcrumb)
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
        <i class="icon fa fa-book-reader"></i>
        <div>
            <h4>Data Penelitian</h4>
            <p class="mg-b-0">Olah Data Penelitian</p>
        </div>
    </div>
    <div class="ml-auto">
        <a href="{{ route('profile.research.create') }}" class="btn btn-teal btn-block mg-b-10 text-white"><i class="fa fa-plus mg-r-10"></i> Penelitian</a>
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
                    Penelitian yang Diketuai
                </h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table id="table_research" class="table display responsive datatable" data-sort="desc" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="600">Judul Penelitian</th>
                            <th class="text-center defaultSort" width="100">Tahun Penelitian</th>
                            <th class="text-center" width="150">Sesuai Bidang<br>Prodi</th>
                            <th class="text-center no-sort" width="50">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penelitianKetua as $p)
                        <tr>
                            <td>
                                <a href="{{route('profile.research.show',encrypt($p->id))}}">
                                    {{ $p->judul_penelitian }}
                                </a>
                            </td>
                            <td class="text-center">{{ $p->academicYear->tahun_akademik.' - '.$p->academicYear->semester }}</td>
                            <td class="text-center">
                                @isset($p->sesuai_prodi)
                                    <i class="fa fa-check"></i>
                                @endisset
                            </td>
                            <td class="text-center" width="50">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item" href="{{ route('profile.research.edit',encrypt($p->id)) }}">Sunting</a>
                                        <form method="POST">
                                            <input type="hidden" value="{{encrypt($p->id)}}" name="id">
                                            <button class="dropdown-item btn-delete" data-dest="{{ route('profile.research.delete') }}">Hapus</button>
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
    <div class="widget-2">
        <div class="card shadow-base mb-3">
            <div class="card-header nm_jurusan">
                <h6 class="card-title">
                    Penelitian yang Dianggotai
                </h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table id="table_research" class="table display responsive datatable" data-sort="desc" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="600">Judul Penelitian</th>
                            <th class="text-center defaultSort" width="100">Tahun Penelitian</th>
                            <th class="text-center" width="150">Sesuai Bidang<br>Prodi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penelitianAnggota as $p)
                        <tr>
                            <td>
                                <a href="{{route('profile.research.show',encrypt($p->id))}}">
                                    {{ $p->judul_penelitian }}
                                </a>
                            </td>
                            <td class="text-center">{{ $p->academicYear->tahun_akademik.' - '.$p->academicYear->semester }}</td>
                            <td class="text-center">
                                @isset($p->sesuai_prodi)
                                    <i class="fa fa-check"></i>
                                @endisset
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
