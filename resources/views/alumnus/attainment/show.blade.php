@extends('layouts.master')

@section('title', 'Capaian Pembelajaran Lulusan - Prodi '.$studyProgram->singkatan)

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('alumnus-attainment-show',$studyProgram) as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon fa fa-list-alt"></i>
    <div>
        <h4>Program Studi: {{$studyProgram->nama}}</h4>
        <p class="mg-b-0">Rincian Pencapaian IPK Lulusan</p>
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
    <table id="table_teacher" class="table table-colored table-dark" >
        <thead>
            <tr>
                <th class="text-center align-middle" rowspan="2">Tahun Lulus</th>
                <th class="text-center align-middle" rowspan="2">Jumlah Lulusan</th>
                <th class="text-center" colspan="3">Indeks Prestasi Kumulatif (IPK)</th>
            </tr>
            <tr>
                <th class="text-center">Min.</th>
                <th class="text-center">Rata-rata</th>
                <th class="text-center">Maks.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($academicYear as $i => $ay)
            <tr>
                <td class="text-center">{{$ay->tahun_akademik}}</td>
                <td class="text-center">{{$jumlah[$ay->tahun_akademik]}}</td>
                <td class="text-center">{{$min_ipk[$ay->tahun_akademik]}}</td>
                <td class="text-center">{{number_format($rata_ipk[$ay->tahun_akademik],2)}}</td>
                <td class="text-center">{{$max_ipk[$ay->tahun_akademik]}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
