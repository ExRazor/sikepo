@extends('layouts.master')

@section('title', 'Ubah Profil')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('account-editprofile') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>

<div class="br-pagetitle">
        <i class="icon fa fa-pen-square"></i>
        <div>
            <h4>Profil</h4>
            <p class="mg-b-0">Ubah Setelan Profil</p>
        </div>
    </div>

<div class="br-pagebody">
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-3">
            <div class="card shadow-base bd-0 profil-kiri">
                <div class="pd-20 pd-xs-30">
                    <div class="profil-avatar">
                        <img src="{{isset(Auth::user()->foto) ? route('download.avatar','type=user&id='.encrypt(Auth::user()->foto)): route('download.avatar','type=avatar&id='.encrypt('user.png'))}}">
                    </div>
                    <div class="profil-status mg-t-20 d-flex justify-content-center">
                        <span class="badge badge-{{ $data->badge }} text-center">{{ ucfirst($data->role) }} {{isset($data->kd_prodi) ? ' - '.$data->studyProgram->nama : ''}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card mb-3">
                <form id="changePass_form" action="{{route('account.editprofile_post')}}" method="POST" enctype="multipart/form-data" data-parsley-validate>
                    <div class="card-body bd bd-y-0 bd-color-gray-lighter">
                        <div class="row">
                            <div class="col-9 mx-auto">
                                @csrf
                                @method('post')
                                <div class="row mb-3">
                                    <label class="col-3 form-control-label">Nama Lengkap:</label>
                                    <div class="col-8">
                                        <input class="form-control" type="text" name="name" value="{{$data->name}}" required>
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-3 form-control-label">Foto Profil:</label>
                                    <div class="col-8">
                                        <div class="form-group mg-b-10-force">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="foto" id="foto_profil">
                                                <label class="custom-file-label custom-file-label-primary" for="foto_profil">Pilih foto</label>
                                            </div>
                                            @if ($errors->has('foto'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('foto') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- card-body -->
                    <div class="card-footer bd bd-color-gray-lighter rounded-bottom">
                        <div class="row">
                            <div class="col-6 mx-auto">
                                <div class="text-center">
                                    <button class="btn btn-info btn-submit">Ganti</button>
                                </div>
                            </div>
                        </div>
                    </div><!-- card-footer -->
                </form>
            </div>
        </div>
    </div>
    <div class="widget-2">

    </div>
</div>
@endsection
