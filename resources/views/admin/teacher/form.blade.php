@extends('layouts.master')

@section('title', 'Data Program Studi')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'teacher-edit' : 'teacher-add' ) as $breadcrumb)
        <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
        @endforeach
    </nav>
</div>

<div class="br-pagetitle">
        <i class="icon ion-calendar"></i>
        @if(isset($data))
        <div>
            <h4>Sunting</h4>
            <p class="mg-b-0">Sunting Data Dosen</p>
        </div>
        @else
        <div>
                <h4>Tambah</h4>
                <p class="mg-b-0">Tambah Data Dosen</p>
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
            <div class="card-header bd rounded-top bd-color-gray-lighter">
                <h6 class="card-title">Import Data dari Excel</h6>
            </div>
            <div class="card-body bd bd-t-0 rounded-bottom bd-color-gray-lighter">
                <div class="row">
                    <div class="col-md-9 mx-auto">
                        <form action="/teacher/import-excel" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            @csrf
                            @if(isset($data))
                                @method('put')
                                <input type="hidden" name="id" value="{{encrypt($data->id)}}">
                            @else
                                @method('post')
                            @endif
                            <div class="form-group row mb-3">
                                <label class="col-3 form-control-label">Program Studi: <span class="tx-danger">*</span></label>
                                <div class="col-6">
                                    <div id="prodi" class="parsley-select">
                                        <select class="form-control select2" name="kd_prodi" data-placeholder="Pilih Prodi" data-parsley-class-handler="#prodi"
                                        data-parsley-errors-container="#errorProdi" required>
                                            <option></option>
                                            @foreach ($studyProgram as $sp)
                                            <option value="{{$sp->kd_prodi}}" {{ isset($data) && ($data->kd_prodi==$sp->kd_prodi || Request::old('kd_prodi')==$sp->kd_prodi) ? 'selected' : ''}}>{{$sp->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="errorProdi"></div>
                                    </div>
                                </div>
                            </div><!-- row -->
                            <div class="form-group row">
                                <label class="col-3 form-control-label">Excel Data Dosen:</label>
                                <div class="col-6">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="bukti" id="bukti_kerjasama" {{ isset($data) ? '' : 'required'}}>
                                        <label class="custom-file-label custom-file-label-primary" for="bukti_kerjasama">Pilih fail</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <button type="submit" class="btn btn-info">Upload</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- card-body -->
        </div>
        <div class="card mb-3">
            <div class="card-header bd rounded-top bd-color-gray-lighter">
                <h6 class="card-title">Tambah Data Manual</h6>
            </div>
            <div class="card-body bd bd-y-0 bd-color-gray-lighter">
                <div class="row">
                    <div class="col-9 mx-auto">
                        <form action="/master/study-program" method="POST">
                            @csrf
                            @if(isset($data))
                                @method('put')
                            @else
                                @method('post')
                            @endif
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Program Studi: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div id="prodi" class="parsley-select">
                                        <select class="form-control select2" name="kd_prodi" data-placeholder="Pilih Prodi" data-parsley-class-handler="#prodi"
                                        data-parsley-errors-container="#errorProdi" required>
                                            <option></option>
                                            @foreach ($studyProgram as $sp)
                                            <option value="{{$sp->kd_prodi}}" {{ isset($data) && ($data->kd_prodi==$sp->kd_prodi || Request::old('kd_prodi')==$sp->kd_prodi) ? 'selected' : ''}}>{{$sp->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="errorProdi"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Nama Dosen: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="nama" value="{{ isset($data) ? $data->nama : Request::old('nama')}}" placeholder="Masukkan Nama Dosen">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Jenis Kelamin: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-lg-3 mg-t-15">
                                            <label class="rdiobox">
                                                <input name="jk" type="radio" value="Laki-Laki" {{ isset($data) && ($data->jk=='Laki-Laki' || Request::old('jk')=='Laki-Laki') ? 'checked' : ''}} required>
                                                <span>Laki-Laki</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-3 mg-t-15">
                                            <label class="rdiobox">
                                                <input name="jk" type="radio" value="Perempuan" {{ isset($data) && ($data->jk=='Perempuan' || Request::old('jk')=='Perempuan') ? 'checked' : ''}} required>
                                                <span>Perempuan</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Agama: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div id="agama" class="parsley-select">
                                        <select class="form-control select2" name="agama" data-placeholder="Pilih Agama" data-parsley-class-handler="#agama"
                                        data-parsley-errors-container="#errorAgama" required>
                                            <option></option>
                                            <option value="Islam" {{ isset($data) && ($data->kd_prodi=='Islam' || Request::old('agama')=='Islam') ? 'selected' : ''}}>Islam</option>
                                            <option value="Kristen" {{ isset($data) && ($data->kd_prodi=='Kristen' || Request::old('agama')=='Kristen') ? 'selected' : ''}}>Kristen</option>
                                            <option value="Katholik" {{ isset($data) && ($data->kd_prodi=='Katholik' || Request::old('agama')=='Katholik') ? 'selected' : ''}}>Katholik</option>
                                            <option value="Buddha" {{ isset($data) && ($data->kd_prodi=='Buddha' || Request::old('agama')=='Buddha') ? 'selected' : ''}}>Buddha</option>
                                            <option value="Hindu" {{ isset($data) && ($data->kd_prodi=='Hindu' || Request::old('agama')=='Hindu') ? 'selected' : ''}}>Hindu</option>
                                            <option value="Kong Hu Cu" {{ isset($data) && ($data->kd_prodi=='Kong Hu Cu' || Request::old('agama')=='Kong Hu Cu') ? 'selected' : ''}}>Kong Hu Cu</option>
                                        </select>
                                        <div id="errorAgama"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Tempat/Tanggal Lahir: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-4">
                                            <input class="form-control" type="text" name="tpt_lhr" value="{{ isset($data) ? $data->tpt_lhr : Request::old('tpt_lhr')}}" placeholder="Masukkan Tempat Lahir">
                                        </div>
                                        <div class="col-8">
                                            <input class="form-control datepicker" type="text" name="tgl_lhr" value="{{ isset($data) ? $data->tgl_lhr : Request::old('tgl_lhr')}}" placeholder="Masukkan Tanggal Lahir" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Alamat: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="alamat" value="{{ isset($data) ? $data->alamat : Request::old('alamat')}}" placeholder="Masukkan Alamat Tempat Tinggal">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">No. Telepon: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="no_telp" value="{{ isset($data) ? $data->no_telp : Request::old('no_telp')}}" placeholder="Masukkan Nomor Telepon yang Dapat Dihubungi">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Email: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="email" value="{{ isset($data) ? $data->email : Request::old('email')}}" placeholder="Masukkan Email Aktif">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Pendidikan Terakhir: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-4">
                                            <div id="pend_terakhir_jenjang" class="parsley-select">
                                                <select class="form-control select2" name="pend_terakhir_jenjang" data-placeholder="Pilih Pendidikan Terakhir" data-parsley-class-handler="#pend_terakhir_jenjang"
                                                data-parsley-errors-container="#errorPendTerakhir" required>
                                                    <option></option>
                                                    <option value="D4" {{ (isset($data) && ($data->jenjang==='D4') || Request::old('jenjang')==='D4') ? 'selected' : ''}}>Diploma D4</option>
                                                    <option value="S1" {{ (isset($data) && ($data->jenjang==='S1') || Request::old('jenjang')==='S1') ? 'selected' : ''}}>Strata 1 / Sarjana</option>
                                                    <option value="S2" {{ (isset($data) && ($data->jenjang==='S2') || Request::old('jenjang')==='S2') ? 'selected' : ''}}>Strata 2 / Magister</option>
                                                    <option value="S3" {{ (isset($data) && ($data->jenjang==='S3') || Request::old('jenjang')==='S3') ? 'selected' : ''}}>Strata 3 / Doktor</option>
                                                </select>
                                                <div id="errorPendTerakhir"></div>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <input class="form-control" type="text" name="pend_terakhir_jurusan" value="{{ isset($data) ? $data->pend_terakhir_jurusan : Request::old('pend_terakhir_jurusan')}}" placeholder="Masukkan Jurusan Pendidikan Terakhir">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Bidang Keahlian: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="bidang_ahli" value="{{ isset($data) ? $data->bidang_ahli : Request::old('bidang_ahli')}}" placeholder="Masukkan Bidang Keahlian">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Status Pengajar: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div id="status_pengajar" class="parsley-select">
                                        <select class="form-control select2" name="status_pengajar" data-placeholder="Pilih Agama" data-parsley-class-handler="#status_pengajar"
                                        data-parsley-errors-container="#errorStatusPengajar" required>
                                            <option></option>
                                            <option value="DT" {{ isset($data) && ($data->kd_prodi=='DT' || Request::old('agama')=='DT') ? 'selected' : ''}}>Dosen Tetap</option>
                                            <option value="DTT" {{ isset($data) && ($data->kd_prodi=='DTT' || Request::old('agama')=='DTT') ? 'selected' : ''}}>Dosen Tidak Tetap</option>
                                        </select>
                                        <div id="errorStatusPengajar"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Jabatan Akademik: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="jabatan_akademik" value="{{ isset($data) ? $data->jabatan_akademik : Request::old('jabatan_akademik')}}" placeholder="Masukkan Jabatan Akademik">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">No. Sertifikat Pendidik: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="sertifikat_pendidik" value="{{ isset($data) ? $data->sertifikat_pendidik : Request::old('sertifikat_pendidik')}}" placeholder="Masukkan No. Sertifikat Pendidik">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Sesuai Bidang PS? <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-lg-3 mg-t-15">
                                            <label class="rdiobox">
                                                <input name="sesuai_bidang_ps" type="radio" value="Ya" {{ isset($data) && ($data->sesuai_bidang_ps=='Ya' || Request::old('sesuai_bidang_ps')=='Ya') ? 'checked' : ''}} required>
                                                <span>Ya</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-3 mg-t-15">
                                            <label class="rdiobox">
                                                <input name="sesuai_bidang_ps" type="radio" value="Tidak" {{ isset($data) && ($data->sesuai_bidang_ps=='Tidak' || Request::old('sesuai_bidang_ps')=='Tidak') ? 'checked' : ''}} required>
                                                <span>Tidak</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div><!-- card-body -->
            <div class="card-footer bd bd-color-gray-lighter rounded-bottom">
                <div class="row">
                    <div class="col-6 mx-auto">
                        <div class="text-center">
                            <button type="submit" class="btn btn-info">Simpan</button>
                            <a href="{{route('master.study-program')}}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </div>
            </div><!-- card-footer -->

        </div>
    </div>
</div>
@endsection

@section('js')
@endsection
