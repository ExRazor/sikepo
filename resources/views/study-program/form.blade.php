@extends('layouts.master')

@section('title', 'Data Program Studi')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'study-program-edit' : 'study-program-add' ) as $breadcrumb)
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
    <div class="card bd-0">
        <div class="card-body bd bd-t-0 rounded-bottom">
            <div class="form-layout form-layout-2">
                <form action="/master/study-program" method="POST">
                    @csrf
                    @if(isset($data))
                        @method('put')
                    @else
                        @method('post')
                    @endif
                    <div class="row no-gutters">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label">Kode Prodi: <span class="tx-danger">*</span></label>
                                <input class="form-control" type="text" name="kd_prodi" maxlength="5" value="{{ isset($data) ? $data->kd_prodi : Request::old('kd_prodi')}}" placeholder="Isikan kode prodi" {{ isset($data) ? 'readonly' : ''}}>
                            </div>
                        </div><!-- col-4 -->
                        <div class="col-md-5 mg-t--1 mg-md-t-0">
                            <div class="form-group mg-md-l--1">
                                <label class="form-control-label">Nama Prodi: <span class="tx-danger">*</span></label>
                                <input class="form-control" type="text" name="nama" value="{{ isset($data) ? $data->nama : Request::old('nama')}}" placeholder="Isikan nama program studi">
                            </div>
                        </div><!-- col-4 -->
                        <div class="col-md-2 mg-md-t-0">
                            <div class="form-group mg-md-l--1">
                                <label class="form-control-label">Singkatan Program Studi: <span class="tx-danger">*</span></label>
                                <input class="form-control" type="text" name="singkatan" value="{{ isset($data) ? $data->singkatan : Request::old('singkatan')}}" placeholder="Isikan singkatan program studi">
                            </div>
                        </div><!-- col-4 -->
                        <div class="col-md-3 mg-t--1 mg-md-t-0">
                            <div class="form-group mg-md-l--1">
                                <label class="form-control-label">Jenjang Pendidikan: <span class="tx-danger">*</span></label>
                                <select class="form-control select2" data-placeholder="Pilih jenjang" name="jenjang">
                                    <option value="D3" {{ (isset($data) && ($data->jenjang==='D3') || Request::old('jenjang')==='D3') ? 'selected' : ''}}>Diploma D3</option>
                                    <option value="S1" {{ (isset($data) && ($data->jenjang==='S1') || Request::old('jenjang')==='S1') ? 'selected' : ''}}>Strata 1 / Sarjana</option>
                                    <option value="S2" {{ (isset($data) && ($data->jenjang==='S2') || Request::old('jenjang')==='S2') ? 'selected' : ''}}>Strata 2 / Magister</option>
                                    <option value="S3" {{ (isset($data) && ($data->jenjang==='S3') || Request::old('jenjang')==='S3') ? 'selected' : ''}}>Strata 3 / Doktor</option>
                                </select>
                            </div>
                        </div><!-- col-4 -->
                        <div class="col-md-3">
                            <div class="form-group bd-t-0-force">
                                <label class="form-control-label">Nomor SK Pembukaan Program Studi: <span class="tx-danger">*</span></label>
                                <input class="form-control" type="text" name="no_sk" value="{{ isset($data) ? $data->no_sk : Request::old('no_sk')}}" placeholder="Isikan nomor SK">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-3">
                            <div class="form-group mg-md-l--1 bd-t-0-force">
                                <label class="form-control-label">Tanggal SK Pembukaan Program Studi: <span class="tx-danger">*</span></label>
                                <input class="form-control datepicker" type="text" name="tgl_sk" value="{{ isset($data) ? $data->tgl_sk : Request::old('tgl_sk')}}" placeholder="Isikan tanggal disahkan SK">
                            </div>
                        </div><!-- col-4 -->
                        <div class="col-md-3 mg-t--1">
                            <div class="form-group mg-md-l--1">
                                <label class="form-control-label">Penandatangan SK Pembukaan: <span class="tx-danger">*</span></label>
                                <input class="form-control" type="text" name="pejabat_sk" value="{{ isset($data) ? $data->pejabat_sk : Request::old('pejabat_sk')}}" placeholder="Isikan nama pejabat penandatangan SK">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-3 mg-md-t--1">
                            <div class="form-group mg-md-l--1">
                                <label class="form-control-label">Tahun Pertama Menerima Mahasiswa: <span class="tx-danger">*</span></label>
                                <input class="form-control" type="text" name="thn_menerima" maxlength="4" value="{{ isset($data) ? $data->thn_menerima : Request::old('thn_menerima')}}" placeholder="Isikan tahun pertama menerima mahasiswa">
                            </div>
                        </div><!-- col-4 -->
                        <div class="col-md-6 mg-md-t--1">
                            <div class="form-group">
                                <label class="form-control-label">NIP Kepala Program Studi:</label>
                                <input class="form-control" type="text" name="nip_kaprodi" value="{{ isset($data) ? $data->nip_kaprodi : Request::old('nip_kaprodi')}}" placeholder="Isikan NIP kepala program studi">
                            </div>
                        </div><!-- col-4 -->
                        <div class="col-md-6 mg-md-t--1">
                            <div class="form-group mg-md-l--1">
                                <label class="form-control-label">Nama Kepala Program Studi:</label>
                                <input class="form-control" type="text" name="nm_kaprodi" value="{{ isset($data) ? $data->nm_kaprodi : Request::old('nm_kaprodi')}}" placeholder="Isikan nama kepala program studi">
                            </div>
                        </div><!-- col-4 -->

                    </div><!-- row -->
                    <div class="form-layout-footer bd pd-20 bd-t-0">
                        <button type="submit" class="btn btn-info">Simpan</button>
                        <a href="{{route('master.study-program')}}" class="btn btn-secondary">Batal</a>
                    </div><!-- form-group -->
                </form>
            </div>
        </div><!-- card-body -->
    </div>
</div>
@endsection

@section('js')
@endsection
