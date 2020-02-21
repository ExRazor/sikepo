@extends('layouts.master')

@section('title', isset($data) ? 'Edit Dosen' : 'Tambah Dosen')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
            @foreach (Breadcrumbs::generate( isset($data) ? 'teacher-edit' : 'teacher-add', isset($data) ? $data : '' ) as $breadcrumb)
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
                <h6 class="card-title">
                    @isset($data)
                        Sunting Data
                    @else
                        Tambah Data
                    @endisset
                </h6>
            </div>
            <form id="teacher_form" action="{{route('teacher.store')}}" method="POST" enctype="multipart/form-data" data-parsley-validate>
                <div class="card-body bd bd-y-0 bd-color-gray-lighter">
                    <div class="row">
                        <div class="col-9 mx-auto">
                            @csrf
                            @if(isset($data))
                                @method('put')
                                <input type="hidden" name="_id" value="{{encrypt($data->nidn)}}">
                            @else
                                @method('post')
                            @endif
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Jurusan: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select class="form-control" name="kd_jurusan" data-type="form" required>
                                        <option value="">- Pilih Jurusan -</option>
                                        @foreach($faculty as $f)
                                            @if($f->department->count())
                                            <optgroup label="{{$f->nama}}">
                                                @foreach($f->department as $d)
                                                <option value="{{$d->kd_jurusan}}" {{ (isset($data) && $data->studyProgram->kd_jurusan==$d->kd_jurusan) || setting('app_department_id')==$d->kd_jurusan ? 'selected': ''}}>{{$d->nama}}</option>
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
                                        <option value="{{$sp->kd_prodi}}" {{ (isset($data) && ($sp->kd_prodi==$data->kd_prodi)) || Request::old('kd_prodi')==$sp->kd_prodi || Auth::user()->kd_prodi==$sp->kd_prodi ? 'selected' : ''}}>{{$sp->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">NIDN: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="nidn" value="{{ isset($data) ? $data->nidn : Request::old('nidn')}}" placeholder="Masukkan NIDN" {{ isset($data) ? 'disabled' : ''}} minlength="8" maxlength="10" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">NIP:</label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="nip" value="{{ isset($data) ? $data->nip : Request::old('nip')}}" placeholder="Masukkan NIP" minlength="16" maxlength="18">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Nama Dosen: <span class="tx-danger">*</span></label>
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
                                <label class="col-3 form-control-label">Agama:</label>
                                <div class="col-8">
                                    <select class="form-control" name="agama">
                                        <option value="">- Pilih Agama -</option>
                                        <option value="Islam" {{ (isset($data) && ($data->agama=='Islam') || Request::old('agama')=='Islam') ? 'selected' : ''}}>Islam</option>
                                        <option value="Kristen" {{ (isset($data) && ($data->agama=='Kristen') || Request::old('agama')=='Kristen') ? 'selected' : ''}}>Kristen</option>
                                        <option value="Katholik" {{ (isset($data) && ($data->agama=='Katholik') || Request::old('agama')=='Katholik') ? 'selected' : ''}}>Katholik</option>
                                        <option value="Buddha" {{ (isset($data) && ($data->agama=='Buddha') || Request::old('agama')=='Buddha') ? 'selected' : ''}}>Buddha</option>
                                        <option value="Hindu" {{ (isset($data) && ($data->agama=='Hindu') || Request::old('agama')=='Hindu') ? 'selected' : ''}}>Hindu</option>
                                        <option value="Kong Hu Cu" {{ (isset($data) && ($data->agama=='Kong Hu Cu') || Request::old('agama')=='Kong Hu Cu') ? 'selected' : ''}}>Kong Hu Cu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Tempat/Tanggal Lahir:</label>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-4">
                                            <input class="form-control" type="text" name="tpt_lhr" value="{{ isset($data) ? $data->tpt_lhr : Request::old('tpt_lhr')}}" placeholder="Masukkan Tempat Lahir">
                                        </div>
                                        <div class="col-8">
                                            <input class="form-control datepicker" type="text" name="tgl_lhr" value="{{ isset($data) ? $data->tgl_lhr : Request::old('tgl_lhr')}}" placeholder="Masukkan Tanggal Lahir">
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
                                <label class="col-3 form-control-label">No. Telepon:</label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="no_telp" value="{{ isset($data) ? $data->no_telp : Request::old('no_telp')}}" placeholder="Masukkan Nomor Telepon yang Dapat Dihubungi">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Email:</label>
                                <div class="col-8">
                                    <input class="form-control" type="email" name="email" value="{{ isset($data) ? $data->email : Request::old('email')}}" placeholder="Masukkan Email Aktif">
                                </div>
                            </div>
                            <div class="row mb-3 form-opsional">
                                <label class="col-3 form-control-label">Pendidikan Terakhir:</label>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-4">
                                            <select class="form-control" name="pend_terakhir_jenjang">
                                                <option value="">- Pilih Pendidikan Terakhir -</option>
                                                <option value="D3" {{ (isset($data) && ($data->pend_terakhir_jenjang=='D3') || Request::old('pend_terakhir_jenjang')=='D3') ? 'selected' : ''}}>Diploma D3</option>
                                                <option value="D4" {{ (isset($data) && ($data->pend_terakhir_jenjang=='D4') || Request::old('pend_terakhir_jenjang')=='D4') ? 'selected' : ''}}>Diploma D4</option>
                                                <option value="S1" {{ (isset($data) && ($data->pend_terakhir_jenjang=='S1') || Request::old('pend_terakhir_jenjang')=='S1') ? 'selected' : ''}}>Strata 1 / Sarjana</option>
                                                <option value="S2" {{ (isset($data) && ($data->pend_terakhir_jenjang=='S2') || Request::old('pend_terakhir_jenjang')=='S2') ? 'selected' : ''}}>Strata 2 / Magister</option>
                                                <option value="S3" {{ (isset($data) && ($data->pend_terakhir_jenjang=='S3') || Request::old('pend_terakhir_jenjang')=='S3') ? 'selected' : ''}}>Strata 3 / Doktor</option>
                                            </select>
                                        </div>
                                        <div class="col-8">
                                            <input class="form-control" type="text" name="pend_terakhir_jurusan" value="{{ isset($data) ? $data->pend_terakhir_jurusan : Request::old('pend_terakhir_jurusan')}}" placeholder="Masukkan Jurusan Pendidikan Terakhir">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 form-opsional">
                                <label class="col-3 form-control-label">Bidang Keahlian:</label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="bidang_ahli" value="{{ isset($data) ? $data->bidang_ahli : Request::old('bidang_ahli')}}" placeholder="Jika lebih dari satu, pisahkan dengan tanda koma.">
                                </div>
                            </div>
                            <div class="row mb-3 form-opsional">
                                <label class="col-3 form-control-label">Sesuai Bidang PS?</label>
                                <div class="col-8">
                                    <div id="sesuai_bidang_ps" class="radio">
                                        <label class="rdiobox rdiobox-inline mb-0">
                                            <input name="sesuai_bidang_ps" type="radio" value="Ya" {{ isset($data) && ($data->sesuai_bidang_ps=='Ya' || Request::old('sesuai_bidang_ps')=='Ya') ? 'checked' : ''}} data-parsley-class-handler="#sesuai_bidang_ps"
                                            data-parsley-errors-container="#errorsBD">
                                            <span>Ya</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label>
                                        <label class="rdiobox rdiobox-inline mb-0">
                                            <input name="sesuai_bidang_ps" type="radio" value="Tidak" {{ isset($data) && ($data->sesuai_bidang_ps=='Tidak' || Request::old('sesuai_bidang_ps')=='Tidak') ? 'checked' : ''}}>
                                            <span>Tidak</span>
                                        </label>
                                    </div>
                                    <div id="errorsBD"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Ikatan Kerja: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select class="form-control" name="ikatan_kerja" required>
                                        <option value="">- Pilih Ikatan Kerja -</option>
                                        <option value="Dosen Tetap PS" {{ isset($data) && ($data->ikatan_kerja=='Dosen Tetap PS' || Request::old('ikatan_kerja')=='Dosen Tetap PS') ? 'selected' : ''}}>Dosen Tetap PS</option>
                                        <option value="Dosen Tetap PT" {{ isset($data) && ($data->ikatan_kerja=='Dosen Tetap PT' || Request::old('ikatan_kerja')=='Dosen Tetap PT') ? 'selected' : ''}}>Dosen Tetap PT</option>
                                        <option value="Dosen Tidak Tetap" {{ isset($data) && ($data->ikatan_kerja=='Dosen Tidak Tetap' || Request::old('ikatan_kerja')=='Dosen Tidak Tetap') ? 'selected' : ''}}>Dosen Tidak Tetap</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Jabatan Akademik: <span class="tx-danger">*</span></label>
                                <div class="col-8">
                                    <select class="form-control" name="jabatan_akademik" required>
                                        <option value="">- Pilih Jabatan Akademik -</option>
                                        <option value="Tenaga Pengajar" {{ isset($data) && ($data->jabatan_akademik=='Tenaga Pengajar' || Request::old('jabatan_akademik')=='Tenaga Pengajar') ? 'selected' : ''}}>Tenaga Pengajar</option>
                                        <option value="Asisten Ahli" {{ isset($data) && ($data->jabatan_akademik=='Asisten Ahli' || Request::old('jabatan_akademik')=='Asisten Ahli') ? 'selected' : ''}}>Asisten Ahli</option>
                                        <option value="Lektor" {{ isset($data) && ($data->jabatan_akademik=='Lektor' || Request::old('jabatan_akademik')=='Lektor') ? 'selected' : ''}}>Lektor</option>
                                        <option value="Lektor Kepala" {{ isset($data) && ($data->jabatan_akademik=='Lektor Kepala' || Request::old('jabatan_akademik')=='Lektor Kepala') ? 'selected' : ''}}>Lektor Kepala</option>
                                        <option value="Guru Besar" {{ isset($data) && ($data->jabatan_akademik=='Guru Besar' || Request::old('jabatan_akademik')=='Guru Besar') ? 'selected' : ''}}>Guru Besar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">No. Sertifikat Pendidik:</label>
                                <div class="col-8">
                                    <input class="form-control" type="text" name="sertifikat_pendidik" value="{{ isset($data) ? $data->sertifikat_pendidik : Request::old('sertifikat_pendidik')}}" placeholder="Masukkan No. Sertifikat Pendidik">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Foto Profil<span class="tx-danger"></span></label>
                                <div class="col-8">
                                    <div class="form-group mg-b-10-force">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="foto" id="foto_profil">
                                            <label class="custom-file-label custom-file-label-primary" for="foto_profil">Pilih fail</label>
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

@section('custom-js')
<script type="text/javascript">

$(document).ready(function() {
    var value = $('select[name=kd_jurusan]').val()
    cek(value);
});

$('#teacher_form').on('change','select[name=kd_jurusan]',function(){
    var value = $(this).val();

    cek(value);

});

function cek(value) {
    if(value!={{setting('app_department_id')}}) {
        $('.form-opsional').hide();
    } else {
        $('.form-opsional').show();
    }
}
</script>
@endsection
