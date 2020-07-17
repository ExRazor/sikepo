@extends('layouts.master')

@section('title', 'Prestasi')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('profile-achievement') as $breadcrumb)
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
        <i class="icon fa fa-medal"></i>
        <div>
            <h4>Prestasi Dosen</h4>
            <p class="mg-b-0">Daftar Prestasi Dosen</p>
        </div>
    </div>
    <div class="ml-auto">
        <button class="btn btn-teal btn-block mg-b-10 btn-add" data-toggle="modal" data-target="#modal-teach-acv" style="color:white"><i class="fa fa-plus mg-r-10"></i> Prestasi Dosen</button>
    </div>
</div>

<div class="br-pagebody">
    @if($errors->any())
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif
    <div class="widget-2">
        <div class="card shadow-base mb-3">
            <div class="card-body bd-color-gray-lighter">
                <table id="table-teacherAcv" class="table table-bordered mb-0 datatable" data-sort="desc">
                    <thead>
                        <tr>
                            <th class="text-center align-middle defaultSort" width="100">Tanggal Diperoleh</th>
                            <th class="text-center align-middle">Prestasi</th>
                            <th class="text-center align-middle">Tingkat</th>
                            <th class="text-center align-middle no-sort">Bukti<br>Pendukung</th>
                            <th class="text-center align-middle no-sort">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($achievement as $acv)
                        <tr>
                            <td class="text-center">{{ $acv->academicYear->tahun_akademik.' - '.$acv->academicYear->semester }}</td>
                            <td>{{ $acv->prestasi }}</td>
                            <td>{{ $acv->tingkat_prestasi }}</td>
                            <td class="text-center align-middle">
                                <a href="{{route('teacher.achievement.download',encode_id($acv->bukti_file))}}" target="_blank">
                                    {{$acv->bukti_nama}}
                                </a>
                            </td>
                            <td width="50" class="text-center">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item btn-edit" href="#" data-id="{{ encode_id($acv->id) }}" data-dest="{{ route('profile.achievement') }}">Sunting</a>
                                        <form method="POST">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" value="{{encode_id($acv->id)}}" name="_id">
                                            <a href="#" class="dropdown-item btn-delete" data-dest="{{ route('profile.achievement.delete') }}">Hapus</a>
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
@include('teacher-view.achievement.form')
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

@push('custom-js')
@endpush
