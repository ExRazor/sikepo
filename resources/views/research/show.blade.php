@extends('layouts.master')

@section('title', 'Rincian Penelitian')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('research-show',$data) as $breadcrumb)
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
    @if(!Auth::user()->hasRole('kajur'))
    <div class="row ml-auto">
        <div class="col-6">
            <form method="POST">
                <input type="hidden" value="{{encode_id($data->id)}}" name="id">
                <button class="btn btn-danger btn-block btn-delete" data-dest="{{ route('research.destroy',$data->id) }}" data-redir="{{ route('research.index') }}"><i class="fa fa-trash"></i> Hapus</button>
            </form>
        </div>
        <div class="col-6">
            <a href="{{ route('research.edit',encode_id($data->id)) }}" class="btn btn-warning btn-block" style="color:white"><i class="fa fa-pencil-alt"></i> Sunting</a>
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
        <div class="card shadow-base mb-3">
            <div class="card-body bd-color-gray-lighter">
                <table class="table table-show display">
                    <tbody>
                        <tr>
                            <th>Judul Penelitian</th>
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
                        <tr>
                            <th>Nama Lembaga Penunjang Biaya</th>
                            <td>:</td>
                            <td>{{isset($data->sumber_biaya_nama) ? $data->sumber_biaya_nama : ''}}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Biaya Penelitian</th>
                            <td>:</td>
                            <td>{{rupiah($data->jumlah_biaya)}}</td>
                        </tr>
                        <tr>
                            <th>Dosen yang Terlibat</th>
                            <td>:</td>
                            <td>
                                <table class="table table-bordered table-colored table-info">
                                    <thead class="text-center">
                                        <tr>
                                            <td>Nama Dosen</td>
                                            <td>Status Anggota</td>
                                            <td>SKS</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data->researchTeacher as $rt)
                                        <tr>
                                            @if(!$rt->nama_lain)
                                            <td>
                                                <a href="{{route('teacher.list.show',$rt->teacher->nidn)}}">
                                                    {{$rt->teacher->nama}}<br>
                                                    <small>NIDN. {{$rt->teacher->nidn}} / {{$rt->teacher->studyProgram->singkatan}}</small>
                                                </a>
                                            </td>
                                            @else
                                            <td>
                                                {{$rt->nama_lain}}<br>
                                                <small>NIDN. {{$rt->nidn}} / {{$rt->asal_lain}}</small>
                                            </td>
                                            @endif
                                            <td class="text-center">
                                                {{$rt->status}}
                                            </td>
                                            <td class="text-center">
                                                {{$rt->sks}}
                                            </td>
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
                            </td>
                        </tr>
                        <tr>
                            <th>Mahasiswa yang Terlibat</th>
                            <td>:</td>
                            <td>
                                <table class="table table-bordered table-colored table-danger">
                                    <thead class="text-center">
                                        <tr>
                                            <td>Nama Mahasiswa</td>
                                            <td>Asal/Program Studi</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data->researchStudent as $rs)
                                        <tr>
                                            @if(!$rs->nama_lain)
                                            <td>
                                                <a href="{{route('student.list.show',encode_id($rs->student->nim))}}">
                                                    {{$rs->student->nama}}<br>
                                                    <small>NIM. {{$rs->student->nim}}</small>
                                                </a>
                                            </td>
                                            <td>
                                                {{$rs->student->studyProgram->nama}}<br>
                                                <small>{{$rs->student->studyProgram->department->nama.' / '.$rs->student->studyProgram->department->faculty->singkatan}}</small>
                                            </td>
                                            @else
                                            <td>
                                                {{$rs->nama_lain}}<br>
                                                <small>NIM. {{$rs->nim}}</small>
                                            </td>
                                            <td>
                                                {{$rs->asal_lain}}<br>
                                            </td>
                                            @endif
                                        </tr>
                                        @empty
                                        <tr>
                                            <td class="text-center" colspan="2">BELUM ADA DATA</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div><!-- card-body -->
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
