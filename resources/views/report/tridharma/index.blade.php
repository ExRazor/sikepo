@extends('layouts.master')

@section('title', 'Laporan Tridharma')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('report-tridharma') as $breadcrumb)
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
        <i class="icon fa fa-file-invoice""></i>
        <div>
            <h4>Laporan Tridharma Dosen</h4>
            <p class="mg-b-0">Grafik, ekspor, dan cetak laporan dari tridharma dosen</p>
        </div>
    </div>
    @if(!Auth::user()->hasRole('kajur'))
    <div class="ml-auto">
        <div class="d-flex">
            <div>
                <button class="btn btn-primary btn-block text-white" data-toggle="modal" data-target="#modal-tridharma-print"><i class="fa fa-print mg-r-10"></i> Cetak</a>
            </div>
            {{-- <div>
                <a href="#" class="btn btn-teal btn-block text-white"><i class="fa fa-file-excel mg-r-10"></i> Ekspor</a>
            </div> --}}
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
                        @foreach($periodeTahun as $pt)
                        <option value="{{$pt->tahun_akademik}}">{{$pt->tahun_akademik}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 pl-0">
                    <select id="periode_akhir_filter" class="form-control filter-box">
                        @foreach($periodeTahun as $pt)
                        <option value="{{$pt->tahun_akademik}}">{{$pt->tahun_akademik}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="row row-sm mg-t-20">
        <div class="col-lg-12">
            <div class="widget-2">
                <div class="card shadow-base overflow-hidden">
                    <div class="card-body">
                        <div id="chart" url-target={{route('ajax.report.tridharma.chart')}}>
                            <canvas id="chart" height="1000px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<iframe id="iframe_cetak" style="display:none;" name="iframe_cetak"></iframe>
@include('report.tridharma.form_cetak')
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/chart.js/Chart.min.js"></script>
@endsection

@push('custom-js')
    <script type="text/javascript">
        chart();

        $('.filter-box').bind("keyup change", function(){
            chart()
        });

        var myChart;
        function chart() {
            var prodi         = $('#kd_prodi_filter').val();
            var periode_awal  = $('#periode_awal_filter').val();
            var periode_akhir = $('#periode_akhir_filter').val();

            var dosen       = new Array();
            var penelitian  = new Array();
            var pengabdian  = new Array();
            var publikasi   = new Array();
            var luaran      = new Array();
            var chartCont   = $('#chart');
            var url         = chartCont.attr('url-target');
            chartCont.html('<canvas height="1000px"></canvas>')
            var canvas      = chartCont.find('canvas');

            var periode_title = periode_awal;
            if(periode_akhir != periode_awal) {
                periode_title = periode_awal+' - '+periode_akhir;
            }

            var prodi_title = '';
            if(prodi != '') {
                prodi_title = '('+$('#kd_prodi_filter option:selected').text()+')';
            }

            var title       = 'Tridharma Dosen Periode '+periode_title+' '+prodi_title;
            var type_chart;

            var ajax_data = {
                prodi:prodi,
                periode_awal:periode_awal,
                periode_akhir:periode_akhir
            };

            $.post(url, ajax_data).done(function(response){

                $.each(response.penelitian, function(i,d){
                    dosen.push(d.nama);
                    penelitian.push(d.jumlah);
                });

                $.each(response.pengabdian, function(index,data){
                    pengabdian.push(data.jumlah);
                });

                $.each(response.publikasi, function(index,data){
                    publikasi.push(data.jumlah);
                });

                // $.each(response.luaran, function(index,data){
                //     luaran.push(data.jumlah);
                // });

                var dataChart = {
                    labels: dosen,
                    datasets: [
                        {
                            label: 'Penelitian',
                            backgroundColor: '#17A2B8',
                            borderWidth: 1,
                            data: penelitian
                        },
                        {
                            label: 'Pengabdian',
                            backgroundColor: '#6f42c1',
                            data: pengabdian
                        },
                        {
                            label: 'Publikasi',
                            backgroundColor: '#1CAF9A',
                            data: publikasi
                        },
                        // {
                        //     label: 'Luaran',
                        //     backgroundColor: '#0866C6',
                        //     data: luaran
                        // },
                    ]
                };

                var optionsChart = {
                    responsive: true,
                    title: {
                        display: true,
                        text: title
                    },
                    hover: {
                        animationDuration: 0
                    },
                    // animation: {
                    //     duration: 1500,
                    //     onComplete: function() {
                    //         var chartInstance = this.chart,
                    //         ctx = chartInstance.ctx;


                    //         ctx.font =  Chart.helpers.fontString(
                    //                         Chart.defaults.global.defaultFontSize,
                    //                         Chart.defaults.global.defaultFontStyle,
                    //                         Chart.defaults.global.defaultFontFamily
                    //                     );
                    //         ctx.fillStyle = 'white';
                    //         ctx.textAlign = 'center';
                    //         ctx.textBaseline = 'bottom';

                    //         this.data.datasets.forEach(function(dataset, i) {
                    //             var meta = chartInstance.controller.getDatasetMeta(i);
                    //             meta.data.forEach(function(bar, index) {
                    //                 var data = dataset.data[index];
                    //                 ctx.fillText(data, bar._model.x -10, bar._model.y +7);
                    //             });
                    //         });
                    //     }
                    // },
                };

                if (myChart) {
                    myChart.destroy();
                }

                var myChart = new Chart(canvas, {
                    type: 'horizontalBar',
                    data: dataChart,
                    options: optionsChart
                });

            });
        }
    </script>
@endpush
