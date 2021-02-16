@extends('layouts.master')

@section('title', isset($data) ? 'Edit Data Publikasi' : 'Tambah Data Publikasi')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'publication-edit' : 'publication-create', isset($data) ? $data : '' ) as $breadcrumb)
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
            <p class="mg-b-0">Sunting Data Publikasi</p>
        </div>
        @else
        <div>
            <h4>Tambah</h4>
            <p class="mg-b-0">Tambah Data Publikasi</p>
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
    @if (session()->has('flash.message'))
        <div class="alert alert-{{ session('flash.class') }}" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('flash.message') }}
        </div>
    @endif
    <div class="widget-2">
        <div class="card mb-3">
            <form id="publication_form" action="@isset($data) {{route('publication.update',encrypt($data->id))}} @else {{route('publication.store')}} @endisset" method="POST" enctype="multipart/form-data" data-parsley-validate>
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

                            {{-- <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Dosen: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <select id="select-dosen" class="form-control select2-dosen" name="nidn" @if(Auth::user()->hasRole('kaprodi')) data-prodi={{Auth::user()->kd_prodi}} @endif required>
                                        <option value="">- Pilih Dosen -</option>
                                        @isset($data)
                                        @foreach ($teacher as $t)
                                            <option value="{{$t->nidn}}" {{$data->nidn == $t->nidn ? 'selected':''}}>{{$t->nama}}</option>
                                        @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div> --}}
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Jenis Publikasi: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="input-group">
                                            <div class="{{Auth::user()->hasRole('admin') ? 'col-sm-9' : 'col-sm-12'}}">
                                                <select class="form-control mb-2" name="jenis_publikasi" required>
                                                    <option value="">- Pilih Jenis Publikasi -</option>
                                                    @foreach ($jenis as $j)
                                                        <option value="{{$j->id}}" {{(isset($data) && $data->jenis_publikasi == $j->id) || old('jenis_publikasi') == $j->id ? 'selected':''}}>{{$j->nama}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if(Auth::user()->hasRole('admin'))
                                            <div class="col-sm-3">
                                                <a href="{{ route('master.publication-category') }}" class="btn btn-teal btn-block mg-b-10" style="color:white">Tambah</a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Judul: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" name="judul" value="{{ isset($data) ? $data->judul : Request::old('judul')}}" placeholder="Contoh: Permasalahan Permukiman Perdesaan Di Indonesia: Kajian geografi Permukiman" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Penerbit: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" name="penerbit" value="{{ isset($data) ? $data->penerbit : Request::old('penerbit')}}" placeholder="Contoh: Kenangan Press" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Tahun Terbit: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control select-academicYear" name="id_ta" required>
                                        @isset($data)
                                        <option value="{{$data->id_ta}}">{{$data->academicYear->tahun_akademik.' - '.$data->academicYear->semester}}</option>
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Bidang: </label>
                                <div class="col-md-8">
                                    <label class="ckbox ckbox-inline mb-0 mr-4">
                                        <input name="sesuai_prodi" type="checkbox" value="1" {{ isset($data) && isset($data->sesuai_prodi) || Request::old('sesuai_prodi')=='1' ? 'checked' : ''}}>
                                        <span class="pl-0">Sesuai Bidang Prodi?</span>
                                    </label>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Nama Terbitan:</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" name="jurnal" value="{{ isset($data) ? $data->jurnal : Request::old('jurnal')}}" placeholder="Contoh: Asian Social Science Vol.9, No.12">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Jumlah Sitasi:</label>
                                <div class="col-md-8">
                                    <input class="form-control number" type="text" name="sitasi" value="{{ isset($data) ? $data->sitasi : Request::old('sitasi')}}" placeholder="Masukkan jumlah sitasi">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Akreditasi:</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" name="akreditasi" value="{{ isset($data) ? $data->akreditasi : Request::old('akreditasi')}}" placeholder="Jika belum terakreditasi, dikosongkan saja">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Tautan:</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" name="tautan" value="{{ isset($data) ? $data->tautan : Request::old('tautan')}}" placeholder="Jika belum ada tautan digital, dikosongkan saja">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-md-9 mx-auto">
                            <h3 class="text-center mb-3">Penulis Utama</h3>
                            <div id="penulisUtama">
                                <div class="row mb-3 justify-content-center align-items-center">
                                    <div class="col-md-3 mb-3">
                                        <select class="form-control mb-2" name="status_penulis_utama" id="status_penulis_utama" required>
                                            <option value="">- Pilih Data Penulis -</option>
                                            <option value="Dosen" {{ isset($data) && $data->penulisUtama->status=='Dosen' ? 'selected' : '' }}>Dosen Jurusan</option>
                                            <option value="Mahasiswa" {{ isset($data) && $data->penulisUtama->status=='Mahasiswa' ? 'selected' : '' }}>Mahasiswa Jurusan</option>
                                            <option value="Lainnya" {{ isset($data) && $data->penulisUtama->status=='Lainnya' ? 'selected' : '' }}>Luar Jurusan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3 tipe-non-lainnya" style="{{ isset($data) && $data->penulisUtama->status!='Lainnya' ? '' : 'display:none;' }}">
                                        <input class="form-control" type="text" name="utama_idunik" value="{{ isset($data) ? $data->penulisUtama->id_unik : Request::old('utama_idunik')}}" placeholder="NIDN/NIM" {{ isset($data) && ($data->penulisUtama->status=='Dosen' || $data->penulisUtama->status=='Mahasiswa' ) ? 'required' : '' }}>
                                    </div>
                                    <div class="col-md-5 mb-3 tipe-lainnya" style="{{ isset($data) && $data->penulisUtama->status=='Lainnya' ? '' : 'display:none;' }}">
                                        <input class="form-control" type="text" name="utama_nama" value="{{ isset($data) ? $data->penulisUtama->nama : Request::old('utama_nama')}}" placeholder="Nama Penulis" {{ isset($data) && $data->penulisUtama->status=='Lainnya' ? 'required' : '' }}>
                                    </div>
                                    <div class="col-md-4 mb-3 tipe-lainnya" style="{{ isset($data) && $data->penulisUtama->status=='Lainnya' ? '' : 'display:none;' }}">
                                        <input class="form-control" type="text" name="utama_asal" value="{{ isset($data) ? $data->penulisUtama->asal : Request::old('utama_asal')}}" placeholder="Asal Penulis" {{ isset($data) && $data->penulisUtama->status=='Lainnya' ? 'required' : '' }}>
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

@push('custom-js')
<script>
    $('#status_penulis_utama').on('change', function() {
        var val = $(this).val();

        var lainnya     = $('.tipe-lainnya');
        var nonlainnya  = $('.tipe-non-lainnya');

        if(val=='Lainnya') {
            nonlainnya.hide();
            nonlainnya.find('input').prop('disabled',true);
            nonlainnya.find('input').prop('required',false);

            lainnya.show();
            lainnya.find('input').prop('disabled',false);
            lainnya.find('input').prop('required',true);
        } else if (val=='') {
            nonlainnya.hide();
            lainnya.hide();
            nonlainnya.find('input').prop('disabled',true);
            nonlainnya.find('input').prop('required',false);
            lainnya.find('input').prop('disabled',true);
            lainnya.find('input').prop('required',false);
        } else {
            lainnya.hide();
            lainnya.find('input').prop('disabled',true);
            lainnya.find('input').prop('required',false);

            nonlainnya.show();
            nonlainnya.find('input').prop('disabled',false);
            nonlainnya.find('input').prop('required',true);
        }
    });
</script>
@endpush
