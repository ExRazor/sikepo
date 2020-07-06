@extends('layouts.master')

@section('title', 'Mahasiswa Asing')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('student-foreign') as $breadcrumb)
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
        <i class="icon fa fa-flag-usa"></i>
        <div>
            <h4>Mahasiswa Asing</h4>
            <p class="mg-b-0">Olah Data Mahasiswa Asing</p>
        </div>
    </div>
</div>

<div class="br-pagebody">
    @if (session()->has('flash.message'))
        <div class="alert alert-{{ session('flash.class') }}" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('flash.message') }}
        </div>
    @endif
    @if(!Auth::user()->hasRole('kaprodi'))
    <div class="row">
        <div class="col-12">
            <form action="{{route('ajax.student.foreign.filter')}}" id="filter-studentForeign" method="POST" data-token="{{encode_id(Auth::user()->role)}}">
                <div class="filter-box d-flex flex-row bd-highlight mg-b-10">
                    <div class="mg-r-10">
                        <input id="nm_jurusan" type="hidden" value="{{setting('app_department_name')}}">
                        <select class="form-control" name="kd_prodi">
                            <option value="">- Pilih Program Studi -</option>
                            @foreach($studyProgram as $sp)
                            <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-purple btn-block " style="color:white">Cari</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
    <div class="row widget-2">
        <div class="order-2 order-md-1 {{Auth::user()->hasRole('kajur') ? 'col-md-12' : 'col-md-8' }}">
            <div class="card shadow-base mb-3">
                <div class="card-header nm_jurusan">
                    <h6 class="card-title">
                        @if(Auth::user()->hasRole('kaprodi'))
                        {{ Auth::user()->studyProgram->nama }}
                        @else
                        {{ setting('app_department_name') }}
                        @endif
                    </h6>
                </div>
                <div class="card-body bd-color-gray-lighter">
                    <table id="table-studentForeign" class="table display responsive datatable" data-sort="desc" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center defaultSort">Mahasiswa</th>
                                <th class="text-center none">Asal Negara</th>
                                <th class="text-center none">Durasi Status Asing</th>
                                @if(!Auth::user()->hasRole('kajur'))
                                <th class="text-center no-sort none" width="50">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($studentForeign as $sf)
                            <tr>
                                <td>
                                    <a href="{{route('student.profile',encode_id($sf->nim))}}">
                                        {{ $sf->student->nama }}<br>
                                        <small>NIM.{{ $sf->student->nim }} / {{ $sf->student->studyProgram->singkatan }}</small>
                                    </a>
                                </td>
                                <td class="text-center">{{ $sf->asal_negara }}</td>
                                <td class="text-center">{{ $sf->durasi }}</td>
                                @if(!Auth::user()->hasRole('kajur'))
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <div><span class="fa fa-caret-down"></span></div>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                            <button class="dropdown-item btn-edit" data-id="{{ encode_id($sf->id) }}">Sunting</button>
                                            <form method="POST">
                                                <input type="hidden" value="{{encode_id($sf->id)}}" name="_id">
                                                <button class="dropdown-item btn-delete" data-dest="{{ route('student.foreign.delete') }}">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- card-body -->
            </div>
        </div>
        @if(!Auth::user()->hasRole('kajur'))
        <div class="col-md-4 order-1 order-md-2 widget-2">
            <div class="card shadow-base mb-3">
                <div class="card-header">
                    <h6 class="card-title"><span class="title-action">Tambah</span> Mahasiswa Asing</h6>
                </div>
                <div class="card-body bd-color-gray-lighter">
                    <form id="form-studentForeign" enctype="multipart/form-data">
                        <div class="alert alert-danger" style="display:none">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                        @isset(Auth::user()->kd_prodi)
                        <input type="hidden" name="kd_prodi" value="{{Auth::user()->kd_prodi}}">
                        @endisset
                        <div class="row mg-t-20">
                            <label class="col-sm-4 form-control-label">Mahasiswa: <span class="tx-danger">*</span></label>
                            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                                <input type="hidden" name="_id">
                                <select class="form-control select-mhs" name="nim" required></select>
                            </div>
                        </div>
                        <div class="row mg-t-20">
                            <label class="col-sm-4 form-control-label">Asal Negara: <span class="tx-danger">*</span></label>
                            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                                <input type="text" name="asal_negara" class="form-control" placeholder="Isikan asal negara mahasiswa" required>
                            </div>
                        </div>
                        <div class="row mg-t-20 category-description">
                            <label class="col-sm-4 form-control-label">Durasi:</label>
                            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                                <select name="durasi" class="form-control" required>
                                    <option value="">- Pilih Durasi Studi -</option>
                                    <option value="Full-time">Full-time (Penuh Waktu)</option>
                                    <option value="Part-time">Part-time (Paruh Waktu)</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mg-t-20">
                            <div class="offset-sm-4 col-sm-8 mg-t-10 mg-sm-t-0">
                                <button type="submit" class="btn btn-info mr-2 btn-save" value="post" data-dest="{{route('student.foreign.store')}}">Simpan</button>
                                <button class="btn btn-secondary btn-add">Reset</button>
                            </div>
                        </div>
                    </form>
                </div><!-- card-body -->
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
