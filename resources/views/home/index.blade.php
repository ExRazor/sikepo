@extends('layouts.master')

@section('title','Beranda')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('dashboard') as $breadcrumb)
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
        <i class="icon fa fa-home"></i>
        <div>
            <h4>Welcome</h4>
            <p class="mg-b-0">Selamat datang {{Auth::user()->name}}</p>
        </div>
    </div>
</div>

<div class="br-pagebody">
    <div class="row">
        <div class="col-sm-6 col-xl-3">
			<div class="bg-info rounded overflow-hidden">
				<div class="pd-x-20 pd-t-20 d-flex align-items-center">
					<i class="fa fa-book-reader tx-60 lh-0 tx-white op-7"></i>
					<div class="mg-l-20">
						<p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">Penelitian</p>
						<p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1 hitung" data-value="{{$count_card['penelitian']}}">0</p>
						<span class="tx-11 tx-roboto tx-white-8">Periode Tahun 2020</span>
					</div>
				</div>
				<div id="ch1" class="ht-50 tr-y-1"></div>
			</div>
        </div><!-- col-3 -->
        <div class="col-sm-6 col-xl-3 mg-t-20 mg-sm-t-0">
			<div class="bg-purple rounded overflow-hidden">
				<div class="pd-x-20 pd-t-20 d-flex align-items-center">
					<i class="fa fa-american-sign-language-interpreting tx-60 lh-0 tx-white op-7"></i>
					<div class="mg-l-20">
						<p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">Pengabdian</p>
						<p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1 hitung" data-value="{{$count_card['pengabdian']}}">0</p>
						<span class="tx-11 tx-roboto tx-white-8">Periode Tahun {{current_academic()->tahun_akademik}}</span>
					</div>
				</div>
				<div id="ch3" class="ht-50 tr-y-1"></div>
			</div>
        </div><!-- col-3 -->
        <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
			<div class="bg-teal rounded overflow-hidden">
				<div class="pd-x-20 pd-t-20 d-flex align-items-center">
					<i class="fa fa-newspaper tx-60 lh-0 tx-white op-7"></i>
					<div class="mg-l-20">
						<p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">Publikasi Dosen</p>
						<p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1 hitung" data-value="{{$count_card['publikasi']}}">0</p>
						<span class="tx-11 tx-roboto tx-white-8">Periode Tahun {{current_academic()->tahun_akademik}}</span>
					</div>
				</div>
				<div id="ch2" class="ht-50 tr-y-1"></div>
			</div>
        </div><!-- col-3 -->
        <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
			<div class="bg-primary rounded overflow-hidden">
				<div class="pd-x-20 pd-t-20 d-flex align-items-center">
					<i class="fa fa-boxes tx-60 lh-0 tx-white op-7"></i>
					<div class="mg-l-20">
						<p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">Luaran Dosen</p>
						<p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1 hitung" data-value="{{$count_card['luaran']}}">0</p>
					<span class="tx-11 tx-roboto tx-white-8">Periode Tahun {{current_academic()->tahun_akademik}}</span>
					</div>
				</div>
				<div id="ch4" class="ht-50 tr-y-1"></div>
			</div>
        </div><!-- col-3 -->
    </div><!-- row -->
    <div class="row row-sm mg-t-20">
        <div class="col-lg-12">
            <div class="widget-2">
            <div class="card shadow-base overflow-hidden">
                <div class="card-header">
                    <h6 class="card-title">Jumlah Tridharma Dosen</h6>
                    <div class="btn-group" role="group">
                        <button class="btn btn-chart active">Penelitian</button>
                        <button class="btn btn-chart">Pengabdian</button>
                        <button class="btn btn-chart">Publikasi</button>
                        <button class="btn btn-chart">Luaran</button>
                    </div>
                </div><!-- card-header -->
                {{-- <div class="card-body pd-0 bd-color-gray-lighter">
                    <div class="row no-gutters tx-center">
                        <div class="col-12 col-sm-4 pd-y-20 tx-left">
                            <p class="pd-l-20 tx-12 lh-8 mg-b-0">

                                Note: Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula...
                            </p>
                        </div><!-- col-4 -->
                        <div class="col-6 col-sm-2 pd-y-20">
                            <h5 class="tx-inverse tx-lato tx-bold mg-b-5">1,343</h5>
                            <p class="tx-12 mg-b-0">Views</p>
                        </div><!-- col-2 -->
                        <div class="col-6 col-sm-2 pd-y-20 bd-l bd-color-gray-lighter">
                            <h5 class="tx-inverse tx-lato tx-bold mg-b-5">102</h5>
                            <p class="tx-12 mg-b-0">Likes</p>
                        </div><!-- col-2 -->
                        <div class="col-6 col-sm-2 pd-y-20 bd-l bd-color-gray-lighter">
                            <h5 class="tx-inverse tx-lato tx-bold mg-b-5">343</h5>
                            <p class="tx-12 mg-b-0">Comments</p>
                        </div><!-- col-2 -->
                        <div class="col-6 col-sm-2 pd-y-20 bd-l bd-color-gray-lighter">
                            <h5 class="tx-inverse tx-lato tx-bold mg-b-5">960</h5>
                            <p class="tx-12 mg-b-0">Shares</p>
                        </div><!-- col-2 -->
                    </div><!-- row -->
                </div><!-- card-body --> --}}
                <div class="card-body">
                    <div><canvas id="chart" height="100" url-target={{route('ajax.index.chart')}}></canvas></div>
                    {{-- <div id="rickshaw1" class="wd-100p ht-150 rounded-bottom rickshaw_graph"><svg width="636.5" height="150"><rect x="0" y="90" width="33.59305555555555" height="60" transform="matrix(1,0,0,1,0,0)" fill="#5058AB"></rect><rect x="70.72222222222221" y="75" width="33.59305555555555" height="75" transform="matrix(1,0,0,1,0,0)" fill="#5058AB"></rect><rect x="141.44444444444443" y="120" width="33.59305555555555" height="30" transform="matrix(1,0,0,1,0,0)" fill="#5058AB"></rect><rect x="212.16666666666666" y="105" width="33.59305555555555" height="45" transform="matrix(1,0,0,1,0,0)" fill="#5058AB"></rect><rect x="282.88888888888886" y="90" width="33.59305555555555" height="60" transform="matrix(1,0,0,1,0,0)" fill="#5058AB"></rect><rect x="353.61111111111114" y="30" width="33.59305555555555" height="120" transform="matrix(1,0,0,1,0,0)" fill="#5058AB"></rect><rect x="424.3333333333333" y="105" width="33.59305555555555" height="45" transform="matrix(1,0,0,1,0,0)" fill="#5058AB"></rect><rect x="495.0555555555555" y="30" width="33.59305555555555" height="120" transform="matrix(1,0,0,1,0,0)" fill="#5058AB"></rect><rect x="565.7777777777777" y="75" width="33.59305555555555" height="75" transform="matrix(1,0,0,1,0,0)" fill="#5058AB"></rect><rect x="33.59305555555555" y="120" width="33.59305555555555" height="30" transform="matrix(1,0,0,1,0,0)" fill="#14A0C1"></rect><rect x="104.31527777777777" y="60" width="33.59305555555555" height="90" transform="matrix(1,0,0,1,0,0)" fill="#14A0C1"></rect><rect x="175.03749999999997" y="15" width="33.59305555555555" height="135" transform="matrix(1,0,0,1,0,0)" fill="#14A0C1"></rect><rect x="245.7597222222222" y="60" width="33.59305555555555" height="90" transform="matrix(1,0,0,1,0,0)" fill="#14A0C1"></rect><rect x="316.4819444444444" y="24" width="33.59305555555555" height="126" transform="matrix(1,0,0,1,0,0)" fill="#14A0C1"></rect><rect x="387.2041666666667" y="90" width="33.59305555555555" height="60" transform="matrix(1,0,0,1,0,0)" fill="#14A0C1"></rect><rect x="457.9263888888889" y="60" width="33.59305555555555" height="90" transform="matrix(1,0,0,1,0,0)" fill="#14A0C1"></rect><rect x="528.648611111111" y="105" width="33.59305555555555" height="45" transform="matrix(1,0,0,1,0,0)" fill="#14A0C1"></rect><rect x="599.3708333333333" y="90" width="33.59305555555555" height="60" transform="matrix(1,0,0,1,0,0)" fill="#14A0C1"></rect></svg></div> --}}
                </div><!-- card-body -->
            </div><!-- card -->
            </div><!-- widget-2 -->
        </div><!-- col-6 -->
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/chart.js/Chart.min.js"></script>
<script src="{{asset('assets')}}/home.js"></script>
@endsection

@section('custom-js')
<script>
    url = $('#chart').attr('url-target');
    chart(url,'Penelitian');

    $('.btn-chart').on('click',function(){
        var type = $(this).text();
        $(document).find('.active').removeClass('active');
        $(this).addClass('active');

        chart(url,type);
    })
</script>
@endsection
