@extends('layouts.master')

@section('title', 'Data Tahun Akademik')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('academic-year') as $breadcrumb)
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
        <h4>Tahun Akademik</h4>
        <p class="mg-b-0">Olah Data Tahun Akademik</p>
    </div>
    <div class="ml-auto">
        <button class="btn btn-teal btn-block mg-b-10 btn-add" data-toggle="modal" data-target="#academicYear-form"><i class="fa fa-plus mg-r-10"></i> Tahun Akademik</button>
    </div>
</div>

<div class="br-pagebody">
    <div class="card bd-0">
        <div class="card-body bd bd-t-0 rounded-bottom">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tahun Akademik</th>
                        <th>Semester</th>
                        <th>Status Aktif</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="table_academicYear">
                    @foreach ($academicYears as $a)
                    <tr>
                        <th scope="row" style="vertical-align:middle">{{$loop->iteration}}</td>
                        <td>{{$a->tahun_akademik}}</td>
                        <td>{{$a->semester}}</td>
                        <td>
                            <div class="br-toggle br-toggle-success toggle-ay-status {{$a->status == 'Aktif' ? 'on' : '' }} " data-id='{{$a->id}}'>
                                <div class="br-toggle-switch"></div>
                            </div>
                        </td>
                        <td width="150">
                            <div class="btn-group hidden-xs-down">
                                <button class="btn btn-primary btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-edit btn-edit-ay" data-id="{{ encrypt($a->id) }}"><div><i class="fa fa-pencil-alt"></i></div></button>
                                <form action="{{ url()->current() }}" method="POST">
                                    @method('delete')
                                    @csrf
                                    <input type="hidden" value="{{ encrypt($a->id) }}" name="id">
                                    <button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-delete" data-dest="{{ route('master.academic-year.delete') }}">
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
    @include('master.academic-year.form');
</div>


@endsection

@section('js')
@endsection
