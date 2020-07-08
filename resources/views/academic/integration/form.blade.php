@extends('layouts.master')

@section('title', isset($data) ? 'Edit Data Integrasi' : 'Tambah Data Integrasi')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'academic-integration-edit' : 'academic-integration-create', isset($data) ? $data : '' ) as $breadcrumb)
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
            <p class="mg-b-0">Sunting Data Integrasi</p>
        </div>
        @else
        <div>
            <h4>Tambah</h4>
            <p class="mg-b-0">Tambah Data Integrasi</p>
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
            <form id="curriculumIntegration_form" action="@isset($data) {{route('academic.integration.update',$data->id)}} @else {{route('academic.integration.store')}}@endisset" method="POST" enctype="multipart/form-data" data-parsley-validate>
                <div class="card-body bd bd-y-0 bd-color-gray-lighter">
                    <div class="row">
                        <div class="col-md-9 mx-auto">
                            @csrf
                            @if(isset($data))
                                @method('put')
                                <input type="hidden" name="id" value="{{encrypt($data->id)}}">
                            @else
                                @method('post')
                            @endif
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Tahun Integrasi: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <div id="pilihTahun" class="parsley-select">
                                        <select class="form-control select-academicYear" name="id_ta" data-parsley-class-handler="#pilihTahun" data-parsley-errors-container="#errorsPilihTahun" required>
                                            @isset($data)
                                            <option value="{{$data->id_ta}}">{{$data->academicYear->tahun_akademik.' - '.$data->academicYear->semester}}</option>
                                            @endisset
                                        </select>
                                    </div>
                                    <div id="errorsPilihTahun"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Jenis Kegiatan: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control" name="kegiatan" required>
                                        <option value="">- Pilih Jenis Kegiatan -</option>
                                        <option value="Penelitian" {{ isset($data) && $data->kegiatan=='Penelitian' ? 'selected' : ''}}>Penelitian</option>
                                        <option value="Pengabdian" {{ isset($data) && $data->kegiatan=='Pengabdian' ? 'selected' : ''}}>Pengabdian</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Judul Kegiatan: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <div id="pilihJudulKegiatan" class="parsley-select">
                                        <select class="form-control select-activity" data-parsley-class-handler="#pilihJudulKegiatan" data-parsley-errors-container="#errorsPilihJudulKegiatan" required>
                                            @isset($data)
                                            <option value="{{isset($data->id_penelitian) ? $data->id_penelitian : $data->id_pengabdian}}">
                                                {{isset($data->id_penelitian) ? $data->research->judul_penelitian : $data->communityService->judul_pengabdian}}
                                            </option>
                                            @endisset
                                        </select>
                                        <div id="errorsPilihJudulKegiatan"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Dosen: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <div id="pilihDosen" class="parsley-select">
                                        @if(Auth::user()->hasRole('kaprodi'))
                                        <input type="hidden" name="kd_prodi" value="{{Auth::user()->kd_prodi}}">
                                        @endif
                                        <select class="form-control select-dsn" name="nidn" data-parsley-class-handler="#pilihDosen" data-parsley-errors-container="#errorsPilihDosen" required>
                                            @isset($data)
                                            <option value="{{$data->nidn}}">{{$data->teacher->nama.' ('.$data->teacher->nidn.')'}}</option>
                                            @endisset
                                        </select>
                                        <div id="errorsPilihDosen"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Mata Kuliah: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
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
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Bentuk Integrasi: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" name="bentuk_integrasi" value="{{ isset($data) ? $data->bentuk_integrasi : Request::old('bentuk_integrasi')}}" placeholder="Tuliskan bentuk integrasi" required>
                                    <small class="w-100">Dapat berupa tambahan materi perkuliahan, studi kasus, bab dalam buku ajar, atau bentuk lain yang relevan</small>
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

@section('js')
@endsection
