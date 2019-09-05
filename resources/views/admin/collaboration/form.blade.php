@extends('layouts.master')

@section('title', 'Data Program Studi')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'collaboration-edit' : 'collaboration-add' ) as $breadcrumb)
        <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
        @endforeach
    </nav>
</div>

<div class="br-pagetitle">
        <i class="icon ion-calendar"></i>
        @if(isset($data))
        <div>
            <h4>Sunting</h4>
            <p class="mg-b-0">Sunting Data Kerja Sama Prodi</p>
        </div>
        @else
        <div>
                <h4>Tambah</h4>
                <p class="mg-b-0">Tambah Data Kerja Sama Prodi</p>
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
            <div class="form-layout form-layout-1">
                <form action="/collaboration" method="POST" enctype="multipart/form-data" data-parsley-validate>
                    @csrf
                    @if(isset($data))
                        @method('put')
                        <input type="hidden" name="id" value="{{encrypt($data->id)}}">
                    @else
                        @method('post')
                    @endif
                    <div class="row mg-b-25">
                        <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-control-label">Program Studi:<span class="tx-danger">*</span></label>
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
                        </div><!-- col-4 -->
                        <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-control-label">Tahun Akademik: <span class="tx-danger">*</span></label>
                            <div id="tahun_akademik" class="parsley-select">
                                <select class="form-control select2" name="id_ta" data-parsley-class-handler="#tahun_akademik"
                                data-parsley-errors-container="#errorTahunAkademik"  required>
                                    <option></option>
                                    @foreach ($academicYear as $ay)
                                    <option value="{{$ay->id}}" {{ isset($data) && ($data->id_ta==$ay->id || Request::old('id_ta')==$ay->id) ? 'selected' : ''}}>{{$ay->tahun_akademik.' - '.$ay->semester}}</option>
                                    @endforeach
                                </select>
                                <div id="errorTahunAkademik"></div>
                            </div>
                        </div>
                        </div><!-- col-4 -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Nama Lembaga: <span class="tx-danger">*</span></label>
                                <input class="form-control" type="text" name="nama_lembaga" value="{{ isset($data) ? $data->nama_lembaga : Request::old('nama_lembaga')}}" placeholder="Tuliskan nama lembaga" required>
                            </div>
                        </div><!-- col-4 -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Tingkat Kegiatan: <span class="tx-danger">*</span></label>
                                <div class="row">
                                <div class="col-lg-4 mg-t-15">
                                    <label class="rdiobox">
                                        <input name="tingkat" type="radio" value="Internasional" {{ isset($data) && ($data->tingkat=='Internasional' || Request::old('tingkat')=='Internasional') ? 'checked' : ''}} required>
                                        <span>Internasional</span>
                                    </label>
                                </div>
                                <div class="col-lg-4 mg-t-15">
                                    <label class="rdiobox">
                                        <input name="tingkat" type="radio" value="Nasional" {{ isset($data) && ($data->tingkat=='Nasional' || Request::old('tingkat')=='Nasional') ? 'checked' : ''}} required>
                                        <span>Nasional</span>
                                    </label>
                                </div>
                                <div class="col-lg-4 mg-t-15">
                                    <label class="rdiobox">
                                        <input name="tingkat" type="radio" value="Lokal" {{ isset($data) && ($data->tingkat=='Lokal' || Request::old('tingkat')=='Lokal') ? 'checked' : ''}} required>
                                        <span>Lokal/Wilayah</span>
                                    </label>
                                </div>
                                </div>
                            </div>
                        </div><!-- col-4 -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Judul Kegiatan: <span class="tx-danger">*</span></label>
                                <input class="form-control" type="text" name="judul_kegiatan" value="{{ isset($data) ? $data->judul_kegiatan : Request::old('judul_kegiatan')}}" placeholder="Tuliskan judul kegiatan kerja sama" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Manfaat Kegiatan: <span class="tx-danger">*</span></label>
                                <textarea rows="3" class="form-control" name="manfaat_kegiatan" placeholder="Tuliskan manfaat kegiatan" required>{{ isset($data) ? $data->manfaat_kegiatan : Request::old('manfaat_kegiatan')}}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Waktu Pelaksanaan: <span class="tx-danger">*</span></label>
                                <input class="form-control datepicker" type="text" name="waktu" value="{{ isset($data) ? $data->waktu : Request::old('waktu')}}" placeholder="Tuliskan waktu pelaksanaan" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Durasi Pelaksanaan: <span class="tx-danger">*</span></label>
                                <input class="form-control" type="text" name="durasi" value="{{ isset($data) ? $data->durasi : Request::old('durasi')}}" placeholder="Tuliskan lama durasi kegiatan" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Bukti Pelaksanaan: <span class="tx-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="bukti" id="bukti_kerjasama" {{ isset($data) ? '' : 'required'}}>
                                    <label class="custom-file-label custom-file-label-primary" for="bukti_kerjasama">Pilih fail</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-layout-footer">
                        <button type="submit" class="btn btn-info">Simpan</button>
                        <a href="{{route('collaboration')}}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div><!-- card-body -->
    </div>
    {{-- @include('admin.academic-year.form'); --}}
</div>
@endsection

@section('js')
@endsection
