@extends('layouts.master')

@section('title', isset($data) ? 'Edit Data Penelitian' : 'Tambah Data Penelitian')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'research-edit' : 'research-add', isset($data) ? $data : '' ) as $breadcrumb)
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
            <p class="mg-b-0">Sunting Data Penelitian</p>
        </div>
        @else
        <div>
                <h4>Tambah</h4>
                <p class="mg-b-0">Tambah Data Penelitian</p>
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
            <form id="research_form" action="{{route('student.store')}}" method="POST" enctype="multipart/form-data" data-parsley-validate>
                <div class="card-body bd bd-y-0 bd-color-gray-lighter">
                    <div class="row">
                        <div class="col-9 mx-auto">
                            @csrf
                            @if(isset($data))
                                @method('put')
                                <input type="hidden" name="_id" value="{{encrypt($data->id)}}">
                            @else
                                @method('post')
                            @endif
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Program Studi: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select class="form-control" name="kd_prodi" required>
                                        <option value="">- Pilih Prodi -</option>
                                        @foreach($studyProgram as $sp)
                                        <option value="{{$sp->kd_prodi}}" {{ (isset($data) && ($sp->kd_prodi==$data->kd_prodi) || Request::old('kd_prodi')==$sp->kd_prodi) ? 'selected' : ''}}>{{$sp->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Dosen: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select class="form-control" name="kd_prodi" required>
                                        <option value="">- Pilih Dosen -</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Tema Penelitian: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="tema_penelitian" value="{{ isset($data) ? $data->tema_penelitian : Request::old('tema_penelitian')}}" placeholder="Masukkan tema penelitian sesuai roadmap" {{ isset($data) ? 'disabled' : ''}} maxlength="9" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Judul Penelitian: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="judul_penelitian" value="{{ isset($data) ? $data->judul_penelitian : Request::old('judul_penelitian')}}" placeholder="Masukkan judul penelitian" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Tahun Penelitian:</label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="tahun_penelitian" value="{{ isset($data) ? $data->tahun_penelitian : Request::old('tahun_penelitian')}}" placeholder="Masukkan Tahun Penelitian" maxlength="4" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Mahasiswa Peserta:</label>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-3">
                                            <input class="form-control" type="text" name="mahasiswa[]" value="{{ isset($data) ? $data->mahasiswa : Request::old('mahasiswa')}}" placeholder="NIM" maxlength="4">
                                        </div>
                                        <div class="col-3">
                                            <input class="form-control" type="text" name="mahasiswa[]" value="{{ isset($data) ? $data->mahasiswa : Request::old('mahasiswa')}}" placeholder="NIM" maxlength="4">
                                        </div>
                                        <div class="col-3">
                                            <input class="form-control" type="text" name="mahasiswa[]" value="{{ isset($data) ? $data->mahasiswa : Request::old('mahasiswa')}}" placeholder="NIM" maxlength="4">
                                        </div>
                                        <div class="col-3">
                                            <input class="form-control" type="text" name="mahasiswa[]" value="{{ isset($data) ? $data->mahasiswa : Request::old('mahasiswa')}}" placeholder="NIM" maxlength="4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Sumber Biaya: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select class="form-control" name="mahasiswa[]" required>
                                        <option value="">- Pilih Sumber Biaya -</option>
                                        <option value="Perguruan Tinggi">Perguruan Tinggi</option>
                                        <option value="Mandiri">Mandiri</option>
                                        <option value="Lembaga Dalam Negeri">Lembaga Dalam Negeri</option>
                                        <option value="Lembaga Luar Negeri">Lembaga Luar Negeri</option>
                                    </select>
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
