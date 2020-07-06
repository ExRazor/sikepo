@extends('layouts.master')

@section('title', isset($data) ? 'Sunting Kepuasan Pengguna Lulusan' : 'Tambah Kepuasan Pengguna Lulusan')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'alumnus-satisfaction-edit' : 'alumnus-satisfaction-add', isset($data) ? $data : '' ) as $breadcrumb)
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
            <p class="mg-b-0">Sunting Data Kepuasan Pengguna Lulusan</p>
        </div>
        @else
        <div>
            <h4>Tambah</h4>
            <p class="mg-b-0">Tambah Data Kepuasan Pengguna Lulusan</p>
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
            <form id="funding_studyProgram_form" action="{{route('alumnus.satisfaction.store')}}" method="POST" enctype="multipart/form-data">
                <div class="card-body bd bd-y-0 bd-color-gray-lighter">
                    <div class="row">
                        <div class="col-md-10 mx-auto">
                            @csrf
                            @if(isset($data))
                                @method('put')
                                <input type="hidden" name="id" value="{{encrypt($data->kd_kepuasan)}}">
                            @else
                                @method('post')
                            @endif
                            <div class="row mb-3">
                                <label class="col-md-2 form-control-label">Program Studi: <span class="tx-danger">*</span></label>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-6">
                                            @if(Auth::user()->hasRole('kaprodi'))
                                                <input type="hidden" name="kd_prodi" value="{{Auth::user()->kd_prodi}}">
                                            @endif
                                            <select class="form-control {{ $errors->has('kd_prodi') ? 'is-invalid' : ''}}" name="kd_prodi" {{isset($data) || Auth::user()->hasRole('kaprodi') ? 'disabled' : 'required'}}>
                                                <option value="">- Pilih Prodi -</option>
                                                @foreach($studyProgram as $sp)
                                                <option value="{{$sp->kd_prodi}}" {{ (isset($data) && ($sp->kd_prodi==$data->kd_prodi)) || Request::old('kd_prodi')==$sp->kd_prodi || Auth::user()->kd_prodi==$sp->kd_prodi ? 'selected' : ''}}>{{$sp->nama}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('kd_prodi'))
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $errors->first('kd_prodi') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-6">
                                            <select class="form-control {{ $errors->has('id_ta') ? 'is-invalid' : ''}}" name="id_ta" {{isset($data) ? 'disabled' : 'required'}}>
                                                <option value="">- Pilih Tahun -</option>
                                                @foreach($academicYear as $ay)
                                                <option value="{{$ay->id}}" {{ (isset($data) && ($data->id_ta==$ay->id) || Request::old('id_ta')==$ay->id) ? 'selected' : ''}}>{{$ay->tahun_akademik}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('id_ta'))
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $errors->first('id_ta') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @foreach($category as $c)
                            <div class="row mb-3">
                                <label class="col-md-2 form-control-label align-items-start pd-t-12">{{ $c->nama }}: </label>
                                <div class="col-md-9">
                                    <div class="row mb-3">
                                        <div class="col-md-3 mb-1">
                                            <div class="input-group">
                                                <input class="form-control" type="number" name="sangat_baik[{{ $c->id }}]" value="{{ isset($data) ? $persen[$c->id]->sangat_baik : Request::old('sangat_baik['.$c->id.']')}}" placeholder="Sangat Baik">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-1">
                                            <div class="input-group">
                                                <input class="form-control" type="number" name="baik[{{ $c->id }}]" value="{{ isset($data) ? $persen[$c->id]->baik : Request::old('baik['.$c->id.']')}}" placeholder="Baik">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-1">
                                            <div class="input-group">
                                                <input class="form-control" type="number" name="cukup[{{ $c->id }}]" value="{{ isset($data) ? $persen[$c->id]->cukup : Request::old('cukup['.$c->id.']')}}" placeholder="Cukup">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-1">
                                            <div class="input-group">
                                                <input class="form-control" type="number" name="kurang[{{ $c->id }}]" value="{{ isset($data) ? $persen[$c->id]->kurang : Request::old('kurang['.$c->id.']')}}" placeholder="Kurang">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <textarea rows="5" name="tindak_lanjut[{{$c->id}}]" class="form-control" placeholder="Tuliskan tindak lanjut UPPS/PS terhadap aspek {{$c->nama}}">{{ isset($data) ? $persen[$c->id]->tindak_lanjut : Request::old('tindak_lanjut['.$c->id.']')}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div><!-- card-body -->
                <div class="card-footer bd bd-color-gray-lighter rounded-bottom">
                    <div class="row">
                        <div class="col-md-6 mx-auto">
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
