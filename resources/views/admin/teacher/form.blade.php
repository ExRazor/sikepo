@extends('layouts.master')

@section('title', 'Data Program Studi')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'teacher-edit' : 'teacher-add' ) as $breadcrumb)
        <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
        @endforeach
    </nav>
</div>

<div class="br-pagetitle">
        <i class="icon ion-calendar"></i>
        @if(isset($data))
        <div>
            <h4>Sunting</h4>
            <p class="mg-b-0">Sunting Data Program Studi</p>
        </div>
        @else
        <div>
                <h4>Tambah</h4>
                <p class="mg-b-0">Tambah Data Program Studi</p>
            </div>
        @endif
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
    <div class="widget-2">
        <div class="card mb-3">
            <div class="card-header bd rounded-top bd-color-gray-lighter">
                <h6 class="card-title">Import Data dari Excel</h6>
            </div>
            <div class="card-body bd bd-t-0 rounded-bottom bd-color-gray-lighter">
                    <form action="/teacher/import-excel" method="POST" enctype="multipart/form-data" data-parsley-validate>
                        @csrf
                        @if(isset($data))
                            @method('put')
                            <input type="hidden" name="id" value="{{encrypt($data->id)}}">
                        @else
                            @method('post')
                        @endif
                        <div class="form-group row mb-3">
                            <label class="col-3 form-control-label">Program Studi: <span class="tx-danger">*</span></label>
                            <div class="col-6">
                                <input class="form-control" type="text" name="kd_prodi" maxlength="5" value="{{ isset($data) ? $data->kd_prodi : Request::old('kd_prodi')}}" placeholder="Isikan kode prodi" {{ isset($data) ? 'readonly' : ''}}>
                            </div>
                        </div><!-- row -->
                        <div class="form-group row">
                            <label class="col-3 form-control-label">Excel Data Dosen:</label>
                            <div class="col-5">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="bukti" id="bukti_kerjasama" {{ isset($data) ? '' : 'required'}}>
                                    <label class="custom-file-label custom-file-label-primary" for="bukti_kerjasama">Pilih fail</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-info">Upload</button>
                            </div>
                        </div>
                    </form>
            </div><!-- card-body -->
        </div>
        <div class="card mb-3">
            <div class="card-header bd rounded-top bd-color-gray-lighter">
                <h6 class="card-title">Tambah Data Manual</h6>
            </div>
            <div class="card-body bd bd-y-0 bd-color-gray-lighter">
                <form action="/master/study-program" method="POST">
                    @csrf
                    @if(isset($data))
                        @method('put')
                    @else
                        @method('post')
                    @endif
                    <div class="row mb-3">
                        <label class="col-3 form-control-label">Program Studi: <span class="tx-danger">*</span></label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="kd_prodi" maxlength="5" value="{{ isset($data) ? $data->kd_prodi : Request::old('kd_prodi')}}" placeholder="Isikan kode prodi" {{ isset($data) ? 'readonly' : ''}}>
                        </div>
                    </div><!-- row -->
                    <div class="row mb-3">
                        <label class="col-3 form-control-label">Kode Prodi: <span class="tx-danger">*</span></label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="kd_prodi" maxlength="5" value="{{ isset($data) ? $data->kd_prodi : Request::old('kd_prodi')}}" placeholder="Isikan kode prodi" {{ isset($data) ? 'readonly' : ''}}>
                        </div>
                    </div><!-- row -->
                </form>
            </div><!-- card-body -->
            <div class="card-footer bd bd-color-gray-lighter rounded-bottom">
                <div class="row">
                    <div class="col-6 mx-auto">
                        <div class="text-center">
                            <button type="submit" class="btn btn-info">Simpan</button>
                            <a href="{{route('master.study-program')}}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </div>
            </div><!-- card-footer -->

        </div>
    </div>
</div>
@endsection

@section('js')
@endsection
