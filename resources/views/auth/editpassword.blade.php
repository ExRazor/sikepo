@extends('layouts.master')

@section('title', 'Ubah Kata Sandi')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('account-editpassword') as $breadcrumb)
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
            <h4>Kata Sandi</h4>
            <p class="mg-b-0">Ubah Kata Sandi</p>
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
    <div class="widget-2">
        <div class="card mb-3">
            <form id="changePass_form" action="{{route('account.editpassword_post')}}" method="POST" enctype="multipart/form-data" data-parsley-validate>
                <div class="card-body bd bd-y-0 bd-color-gray-lighter">
                    <div class="row">
                        <div class="col-9 mx-auto">
                            @csrf
                            @if(isset($data))
                                @method('put')
                                <input type="hidden" name="id" value="{{encrypt($data->id)}}">
                            @else
                                @method('post')
                            @endif
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Kata Sandi Lama: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="password" name="password_lama" placeholder="" required>
                                    @if ($errors->has('password_lama'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_lama') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Kata Sandi Baru: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="password" name="password_baru" required>
                                    @if ($errors->has('password_baru'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_baru') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Konfirmasi Sandi Baru: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="password" name="password_baru_confirmation" required>
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
@endsection
