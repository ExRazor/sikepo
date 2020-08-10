@extends('layouts.master')

@section('title', 'Data Fakultas')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('faculty') as $breadcrumb)
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
        <i class="icon fa fa-briefcase"></i>
        <div>
            <h4>Data Fakultas</h4>
            <p class="mg-b-0">Olah Data Fakultas</p>
        </div>
    </div>
    <div class="ml-auto">
        <button class="btn btn-teal btn-block mg-b-10 btn-add" style="color:white" data-toggle="modal" data-target="#modal-master-faculty"><i class="fa fa-plus mg-r-10"></i> Fakultas</button>
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
    <div class="card bd-0">
        <div class="card-body bd bd-t-0 rounded-bottom">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="25">ID</th>
                            <th width="400">Nama Fakultas</th>
                            <th width="150">Singkatan</th>
                            {{-- <th>Nama Dekan</th> --}}
                            <th class="text-center" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($faculty as $f)
                        <tr>
                            <th scope="row" style="vertical-align:middle">{{$f->id}}</td>
                            <td>{{$f->nama}}</td>
                            <td>{{$f->singkatan}}</td>
                            {{-- <td>
                                {{$f->nm_dekan}}<br>
                                <small>{{$f->nip_dekan}}</small>
                            </td> --}}
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-edit btn-edit-faculty" data-id="{{ encrypt($f->id) }}"><div><i class="fa fa-pencil-alt"></i></div></button>
                                    <form method="POST">
                                        <input type="hidden" value="{{encrypt($f->id)}}" name="_id">
                                        <button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-delete" data-dest="{{ route('master.faculty.delete') }}">
                                            <div><i class="fa fa-trash"></i></div>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div><!-- card-body -->
    </div>
</div>
@include('master.faculty.form');
@endsection

@section('js')
@endsection
