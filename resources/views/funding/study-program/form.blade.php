@extends('layouts.master')

@section('title', isset($data) ? 'Sunting Keuangan Program Studi' : 'Tambah Keuangan Program Studi')

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
            <p class="mg-b-0">Sunting Data Keuangan Program Studi</p>
        </div>
        @else
        <div>
            <h4>Tambah</h4>
            <p class="mg-b-0">Tambah Data Keuangan Program Studi</p>
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
                        <div class="col-10 mx-auto">
                            @csrf
                            @if(isset($data))
                                @method('put')
                                <input type="hidden" name="_id" value="{{encrypt($data->nim)}}">
                            @else
                                @method('post')
                            @endif
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">Program Studi: <span class="tx-danger">*</span></label>
                                <div class="col-7">
                                    <div class="row">
                                        <div class="col-6">
                                            <select class="form-control" name="kd_prodi" required>
                                                <option value="">- Pilih Prodi -</option>
                                                @foreach($studyProgram as $sp)
                                                <option value="{{$sp->kd_prodi}}" {{ (isset($data) && ($sp->kd_prodi==$data->kd_prodi) || Request::old('kd_prodi')==$sp->kd_prodi) ? 'selected' : ''}}>{{$sp->nama}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select class="form-control" name="id_ta" required>
                                                <option value="">- Pilih Tahun -</option>
                                                @foreach($academicYear as $ay)
                                                <option value="{{$ay->id}}" {{ (isset($data) && ($data->id_ta==$ay->id) || Request::old('id_ta')==$ay->id) ? 'selected' : ''}}>{{$ay->tahun_akademik}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @foreach($category as $c)
                            <div class="row mb-3">
                                <label class="col-3 form-control-label">{{ $c->nama }}: <span class="tx-danger">*</span></label>
                                <div class="col-7">
                                    @if($c->children->count())
                                        @foreach($c->children as $child)
                                        <div class="row my-3">
                                            <div class="col-12">
                                                <input class="form-control" type="text" name="{{ $child->id }}" value="{{ isset($data) ? $data->nim : Request::old($child->id)}}" placeholder="Masukkan {{ $child->nama }}">
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <input class="form-control" type="text" name="{{ $c->id }}" value="{{ isset($data) ? $data->nim : Request::old('nim')}}" placeholder="Masukkan {{ $c->nama }}">
                                    @endif
                                </div>
                            </div>
                            @endforeach
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
