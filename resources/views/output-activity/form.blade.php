@extends('layouts.master')

@section('title', isset($data) ? 'Edit Data Luaran' : 'Tambah Data Luaran')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'output-activity-edit' : 'output-activity-add', isset($data) ? $data : '' ) as $breadcrumb)
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
            <p class="mg-b-0">Sunting Data Luaran</p>
        </div>
        @else
        <div>
            <h4>Tambah</h4>
            <p class="mg-b-0">Tambah Data Luaran</p>
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
    <div class="row">
        <div class="col-9">
            <div class="widget-2">
                <div class="card mb-3">
                    <form id="outputActivity_form" action="{{route('output-activity.store')}}" method="POST" enctype="multipart/form-data" data-parsley-validate>
                        <div class="card-body bd bd-y-0 bd-color-gray-lighter">
                            <div class="row">
                                <div class="col-12 mx-auto">
                                    @csrf
                                    @if(isset($data))
                                        @method('put')
                                        <input type="hidden" name="id" value="{{encrypt($data->id)}}">
                                    @else
                                        @method('post')
                                    @endif
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Jenis Kegiatan: <span class="tx-danger">*</span></label>
                                        <div class="col-8">
                                            <select class="form-control" name="kegiatan" required>
                                                <option value="">- Pilih Jenis Kegiatan -</option>
                                                <option value="Penelitian" {{ isset($data) && $data->kegiatan=='Penelitian' ? 'selected' : ''}}>Penelitian</option>
                                                <option value="Pengabdian" {{ isset($data) && $data->kegiatan=='Pengabdian' ? 'selected' : ''}}>Pengabdian</option>
                                                {{-- <option value="Lainnya" {{ isset($data) && $data->kegiatan=='Lainnya' ? 'selected' : ''}}>Lainnya</option> --}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Nama Kegiatan: <span class="tx-danger">*</span></label>
                                        <div class="col-8">
                                            <div id="pilihNamaKegiatan" class="parsley-select" {{ isset($data) && $data->kegiatan=="Lainnya" ? 'style=display:none;' : ''}}>
                                                <select class="form-control select-activity" data-parsley-class-handler="#pilihNamaKegiatan" data-parsley-errors-container="#errorsPilihNamaKegiatan" {{ isset($data) && $data->kegiatan=="Lainnya" ? 'disabled' : 'required'  }}>
                                                    @if(isset($data->id_penelitian))
                                                        <option value="{{$data->id_penelitian}}">
                                                            {{$data->research->judul_penelitian}}
                                                        </option>
                                                    @elseif(isset($data->id_pengabdian))
                                                        <option value="{{$data->id_pengabdian}}">
                                                            {{$data->communityService->judul_pengabdian}}
                                                        </option>
                                                    @endif
                                                </select>
                                                <div id="errorsPilihNamaKegiatan"></div>
                                            </div>
                                            <input id="namaLainnya" class="form-control" type="text" name="lainnya" value="{{ isset($data) ? $data->lainnya : Request::old('lainnya')}}" placeholder="Masukkan nama kegiatan" {{ isset($data) && $data->kegiatan=="Lainnya" ? 'required' : 'style=display:none disabled'}}>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Pembuat Luaran: <span class="tx-danger">*</span></label>
                                        <div class="col-8">
                                            <select class="form-control" name="pembuat_luaran" required>
                                                <option value="">- Pilih Pembuat Luaran -</option>
                                                <option value="Dosen" {{ isset($data) && $data->pembuat_luaran=='Dosen' ? 'selected' : ''}}>Dosen</option>
                                                <option value="Mahasiswa" {{ isset($data) && $data->pembuat_luaran=='Mahasiswa' ? 'selected' : ''}}>Mahasiswa</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Judul Luaran: <span class="tx-danger">*</span></label>
                                        <div class="col-8">
                                            <input class="form-control" type="text" name="judul_luaran" value="{{ isset($data) ? $data->judul_luaran : Request::old('judul_luaran')}}" placeholder="Masukkan judul luaran" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Kategori Luaran: <span class="tx-danger">*</span></label>
                                        <div class="col-8">
                                            <select class="form-control" name="id_kategori" required>
                                                <option value="">- Pilih Kategori Luaran -</option>
                                                @foreach($category as $c)
                                                <option value="{{$c->id}}" {{ isset($data) && $data->id_kategori==$c->id || Request::old('id_kategori')==$c->id ? 'selected' : ''}}>{{$c->nama}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Nama Jurnal:</label>
                                        <div class="col-8">
                                            <input class="form-control" type="text" name="jurnal_luaran" value="{{ isset($data) ? $data->jurnal_luaran : Request::old('jurnal_luaran')}}" placeholder="Masukkan nama jurnal tempat dipublikasikan">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Tahun Publikasi: <span class="tx-danger">*</span></label>
                                        <div class="col-3">
                                            <select class="form-control" name="tahun_luaran" required>
                                                @for($i=date('Y');$i>=2000;$i--)
                                                <option value={{$i}} {{ isset($data) && $i == $data->tahun_luaran ? 'selected' : ''}}>{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">ISSN / ISBN:</label>
                                        <div class="col-8">
                                            <input class="form-control" type="text" name="issn" value="{{ isset($data) ? $data->issn : Request::old('issn')}}" placeholder="Contoh: 1234-5678">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Halaman:</label>
                                        <div class="col-8">
                                            <input class="form-control" type="text" name="volume_hal" value="{{ isset($data) ? $data->volume_hal : Request::old('volume_hal')}}" placeholder="Contoh: Vol.6, No.132, Hal.132-135">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">URL:</label>
                                        <div class="col-8">
                                            <input class="form-control" type="url" name="url" value="{{ isset($data) ? $data->url : Request::old('url')}}" placeholder="Contoh: http://mub.journal.org/">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Keterangan:</label>
                                        <div class="col-8">
                                            <input class="form-control" type="text" name="keterangan" value="{{ isset($data) ? $data->keterangan : Request::old('keterangan')}}" placeholder="Masukkan keterangan luaran">
                                        </div>
                                    </div>
                                    <div class="form-group row mg-t-20">
                                        <label class="col-sm-3 form-control-label align-items-start pd-t-12">Surat Keterangan Accepted:</label>
                                        <div class="col-sm-7">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="file_keterangan" id="file_keterangan" accept=".pdf" {{ isset($data) ? '' : 'required'}}>
                                                <label class="custom-file-label custom-file-label-primary" for="file_keterangan">{{isset($data->file_keterangan) ? $data->file_keterangan : 'Pilih fail'}}</label>
                                            </div>
                                            <small class="w-100">
                                                Harap dikemas dalam 1 PDF jika lebih dari 1 file.
                                            </small>
                                        </div>
                                        @if(isset($data->file_keterangan))
                                        <div class="col-sm-1">
                                            <button class="btn btn-danger w-100 btn-delfile" data-dest="{{route('output-activity.file.delete','type=keterangan&id='.encrypt($data->id))}}">X</button>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="form-group row mg-t-20">
                                        <label class="col-sm-3 form-control-label align-items-start pd-t-12">Unggah Artikel:</label>
                                        <div class="col-sm-7">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="file_artikel" id="file_artikel" accept=".pdf" {{ isset($data) ? '' : 'required'}}>
                                                <label class="custom-file-label custom-file-label-primary" for="file_artikel">{{isset($data->file_artikel) ? $data->file_artikel : 'Pilih fail'}}</label>
                                            </div>
                                            <small class="w-100">
                                                Harap dikemas dalam 1 PDF jika lebih dari 1 file.
                                            </small>
                                        </div>
                                        @isset($data->file_artikel)
                                        <div class="col-sm-1">
                                            <button class="btn btn-danger w-100 btn-delfile" data-dest="{{route('output-activity.file.delete','type=artikel&id='.encrypt($data->id))}}">X</button>
                                        </div>
                                        @endisset
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
        <div class="col-3">
            <div class="alert alert-info">
                <h5>KATEGORI LUARAN</h5>
                <hr>
                @foreach($category as $c)
                <strong class="d-block d-sm-inline-block-force">{{$loop->iteration.'. '.$c->nama}}</strong><br>
                {{$c->deskripsi}}
                @if (!$loop->last)
                    <br><br>
                @endif
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')
@endsection
