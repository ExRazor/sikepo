@extends('layouts.master')

@section('title', 'Rincian Luaran')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('output-activity-teacher-show',$data) as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon fa fa-boxes"></i>
    <div>
        <h4>Rincian Luaran</h4>
        <p class="mg-b-0">Rincian data luaran dosen</p>
    </div>
    <div class="row ml-auto" style="width:300px">
        <div class="col-6 pr-1">
            <form method="POST">
                <input type="hidden" value="{{encode_id($data->id)}}" name="id">
                <button class="btn btn-danger btn-block btn-delete" data-dest="{{ route('output-activity.teacher.delete') }}" data-redir="{{ route('output-activity.teacher') }}"><i class="fa fa-trash mg-r-10"></i> Hapus</button>
            </form>
        </div>
        <div class="col-6">
            <a href="{{ route('output-activity.teacher.edit',encode_id($data->id)) }}" class="btn btn-warning btn-block" style="color:white"><i class="fa fa-pencil-alt mg-r-10"></i>Sunting</a>
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
    <div class="row">
        <div class="col-9">
            <div class="widget-2">
                <div class="card shadow-base mb-3">
                    <div class="card-body bd-color-gray-lighter">
                        <table id="table_teacher" class="table display responsive nowrap" data-sort="desc">
                            <tbody>
                                <tr>
                                    <td width="200">Jenis Kegiatan</td>
                                    <td width="20">:</td>
                                    <td>{{$data->kegiatan}}</td>
                                </tr>
                                <tr>
                                    <td>Nama Kegiatan</td>
                                    <td>:</td>
                                    <td>{{$data->nm_kegiatan}}</td>
                                </tr>
                                <tr>
                                    <td>Pembuat Luaran</td>
                                    <td>:</td>
                                    <td>
                                        <a href="{{ route('teacher.profile',encode_id($data->nidn)) }}">
                                            {{$data->teacher->nama}}<br>
                                            <small>NIDN. {{$data->nidn}} / {{$data->teacher->studyProgram->singkatan}}</small>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kategori Luaran</td>
                                    <td>:</td>
                                    <td>{{$data->outputActivityCategory->nama}}</td>
                                </tr>
                                <tr>
                                    <td>Judul Luaran</td>
                                    <td>:</td>
                                    <td>{{$data->judul_luaran}}</td>
                                </tr>
                                <tr>
                                    <td>Tahun Luaran</td>
                                    <td>:</td>
                                    <td>{{$data->thn_luaran}}</td>
                                </tr>
                                <tr>
                                    <td>Jenis Luaran</td>
                                    <td>:</td>
                                    <td><strong>{{$data->jenis_luaran}}</strong></td>
                                </tr>
                                @if($data->jenis_luaran=='Buku')
                                <tr>
                                    <td>Nama Buku</td>
                                    <td>:</td>
                                    <td>{{$data->nama_karya}}</td>
                                </tr>
                                <tr>
                                    <td>ISSN/ISBN</td>
                                    <td>:</td>
                                    <td>{{$data->issn}}</td>
                                </tr>
                                <tr>
                                    <td>Penerbit</td>
                                    <td>:</td>
                                    <td>{{$data->penerbit}}</td>
                                </tr>
                                <tr>
                                    <td>URL</td>
                                    <td>:</td>
                                    <td><a href="{{$data->url}}">{{$data->url}}</a></td>
                                </tr>
                                @elseif($data->jenis_luaran=='Jurnal')
                                <tr>
                                    <td>Nama Jurnal</td>
                                    <td>:</td>
                                    <td>{{$data->nama_karya}}</td>
                                </tr>
                                <tr>
                                    <td>ISSN/ISBN/e-ISSN</td>
                                    <td>:</td>
                                    <td>{{$data->issn}}</td>
                                </tr>
                                <tr>
                                    <td>Penyelenggara</td>
                                    <td>:</td>
                                    <td>{{$data->penyelenggara}}</td>
                                </tr>
                                <tr>
                                    <td>URL</td>
                                    <td>:</td>
                                    <td><a href="{{$data->url}}">{{$data->url}}</a></td>
                                </tr>
                                @elseif($data->jenis_luaran=='HKI')
                                <tr>
                                    <td>Nama Karya</td>
                                    <td>:</td>
                                    <td>{{$data->nama_karya}}</td>
                                </tr>
                                <tr>
                                    <td>Jenis Karya</td>
                                    <td>:</td>
                                    <td>{{$data->jenis_karya}}</td>
                                </tr>
                                <tr>
                                    <td>No. Permohonan</td>
                                    <td>:</td>
                                    <td>{{$data->no_permohonan}}</td>
                                </tr>
                                <tr>
                                    <td>Tgl. Permohonan</td>
                                    <td>:</td>
                                    <td>{{$data->tgl_permohonan}}</td>
                                </tr>
                                @elseif($data->jenis_luaran=='HKI Paten')
                                <tr>
                                    <td>Nama Karya</td>
                                    <td>:</td>
                                    <td>{{$data->nama_karya}}</td>
                                </tr>
                                <tr>
                                    <td>Jenis Karya</td>
                                    <td>:</td>
                                    <td>{{$data->jenis_karya}}</td>
                                </tr>
                                <tr>
                                    <td>No. Paten</td>
                                    <td>:</td>
                                    <td>{{$data->no_paten}}</td>
                                </tr>
                                <tr>
                                    <td>Tgl. Disahkan</td>
                                    <td>:</td>
                                    <td>{{$data->tgl_sah}}</td>
                                </tr>
                                @elseif($data->jenis_luaran=='Lainnya')
                                <tr>
                                    <td>Nama Karya</td>
                                    <td>:</td>
                                    <td>{{$data->nama_karya}}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Keterangan</td>
                                    <td>:</td>
                                    <td>
                                        {!!nl2br($data->keterangan)!!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>File Karya</td>
                                    <td>:</td>
                                    <td class="align-middle">
                                        <a href="{{route('output-activity.file.download','id='.encrypt($data->id))}}" target="_blank">
                                            {{$data->file_karya}}
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- card-body -->
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="alert alert-info">
                <h5>KATEGORI LUARAN</h5>
                <hr>
                @foreach($category as $c)
                <strong class="d-block d-sm-inline-block-force">{{$loop->iteration.'. '.$c->nama}}</strong><br>
                {{$c->deskripsi}}
                @if (!$loop->last)
                    <br><br>
                @endif
                @endforeach
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
