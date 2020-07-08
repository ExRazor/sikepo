@extends('layouts.master')

@section('title', 'Rincian Kepuasan Akademik')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('academic-satisfaction-show',$data) as $breadcrumb)
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
        <i class="icon fa fa-percentage"></i>
        <div>
            <h4>{{ $data->studyProgram->nama }} Tahun {{ $data->academicYear->tahun_akademik }}</h4>
            <p class="mg-b-0">Tingkat Kepuasan Mahasiswa Terhadap Akademik Program Studi {{ $data->studyProgram->nama }} Tahun {{ $data->academicYear->tahun_akademik }}</p>
        </div>
    </div>
    @if (!Auth::user()->hasRole('kajur'))
    <div class="row ml-auto">
        <div class="col-6 pr-1">
            <form method="POST">
                <input type="hidden" value="{{encrypt($data->kd_kepuasan)}}" name="id">
                <button class="btn btn-danger btn-block btn-delete" data-dest="{{ route('academic.satisfaction.destroy',encrypt($data->kd_kepuasan)) }}" data-redir="{{ route('academic.satisfaction.index') }}"><i class="fa fa-trash mg-r-10"></i> Hapus</button>
            </form>
        </div>
        <div class="col-6">
            <a href="{{ route('academic.satisfaction.edit',encrypt($data->kd_kepuasan)) }}" class="btn btn-warning btn-block pr-4"><i class="fa fa-pencil-alt mg-r-10"></i>Sunting</a>
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
                <table class="table display responsive nowrap" data-sort="desc">
                    <thead>
                        <tr>
                            <th class="text-center align-middle" rowspan="2" width="25">#</th>
                            <th class="text-center align-middle" rowspan="2" width="400">Aspek yang Diukur</th>
                            <th class="text-center align-middle" colspan="4">Tingkat Kepuasan Mahasiswa</th>
                            <th class="text-center align-middle" rowspan="2" width="350">Rencana Tindak Lanjut<br>oleh UPPS/PS</th>
                        </tr>
                        <tr>
                            <th class="text-center align-middle">Sangat Baik</th>
                            <th class="text-center align-middle">Baik</th>
                            <th class="text-center align-middle">Cukup</th>
                            <th class="text-center align-middle">Kurang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aspek as $a)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>
                                {{$a->satisfactionCategory->nama}} {{isset($a->satisfactionCategory->alias) ? ' ('.$a->satisfactionCategory->alias.')' : ''}}<br>
                                @isset($a->satisfactionCategory->deskripsi)
                                <small>{{$a->satisfactionCategory->deskripsi}}</small>
                                @endisset
                            </td>
                            <td class="text-center">{{$a->sangat_baik}}%</td>
                            <td class="text-center">{{$a->baik}}%</td>
                            <td class="text-center">{{$a->cukup}}%</td>
                            <td class="text-center">{{$a->kurang}}%</td>
                            <td class="text-center">{{$a->tindak_lanjut}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-center font-weight-bold" colspan="2">Jumlah</th>
                            <th class="text-center">{{$jumlah->sangat_baik}}%</th>
                            <th class="text-center">{{$jumlah->baik}}%</th>
                            <th class="text-center">{{$jumlah->cukup}}%</th>
                            <th class="text-center">{{$jumlah->kurang}}%</th>
                            <th></th>
                        </tr>
                    </tfoot>
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
