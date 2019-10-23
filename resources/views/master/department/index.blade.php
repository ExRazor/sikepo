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
        <button class="btn btn-teal btn-block mg-b-10 btn-add btn-add-department" style="color:white" data-toggle="modal" data-target="#modal-master-department"><i class="fa fa-plus mg-r-10"></i> Jurusan</button>
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
                <h6 class="card-title">Tampilkan</h6>
            </div>
            <div class="card-body bd bd-t-0 rounded-bottom bd-color-gray-lighter">
                <div class="row">
                    <div class="col-md-10">
                        <form action="{{route('master.department.show')}}" id="filter-department" method="POST">
                            <div class="form-group row mb-3">
                                <label class="col-3 form-control-label">Fakultas:</label>
                                <div class="col-6">
                                    <select id="fakultas" class="form-control" name="id_fakultas" data-placeholder="Pilih Prodi" required>
                                        <option value="0">Semua Fakultas</option>
                                        @foreach ($faculty as $f)
                                        <option value="{{$f->id}}">{{$f->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-6 offset-3">
                                    <button type="submit" class="btn btn-info">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- card-body -->
        </div>
        <div class="card mb-3">
            <div class="card-header bd rounded-top bd-color-gray-lighter">
                <h6 class="card-title">
                    <span class="nm_fakultas">-</span>  ||  Total: <span class="tot_jurusan">-</span> Jurusan
                </h6>
            </div>
            <div class="card-body bd bd-t-0 rounded-bottom bd-color-gray-lighter">
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
