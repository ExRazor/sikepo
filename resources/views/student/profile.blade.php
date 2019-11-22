@extends('layouts.master')

@section('title', 'Detail Data Mahasiswa')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('student-profile',$data) as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>

<div class="br-profile-page">
    <div class="card shadow-base bd-0 rounded-0 widget-3">
        <div class="card-body">
            <div class="d-flex align-items-center text-white">
                <div class="mx-auto">
                    <h3 class="my-0">{{$data->nama}}</h3>
                </div>
            </div>
        </div><!-- card-body -->
    </div>
    <div class="row pd-x-35 pd-t-20">
        <div class="col-lg-12">
            @if (session()->has('flash.message'))
            <div class="alert alert-{{ session('flash.class') }}" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ session('flash.message') }}
                </div>
            @endif
        </div>
    </div>
    <div class="row br-profile-body">
        <div class="col-lg-3">
            <div class="card shadow-base bd-0 profil-kiri">
                <div class="pd-20 pd-xs-30">
                    <div class="profil-avatar">
                        <?php $avatar = ($data->jk=='Laki-Laki' ? 'student_male.png' : 'student_female.png') ?>
                        <img src="{{isset($data->foto) ? route('download.avatar','type=student&id='.encrypt($data->foto)): route('download.avatar','type=avatar&id='.encrypt($avatar))}}">
                    </div>
                    @if($data->studyProgram->kd_jurusan == setting('app_department_id'))
                    <div class="profil-status mg-t-20 d-flex justify-content-center">
                        <span class="{{ $status->status_button }}">{{ $status->status }}</span>
                    </div>
                    @endif
                </div>
                <div class="profil-tombol">
                    <ul class="profil-menu">
                        <li class="profil-menu-list">
                            <a class="profil-menu-link" href="{{ route('student.edit',encode_id($data->nim)) }}">
                                <i class="fa fa-pencil-alt"></i>
                                <span>Sunting Profil</span>
                            </a>
                        </li>
                        <li class="profil-menu-list">
                            <a href="javascript:void(0)" class="profil-menu-link" data-toggle="modal" data-target="#modal-profile-photo">
                                <i class="fa fa-camera-retro"></i>
                                <span>Ganti Foto</span>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
        <div class="col-lg-9 mg-t-30 mg-lg-t-0">
            <div class="tab-content">
                <div class='widget-custom-tab'>
                    <div class="card shadow-base">
                        <div class="card-header">
                            <div class="tab-header d-flex">
                                <h4>-</h4>
                                <div class="ml-auto">
                                    <ul class="nav nav-outline active-info align-items-center flex-row profile-tab" role="tablist">
                                        <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#profile" role="tab">Biodata Mahasiswa</a></li>
                                        @if($data->studyProgram->kd_jurusan == setting('app_department_id'))
                                        <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#status" role="tab">Status Akademik</a></li>
                                        <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#achievement" role="tab">Prestasi</a></li>
                                        @endif
                                        <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#research" role="tab">Penelitian</a></li>
                                        <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#community-service" role="tab">Pengabdian</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                @include('student.tab-profile')
                                @if($data->studyProgram->kd_jurusan == setting('app_department_id'))
                                @include('student.tab-status')
                                @include('student.tab-achievement')
                                @endif
                                @include('student.tab-research')
                                @include('student.tab-service')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('student.form-photo')
@if($data->studyProgram->kd_jurusan == setting('app_department_id'))
    @include('student.form-status')
    @include('student.form-achievement')
@endif
@endsection

@section('js')
@endsection
