@extends('layouts.master')

@section('title', 'Kuota Mahasiswa')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('student-quota') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon fa fa-sort-amount-down"></i>
    <div>
        <h4>Kuota Mahasiswa</h4>
        <p class="mg-b-0">Olah Data Kuota Mahasiswa</p>
    </div>
    @if(!Auth::user()->hasRole('kajur'))
    <div class="ml-auto">
        <button class="btn btn-teal btn-block mg-b-10 text-white btn-add" data-toggle="modal" data-target="#modal-student-quota"><i class="fa fa-plus mg-r-10"></i> Kuota Mahasiswa</button>
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
            <div class="card-header nm_jurusan">
                <h6 class="card-title">
                    @if(Auth::user()->hasRole('kaprodi'))
                    {{ Auth::user()->studyProgram->nama }}
                    @else
                    {{ setting('app_department_name') }}
                    @endif
                </h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table id="table_student_quota" class="table display responsive nowrap datatable" data-sort="desc">
                    <thead>
                        <tr>
                            @if(!Auth::user()->hasRole('kaprodi'))
                            <th class="text-center">Program Studi</th>
                            @endif
                            <th class="text-center defaultSort">Tahun Akademik</th>
                            <th class="text-center">Daya Tampung</th>
                            <th class="text-center">Calon Mahasiswa<br>Pendaftar</th>
                            <th class="text-center">Calon mahasiswa<br>Lulus Seleksi</th>
                            @if(!Auth::user()->hasRole('kajur'))
                            <th class="text-center no-sort">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quota as $q)
                        <tr>
                            @if(!Auth::user()->hasRole('kaprodi'))
                            <td>{{ $q->studyProgram->nama }}</td>
                            @endif
                            <td class="text-center">{{ $q->academicYear->tahun_akademik }}</td>
                            <td class="text-center">{{ $q->daya_tampung }}</td>
                            <td class="text-center">{{ $q->calon_pendaftar }}</td>
                            <td class="text-center">{{ $q->calon_lulus }}</td>
                            @if(!Auth::user()->hasRole('kajur'))
                            <td class="text-center" width="50">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <button class="dropdown-item btn-edit btn-edit-quota" data-id="{{encrypt($q->id)}}">Sunting</button>
                                        <form method="POST">
                                            <input type="hidden" value="{{encrypt($q->id)}}" name="id">
                                            <button class="dropdown-item btn-delete" data-dest="{{ route('student.quota.delete') }}">Hapus</button>
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
@if(!Auth::user()->hasRole('kajur'))
    @include('student.quota.form')
@endif
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
