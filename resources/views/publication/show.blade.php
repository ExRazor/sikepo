@extends('layouts.master')

@section('title', 'Rincian Data Publikasi')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('publication-show',$data) as $breadcrumb)
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
        <i class="icon fa fa-newspaper"></i>
        <div>
            <h4>Rincian Publikasi</h4>
            <p class="mg-b-0">Rincian data publikasi dosen</p>
        </div>
    </div>
    @if(!Auth::user()->hasRole('kajur'))
    <div class="row ml-sm-auto" style="width:300px">
        <div class="col-6 pr-1">
            <form method="POST">
                <button class="btn btn-danger btn-block btn-delete" data-dest="{{ route('publication.destroy',encrypt($data->id)) }}" data-redir="{{ route('publication.index') }}"><i class="fa fa-trash mg-r-10"></i> Hapus</button>
            </form>
        </div>
        <div class="col-6">
            <a href="{{ route('publication.edit',encrypt($data->id)) }}" class="btn btn-warning btn-block" ><i class="fa fa-pencil-alt mg-r-10"></i>Sunting</a>
        </div>
    </div>
    @endif
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
    <div class="row widget-2">
        <div class="col-md-9">
            <div class="card shadow-base mb-3">
                <div class="card-body bd-color-gray-lighter">
                    <table class="table table-show display">
                        <tbody>
                            <tr>
                                <th width="225px">Judul Publikasi</th>
                                <td width="20px">:</td>
                                <td>{{$data->judul}}</td>
                            </tr>
                            <tr>
                                <th>Jenis Publikasi</th>
                                <td>:</td>
                                <td>{{$data->publicationCategory->nama}}</td>
                            </tr>
                            <tr>
                                <th>Penulis</th>
                                <td>:</td>
                                <td>
                                    <ul style="padding-left:15px;margin-bottom:10px">
                                        <li>
                                            <strong>
                                            @if($data->penulisUtama->nama)
                                                {{$data->penulisUtama->nama.' ('.$data->penulisUtama->asal.')'}}
                                            @else
                                                @if($data->penulisUtama->teacher)
                                                    <a href={{route('teacher.list.show',$data->penulisUtama->id_unik)}}>{{$data->penulisUtama->teacher->nama.' ('.$data->penulisUtama->status.' - '.$data->penulisUtama->teacher->latestStatus->studyProgram->singkatan.')'}}</a>
                                                @else
                                                <a href={{route('student.list.show',encode_id($data->penulisUtama->id_unik))}}>{{$data->penulisUtama->student->nama.' ('.$data->penulisUtama->status.' - '.$data->penulisUtama->student->studyProgram->singkatan.')'}}</a>
                                                @endif
                                            @endif
                                            </strong>
                                        </li>
                                        @foreach($data->penulisAnggota as $pa)
                                        <li>
                                            <div class="d-flex justify-content-between">
                                            <div style="max-width: 350px;">
                                                @if($pa->nama)
                                                    {{$pa->nama.' ('.$pa->asal.')'}}
                                                @else
                                                    @if($pa->teacher)
                                                        <a href={{route('teacher.list.show',$pa->id_unik)}}>{{$pa->teacher->nama.' ('.$pa->status.' - '.$pa->teacher->latestStatus->studyProgram->singkatan.')'}}</a>
                                                    @else
                                                        <a href={{route('student.list.show',encode_id($pa->id_unik))}}>{{$pa->student->nama.' ('.$pa->status.' - '.$pa->student->studyProgram->singkatan.')'}}</a>
                                                    @endif
                                                @endif
                                            </div>
                                            <div>
                                                <a href="javascript:void(0)" class="btn-delget" data-dest="{{ route('publication.member.destroy',encrypt($pa->id)) }}" data-id="{{encrypt($pa->id)}}">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-sm btn-teal mg-b-10 text-white" data-toggle="modal" data-target="#modal-publication-author">Tambah Penulis</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Bidang Program Studi</th>
                                <td>:</td>
                                <td>{{isset($data->sesuai_prodi) ? 'Sesuai' : 'Tidak Sesuai'}}</td>
                            </tr>
                            <tr>
                                <th>Tahun Terbit</th>
                                <td>:</td>
                                <td>{{$data->academicYear->tahun_akademik.' - '.$data->academicYear->semester}}</td>
                            </tr>
                            <tr>
                                <th>Penerbit</th>
                                <td>:</td>
                                <td>{{$data->penerbit}}</td>
                            </tr>
                            <tr>
                                <th>Nama Terbitan</th>
                                <td>:</td>
                                <td>{{isset($data->jurnal) ? $data->jurnal : '?'}}</td>
                            </tr>
                            <tr>
                                <th>Akreditasi</th>
                                <td>:</td>
                                <td>{{isset($data->akreditasi) ? $data->akreditasi : '?'}}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Sitasi</th>
                                <td>:</td>
                                <td>{{isset($data->sitasi) ? $data->sitasi : '?'}}</td>
                            </tr>
                            <tr>
                                <th>Tautan</th>
                                <td>:</td>
                                <td>@isset($data->tautan) <a href="{{$data->tautan}}">{{$data->tautan}} @else ? @endisset</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div><!-- card-body -->
            </div>
        </div>
    </div>
</div>
@include('publication.form-author')
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
