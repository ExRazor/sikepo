@extends('layouts.master')

@section('title', 'Capaian Pembelajaran Lulusan')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('alumnus-attainment') as $breadcrumb)
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
        <h4>Capaian Pembelajaran Lulusan</h4>
        <p class="mg-b-0">Data Capaian Pembelajaran Lulusan</p>
    </div>
</div>

<div class="br-pagebody">
    <div id="accordion_alumnusAttainment" class="accordion accordion-head-colored accordion-info" role="tablist" aria-multiselectable="false">
        @foreach($studyProgram as $sp)
        <div class="card">
            <div class="card-header" role="tab" id="heading_attainment_{{$sp->kd_prodi}}">
                <h6 class="mg-b-0">
                    <a class="collapsed transition" data-toggle="collapse" data-parent="#accordion_alumnusAttainment" href="#schedule_attainment_{{$sp->kd_prodi}}" aria-expanded="true" aria-controls="schedule_attainment_{{$sp->kd_prodi}}">
                        {{$sp->nama}}
                    </a>
                </h6>
            </div><!-- card-header -->
            <div id="schedule_attainment_{{$sp->kd_prodi}}" class="collapse" role="tabpanel" aria-labelledby="heading_attainment_{{$sp->kd_prodi}}">
                <div class="card-block pd-20">
                    <table id="table_attainment_{{$sp->kd_prodi}}" class="table table-colored table-dark" >
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
                            @foreach($academicYear as $ay)
                            <tr>
                                <td class="text-center">{{$ay->tahun_akademik}}</td>
                                <td class="text-center">{{$jumlah[$sp->kd_prodi][$ay->tahun_akademik]}}</td>
                                <td class="text-center">{{$min_ipk[$sp->kd_prodi][$ay->tahun_akademik]}}</td>
                                <td class="text-center">{{number_format($rata_ipk[$sp->kd_prodi][$ay->tahun_akademik],2)}}</td>
                                <td class="text-center">{{$max_ipk[$sp->kd_prodi][$ay->tahun_akademik]}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div><!-- accordion -->
</div>
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
