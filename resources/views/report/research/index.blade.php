@extends('layouts.master')

@section('title', 'Data Penelitian')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('research') as $breadcrumb)
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
            <h4>Data Penelitian</h4>
            <p class="mg-b-0">Olah Data Penelitian</p>
        </div>
    </div>
    @if(!Auth::user()->hasRole('kajur'))
    <div class="ml-auto">
        <a href="{{ route('research.create') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Penelitian</a>
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
    @if(!Auth::user()->hasRole('kaprodi'))
    <div class="row">
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select id="kd_prodi_filter" class="form-control filter-box">
                <option value="">- Pilih Program Studi -</option>
                @foreach($studyProgram as $sp)
                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <div class="row">
                <div class="col-6 pr-1">
                    <select id="periode_awal_filter" class="form-control filter-box">
                        <option value="">- Thn Awal -</option>
                        @foreach($periodeTahun as $pt)
                        <option value="{{$pt->tahun_akademik}}">{{$pt->tahun_akademik}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 pl-0">
                    <select id="periode_akhir_filter" class="form-control filter-box">
                        <option value="">- Thn Akhir -</option>
                        @foreach($periodeTahun as $pt)
                        <option value="{{$pt->tahun_akademik}}">{{$pt->tahun_akademik}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


        </div>
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">

        </div>
    </div>
    @endif
    <div class="row row-sm mg-t-20">
        <div class="col-lg-12">
            <div class="widget-2">
                <div class="card shadow-base overflow-hidden">
                    <div class="card-header">
                        <h6 class="card-title">Tridharma Dosen <span class="tahun_periode"></span></h6>
                    </div>
                    <div class="card-body">
                        <div><canvas id="chart" height="100" url-target={{route('ajax.index.chart')}}></canvas></div>
                    </div>
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
<script src="{{asset('assets/lib')}}/datatables.net/js/dataTables.hideEmptyColumns.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection

@push('custom-js')
    <script type="text/javascript">

        url = $('#chart').attr('url-target');
        chart(url);

        function chart(url) {
            var dosen       = new Array();
            var penelitian  = new Array();
            var pengabdian  = new Array();
            var publikasi   = new Array();
            var luaran      = new Array();
            var canvas      = $('#chart');
            var title       = 'Tridharma Dosen Periode ';
            var type_chart;

            $.ajax({

            });

            $.get(url, function(response){

                $.each(response.penelitian, function(index,data){
                    dosen.push(index);
                    penelitian.push(data);
                });

                $.each(response.pengabdian, function(index,data){
                    pengabdian.push(data);
                });

                $.each(response.publikasi, function(index,data){
                    publikasi.push(data);
                });

                $.each(response.luaran, function(index,data){
                    luaran.push(data);
                });

                var dataChart = {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                    datasets: [{
                        label: 'Dataset 1',
                        backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
                        borderColor: window.chartColors.red,
                        borderWidth: 1,
                        data: [
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor()
                        ]
                    }, {
                        label: 'Dataset 2',
                        backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
                        borderColor: window.chartColors.blue,
                        data: [
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor()
                        ]
                    }]
                };

                new Chart(canvas, {
                    type: 'horizontalBar',
                    data: dataChart,
                    options: {
                        responsive: true,
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: true,
                            text: 'TRIDHARMA DOSEN'
                        }
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });

            });
        }
    </script>
@endpush
