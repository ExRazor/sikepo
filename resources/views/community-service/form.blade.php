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
                                <label class="col-md-3 form-control-label">Ketua Pelaksana: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control select2-dosen" name="ketua_nidn" @if(Auth::user()->hasRole('kaprodi')) data-prodi={{Auth::user()->kd_prodi}} @endif required>
                                        @isset($data)
                                        <option value="{{$data->serviceKetua->nidn}}">{{$data->serviceKetua->teacher->nama.' ('.$data->serviceKetua->nidn.')'}}</option>
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
                                                <option value="Perguruan Tinggi" {{ (isset($data) && $data->sumber_biaya == 'Perguruan Tinggi') ? 'selected' : '' }}>Perguruan Tinggi</option>
                                                <option value="Mandiri" {{ (isset($data) && $data->sumber_biaya == 'Mandiri') ? 'selected' : '' }}>Mandiri</option>
                                                <option value="Lembaga Dalam Negeri" {{ (isset($data) && $data->sumber_biaya == 'Lembaga Dalam Negeri') ? 'selected' : '' }}>Lembaga Dalam Negeri</option>
                                                <option value="Lembaga Luar Negeri" {{ (isset($data) && $data->sumber_biaya == 'Lembaga Luar Negeri') ? 'selected' : '' }}>Lembaga Luar Negeri</option>
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
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-9 mx-auto">
                            <h3 class="text-center mb-3">Anggota Dosen</h3>
                            @isset($data)
                            <div id="daftarDosen">
                                @foreach ($data->serviceAnggota as $i => $rt)
                                <div class="row mb-3 justify-content-center align-items-center">
                                    <button class="btn btn-danger btn-sm btn-delget" data-dest="{{ route('community-service.teacher.delete',encode_id($data->id)) }}" data-id="{{encrypt($rt->id)}}"><i class="fa fa-times"></i></button>
                                    <div class="col-7">
                                        <div id="pilihDosen{{$i}}" class="parsley-select">
                                            <select class="form-control select2-dosen" data-parsley-class-handler="#pilihDosen{{$i}}" data-parsley-errors-container="#errorsPilihDosen{{$i}}" name="anggota_nidn[]" required>
                                                <option value="{{$rt->nidn}}">{{$rt->teacher->nama.' ('.$rt->teacher->nidn.')'}}</option>
                                            </select>
                                        </div>
                                        <div id="errorsPilihDosen{{$i}}"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endisset
                            <div id="panelDosen" data-jumlah="0"></div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                <button class="add-dosen btn btn-primary" href="javascript:void(0)"><i class="fa fa-plus pd-r-10"></i> Tambah</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-9 mx-auto">
                            <h3 class="text-center mb-3">Daftar Mahasiswa</h3>
                            @isset($data)
                            <div id="daftarMahasiswa">
                                @foreach ($data->serviceStudent as $i => $cs)
                                <div class="row mb-3 justify-content-center align-items-center">
                                    <button class="btn btn-danger btn-sm btn-delget" data-dest="{{ route('community-service.students.delete',encode_id($data->id)) }}" data-id="{{encrypt($cs->id)}}"><i class="fa fa-times"></i></button>
                                    <div class="col-7">
                                        <div id="pilihMhs{{$i}}" class="parsley-select">
                                            <select class="form-control select-mhs" data-parsley-class-handler="#pilihMhs{{$i}}" data-parsley-errors-container="#errorsPilihMhs{{$i}}" name="mahasiswa_nim[]" required>
                                                <option value="{{$cs->nim}}">{{$cs->student->nama.' ('.$cs->student->nim.')'}}</option>
                                            </select>
                                        </div>
                                        <div id="errorsPilihMhs{{$i}}"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endisset
                            <div id="panelMahasiswa" data-jumlah="0"></div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                <button class="add-mahasiswa btn btn-primary" href="javascript:void(0)"><i class="fa fa-plus pd-r-10"></i> Tambah</button>
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
    var cont  = $('.select2-dosen');
    var prodi = cont.attr('data-prodi');
    select2_dosen(cont,prodi);
</script>
@endpush

