@extends('layouts.master')

@section('title', 'Setelan Umum')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( 'setting-general' ) as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>

<div class="br-pagetitle">
        <i class="icon fa fa-cog"></i>
        <div>
            <h4>Setelan Umum</h4>
            <p class="mg-b-0">Kelola setelan umum aplikasi</p>
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
        <div class="card mb-3">
            <form id="setting_form" action="{{route('setting.general')}}" method="POST" enctype="multipart/form-data" data-parsley-validate>
                <div class="card-body bd bd-y-0 bd-color-gray-lighter">
                    <div class="row">
                        <div class="col-9 mx-auto">
                            @csrf
                            @method('put')
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Nama Aplikasi:</label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="app_name" value="{{ isset($data) ? $data['app_name'] : Request::old('app_name')}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Singkatan Aplikasi:</label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="app_short" value="{{ isset($data) ? $data['app_short'] : Request::old('app_short')}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Deskripsi Aplikasi:</label>
                                <div class="col-8">
                                    <textarea class="form-control" name="app_description" rows="5">{{ isset($data) ? $data['app_description'] : Request::old('app_description')}}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Jurusan: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select class="form-control" name="app_department_id" required>
                                        <option value="">- Pilih Jurusan Pengguna Aplikasi -</option>
                                        @foreach($faculty as $f)
                                            @if($f->department->count())
                                            <optgroup label="{{$f->nama}}">
                                                @foreach($f->department as $d)
                                                <option value="{{encrypt($d->kd_jurusan)}}" {{ (isset($data) && $data['app_department_id']==$d->kd_jurusan) ? 'selected': ''}}>{{$d->nama}}</option>
                                                @endforeach
                                            </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- card-body -->
                <div class="card-footer bd bd-color-gray-lighter rounded-bottom">
                    <div class="row">
                        <div class="col-6 mx-auto">
                            <div class="text-center">
                                <button class="btn btn-info btn-submit-teacher">Simpan</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </div>
                    </div>
                </div><!-- card-footer -->
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection
