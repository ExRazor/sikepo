@extends('layouts.master')

@section('title', 'Rincian Kerja Sama')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('collaboration-show',$data) as $breadcrumb)
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
        <i class="icon fa fa-handshake"></i>
        <div>
            <h4>Rincian Kerja Sama</h4>
            <p class="mg-b-0">Rincian data kerja sama</p>
        </div>
    </div>
    @if(!Auth::user()->hasRole('kajur'))
    <div class="row ml-auto">
        <div class="col-6 pr-1">
            <form method="POST">
                <input type="hidden" value="{{encrypt($data->id)}}" name="id">
                <button class="btn btn-danger btn-block btn-delete" data-dest="{{ route('collaboration.destroy',$data->id) }}" data-redir="{{ route('collaboration.index') }}"><i class="fa fa-trash mg-r-10"></i> Hapus</button>
            </form>
        </div>
        <div class="col-6 pl-1">
            <a href="{{ route('collaboration.edit',$data->id) }}" class="btn btn-warning btn-block text-white"><i class="fa fa-pencil-alt mg-r-10"></i>Sunting</a>
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
    <div class="row">
        <div class="col-lg-8">
            <div class="widget-2">
                <div class="card shadow-base mb-3">
                    <div class="card-body bd-color-gray-lighter">
                        <table class="table table-show display">
                            <tbody>
                                <tr>
                                    <th width="225">Tahun Akademik</th>
                                    <td width="1">:</td>
                                    <td>{{$data->academicYear->tahun_akademik." - ".$data->academicYear->semester}}</td>
                                </tr>
                                <tr>
                                    <th>Program Studi</th>
                                    <td>:</td>
                                    <td>{{$data->studyProgram->nama}}</td>
                                </tr>
                                <tr>
                                    <th>Lembaga Mitra</th>
                                    <td>:</td>
                                    <td>{{$data->nama_lembaga}}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kerja Sama</th>
                                    <td>:</td>
                                    <td>{{$data->jenis}}</td>
                                </tr>
                                <tr>
                                    <th>Tingkat Kerja Sama</th>
                                    <td>:</td>
                                    <td>{{$data->tingkat}}</td>
                                </tr>
                                <tr>
                                    <th>Judul Kegiatan</th>
                                    <td>:</td>
                                    <td>{{$data->judul_kegiatan}}</td>
                                </tr>
                                <tr>
                                    <th>Manfaat Kegiatan</th>
                                    <td>:</td>
                                    <td>{{$data->manfaat_kegiatan}}</td>
                                </tr>
                                <tr>
                                    <th>Waktu Kegiatan</th>
                                    <td>:</td>
                                    <td>{{$data->waktu}}</td>
                                </tr>
                                <tr>
                                    <th>Durasi Kegiatan</th>
                                    <td>:</td>
                                    <td>{{$data->durasi}}</td>
                                </tr>
                                <tr>
                                    <th>Tindak Lanjut UPPS</th>
                                    <td>:</td>
                                    <td>{!!nl2br($data->tindak_lanjut)!!}</td>
                                </tr>
                                <tr>
                                    <th>Bukti Kerja Sama</th>
                                    <td>:</td>
                                    <td>
                                        <a href="{{route('collaboration.download',$data->bukti_file)}}" target="_blank">
                                            <i class="fa fa-download mr-1"></i> {{$data->bukti_nama}}
                                        </a>
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
@endsection


@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
