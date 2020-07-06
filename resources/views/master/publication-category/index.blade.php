@extends('layouts.master')

@section('title', 'Kategori Publikasi')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('publication-category') as $breadcrumb)
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
        <i class="icon fa fa-paperclip"></i>
        <div>
            <h4>Kategori Publikasi</h4>
            <p class="mg-b-0">Olah Data Kategori Publikasi</p>
        </div>
    </div>
</div>

<div class="br-pagebody">
    <div class="alert alert-warning" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong class="d-block d-sm-inline-block-force">Hati-Hati!</strong><br>
        Data kategori publikasi yang disunting atau pun dihapus akan berdampak langung pada Data Publikasi.<br>
        Jika data kategori dihapus, maka data publikasi yang berkaitan dengan kategori tersebut akan ikut terhapus.<br>
    </div>
    @if (session()->has('flash.message'))
        <div class="alert alert-{{ session('flash.class') }}" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('flash.message') }}
        </div>
    @endif
    <div class="row widget-2">
        <div class="col-md-8 order-2 order-md-1 mb-3">
            <div class="card shadow-base">
                <div class="card-header">
                        <h6 class="card-title">Daftar Kategori Publikasi</span></h6>
                    </div>
                <div class="card-body bd-color-gray-lighter">
                    <table id="table-publishCat" class="table table-striped">
                        <thead>
                            <tr>
                                <th width="25">#</th>
                                <th width="350">Kategori Publikasi</th>
                                <th class="text-center" width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($category as $c)
                                <tr>
                                    <th scope="row" style="vertical-align:middle">{{ $loop->iteration }}</th>
                                    <td>
                                        {{$c->nama}}
                                        @isset($c->deskripsi)
                                        <br><small>{{$c->deskripsi}}</small>
                                        @endisset
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-primary btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-edit" data-id="{{ encrypt($c->id) }}"><div><i class="fa fa-pencil-alt"></i></div></button>
                                            <form method="POST">
                                                <input type="hidden" value="{{encrypt($c->id)}}" name="_id">
                                                <button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-delete" data-dest="{{ route('master.publication-category.delete') }}">
                                                    <div><i class="fa fa-trash"></i></div>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- card-body -->
            </div>
        </div>
        <div class="col-md-4 order-1 order-md-2 mb-3 widget-2">
            <div class="card shadow-base">
                <div class="card-header">
                    <h6 class="card-title"><span class="title-action">Tambah</span> Kategori</h6>
                </div>
                <div class="card-body bd-color-gray-lighter">
                    <form id="form-publishCat" enctype="multipart/form-data">
                        <div class="alert alert-danger" style="display:none">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                        <div class="row mg-t-20">
                            <label class="col-sm-3 form-control-label">Nama: <span class="tx-danger">*</span></label>
                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                <input type="hidden" name="_id">
                                <input type="text" name="nama" class="form-control" placeholder="Isikan nama kategori" required>
                            </div>
                        </div>
                        <div class="row mg-t-20 category-description">
                            <label class="col-sm-3 form-control-label">Deskripsi:</label>
                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                <textarea rows="5" name="deskripsi" class="form-control" placeholder="Isikan deskripsi kategori"></textarea>
                            </div>
                        </div>
                        <div class="row mg-t-20">
                            <div class="offset-sm-3 col-sm-9 mg-t-10 mg-sm-t-0">
                                <button type="submit" class="btn btn-info mr-2 btn-save" value="post" data-dest="{{route('master.publication-category.store')}}">Simpan</button>
                                <button class="btn btn-secondary btn-add">Reset</button>
                            </div>
                        </div>
                    </form>
                </div><!-- card-body -->
            </div>
        </div>
    </div>

</div>
{{-- @include('funding.category.form'); --}}
@endsection

@section('js')
@endsection
