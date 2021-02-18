@extends('layouts.master')

@section('title', 'Rincian Penelitian')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('profile-research-show',$data) as $breadcrumb)
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
        <i class="icon fa fa-book-reader"></i>
        <div>
            <h4>Rincian Penelitian</h4>
            <p class="mg-b-0">Rincian data penelitian</p>
        </div>
    </div>
    @if($status=='Ketua')
    <div class="row ml-auto" style="width:300px">
        <div class="col-6 pr-1">
            <form method="POST">
                <button class="btn btn-danger btn-block btn-delete" data-dest="{{ route('research.destroy',encrypt($data->id)) }}" data-redir="{{ route('profile.research') }}"><i class="fa fa-trash mg-r-10"></i> Hapus</button>
            </form>
        </div>
        <div class="col-6">
            <a href="{{ route('profile.research.edit',encrypt($data->id)) }}" class="btn btn-warning btn-block" style="color:white"><i class="fa fa-pencil-alt mg-r-10"></i>Sunting</a>
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
                        <table class="table table-show display">
                            <tbody>
                                <tr>
                                    <th width="200">Judul Penelitian</th>
                                    <td>:</td>
                                    <td>{{$data->judul_penelitian}}</td>
                                </tr>
                                <tr>
                                    <th>Tema Penelitian</th>
                                    <td>:</td>
                                    <td>{{$data->tema_penelitian}}</td>
                                </tr>
                                <tr>
                                    <th>Tingkat Penelitian</th>
                                    <td>:</td>
                                    <td>{{$data->tingkat_penelitian}}</td>
                                </tr>
                                <tr>
                                    <th>Bidang Program Studi</th>
                                    <td>:</td>
                                    <td>{{isset($data->sesuai_prodi) ? 'Sesuai' : 'Tidak Sesuai'}}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah SKS Penelitian</th>
                                    <td>:</td>
                                    <td>{{$data->sks_penelitian}}</td>
                                </tr>
                                <tr>
                                    <th>Tahun Penelitian</th>
                                    <td>:</td>
                                    <td>{{$data->academicYear->tahun_akademik.' - '.$data->academicYear->semester}}</td>
                                </tr>
                                <tr>
                                    <th>Sumber Biaya Penelitian</th>
                                    <td>:</td>
                                    <td>{{$data->sumber_biaya}}</td>
                                </tr>
                                @if($data->sumber_biaya_nama)
                                <tr>
                                    <th>Nama Lembaga Penunjang Biaya</th>
                                    <td>:</td>
                                    <td>{{$data->sumber_biaya_nama}}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Jumlah Biaya Penelitian</th>
                                    <td>:</td>
                                    <td>{{rupiah($data->jumlah_biaya)}}</td>
                                </tr>
                                @if($data->bukti_fisik)
                                <tr>
                                    <th>Bukti Fisik</th>
                                    <th>:</th>
                                    <td>
                                        <a href="{{route('download.research',$data->bukti_fisik)}}" target="_blank">
                                            <i class="fa fa-download mr-1"></i> {{$data->bukti_fisik}}
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Dosen yang Terlibat</th>
                                    <th>:</th>
                                    <td>
                                        <table class="table table-bordered table-colored table-info">
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
                                                @forelse($data->researchTeacher as $rt)
                                                <tr>
                                                    @if($rt->nidn)
                                                    <td>
                                                        {{$rt->teacher->nama}}<br>
                                                        <small>NIDN. {{$rt->teacher->nidn}} / {{$rt->teacher->latestStatus->studyProgram->singkatan}}</small>
                                                    </td>
                                                    @else
                                                    <td>
                                                        {{$rt->nama}}<br>
                                                        <small>{{$rt->asal}}</small>
                                                    </td>
                                                    @endif
                                                    <td class="text-center">
                                                        {{$rt->status}}
                                                    </td>
                                                    <td class="text-center">
                                                        {{$rt->sks}}
                                                    </td>
                                                    @if($status=='Ketua')
                                                    <td class="text-center">
                                                        @if($rt->status!='Ketua')
                                                        <a href="javascript:void(0)" class="btn-delete" data-dest="{{ route('research.teacher.destroy',encrypt($rt->id)) }}">
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
                                        <table class="table table-bordered table-colored table-danger">
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
                                                @forelse($data->researchStudent as $rs)
                                                <tr>
                                                    @if($rs->nim)
                                                    <td>
                                                        {{$rs->student->nama}}<br>
                                                        <small>NIM. {{$rs->student->nim}}</small>
                                                    </td>
                                                    <td>
                                                        {{$rs->student->studyProgram->nama}}<br>
                                                        <small>{{$rs->student->studyProgram->department->nama.' / '.$rs->student->studyProgram->department->faculty->singkatan}}</small>
                                                    </td>
                                                    @else
                                                    <td>
                                                        {{$rs->nama}}<br>
                                                    </td>
                                                    <td>
                                                        {{$rs->asal}}<br>
                                                    </td>
                                                    @endif
                                                    @if($status=='Ketua')
                                                    <td class="text-center">
                                                        <a href="javascript:void(0)" class="btn-delete" data-dest="{{ route('research.student.destroy',encrypt($rs->id)) }}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                    @endif
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td class="text-center" colspan="3">BELUM ADA DATA</td>
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
@include('research.form-member-teacher')
@include('research.form-member-student')
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
