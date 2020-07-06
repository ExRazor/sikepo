@extends('layouts.master')

@section('title', 'Data Program Studi')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('study-program') as $breadcrumb)
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
            <h4>Program Studi</h4>
            <p class="mg-b-0">Olah Data Program Studi</p>
        </div>
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
    <div class="row widget-2 mg-b-50">
        <div class="col-12">
            <div class="card bd-0">
                <div class="card-header bd rounded-top bd-color-gray-lighter">
                    <h6 class="card-title">
                        <span class="nm_jurusan">-</span>  // Total: <span class="tot_prodi">-</span> Prodi
                    </h6>
                </div>
                <div class="card-body bd bd-t-0 rounded-bottom">
                    <form action="{{route('ajax.study-program.filter')}}" id="filter-study-program" method="POST">
                        <div class="filter-box d-flex flex-row bd-highlight mg-b-10">
                            <div class="mg-r-10">
                                <select id="fakultas" class="form-control" name="kd_jurusan" data-placeholder="Pilih Jurusan" required>
                                    <option value="all">Semua Jurusan</option>
                                    @foreach($faculty as $f)
                                        @if($f->department->count())
                                        <optgroup label="{{$f->nama}}">
                                            @foreach($f->department as $d)
                                            <option value="{{$d->kd_jurusan}}" {{ ($d->kd_jurusan == setting('app_department_id') ? 'selected': '')}}>{{$d->nama}}</option>
                                            @endforeach
                                        </optgroup>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-purple btn-block " style="color:white">Cari</a>
                            </div>
                        </div>
                    </form>
                    <table id="table_studyProgram" class="table responsive" data-sort="asc">
                        <thead>
                            <tr>
                                <th width="75"  class="defaultSort">Kode</th>
                                <th width="400">Nama Prodi</th>
                                <th width="100">Singkatan</th>
                                <th width="150">Jenjang</th>
                                <th>Nama Kaprodi</th>
                                <th width="150" class="no-sort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div><!-- card-body -->
            </div>
        </div>
    </div>

    @include('master.study-program.show');
</div>
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
