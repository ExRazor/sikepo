@extends('layouts.master')

@section('title', 'Data Dosen')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('academic-schedule') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon fa fa-chalkboard-teacher"></i>
    <div>
        <h4>Jadwal Mata Kuliah</h4>
        <p class="mg-b-0">Olah Data Jadwal Mata Kuliah</p>
    </div>
    <div class="ml-auto">
        <div class="row">
            <div class="col-6 pr-1">
                <a href="{{ route('teacher.add') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Data Dosen</a>
            </div>
            <div class="col-6 pl-1">
                <a href="{{ route('teacher.import') }}" class="btn btn-primary btn-block mg-b-10" style="color:white"><i class="fa fa-file-import mg-r-10"></i> Import Data</a>
            </div>
        </div>
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
            <div class="card-header">
                <h6 class="card-title"><span class="nm_jurusan">{{ setting('app_department_name') }}</span></h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table id="table_teacher" class="table display responsive nowrap" data-sort="asc">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center defaultSort">Nama Dosen</th>
                            <th class="text-center">Pendidikan Pasca Sarjana</th>
                            <th class="text-center">Bidang Keahlian</th>
                            <th class="text-center">Kesesuaian dengan Kompetensi Inti PS</th>
                            <th class="text-center">Jabatan Akademik</th>
                            <th class="text-center">Sertifikat Pendidik Profesional</th>
                            <th class="text-center">Mata Kuliah di PS</th>
                            <th class="text-center">Kesesuaian Matkul dengan PS</th>
                            <th class="text-center">Matkul di Luar PS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>
                                {{$d->nama}}<br>
                                <small>NIDN. {{$d->nidn}}</small>
                            </td>
                            <td>{{$d->pend_terakhir_jenjang}} - {{$d->pend_terakhir_jurusan}}</td>
                            <td>{{implode(', ',json_decode($d->bidang_ahli))}}</td>
                            <td>{!!$d->sesuai_bidang_ps=='Ya' ? '<i class="fa fa-check"></i>' : ''!!}</td>
                            <td>{{$d->jabatan_akademik}}</td>
                            <td>{{$d->sertifikat_pendidik}}</td>
                            <td>
                                @foreach($d->schedule as $schedule)
                                    @if($schedule->sesuai_prodi!=0)
                                        {{$schedule->curriculum->nama}}{{($loop->parent->last ? '' : ', ')}}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @if($d->schedule->count() > 0 && $d->schedule->first()->sesuai_bidang)
                                <i class="fa fa-check"></i>
                                @endif
                            </td>
                            <td>
                                @foreach($d->schedule as $schedule)
                                    @if($schedule->kd_matkul==null && $schedule->sesuai_prodi!=1)
                                        {{$schedule->nm_matkul}}{{$loop->last ? '' : ', '}}
                                    @endif
                                @endforeach
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
