@extends('layouts.master')

@section('title', 'Rincian Pengabdian')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('profile-community-service-show',$data) as $breadcrumb)
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
        <i class="icon fa fa-american-sign-language-interpreting"></i>
        <div>
            <h4>Rincian Pengabdian</h4>
            <p class="mg-b-0">Rincian data pengabdian</p>
        </div>
    </div>
    @if($status=='Ketua')
    <div class="row ml-auto" style="width:300px">
        <div class="col-6 pr-1">
            <form method="POST">
                <input type="hidden" value="{{encrypt($data->id)}}" name="id">
                <button class="btn btn-danger btn-block btn-delete" data-dest="{{ route('profile.community-service.delete') }}" data-redir="{{ route('profile.community-service') }}"><i class="fa fa-trash mg-r-10"></i> Hapus</button>
            </form>
        </div>
        <div class="col-6">
            <a href="{{ route('profile.community-service.edit',encrypt($data->id)) }}" class="btn btn-warning btn-block" style="color:white"><i class="fa fa-pencil-alt mg-r-10"></i>Sunting</a>
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
    <div class="widget-2">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-base mb-3">
                    <div class="card-body bd-color-gray-lighter">
                        <table id="table_teacher" class="table display responsive nowrap" data-sort="desc">
                            <tbody>
                                <tr>
                                    <th>Judul Pengabdian</th>
                                    <td>:</td>
                                    <td>{{$data->judul_pengabdian}}</td>
                                </tr>
                                <tr>
                                    <th>Tema Pengabdian</th>
                                    <td>:</td>
                                    <td>{{$data->tema_pengabdian}}</td>
                                </tr>
                                <tr>
                                    <th>Bidang Program Studi</th>
                                    <td>:</td>
                                    <td>{{isset($data->sesuai_prodi) ? 'Sesuai' : 'Tidak Sesuai'}}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah SKS Pengabdian</th>
                                    <td>:</td>
                                    <td>{{$data->sks_pengabdian}}</td>
                                </tr>
                                <tr>
                                    <th>Tahun Pengabdian</th>
                                    <td>:</td>
                                    <td>{{$data->academicYear->tahun_akademik.' - '.$data->academicYear->semester}}</td>
                                </tr>
                                <tr>
                                    <th>Sumber Biaya Pengabdian</th>
                                    <td>:</td>
                                    <td>{{$data->sumber_biaya}}</td>
                                </tr>
                                @isset($data->sumber_biaya_nama)
                                <tr>
                                    <th>Nama Lembaga Penunjang Biaya</th>
                                    <td>:</td>
                                    <td>{{$data->sumber_biaya_nama}}</td>
                                </tr>
                                @endisset
                                <tr>
                                    <th>Jumlah Biaya Pengabdian</th>
                                    <td>:</td>
                                    <td>{{rupiah($data->jumlah_biaya)}}</td>
                                </tr>
                                <tr>
                                    <th>Dosen yang Terlibat</th>
                                    <th>:</th>
                                    <td>
                                        <table class="table table-bordered table-colored table-purple">
                                            <thead class="text-center">
                                                <tr>
                                                    <td>Nama Dosen</td>
                                                    <td>Status Anggota</td>
                                                    <td>SKS</td>
                                                    @if($status=='Ketua')
                                                    <td></td>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($data->serviceTeacher as $st)
                                                <tr>
                                                    @if($st->nidn)
                                                    <td>
                                                        <a href="{{route('teacher.list.show',$st->teacher->nidn)}}">
                                                            {{$st->teacher->nama}}<br>
                                                            <small>NIDN. {{$st->nidn}} / {{$st->teacher->latestStatus->studyProgram->singkatan}}</small>
                                                        </a>
                                                    </td>
                                                    @else
                                                    <td>
                                                        {{$st->nama}}<br>
                                                        <small>{{$st->asal}}</small>
                                                    </td>
                                                    @endif
                                                    <td class="text-center">
                                                        {{$st->status}}
                                                    </td>
                                                    <td class="text-center">
                                                        {{$st->sks}}
                                                    </td>
                                                    @if($status=='Ketua')
                                                    <td class="text-center">
                                                        @if($st->status!='Ketua')
                                                        <a href="javascript:void(0)" class="btn-delete" data-dest="{{ route('community-service.teacher.destroy',encrypt($st->id)) }}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                        @endif
                                                    </td>
                                                    @endif
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td class="text-center" colspan="4">
                                                        BELUM ADA DATA
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        @if($status=='Ketua')
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-sm btn-primary mg-b-10 text-white" data-toggle="modal" data-target="#modal-member-teacher">Tambah Dosen</button>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Mahasiswa yang Terlibat</th>
                                    <th>:</th>
                                    <td>
                                        <table class="table table-bordered table-colored table-pink">
                                            <thead class="text-center">
                                                <tr>
                                                    <td>Nama Mahasiswa</td>
                                                    <td>Asal/Program Studi</td>
                                                    @if($status=='Ketua')
                                                    <td></td>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($data->serviceStudent as $ss)
                                                <tr>
                                                    @if($ss->nim)
                                                    <td>
                                                        <a href="{{route('student.list.show',encode_id($ss->student->nim))}}">
                                                            {{$ss->student->nama}}<br>
                                                            <small>NIM. {{$ss->nim}}</small>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{$ss->student->studyProgram->nama}}<br>
                                                        <small>{{$ss->student->studyProgram->department->nama.' / '.$ss->student->studyProgram->department->faculty->singkatan}}</small>
                                                    </td>
                                                    @else
                                                    <td>
                                                        {{$ss->nama}}<br>
                                                    </td>
                                                    <td>
                                                        {{$ss->asal}}<br>
                                                    </td>
                                                    @endif
                                                    @if($status=='Ketua')
                                                    <td class="text-center">
                                                        <a href="javascript:void(0)" class="btn-delete" data-dest="{{ route('community-service.student.destroy',encrypt($ss->id)) }}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                    @endif
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td class="text-center" colspan="2">
                                                        BELUM ADA DATA
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        @if($status=='Ketua')
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-sm btn-danger mg-b-10 text-white" data-toggle="modal" data-target="#modal-member-student">Tambah Mahasiswa</button>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- card-body -->
                </div>
            </div>
        </div>
    </div>
</div>
@include('community-service.form-member-teacher')
@include('community-service.form-member-student')
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
