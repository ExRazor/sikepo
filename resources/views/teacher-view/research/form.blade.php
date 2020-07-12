@extends('layouts.master')

@section('title', isset($data) ? 'Edit Data Penelitian' : 'Tambah Data Penelitian')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'profile-research-edit' : 'profile-research-add', isset($data) ? $data : '' ) as $breadcrumb)
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
            <p class="mg-b-0">Sunting Data Penelitian</p>
        </div>
        @else
        <div>
            <h4>Tambah</h4>
            <p class="mg-b-0">Tambah Data Penelitian</p>
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
            <form id="research_form" action="{{route('profile.research.store')}}" method="POST" enctype="multipart/form-data" data-parsley-validate>
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
                                <label class="col-3 form-control-label">Tahun Akademik: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select class="form-control select-academicYear" name="id_ta" required>
                                        @isset($data)
                                        <option value="{{$data->id_ta}}">{{$data->academicYear->tahun_akademik.' - '.$data->academicYear->semester}}</option>
                                        @endisset
                                    </select>
                                    <input type="hidden" name="ketua_nidn" value="{{Auth::user()->username}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Judul Penelitian: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="judul_penelitian" value="{{ isset($data) ? $data->judul_penelitian : Request::old('judul_penelitian')}}" placeholder="Masukkan judul penelitian" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Tema Penelitian: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="tema_penelitian" value="{{ isset($data) ? $data->tema_penelitian : Request::old('tema_penelitian')}}" placeholder="Masukkan tema penelitian sesuai roadmap" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 form-control-label">Tingkat Penelitian: <span class="tx-danger">*</span></label>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-sm-4 mg-t-15">
                                            <label class="rdiobox">
                                                <input name="tingkat_penelitian" type="radio" value="Internasional" {{ isset($data) && ($data->tingkat_penelitian=='Internasional' || Request::old('tingkat_penelitian')=='Internasional') ? 'checked' : ''}} required>
                                                <span>Internasional</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-4 mg-t-15">
                                            <label class="rdiobox">
                                                <input name="tingkat_penelitian" type="radio" value="Nasional" {{ isset($data) && ($data->tingkat_penelitian=='Nasional' || Request::old('tingkat_penelitian')=='Nasional') ? 'checked' : ''}} required>
                                                <span>Nasional</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-4 mg-t-15">
                                            <label class="rdiobox">
                                                <input name="tingkat_penelitian" type="radio" value="Lokal" {{ isset($data) && ($data->tingkat_penelitian=='Lokal' || Request::old('tingkat_penelitian')=='Lokal') ? 'checked' : ''}} required>
                                                <span>Lokal/Wilayah</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Bidang: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <label class="ckbox ckbox-inline mb-0 mr-4">
                                        <input name="sesuai_prodi" type="checkbox" value="1" {{ isset($data) && isset($data->sesuai_prodi) || Request::old('sesuai_prodi')=='1' ? 'checked' : ''}}>
                                        <span class="pl-0">Sesuai Bidang Prodi?</span>
                                    </label>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Jumlah SKS: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control number" type="text" name="sks_penelitian" value="{{ isset($data) ? $data->sks_penelitian : Request::old('sks_penelitian')}}" placeholder="Masukkan jumlah SKS" value="3" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Sumber Biaya: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-6">
                                            <select id="sumber_biaya_select" class="form-control" name="sumber_biaya" required>
                                                <option value="">- Pilih Sumber Biaya -</option>
                                                <option value="Perguruan Tinggi" {{ (isset($data) && $data->sumber_biaya == 'Perguruan Tinggi') ? 'selected' : '' }}>Perguruan Tinggi</option>
                                                <option value="Mandiri" {{ (isset($data) && $data->sumber_biaya == 'Mandiri') ? 'selected' : '' }}>Mandiri</option>
                                                <option value="Lembaga Dalam Negeri" {{ (isset($data) && $data->sumber_biaya == 'Lembaga Dalam Negeri') ? 'selected' : '' }}>Lembaga Dalam Negeri</option>
                                                <option value="Lembaga Luar Negeri" {{ (isset($data) && $data->sumber_biaya == 'Lembaga Luar Negeri') ? 'selected' : '' }}>Lembaga Luar Negeri</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <input id="sumber_biaya_input" class="form-control" type="text" name="sumber_biaya_nama" value="{{ isset($data) ? $data->sumber_biaya_nama : Request::old('sumber_biaya_nama')}}" placeholder="Tuliskan nama lembaga" {{isset($data) && $data->sumber_biaya_nama!='' ? 'required' : 'disabled'}}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Jumlah Biaya: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                            Rp
                                            </div>
                                        </div>
                                        <input class="form-control rupiah" type="text" name="jumlah_biaya" value="{{ isset($data) ? $data->jumlah_biaya : Request::old('jumlah_biaya')}}" placeholder="Masukkan jumlah biaya untuk penelitian" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-9 mx-auto">
                            <h3 class="text-center mb-3">Anggota Dosen</h3>
                            @isset($data)
                            <div id="daftarDosen">
                                @foreach ($data->researchAnggota as $i => $rt)
                                <div class="row mb-3 justify-content-center align-items-center">
                                    <button class="btn btn-danger btn-sm btn-delget" data-dest="{{ route('profile.research.teacher.delete',encode_id($data->id)) }}" data-id="{{encrypt($rt->id)}}"><i class="fa fa-times"></i></button>
                                    <div class="col-7">
                                        <div id="pilihDosen{{$i}}" class="parsley-select">
                                            <select class="form-control select-dsn" data-parsley-class-handler="#pilihDosen{{$i}}" data-parsley-errors-container="#errorsPilihDosen{{$i}}" name="anggota_nidn[]" required>
                                                <option value="{{$rt->nidn}}">{{$rt->teacher->nama.' ('.$rt->teacher->nidn.')'}}</option>
                                            </select>
                                        </div>
                                        <div id="errorsPilihDosen{{$i}}"></div>
                                    </div>
                                    {{-- <div class="col-2">
                                        <input class="form-control number" type="text" name="anggota_nidn[]" value="{{ isset($rt) ? $rt->nidn : Request::old('nidn')}}" placeholder="Masukkan NIDN Dosen" value="3" required>
                                    </div>
                                    <div class="col-4">
                                        <input class="form-control" type="text" name="anggota_nama_lain[]" value="{{ isset($rt) ? $rt->nama_lain : Request::old('nama_lain')}}" placeholder="Masukkan Nama Dosen" value="3" required>
                                    </div>
                                    <div class="col-3">
                                        <input class="form-control" type="text" name="anggota_asal_lain[]" value="{{ isset($rt) ? $rt->asal_lain : Request::old('asal_lain')}}" placeholder="Masukkan Asal Dosen" value="3" required>
                                    </div>
                                    <div clas="col-1">
                                        <label class="ckbox ckbox-inline mb-0 mr-4">
                                            <input name="sesuai_prodi" type="checkbox" value="1" {{ isset($data) && isset($data->sesuai_prodi) || Request::old('sesuai_prodi')=='1' ? 'checked' : ''}}>
                                            <span class="pl-0">Custom?</span>
                                        </label>
                                    </div> --}}
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
                        <div class="col-9 mx-auto">
                            <h3 class="text-center mb-3">Mahasiwa yang Terlibat</h3>
                            @isset($data)
                            <div id="daftarMahasiswa">
                                @foreach ($data->researchStudent as $i => $rs)
                                <div class="row mb-3 justify-content-center align-items-center">
                                    <button class="btn btn-danger btn-sm btn-delget" data-dest="{{ route('profile.research.students.delete',encode_id($data->id)) }}" data-id="{{encrypt($rs->id)}}"><i class="fa fa-times"></i></button>
                                    <div class="col-7">
                                        <div id="pilihMhs{{$i}}" class="parsley-select">
                                            <select class="form-control select-mhs" data-parsley-class-handler="#pilihMhs{{$i}}" data-parsley-errors-container="#errorsPilihMhs{{$i}}" name="mahasiswa_nim[]" required>
                                                <option value="{{$rs->nim}}">{{$rs->student->nama.' ('.$rs->student->nim.')'}}</option>
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

@section('js')
@endsection
