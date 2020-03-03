@extends('layouts.master')

@section('title', isset($data) ? 'Edit Data Luaran' : 'Tambah Data Luaran')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'output-activity-teacher-edit' : 'output-activity-teacher-add', isset($data) ? $data : '' ) as $breadcrumb)
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
                    <form id="outputActivity_form" action="{{route('output-activity.teacher.store')}}" method="POST" enctype="multipart/form-data" data-parsley-validate>
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
                                                <option value="Penelitian" {{ isset($data) && $data->kegiatan=='Penelitian' || Request::old('kegiatan')=='Penelitian' ? 'selected' : ''}}>Penelitian</option>
                                                <option value="Pengabdian" {{ isset($data) && $data->kegiatan=='Pengabdian' || Request::old('kegiatan')=='Pengabdian' ? 'selected' : ''}}>Pengabdian</option>
                                                <option value="Lainnya" {{ isset($data) && $data->kegiatan=='Lainnya' || Request::old('kegiatan')=='Lainnya' ? 'selected' : ''}}>Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Nama Kegiatan: <span class="tx-danger">*</span></label>
                                        <div class="col-8">
                                            <input class="form-control" type="text" name="nm_kegiatan" value="{{ isset($data) ? $data->nm_kegiatan : Request::old('nm_kegiatan')}}" placeholder="Masukkan nama kegiatan" {{ isset($data) ? 'required' : 'disabled'}}>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Pemilik Luaran: <span class="tx-danger">*</span></label>
                                        <div class="col-8">
                                            @if(Auth::user()->hasRole('kaprodi'))
                                                <input type="hidden" name="prodi_dsn" value="{{Auth::user()->kd_prodi}}">
                                            @endif
                                            <div id="pilihDosen" class="parsley-select">
                                                <select class="form-control select-dsn-prodi" name="nidn"  data-parsley-class-handler="#pilihDosen" data-parsley-errors-container="#errorsPilihDosen" required>
                                                    @isset($data)
                                                    <option value="{{$data->nidn}}" selected>{{$data->teacher->nama.' ('.$data->nidn.')'}}</option>
                                                    @endisset
                                                </select>
                                            </div>
                                            <div id="errorsPilihDosen"></div>
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
                                        <label class="col-3 form-control-label">Judul Luaran: <span class="tx-danger">*</span></label>
                                        <div class="col-8">
                                            <input class="form-control" type="text" name="judul_luaran" value="{{ isset($data) ? $data->judul_luaran : Request::old('judul_luaran')}}" placeholder="Masukkan judul luaran" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Tahun Luaran: <span class="tx-danger">*</span></label>
                                        <div class="col-3">
                                            <select class="form-control" name="thn_luaran" required>
                                                @for($i=date('Y');$i>=2000;$i--)
                                                <option value={{$i}} {{ isset($data) && $data->thn_luaran == $i || Request::old('thn_luaran') == $i ? 'selected' : ''}}>{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <hr class="my-5">
                                    <div class="row mb-3">
                                        <label class="col-3 form-control-label">Jenis: <span class="tx-danger">*</span></label>
                                        <div class="col-8">
                                            <select id="jenis" class="form-control" name="jenis_luaran" required>
                                                <option value="">- Pilih Jenis -</option>
                                                <option {{isset($data) && $data->jenis_luaran=='Buku' || Request::old('jenis_luaran')=='Buku' ? 'selected' : '' }}>Buku</option>
                                                <option {{isset($data) && $data->jenis_luaran=='Jurnal' || Request::old('jenis_luaran')=='Jurnal' ? 'selected' : '' }}>Jurnal</option>
                                                <option {{isset($data) && $data->jenis_luaran=='HKI' || Request::old('jenis_luaran')=='HKI' ? 'selected' : '' }}>HKI</option>
                                                <option {{isset($data) && $data->jenis_luaran=='HKI Paten' || Request::old('jenis_luaran')=='HKI Paten' ? 'selected' : '' }}>HKI Paten</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="karya">
                                        <div class="row mb-3 {{isset($data) ? '' : 'd-none'}}" id="nama_karya">
                                            <label class="col-3 form-control-label">Nama Karya: <span class="tx-danger">*</span></label>
                                            <div class="col-8">
                                                <input class="form-control" type="text" name="nama_karya" value="{{ isset($data) ? $data->nama_karya : Request::old('nama_karya')}}" placeholder="Masukkan nama karya">
                                            </div>
                                        </div>
                                        <div class="row mb-3 {{isset($data) && ($data->jenis_luaran=='HKI' || $data->jenis_luaran=='HKI Paten') ? '' : 'd-none'}}" id="jenis_karya">
                                            <label class="col-3 form-control-label">Jenis Karya: <span class="tx-danger">*</span></label>
                                            <div class="col-8">
                                                <input class="form-control" type="text" name="jenis_karya" value="{{ isset($data) ? $data->jenis_karya : Request::old('jenis_karya')}}" placeholder="Masukkan jenis karya yang dipatenkan">
                                            </div>
                                        </div>
                                        <div class="row mb-3 {{isset($data) && ($data->jenis_luaran=='HKI' || $data->jenis_luaran=='HKI Paten') ? '' : 'd-none'}}" id="pencipta_karya">
                                            <label class="col-3 form-control-label">Pencipta Karya:</label>
                                            <div class="col-8">
                                                <input class="form-control" type="text" name="pencipta_karya" value="{{ isset($data) ? $data->pencipta_karya : Request::old('pencipta_karya')}}" placeholder="Masukkan nama pencipta karya">
                                            </div>
                                        </div>
                                        <div class="row mb-3 {{isset($data) && ($data->jenis_luaran=='Buku' || $data->jenis_luaran=='Jurnal') ? '' : 'd-none'}}" id="issn">
                                            <label class="col-3 form-control-label">ISSN/ISBN: <span class="tx-danger">*</span></label>
                                            <div class="col-8">
                                                <input class="form-control" type="text" name="issn" value="{{ isset($data) ? $data->issn : Request::old('issn')}}" placeholder="Contoh: 1234-5678">
                                            </div>
                                        </div>
                                        <div class="row mb-3 {{isset($data) && $data->jenis_luaran=='HKI Paten' ? '' : 'd-none'}}" id="no_paten">
                                            <label class="col-3 form-control-label">No. Paten: <span class="tx-danger">*</span></label>
                                            <div class="col-8">
                                                <input class="form-control" type="text" name="no_paten" value="{{ isset($data) ? $data->no_paten : Request::old('no_paten')}}" placeholder="Isikan nomor paten">
                                            </div>
                                        </div>
                                        <div class="row mb-3 {{isset($data) && $data->jenis_luaran=='HKI Paten' ? '' : 'd-none'}}" id="tgl_sah">
                                            <label class="col-3 form-control-label">Tanggal Disahkan: <span class="tx-danger">*</span></label>
                                            <div class="col-8">
                                                <input class="form-control" type="date" name="tgl_sah" value="{{ isset($data) ? $data->tgl_sah : Request::old('tgl_sah')}}">
                                            </div>
                                        </div>
                                        <div class="row mb-3 {{isset($data) && $data->jenis_luaran=='HKI' ? '' : 'd-none'}}" id="no_permohonan">
                                            <label class="col-3 form-control-label">No. Permohonan: <span class="tx-danger">*</span></label>
                                            <div class="col-8">
                                                <input class="form-control" type="text" name="no_permohonan" value="{{ isset($data) ? $data->no_permohonan : Request::old('no_permohonan')}}" placeholder="Isikan nomor permohonan HKI">
                                            </div>
                                        </div>
                                        <div class="row mb-3 {{isset($data) && $data->jenis_luaran=='HKI' ? '' : 'd-none'}}" id="tgl_permohonan">
                                            <label class="col-3 form-control-label">Tanggal Permohonan: <span class="tx-danger">*</span></label>
                                            <div class="col-8">
                                                <input class="form-control" type="date" name="tgl_permohonan" value="{{ isset($data) ? $data->tgl_permohonan : Request::old('tgl_permohonan')}}">
                                            </div>
                                        </div>
                                        <div class="row mb-3 {{isset($data) && $data->jenis_luaran=='Buku' ? '' : 'd-none'}}" id="penerbit">
                                            <label class="col-3 form-control-label">Penerbit: <span class="tx-danger">*</span></label>
                                            <div class="col-8">
                                                <input class="form-control" type="text" name="penerbit" value="{{ isset($data) ? $data->penerbit : Request::old('penerbit')}}" placeholder="Isikan nama penerbit">
                                            </div>
                                        </div>
                                        <div class="row mb-3 {{isset($data) && $data->jenis_luaran=='Jurnal' ? '' : 'd-none'}}" id="penyelenggara">
                                            <label class="col-3 form-control-label">Penyelenggara: <span class="tx-danger">*</span></label>
                                            <div class="col-8">
                                                <input class="form-control" type="text" name="penyelenggara" value="{{ isset($data) ? $data->penyelenggara : Request::old('penyelenggara')}}" placeholder="Isikan nama penyelenggara">
                                            </div>
                                        </div>
                                        <div class="row mb-3 {{isset($data) && ($data->jenis_luaran=='Buku' || $data->jenis_luaran=='Jurnal') ? '' : 'd-none'}}" id="url">
                                            <label class="col-3 form-control-label">URL:</label>
                                            <div class="col-8">
                                                <input class="form-control" type="url" name="url" value="{{isset($data) ? $data->url : Request::old('url')}}" placeholder="Contoh: http://mub.journal.org/">
                                            </div>
                                        </div>
                                        <div class="row mb-3 {{isset($data) ? '' : 'd-none'}}" id="keterangan">
                                            <label class="col-3 form-control-label">Keterangan:</label>
                                            <div class="col-8">
                                                {{-- <input class="form-control" type="text" name="keterangan" value="{{ isset($data) ? $data->keterangan : Request::old('keterangan')}}" placeholder="Isikan keterangan tambahan"> --}}
                                                <textarea class="form-control" name="keterangan" placeholder="Isikan keterangan tambahan" rows="6">{{ isset($data) ? $data->keterangan : Request::old('keterangan')}}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row mg-t-20 {{isset($data) ? '' : 'd-none'}}" id="file">
                                            <label class="col-sm-3 form-control-label align-items-start pd-t-12">Unggah File:</label>
                                            <div class="col-sm-7">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="file_karya" id="file_karya" accept=".pdf" {{ isset($data) ? '' : 'required'}}>
                                                    <label class="custom-file-label custom-file-label-primary" for="file_karya">{{isset($data->file_karya) ? $data->file_karya : 'Pilih fail'}}</label>
                                                </div>
                                                <small class="w-100">
                                                    Harap dikemas dalam 1 PDF jika lebih dari 1 file.
                                                </small>
                                            </div>
                                            @isset($data->file_karya)
                                            <div class="col-sm-1">
                                                <button class="btn btn-danger w-100 btn-delfile" data-dest="{{route('output-activity.file.delete','type=file&id='.encrypt($data->id))}}">X</button>
                                            </div>
                                            @endisset
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
