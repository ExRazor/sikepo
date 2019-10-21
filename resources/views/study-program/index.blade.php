@extends('layouts.master')

@section('title', 'Data Program Studi')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('study-program') as $breadcrumb)
            @isset($breadcrumb->url)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endisset
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon ion-calendar"></i>
    <div>
        <h4>Program Studi</h4>
        <p class="mg-b-0">Olah Data Program Studi</p>
    </div>
    <div class="ml-auto">
        <a href="{{ route('master.study-program.add') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Program Studi</a>
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
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="75">Kode</th>
                        <th width="400">Nama Prodi</th>
                        <th width="100">Singkatan</th>
                        <th width="150">Jenjang Pendidikan</th>
                        <th>Nama Kaprodi</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table_studyProgram">
                    @foreach ($data as $d)
                    <tr>
                        <td>{{$d->kd_prodi}}</td>
                        <td>{{$d->nama}}</td>
                        <td>{{$d->singkatan}}</td>
                        <td>{{$d->jenjang}}</td>
                        <td>
                            {{$d->nm_kaprodi}}<br>
                            <small>NIP. {{$d->nip_kaprodi}}</small>
                        </td>
                        <td>
                            <div class="btn-group hidden-xs-down">
                                <button class="btn btn-success btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-show-sp" data-id="{{encrypt($d->kd_prodi)}}" ><div><i class="fa fa-search-plus"></i></div></button>
                                <a href="{{ route('master.study-program.edit',encrypt($d->kd_prodi)) }}" class="btn btn-primary btn-sm btn-icon rounded-circle mg-r-5 mg-b-10"><div><i class="fa fa-pencil-alt"></i></div></a>
                                <form method="POST">
                                    @method('delete')
                                    @csrf
                                    <input type="hidden" value="{{encrypt($d->kd_prodi)}}" name="id">
                                    <button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-delete" data-dest="{{ route('master.study-program.delete') }}">
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
    @include('study-program.show');
</div>
@endsection

@section('js')
@endsection
