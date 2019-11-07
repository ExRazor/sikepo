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
            <form id="research_form" action="{{route('research.store')}}" method="POST" enctype="multipart/form-data" data-parsley-validate>
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
                                <label class="col-3 form-control-label">Program Studi: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select id="prodi_dosen" class="form-control" name="kd_prodi" required>
                                        <option value="">- Pilih Prodi -</option>
                                        @foreach($studyProgram as $sp)
                                        <option value="{{$sp->kd_prodi}}" {{ (isset($data) && ($sp->kd_prodi==$data->teacher->kd_prodi) || Request::old('kd_prodi')==$sp->kd_prodi) ? 'selected' : ''}}>{{$sp->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Dosen: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select id="select-dosen" class="form-control" name="nidn" required>
                                        <option value="">- Pilih Dosen -</option>
                                        @isset($data)
                                        @foreach ($teacher as $t)
                                            <option value="{{$t->nidn}}" {{$data->nidn == $t->nidn ? 'selected':''}}>{{$t->nama}}</option>
                                        @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Tema Penelitian: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="tema_penelitian" value="{{ isset($data) ? $data->tema_penelitian : Request::old('tema_penelitian')}}" placeholder="Masukkan tema penelitian sesuai roadmap" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Judul Penelitian: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="judul_penelitian" value="{{ isset($data) ? $data->judul_penelitian : Request::old('judul_penelitian')}}" placeholder="Masukkan judul penelitian" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Tahun Penelitian: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control number" type="text" name="tahun_penelitian" value="{{ isset($data) ? $data->tahun_penelitian : Request::old('tahun_penelitian')}}" placeholder="Masukkan Tahun Penelitian" maxlength="4" required>
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
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-9 mx-auto">
                            <h3 class="text-center mb-3">Daftar Mahasiswa</h3>
                            @isset($data)
                            <div id="daftarMahasiswa">
                                @foreach ($data->researchStudents as $i => $rs)
                                <div class="row mb-3 align-items-center">
                                    <button class="btn btn-danger btn-sm btn-delget" data-dest="{{ route('research.students.delete',encode_id($data->id)) }}" data-id="{{encrypt($rs->id)}}"><i class="fa fa-times"></i></button>
                                    <div class="col-2">
                                        <input class="form-control number" type="text" name="mahasiswa_nim[]" value="{{ $rs->nim }}" placeholder="NIM" maxlength="9" readonly>
                                    </div>
                                    <div class="col-5">
                                        <input class="form-control" type="text" name="mahasiswa_nama[]" value="{{ $rs->nama }}" placeholder="Nama Mahasiswa" required>
                                    </div>
                                    <div class="col-4">
                                        <div id="prodiMhs{{$i}}" class="parsley-select">
                                            <select class="form-control select-prodi" data-parsley-class-handler="#prodiMhs{{$i}}" data-parsley-errors-container="#errorsProdiMhs{{$i}}" name="mahasiswa_prodi[]" required>
                                                <option value="{{$rs->kd_prodi}}">{{$rs->studyProgram->nama}}</option>
                                            </select>
                                        </div>
                                        <div id="errorsProdiMhs{{$i}}"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endisset
                            <div id="panelMahasiswa" data-jumlah="0"></div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                <a class="add-mahasiswa btn btn-primary" href="javascript:void(0)"><i class="fa fa-plus pd-r-10"></i> Tambah</a>
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
