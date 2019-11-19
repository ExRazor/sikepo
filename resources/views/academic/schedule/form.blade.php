@extends('layouts.master')

@section('title', isset($data) ? 'Edit Jadwal Mengajar' : 'Tambah Jadwal Mengajar')

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
        <i class="icon fa fa-pen-square"></i>
        @if(isset($data))
        <div>
            <h4>Sunting</h4>
            <p class="mg-b-0">Sunting Data Jadwal Mengajar</p>
        </div>
        @else
        <div>
                <h4>Tambah</h4>
                <p class="mg-b-0">Tambah Data Jadwal Mengajar</p>
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
                <h6 class="card-title">
                    @isset($data)
                        Sunting Data
                    @else
                        Tambah Data
                    @endisset
                </h6>
            </div>
            <form id="teacher_form" action="{{route('teacher.store')}}" method="POST" enctype="multipart/form-data" data-parsley-validate>
                <div class="card-body bd bd-y-0 bd-color-gray-lighter">
                    <div class="row">
                        <div class="col-9 mx-auto">
                            @csrf
                            @if(isset($data))
                                @method('put')
                                <input type="hidden" name="_id" value="{{encrypt($data->nidn)}}">
                            @else
                                @method('post')
                            @endif
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Jurusan: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select class="form-control" name="kd_jurusan" data-type="form" required>
                                        <option value="">- Pilih Jurusan -</option>
                                        @foreach($faculty as $f)
                                            @if($f->department->count())
                                            <optgroup label="{{$f->nama}}">
                                                @foreach($f->department as $d)
                                                <option value="{{$d->kd_jurusan}}" {{ (isset($data) && $data->studyProgram->kd_jurusan==$d->kd_jurusan) ? 'selected': ''}}>{{$d->nama}}</option>
                                                @endforeach
                                            </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Program Studi: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select id="select-prodi-dsn" class="form-control" name="kd_prodi" required>
                                        <option value="">- Pilih Prodi -</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Dosen: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select id="select-dosen" class="form-control" name="nidn" required>
                                        <option value="">- Pilih Dosen -</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Tahun Akademik: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div id="academicYear" class="parsley-select">
                                        <select class="form-control select-academicYear" name="id_ta" data-parsley-class-handler="#academicYear" data-parsley-errors-container="#errorsAcademicYear" required>
                                            <option value="">- Pilih Tahun Akademik -</option>
                                        </select>
                                        <div id="errorsAcademicYear"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Mata Kuliah: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div id="curriculum" class="parsley-select">
                                        <select class="form-control select-curriculum" name="kd_matkul" data-parsley-class-handler="#curriculum" data-parsley-errors-container="#errorsCurriculum" required>
                                            <option value="">- Pilih Mata Kuliah -</option>
                                        </select>
                                        <div id="errorsCurriculum"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 form-opsional">
                                <label class="col-3 form-control-label">Sesuai Bidang Ilmu? <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div id="sesuai_bidang_ps" class="radio">
                                        <label class="rdiobox rdiobox-inline mb-0">
                                            <input name="sesuai_bidang_ps" type="radio" value="1" {{ isset($data) && ($data->sesuai_bidang_ps=='1' || Request::old('sesuai_bidang_ps')=='1') ? 'checked' : ''}} data-parsley-class-handler="#sesuai_bidang_ps"
                                            data-parsley-errors-container="#errorsBD" required>
                                            <span>Ya</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label>
                                        <label class="rdiobox rdiobox-inline mb-0">
                                            <input name="sesuai_bidang_ps" type="radio" value="0" {{ isset($data) && ($data->sesuai_bidang_ps=='0' || Request::old('sesuai_bidang_ps')=='0') ? 'checked' : ''}}>
                                            <span>Tidak</span>
                                        </label>
                                    </div>
                                    <div id="errorsBD"></div>
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
