@extends('layouts.master')

@section('title', 'Rincian Luaran Penelitian')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('output-activity-show',$data) as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon fa fa-american-sign-language-interpreting"></i>
    <div>
        <h4>Rincian Luaran Penelitian</h4>
        <p class="mg-b-0">Rincian data luaran penelitian</p>
    </div>
    <div class="row ml-auto" style="width:300px">
        <div class="col-6 pr-1">
            <form method="POST">
                <input type="hidden" value="{{encode_id($data->id)}}" name="id">
                <button class="btn btn-danger btn-block btn-delete" data-dest="{{ route('output-activity.delete') }}" data-redir="{{ route('output-activity') }}"><i class="fa fa-trash mg-r-10"></i> Hapus</button>
            </form>
        </div>
        <div class="col-6">
            <a href="{{ route('output-activity.edit',encode_id($data->id)) }}" class="btn btn-warning btn-block" style="color:white"><i class="fa fa-pencil-alt mg-r-10"></i>Sunting</a>
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
                                    <td>Judul Luaran</td>
                                    <td>:</td>
                                    <td>{{$data->judul_luaran}}</td>
                                </tr>
                                <tr>
                                    <td>Kategori Luaran</td>
                                    <td>:</td>
                                    <td>{{$data->outputActivityCategory->nama}}</td>
                                </tr>
                                <tr>
                                    <td>Tahun Luaran</td>
                                    <td>:</td>
                                    <td>{{$data->tahun_luaran}}</td>
                                </tr>
                                <tr>
                                    <td>Jenis Kegiatan</td>
                                    <td>:</td>
                                    <td>{{$data->kegiatan}}</td>
                                </tr>
                                <tr>
                                    <td>Judul Kegiatan</td>
                                    <td>:</td>
                                    @if($data->kegiatan=='Penelitian')
                                    <td>
                                        <a href="{{route('research.show',encode_id($data->research->id))}}">
                                            {{$data->research->judul_penelitian}}
                                        </a>
                                    </td>
                                    @else
                                    <td>
                                        <a href="{{route('community-service.show',encode_id($data->communityService->id))}}">
                                            {{$data->communityService->judul_pengabdian}}
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Dosen Penanggung Jawab</td>
                                    <td>:</td>
                                    @if($data->kegiatan=='Penelitian')
                                    <td>
                                        {{$data->research->researchKetua->teacher->nama}}
                                        ({{$data->research->researchKetua->teacher->nidn}})
                                    </td>
                                    @else
                                    <td>
                                        {{$data->communityService->serviceKetua->teacher->nama}}
                                        ({{$data->communityService->serviceKetua->teacher->nidn}})
                                    </td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Asal Program Studi</td>
                                    <td>:</td>
                                    @if($data->kegiatan=='Penelitian')
                                    <td>
                                        {{$data->research->researchKetua->teacher->studyProgram->nama}}
                                        ({{$data->research->researchKetua->teacher->studyProgram->department->nama}} - {{$data->research->researchKetua->teacher->studyProgram->department->faculty->singkatan}})
                                    </td>
                                    @else
                                    <td>
                                        {{$data->communityService->serviceKetua->teacher->studyProgram->nama}}
                                        ({{$data->communityService->serviceKetua->teacher->studyProgram->department->nama}} - {{$data->communityService->serviceKetua->teacher->studyProgram->department->faculty->singkatan}})
                                    </td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Keterangan</td>
                                    <td>:</td>
                                    <td>{{$data->keterangan}}</td>
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
