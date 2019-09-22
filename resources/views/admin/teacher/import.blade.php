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
        <div>
            <h4>Import</h4>
            <p class="mg-b-0">Import Data Dosen</p>
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
    <div class="widget-2">
        <div class="card mb-3">
            <div class="card-header bd rounded-top bd-color-gray-lighter">
                <h6 class="card-title">Import Data Dosen dari Excel</h6>
            </div>
            <div class="card-body bd bd-t-0 rounded-bottom bd-color-gray-lighter">
                <div class="row">
                    <div class="col-md-9 mx-auto">
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
                                    <div id="prodi" class="parsley-select">
                                        <select class="form-control select2" name="kd_prodi" data-placeholder="Pilih Prodi" data-parsley-class-handler="#prodi"
                                        data-parsley-errors-container="#errorProdi" required>
                                            <option></option>
                                            @foreach ($studyProgram as $sp)
                                            <option value="{{$sp->kd_prodi}}" {{ isset($data) && ($data->kd_prodi==$sp->kd_prodi || Request::old('kd_prodi')==$sp->kd_prodi) ? 'selected' : ''}}>{{$sp->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="errorProdi"></div>
                                    </div>
                                </div>
                            </div><!-- row -->
                            <div class="form-group row">
                                <label class="col-3 form-control-label">Excel Data Dosen:</label>
                                <div class="col-6">
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
                    </div>
                </div>
            </div><!-- card-body -->
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection
