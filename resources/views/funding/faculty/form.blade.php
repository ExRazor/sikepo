@extends('layouts.master')

@section('title', isset($data) ? 'Sunting Keuangan Fakultas' : 'Tambah Keuangan Fakultas')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'funding-faculty-edit' : 'funding-faculty-add', isset($data) ? $data : '' ) as $breadcrumb)
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
        <i class="icon fa fa-pen-square"></i>
        @if(isset($data))
        <div>
            <h4>Sunting</h4>
            <p class="mg-b-0">Sunting Data Keuangan Fakultas</p>
        </div>
        @else
        <div>
            <h4>Tambah</h4>
            <p class="mg-b-0">Tambah Data Keuangan Fakultas</p>
        </div>
        @endif
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
            <form id="funding_faculty_form" action="{{isset($data) ? route('funding.faculty.update',encrypt($data->kd_dana)) : route('funding.faculty.store')}}" method="POST" enctype="multipart/form-data">
                <div class="card-body bd bd-y-0 bd-color-gray-lighter">
                    <div class="row">
                        <div class="col-md-10 mx-auto">
                            @csrf
                            @if(isset($data))
                                @method('put')
                                <input type="hidden" name="_id" value="{{encrypt($data->kd_dana)}}">
                            @else
                                @method('post')
                            @endif
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Periode Tahun: <span class="tx-danger">*</span></label>
                                <div class="col-md-7">
                                    <input type="hidden" name="id_fakultas" value="{{ encrypt(setting('app_faculty_id'))}}">
                                    <select class="form-control {{ $errors->has('id_ta') ? 'is-invalid' : ''}}" name="id_ta" required>
                                        <option value="">- Pilih Tahun -</option>
                                        @foreach($academicYear as $ay)
                                        <option value="{{$ay->id}}" {{ (isset($data) && ($data->id_ta==$ay->id) || Request::old('id_ta')==$ay->id) ? 'selected' : ''}}>{{$ay->tahun_akademik}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('id_ta'))
                                        <span class="invalid-feedback" role="alert">
                                            {{ $errors->first('id_ta') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @foreach($category as $c)
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">{{ $c->nama }}: </label>
                                <div class="col-md-7">
                                    @if($c->children->count())
                                        @foreach($c->children as $child)
                                        <div class="row mb-1">
                                            <div class="col-12">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        Rp
                                                        </div>
                                                    </div>
                                                    <input class="form-control rupiah" type="text" name="id_kategori[{{ $child->id }}]" value="{{ isset($data) ? $nominal[$child->id] : Request::old($child->id)}}" placeholder="Masukkan {{ $child->nama }}">
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                Rp
                                                </div>
                                            </div>
                                            <input class="form-control rupiah" type="text" name="id_kategori[{{ $c->id }}]" value="{{ isset($data) ? $nominal[$c->id] : Request::old($c->id)}}" placeholder="Masukkan {{ $c->nama }}">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div><!-- card-body -->
                <div class="card-footer bd bd-color-gray-lighter rounded-bottom">
                    <div class="row">
                        <div class="col-6 mx-auto">
                            <div class="text-center">
                                <button class="btn btn-info btn-submit">Simpan</button>
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
