@extends('layouts.master')

@section('title', 'Rincian Data Publikasi')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('publication-teacher-show',$data) as $breadcrumb)
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
                <input type="hidden" value="{{encode_id($data->id)}}" name="id">
                <button class="btn btn-danger btn-block btn-delete" data-dest="{{ route('publication.teacher.destroy',encode_id($data->id)) }}" data-redir="{{ route('publication.teacher.index') }}"><i class="fa fa-trash mg-r-10"></i> Hapus</button>
            </form>
        </div>
        <div class="col-6">
            <a href="{{ route('publication.teacher.edit',encode_id($data->id)) }}" class="btn btn-warning btn-block" ><i class="fa fa-pencil-alt mg-r-10"></i>Sunting</a>
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
                                <th>Penulis Utama</th>
                                <td>:</td>
                                <td>
                                    <a href="{{route('teacher.list.show',$data->nidn)}}" target="_blank">
                                        {{$data->teacher->nama}} / NIDN. {{$data->nidn}}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Penulis Lain</th>
                                <td>:</td>
                                <td>
                                    <table class="table table-bordered table-colored table-info">
                                        <thead class="text-center">
                                            <tr>
                                                <td>Nama</td>
                                                <td>Status</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($data->publicationMembers->count() || $data->publicationStudents->count() )
                                                @foreach($data->publicationMembers as $pm)
                                                    @if($pm->status=='Dosen' || $pm->status=='Mahasiswa')
                                                    <tr>
                                                        <td>
                                                            {{$pm->status=='Dosen' ? $pm->teacher->nama : $pm->student->nama}}<br>
                                                            {{-- <small>{{$pm->status=='Dosen' ? 'NIDN.'.$pm->teacher->nidn : 'NIM.'.$pm->student->nim }} / {{ $pm->status=='Dosen' ? $pm->teacher->studyProgram->nama : $pm->student->studyProgram->nama }}</small> --}}
                                                        </td>
                                                        <td class="text-center">
                                                            {{$pm->status}}
                                                        </td>
                                                    </tr>
                                                    @else
                                                    <tr>
                                                        <td>
                                                            {{$pm->nama}}<br>
                                                            <small>{{$pm->asal}}</small>
                                                        </td>
                                                        <td class="text-center">
                                                            {{$pm->status}}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                                @foreach($data->publicationStudents as $ps)
                                                <tr>
                                                    <td>
                                                        {{$ps->nama}}<br>
                                                        <small>NIM. {{$ps->nim}} / {{$ps->studyProgram ?? $ps->asal}}</small>
                                                    </td>
                                                    <td class="text-center">
                                                        Mahasiswa
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2" class="text-center">BELUM ADA DATA</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
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
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
