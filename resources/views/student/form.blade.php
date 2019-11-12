@extends('layouts.master')

@section('title', isset($data) ? 'Edit Mahasiswa' : 'Tambah Mahasiswa')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'student-edit' : 'student-add', isset($data) ? $data : '' ) as $breadcrumb)
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
            <p class="mg-b-0">Sunting Data Mahasiswa</p>
        </div>
        @else
        <div>
                <h4>Tambah</h4>
                <p class="mg-b-0">Tambah Data Mahasiswa</p>
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
            <form id="student_form" action="{{route('student.store')}}" method="POST" enctype="multipart/form-data" data-parsley-validate>
                <div class="card-body bd bd-y-0 bd-color-gray-lighter">
                    <div class="row">
                        <div class="col-9 mx-auto">
                            @csrf
                            @if(isset($data))
                                @method('put')
                                <input type="hidden" name="_id" value="{{encrypt($data->nim)}}">
                            @else
                                @method('post')
                            @endif
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Jurusan: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select class="form-control" name="kd_jurusan" data-type="form" disabled>
                                        <option value="">- Pilih Jurusan -</option>
                                        @foreach($faculty as $f)
                                            @if($f->department->count())
                                            <optgroup label="{{$f->nama}}">
                                                @foreach($f->department as $d)
                                                <option value="{{$d->kd_jurusan}}" {{ (isset($data) && $data->studyProgram->kd_jurusan==$d->kd_jurusan) || $d->kd_jurusan==setting('app_department_id') ? 'selected': ''}}>{{$d->nama}}</option>
                                                @endforeach
                                            </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
                                <label class="col-3 form-control-label">NIM: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="nim" value="{{ isset($data) ? $data->nim : Request::old('nim')}}" placeholder="Masukkan NIM" {{ isset($data) ? 'disabled' : ''}} maxlength="9" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Nama Mahasiswa: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="nama" value="{{ isset($data) ? $data->nama : Request::old('nama')}}" placeholder="Masukkan Nama Dosen" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Jenis Kelamin: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div id="jenis_kelamin" class="radio">
                                        <label class="rdiobox rdiobox-inline mb-0">
                                            <input name="jk" type="radio" value="Laki-Laki" {{ isset($data) && ($data->jk=='Laki-Laki' || Request::old('jk')=='Laki-Laki') ? 'checked' : ''}} data-parsley-class-handler="#jenis_kelamin"
                                            data-parsley-errors-container="#errorsJK" required>
                                            <span>Laki-Laki</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label>
                                        <label class="rdiobox rdiobox-inline mb-0">
                                            <input name="jk" type="radio" value="Perempuan" {{ isset($data) && ($data->jk=='Perempuan' || Request::old('jk')=='Perempuan') ? 'checked' : ''}}>
                                            <span>Perempuan</span>
                                        </label>
                                    </div>
                                    <div id="errorsJK"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Agama: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select class="form-control" name="agama" required>
                                        <option value="">- Pilih Agama -</option>
                                        <option value="Islam" {{ (isset($data) && ($data->agama=='Islam') || Request::old('agama')=='Islam') ? 'selected' : ''}}>Islam</option>
                                        <option value="Kristen Protestan" {{ (isset($data) && ($data->agama=='Kristen Protestan') || Request::old('agama')=='Kristen Protestan') ? 'selected' : ''}}>Kristen Protestan</option>
                                        <option value="Kristen Katholik" {{ (isset($data) && ($data->agama=='Kristen Katholik') || Request::old('agama')=='Kristen Katholik') ? 'selected' : ''}}>Kristen Katholik</option>
                                        <option value="Buddha" {{ (isset($data) && ($data->agama=='Buddha') || Request::old('agama')=='Buddha') ? 'selected' : ''}}>Buddha</option>
                                        <option value="Hindu" {{ (isset($data) && ($data->agama=='Hindu') || Request::old('agama')=='Hindu') ? 'selected' : ''}}>Hindu</option>
                                        <option value="Kong Hu Cu" {{ (isset($data) && ($data->agama=='Kong Hu Cu') || Request::old('agama')=='Kong Hu Cu') ? 'selected' : ''}}>Kong Hu Cu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Tempat Tanggal Lahir: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-6">
                                            <input class="form-control" type="text" name="tpt_lhr" value="{{ isset($data) ? $data->tpt_lhr : Request::old('tpt_lhr')}}" placeholder="Masukkan Tempat Lahir">
                                        </div>
                                        <div class="col-6">
                                            <input class="form-control datepicker" type="text" name="tgl_lhr" value="{{ isset($data) ? $data->tgl_lhr : Request::old('tgl_lhr')}}" placeholder="Masukkan Tanggal Lahir" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Alamat:</label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="alamat" value="{{ isset($data) ? $data->alamat : Request::old('alamat')}}" placeholder="Masukkan Alamat Tempat Tinggal">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Kewarganegaraan: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div id="kewarganegaraan" class="radio">
                                        <label class="rdiobox rdiobox-inline mb-0">
                                            <input name="kewarganegaraan" type="radio" value="WNI" {{ isset($data) && ($data->kewarganegaraan=='WNI' || Request::old('kewarganegaraan')=='WNI') ? 'checked' : ''}} data-parsley-class-handler="#kewarganegaraan"
                                            data-parsley-errors-container="#errorsKWNG" required>
                                            <span>Warga Negara Indonesia</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label>
                                        <label class="rdiobox rdiobox-inline mb-0">
                                            <input name="kewarganegaraan" type="radio" value="WNA" {{ isset($data) && ($data->kewarganegaraan=='WNA' || Request::old('kewarganegaraan')=='WNA') ? 'checked' : ''}}>
                                            <span>Warga Negara Asing</span>
                                        </label>
                                    </div>
                                    <div id="errorsKWNG"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Kelas Mahasiswa: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select class="form-control" name="kelas" required>
                                        <option value="">- Pilih Kelas Mahasiswa -</option>
                                        <option value="Reguler" {{ (isset($data) && ($data->kelas=='Reguler') || Request::old('kelas')=='Reguler') ? 'selected' : ''}}>Reguler</option>
                                        <option value="Non-Reguler" {{ (isset($data) && ($data->kelas=='Non-Reguler') || Request::old('kelas')=='Non-Reguler') ? 'selected' : ''}}>Non-Reguler</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Tipe Mahasiswa: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-12" id="tipe_mahasiswa">
                                            <select class="form-control" name="tipe" required>
                                                <option value="">- Pilih Tipe Mahasiswa -</option>
                                                <option value="Reguler" {{ (isset($data) && ($data->tipe=='Reguler') || Request::old('tipe')=='Reguler') ? 'selected' : ''}}>Reguler</option>
                                                <option value="Non-Reguler" {{ (isset($data) && ($data->tipe=='Non-Reguler') || Request::old('tipe')=='Non-Reguler') ? 'selected' : ''}}>Non-Reguler</option>
                                                <option value="Alih Status" {{ (isset($data) && ($data->tipe=='Alih Status') || Request::old('tipe')=='Alih Status') ? 'selected' : ''}}>Alih Status</option>
                                                <option value="Lain-Lain" {{ (isset($data) && ($data->tipe!='Reguler' && $data->tipe!='Non-Reguler' && $data->tipe!='Alih Status') || Request::old('tipe')=='S1') ? 'selected' : ''}}>Lain-Lain</option>
                                            </select>
                                        </div>
                                        <div class="col-6" id="tipe_lainlain" style="display:none">
                                            <input class="form-control" name="tipe" type="text" value="{{ isset($data) ? $data->tipe : Request::old('tipe')}}" placeholder="Masukkan tipe mahasiswa" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Program: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select class="form-control" name="program" required>
                                        <option value="">- Pilih Program -</option>
                                        <option value="Reguler" {{ (isset($data) && ($data->program=='Reguler') || Request::old('program')=='Reguler') ? 'selected' : ''}}>Reguler</option>
                                        <option value="Asing" {{ (isset($data) && ($data->program=='Asing') || Request::old('program')=='Asing') ? 'selected' : ''}}>Asing</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Jalur Seleksi: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-6">
                                            <select class="form-control" name="seleksi_jenis" required>
                                                <option value="">- Pilih Jenis Seleksi -</option>
                                                <option value="Nasional" {{ (isset($data) && ($data->seleksi_jenis=='Nasional') || Request::old('seleksi_jenis')=='Nasional') ? 'selected' : ''}}>Nasional</option>
                                                <option value="Lokal" {{ (isset($data) && ($data->seleksi_jenis=='Lokal') || Request::old('seleksi_jenis')=='Lokal') ? 'selected' : ''}}>Lokal</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <input class="form-control" name="seleksi_jalur" type="text" value="{{ isset($data) ? $data->seleksi_jalur : Request::old('seleksi_jalur')}}" placeholder="Masukkan jalur seleksi">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Status Masuk: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-6">
                                            <select class="form-control" name="status_masuk" required>
                                                <option value="">- Pilih Status Awal Masuk -</option>
                                                <option value="Baru" {{ (isset($data) && ($data->status_masuk=='Baru') || Request::old('status_masuk')=='Baru') ? 'selected' : ''}}>Baru</option>
                                                <option value="Pindahan" {{ (isset($data) && ($data->status_masuk=='Pindahan') || Request::old('status_masuk')=='Pindahan') ? 'selected' : ''}}>Pindahan</option>
                                                <option value="Pertukaran" {{ (isset($data) && ($data->status_masuk=='Pertukaran') || Request::old('status_masuk')=='Pertukaran') ? 'selected' : ''}}>Pertukaran</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select class="form-control" name="tahun_masuk" required>
                                                <option value="">- Pilih Tahun Masuk -</option>
                                                @foreach($academicYear as $ay)
                                                <option value="{{$ay->id}}" {{ (isset($status) && ($status->id_ta==$ay->id) || Request::old('tahun_masuk')==$ay->id) ? 'selected' : ''}}>{{$ay->tahun_akademik}} - {{ $ay->semester }}</option>
                                                @endforeach
                                            </select>
                                        </div>
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

@section('js')
@endsection
