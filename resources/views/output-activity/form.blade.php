@extends('layouts.master')

@section('title', isset($data) ? 'Edit Data Pengabdian' : 'Tambah Data Pengabdian')

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
            <p class="mg-b-0">Sunting Data Pengabdian</p>
        </div>
        @else
        <div>
            <h4>Tambah</h4>
            <p class="mg-b-0">Tambah Data Pengabdian</p>
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
                    <form id="communityService_form" action="{{route('output-activity.store')}}" method="POST" enctype="multipart/form-data" data-parsley-validate>
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
                                        <label class="col-3 form-control-label">Tahun Luaran: <span class="tx-danger">*</span></label>
                                        <div class="col-8">
                                            <input class="form-control number" type="text" name="tahun_luaran" value="{{ isset($data) ? $data->tahun_luaran : Request::old('tahun_luaran')}}" placeholder="Masukkan tahun luaran penelitian" maxlength="4" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Jenis Kegiatan: <span class="tx-danger">*</span></label>
                                        <div class="col-8">
                                            <select class="form-control" name="kegiatan" required>
                                                <option value="">- Pilih Jenis Kegiatan -</option>
                                                <option value="Penelitian" {{ isset($data) && $data->kegiatan=='Penelitian' ? 'selected' : ''}}>Penelitian</option>
                                                <option value="Pengabdian" {{ isset($data) && $data->kegiatan=='Pengabdian' ? 'selected' : ''}}>Pengabdian</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Judul Kegiatan: <span class="tx-danger">*</span></label>
                                        <div class="col-8">
                                            <select class="form-control select-activity" required>
                                                @isset($data)
                                                <option value="{{isset($data->id_penelitian) ? $data->id_penelitian : $data->id_pengabdian}}">
                                                    {{isset($data->id_penelitian) ? $data->research->judul_penelitian : $data->communityService->judul_pengabdian}}
                                                </option>
                                                @endisset
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Keterangan:</label>
                                        <div class="col-8">
                                            <input class="form-control" type="text" name="keterangan" value="{{ isset($data) ? $data->keterangan : Request::old('keterangan')}}" placeholder="Masukkan keterangan luaran">
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
