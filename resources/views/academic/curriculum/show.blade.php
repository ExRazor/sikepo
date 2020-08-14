@extends('layouts.master')

@section('title', 'Rincian Kurikulum')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('academic-curriculum-show',$data) as $breadcrumb)
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
            <h4>Rincian Kurikulum</h4>
            <p class="mg-b-0">Rincian data kurikulum</p>
        </div>
    </div>
    @if(!Auth::user()->hasRole('kajur'))
    <div class="row ml-auto">
        <div class="col-6 pr-1">
            <form method="POST">
                <input type="hidden" value="{{encrypt($data->id)}}" name="id">
                <button class="btn btn-danger btn-block btn-delete" data-dest="{{ route('academic.curriculum.destroy',$data->id) }}" data-redir="{{ route('academic.curriculum.index') }}"><i class="fa fa-trash mg-r-10"></i> Hapus</button>
            </form>
        </div>
        <div class="col-6 pl-1">
            <a href="{{ route('academic.curriculum.edit',$data->id) }}" class="btn btn-warning btn-block text-white"><i class="fa fa-pencil-alt mg-r-10"></i>Sunting</a>
        </div>
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
        <div class="col-lg-8">
            <div class="widget-2">
                <div class="card shadow-base mb-3">
                    <div class="card-body bd-color-gray-lighter">
                        <table class="table table-show display">
                            <tbody>
                                <tr>
                                    <th width="225">Kode Mata Kuliah</th>
                                    <td width="1">:</td>
                                    <td>{{$data->kd_matkul}}</td>
                                </tr>
                                <tr>
                                    <th>Nama Mata Kuliah</th>
                                    <td>:</td>
                                    <td>{{$data->nama}}</td>
                                </tr>
                                <tr>
                                    <th>Program Studi</th>
                                    <td>:</td>
                                    <td>{{$data->studyProgram->nama}}</td>
                                </tr>
                                <tr>
                                    <th>Semester</th>
                                    <td>:</td>
                                    <td>{{$data->semester}}</td>
                                </tr>
                                <tr>
                                    <th>Jenis</th>
                                    <td>:</td>
                                    <td>{{$data->jenis}}</td>
                                </tr>
                                <tr>
                                    <th>Sesuai Kompetensi</th>
                                    <td>:</td>
                                    <td>{{isset($data->kompetensi_prodi) ? 'Sesuai' : 'Tidak Sesuai'}}</td>
                                </tr>
                                <tr>
                                    <th>Tahun Kurikulum</th>
                                    <td>:</td>
                                    <td>{{$data->versi}}</td>
                                </tr>
                                <tr>
                                    <th class="align-middle">SKS Mata Kuliah</th>
                                    <td>:</td>
                                    <td>
                                        <ul class="px-sm-0 my-sm-0">
                                            <li>SKS Teori : {{$data->sks_teori}}</li>
                                            <li>SKS Seminar : {{$data->sks_seminar}}</li>
                                            <li>SKS Praktikum : {{$data->sks_praktikum}}</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Capaian Pembelajaran</th>
                                    <td>:</td>
                                    <td>{{implode(', ',$data->capaian)}}</td>
                                </tr>
                                <tr>
                                    <th>Dokumen Rencana Pembelajaran</th>
                                    <td>:</td>
                                    <td>{{$data->dokumen_nama}}</td>
                                </tr>
                                <tr>
                                    <th>Unit Penyelenggara</th>
                                    <td>:</td>
                                    <td>{{$data->unit_penyelenggara}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- card-body -->
                </div>
            </div>
        </div>
    </div>

</div>
@endsection


@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
