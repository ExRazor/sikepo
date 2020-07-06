@extends('layouts.master')

@section('title', isset($data) ? 'Edit Jadwal Kurikulum' : 'Tambah Jadwal Kurikulum')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
            @foreach (Breadcrumbs::generate( isset($data) ? 'academic-schedule-edit' : 'academic-schedule-add', isset($data) ? $data : '' ) as $breadcrumb)
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
        <i class="icon fa fa-pen-square"></i>
        @if(isset($data))
        <div>
            <h4>Sunting</h4>
            <p class="mg-b-0">Sunting Data Jadwal Kurikulum</p>
        </div>
        @else
        <div>
            <h4>Tambah</h4>
            <p class="mg-b-0">Tambah Data Jadwal Kurikulum</p>
        </div>
        @endif
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
                <h6 class="card-title">
                    @isset($data)
                        Sunting Data
                    @else
                        Tambah Data
                    @endisset
                </h6>
            </div>
            <form id="curriculumSchedule_form" action="{{route('academic.schedule.store')}}" method="POST" enctype="multipart/form-data" data-parsley-validate>
                <div class="card-body bd bd-y-0 bd-color-gray-lighter">
                    <div class="row">
                        <div class="col-md-9 mx-auto">
                            @csrf
                            <input type="hidden" name="url_current" value="{{ url()->current() }}">
                            <input type="hidden" name="url_previous" value="{{ url()->previous() }}">
                            @if(isset($data))
                                @method('put')
                                <input type="hidden" name="id" value="{{encode_id($data->id)}}">
                            @else
                                @method('post')
                            @endif
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Asal Dosen: <span class="tx-danger">*</span></label>
                                <div class="col-md-4 mb-2">
                                    <select class="form-control" name="kd_jurusan" data-type="form" required>
                                        <option value="">- Pilih Jurusan -</option>
                                        @foreach($faculty as $f)
                                            @if($f->department->count())
                                            <optgroup label="{{$f->nama}}">
                                                @foreach($f->department as $d)
                                                <option value="{{$d->kd_jurusan}}" {{ (isset($data) && $data->teacher->studyProgram->kd_jurusan==$d->kd_jurusan) ? 'selected': ''}}>{{$d->nama}}</option>
                                                @endforeach
                                            </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select id="selectProdiDsn" class="form-control" name="kd_prodi" required>
                                        <option value="">- Pilih Prodi -</option>
                                        @isset($data)
                                            @foreach($studyProgram as $sp)
                                                <option value="{{$sp->kd_prodi}}" {{ (isset($data) && $data->teacher->kd_prodi==$sp->kd_prodi) ? 'selected': ''}}>{{$sp->nama}}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Dosen: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control select-dsn" name="nidn" {{isset($data) ? 'required' : 'disabled'}}>
                                        <option value="">- Pilih Dosen -</option>
                                        @isset($data)
                                            @foreach($teacher as $t)
                                                <option value="{{$t->nidn}}" {{ (isset($data) && $data->nidn==$t->nidn) ? 'selected': ''}}>{{$t->nama}}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Tahun Akademik: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <div id="academicYear" class="parsley-select">
                                        <select class="form-control select-academicYear" name="id_ta" data-parsley-class-handler="#academicYear" data-parsley-errors-container="#errorsAcademicYear" required>
                                            <option value="">- Pilih Tahun Akademik -</option>
                                            @isset($data)
                                            <option value="{{$data->id_ta}}" selected>{{$data->academicYear->tahun_akademik.' - '.$data->academicYear->semester}}</option>
                                            @endisset
                                        </select>
                                        <div id="errorsAcademicYear"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Mata Kuliah: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <div class="row align-items-center">
                                        <div class="col-md-8 mb-2">
                                            <div id="curriculum" class="parsley-select">
                                                <select class="form-control select-curriculum" name="kd_matkul" data-parsley-class-handler="#curriculum" data-parsley-errors-container="#errorsCurriculum" required>
                                                    <option value="">- Pilih Mata Kuliah -</option>
                                                    @isset($data)
                                                    <option value="{{$data->kd_matkul}}" selected>{{$data->curriculum->nama.' - '.$data->curriculum->studyProgram->singkatan.' ('.$data->curriculum->versi.')'}}</option>
                                                    @endisset
                                                </select>
                                                <div id="errorsCurriculum"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="sesuai_bidang" class="checkbox">
                                                <label class="ckbox ckbox-inline mb-0 mr-4">
                                                    <input name="sesuai_bidang" type="checkbox" value="1" {{ (isset($data) && isset($data->sesuai_bidang)) || Request::old('sesuai_bidang')=='1' ? 'checked' : ''}}>
                                                    <span class="pl-0">Sesuai Bidang Ilmu?</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- card-body -->
                <div class="card-footer bd bd-color-gray-lighter rounded-bottom">
                    <div class="row">
                        <div class="col-6 mx-auto">
                            <div class="text-center">
                                <button class="btn btn-info btn-submit">Simpan</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </div>
                    </div>
                </div><!-- card-footer -->
            </form>
        </div>
    </div>
</div>
@endsection
