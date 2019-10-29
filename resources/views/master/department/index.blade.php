@extends('layouts.master')

@section('title', 'Data Jurusan')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('department') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon ion-ios-briefcase"></i>
    <div>
        <h4>Data Jurusan</h4>
        <p class="mg-b-0">Olah Data Jurusan</p>
    </div>
    <div class="ml-auto">
        <button class="btn btn-teal btn-block mg-b-10 btn-add btn-add-department text-white" data-toggle="modal" data-target="#modal-master-department"><i class="fa fa-plus mg-r-10"></i> Jurusan</button>
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
            <div class="card-header bd rounded-top bd-color-gray-lighter">
                <h6 class="card-title">
                    <span class="nm_fakultas">-</span>  ||  Total: <span class="tot_jurusan">-</span> Jurusan
                </h6>
            </div>
            <div class="card-body bd bd-t-0 rounded-bottom bd-color-gray-lighter">
                <form action="{{route('master.department.show')}}" id="filter-department" method="POST">
                    <div class="filter-box d-flex flex-row bd-highlight mg-b-10">
                        <div class="mg-r-10">
                            <select id="fakultas" class="form-control" name="id_fakultas" required>
                                <option value="0">Semua Fakultas</option>
                                @foreach ($faculty as $f)
                                <option value="{{encode_id($f->id)}}">{{$f->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-purple btn-block btn-cari " style="color:white">Cari</a>
                        </div>
                    </div>
                </form>
                <table id="table_department" class="table table-striped">
                    <thead>
                        <tr>
                            <th width="25">Kode</th>
                            <th width="250">Nama Jurusan</th>
                            <th width="300">Asal Fakultas</th>
                            <th>Nama Kajur</th>
                            <th class="text-center" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div><!-- card-body -->
        </div>
    </div>
</div>
@include('master.department.form');
<script>
    $(document).ready(function(){


    })
</script>
@endsection

@section('js')
@endsection
