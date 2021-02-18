@extends('layouts.master')

@section('title', isset($data) ? 'Edit Data Pengabdian' : 'Tambah Data Pengabdian')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'community-service-edit' : 'community-service-create', isset($data) ? $data : '' ) as $breadcrumb)
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
            <p class="mg-b-0">Sunting Data Pengabdian</p>
        </div>
        @else
        <div>
            <h4>Tambah</h4>
            <p class="mg-b-0">Tambah Data Pengabdian</p>
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
            <form id="communityService_form" action="@isset($data) {{route('community-service.update',$data->id)}} @else {{route('community-service.store')}} @endisset" method="POST" enctype="multipart/form-data" data-parsley-validate>
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
                                <label class="col-md-3 form-control-label">Tahun Akademik: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control select-academicYear" name="id_ta" required>
                                        @isset($data)
                                        <option value="{{$data->id_ta}}">{{$data->academicYear->tahun_akademik.' - '.$data->academicYear->semester}}</option>
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Judul Pengabdian: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" name="judul_pengabdian" value="{{ isset($data) ? $data->judul_pengabdian : Request::old('judul_pengabdian')}}" placeholder="Masukkan judul pengabdian" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Tema Pengabdian: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" name="tema_pengabdian" value="{{ isset($data) ? $data->tema_pengabdian : Request::old('tema_pengabdian')}}" placeholder="Masukkan tema pengabdian sesuai roadmap" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Tingkat Pengabdian: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-sm-4 mg-t-15">
                                            <label class="rdiobox">
                                                <input name="tingkat_pengabdian" type="radio" value="Internasional" {{ (isset($data) && $data->tingkat_pengabdian=='Internasional') || Request::old('tingkat_pengabdian')=='Internasional' ? 'checked' : ''}} required>
                                                <span>Internasional</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-4 mg-t-15">
                                            <label class="rdiobox">
                                                <input name="tingkat_pengabdian" type="radio" value="Nasional" {{ (isset($data) && $data->tingkat_pengabdian=='Nasional') || Request::old('tingkat_pengabdian')=='Nasional' ? 'checked' : ''}} required>
                                                <span>Nasional</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-4 mg-t-15">
                                            <label class="rdiobox">
                                                <input name="tingkat_pengabdian" type="radio" value="Lokal" {{ (isset($data) && $data->tingkat_pengabdian=='Lokal') || Request::old('tingkat_pengabdian')=='Lokal' ? 'checked' : ''}} required>
                                                <span>Lokal/Wilayah</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Bidang: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <label class="ckbox ckbox-inline mb-0 mr-4">
                                        <input name="sesuai_prodi" type="checkbox" value="1" {{ isset($data) && isset($data->sesuai_prodi) || Request::old('sesuai_prodi')=='1' ? 'checked' : ''}}>
                                        <span class="pl-0">Sesuai Bidang Prodi?</span>
                                    </label>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Jumlah SKS: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <input class="form-control number" type="text" name="sks_pengabdian" value="{{ isset($data) ? $data->sks_pengabdian : Request::old('sks_pengabdian')}}" placeholder="Masukkan jumlah SKS" value="3" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Sumber Biaya: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select id="sumber_biaya_select" class="form-control" name="sumber_biaya" required>
                                                <option value="">- Pilih Sumber Biaya -</option>
                                                <option value="Perguruan Tinggi" {{ (isset($data) && $data->sumber_biaya == 'Perguruan Tinggi') || Request::old('sumber_biaya') == 'Perguruan Tinggi' ? 'selected' : '' }}>Perguruan Tinggi</option>
                                                <option value="Mandiri" {{ (isset($data) && $data->sumber_biaya == 'Mandiri') || Request::old('sumber_biaya') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                                <option value="Lembaga Dalam Negeri" {{ (isset($data) && $data->sumber_biaya == 'Lembaga Dalam Negeri') || Request::old('sumber_biaya') == 'Lembaga Dalam Negeri' ? 'selected' : '' }}>Lembaga Dalam Negeri</option>
                                                <option value="Lembaga Luar Negeri" {{ (isset($data) && $data->sumber_biaya == 'Lembaga Luar Negeri') || Request::old('sumber_biaya') == 'Lembaga Luar Negeri' ? 'selected' : '' }}>Lembaga Luar Negeri</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input id="sumber_biaya_input" class="form-control" type="text" name="sumber_biaya_nama" value="{{ isset($data) ? $data->sumber_biaya_nama : Request::old('sumber_biaya_nama')}}" placeholder="Tuliskan nama lembaga" {{isset($data) && $data->sumber_biaya_nama!='' ? 'required' : 'disabled'}}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Jumlah Biaya: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                            Rp
                                            </div>
                                        </div>
                                        <input class="form-control rupiah" type="text" name="jumlah_biaya" value="{{ isset($data) ? $data->jumlah_biaya : Request::old('jumlah_biaya')}}" placeholder="Masukkan jumlah biaya untuk pengabdian" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Bukti Fisik: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="bukti_fisik" accept=".pdf,.zip,.rar" {{ isset($data) ? '' : 'required'}}>
                                        <label class="custom-file-label custom-file-label-primary" for="bukti_kerjasama">{{ isset($data->bukti_fisik) ? $data->bukti_fisik : 'Pilih berkas'}}</label>
                                    </div>
                                    <small class="w-100">
                                        Jika bukti fisik lebih dari satu, mohon dirangkum dalam bentuk 1 file PDF/ZIP sebelum diunggah.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-9 mx-auto">
                            <h3 class="text-center mb-3">Ketua Penyelenggara</h3>
                            <div id="serviceKetua">
                                @if(isset($data) && $data->serviceKetua)
                                <div class="row mb-3 justify-content-center align-items-center">
                                    <div class="col-md-3 mb-3">
                                        <select class="form-control mb-2" name="asal_penyelenggara" id="asal_penyelenggara" required>
                                            <option value="">- Pilih Asal Dosen -</option>
                                            <option value="Jurusan" {{ (isset($data) && $data->serviceKetua->nidn) || Request::old('asal_penyelenggara') == 'Jurusan' ? 'selected' : '' }}>Dosen Jurusan</option>
                                            <option value="Luar" {{ (isset($data) && !$data->serviceKetua->nidn) || Request::old('asal_penyelenggara') == 'Luar' ? 'selected' : '' }}>Dosen Luar</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3 tipe-dosen" style="{{ (isset($data) && $data->serviceKetua->nidn) || Request::old('asal_penyelenggara') == 'Jurusan' ? '' : 'display:none;' }}">
                                        <input class="form-control" type="text" name="anggota_nidn" value="{{ isset($data) ? $data->serviceKetua->nidn : Request::old('anggota_nidn')}}" placeholder="NIDN" {{ isset($data) && $data->serviceKetua->nidn ? 'required' : '' }}>
                                    </div>
                                    <div class="col-md-5 mb-3 tipe-lainnya" style="{{ (isset($data) && !$data->serviceKetua->nidn) || Request::old('asal_penyelenggara') == 'Luar'  ? '' : 'display:none;' }}">
                                        <input class="form-control" type="text" name="anggota_nama" value="{{ isset($data) ? $data->serviceKetua->nama : Request::old('anggota_nama')}}" placeholder="Nama Dosen" {{ isset($data) && !$data->serviceKetua->nidn ? 'required' : '' }}>
                                    </div>
                                    <div class="col-md-4 mb-3 tipe-lainnya" style="{{ (isset($data) && !$data->serviceKetua->nidn) || Request::old('asal_penyelenggara') == 'Luar'  ? '' : 'display:none;' }}">
                                        <input class="form-control" type="text" name="anggota_asal" value="{{ isset($data) ? $data->serviceKetua->asal : Request::old('anggota_asal')}}" placeholder="Asal Dosen" {{ isset($data) && !$data->serviceKetua->nidn ? 'required' : '' }}>
                                    </div>
                                </div>
                                @else
                                <div class="row mb-3 justify-content-center align-items-center">
                                    <div class="col-md-3 mb-3">
                                        <select class="form-control mb-2" name="asal_penyelenggara" id="asal_penyelenggara" required>
                                            <option value="">- Pilih Asal Dosen -</option>
                                            <option value="Jurusan" {{ Request::old('asal_penyelenggara') == 'Jurusan' ? 'selected' : '' }}>Dosen Jurusan</option>
                                            <option value="Luar" {{ Request::old('asal_penyelenggara') == 'Luar' ? 'selected' : '' }}>Dosen Luar</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3 tipe-dosen" style="{{ Request::old('asal_penyelenggara') == 'Jurusan' ? '' : 'display:none;' }}">
                                        <input class="form-control" type="text" name="anggota_nidn" value="{{ Request::old('anggota_nidn')}}" placeholder="NIDN">
                                    </div>
                                    <div class="col-md-5 mb-3 tipe-lainnya" style="{{ Request::old('asal_penyelenggara') == 'Luar'  ? '' : 'display:none;' }}">
                                        <input class="form-control" type="text" name="anggota_nama" value="{{ Request::old('anggota_nama')}}" placeholder="Nama Dosen">
                                    </div>
                                    <div class="col-md-4 mb-3 tipe-lainnya" style="{{ Request::old('asal_penyelenggara') == 'Luar'  ? '' : 'display:none;' }}">
                                        <input class="form-control" type="text" name="anggota_asal" value="{{ Request::old('anggota_asal')}}" placeholder="Asal Dosen">
                                    </div>
                                </div>
                                @endif
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
{{-- <script>
    var cont  = $('.select2-dosen');
    var prodi = cont.attr('data-prodi');
    select2_dosen(cont,prodi);
</script> --}}
<script>
    $('#asal_penyelenggara').on('change', function() {
        var val = $(this).val();

        var cont    = $('#communityService_form');
        var dosen   = cont.find('.tipe-dosen');
        var lainnya = cont.find('.tipe-lainnya');

        switch(val) {
            case 'Jurusan':
                lainnya.hide();
                lainnya.find('input').prop('disabled',true).prop('required',false);

                dosen.show();
                dosen.find('input').prop('disabled',false).prop('required',true);
            break;
            case 'Luar':
                dosen.hide();
                dosen.find('input').prop('disabled',true).prop('required',false);

                lainnya.show();
                lainnya.find('input').prop('disabled',false).prop('required',true);
            break;
            default:
                dosen.hide();
                lainnya.hide();
                dosen.find('input').prop('disabled',true).prop('required',false);
                lainnya.find('input').prop('disabled',true).prop('required',false);
            break;
        }
    });
</script>
@endpush

