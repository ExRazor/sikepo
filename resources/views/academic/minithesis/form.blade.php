@extends('layouts.master')

@section('title', isset($data) ? 'Sunting Tugas Akhir' : 'Tambah Tugas Akhir')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'academic-minithesis-edit' : 'academic-minithesis-add', isset($data) ? $data : '' ) as $breadcrumb)
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
            <p class="mg-b-0">Sunting Tugas Akhir</p>
        </div>
        @else
        <div>
            <h4>Tambah</h4>
            <p class="mg-b-0">Tambah Tugas Akhir</p>
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
            <form id="research_form" action="{{route('academic.minithesis.store')}}" method="POST" enctype="multipart/form-data" data-parsley-validate>
                <div class="card-body bd bd-y-0 bd-color-gray-lighter">
                    <div class="row">
                        <div class="col-9 mx-auto">
                            @csrf
                            @if(isset($data))
                                @method('put')
                                <input type="hidden" name="id" value="{{encrypt($data->id)}}">
                            @else
                                @method('post')
                            @endif
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Mahasiswa: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div id="pilihMhs" class="parsley-select">
                                        @if(Auth::user()->hasRole('kaprodi'))
                                        <input type="hidden" name="prodi_mhs" value="{{Auth::user()->kd_prodi}}">
                                        @endif
                                        <select class="form-control select-mhs-prodi" name="nim" data-parsley-class-handler="#pilihMhs" data-parsley-errors-container="#errorsPilihMhs" required>
                                            @isset($data)
                                            <option value="{{$data->nim}}">{{$data->student->nama.' ('.$data->student->nim.')'}}</option>
                                            @endisset
                                        </select>
                                    </div>
                                    <div id="errorsPilihMhs"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Judul Tugas Akhir: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="judul" value="{{ isset($data) ? $data->judul : Request::old('judul')}}" placeholder="Masukkan judul tugas akhir" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Tahun Diangkat: <span class="tx-danger">*</span></label>
                                <div class="col-8">
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
                                <label class="col-3 form-control-label">Pembimbing Utama: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div id="pilihUtama" class="parsley-select">
                                            <select class="form-control select-dsn" name="pembimbing_utama" data-parsley-class-handler="#pilihUtama" data-parsley-errors-container="#errorsPilihUtama" required>
                                                @isset($data)
                                                <option value="{{$data->pembimbing_utama}}">{{$data->pembimbingUtama->nama.' ('.$data->pembimbingUtama->nidn.')'}}</option>
                                                @endisset
                                            </select>
                                    </div>
                                    <div id="errorsPilihUtama"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Pembimbing Pendamping: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div id="pilihPendamping" class="parsley-select">
                                        <select class="form-control select-dsn" name="pembimbing_pendamping" data-parsley-class-handler="#pilihPendamping" data-parsley-errors-container="#errorsPilihPendamping" required>
                                            @isset($data)
                                            <option value="{{$data->pembimbing_pendamping}}">{{$data->pembimbingPendamping->nama.' ('.$data->pembimbingPendamping->nidn.')'}}</option>
                                            @endisset
                                        </select>
                                    </div>
                                    <div id="errorsPilihPendamping"></div>
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
