@extends('layouts.master')

@section('title', 'Penilaian Penelitian')

@section('style')
@endsection

@section('content')
{{-- <div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('research') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div> --}}
<div class="br-pagetitle">
    <i class="icon fa fa-sort-amount-down"></i>
    <div>
        <h4>Penelitian</h4>
        <p class="mg-b-0">Penilaian Kinerja Penelitian DTPS pada Program Studi</p>
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
    <div class="card shadow-base mb-3">
        <form id="form_penilaian" method="POST" enctype="application/x-www-form-urlencoded">
            <div class="card-body bd-color-gray-lighter">
                <div class="row">
                    <div class="col-md-4 offset-md-4">
                        <div class="form-group">
                            <label>Program Studi:<span class="tx-danger">*</span></label>
                            <div id="prodi" class="parsley-select">
                                <select class="form-control" name="kd_prodi" data-placeholder="Pilih Prodi" {{Auth::user()->role=='kaprodi' ? 'disabled' : 'required'}}>
                                    {{-- <option value="0">Semua</option> --}}
                                    @foreach ($studyProgram as $sp)
                                    <option value="{{$sp->kd_prodi}}" {{ (isset($data) && $data->kd_prodi==$sp->kd_prodi) || Request::old('kd_prodi')==$sp->kd_prodi || Auth::user()->kd_prodi==$sp->kd_prodi  ? 'selected' : ''}}>{{$sp->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bd-color-gray-lighter">
                <div class="row">
                    <div class="col-6 mx-auto">
                        <div class="text-center">
                            <button type="submit" class="btn btn-info btn-submit">Tampilkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="penelitian_dtps" class="widget-2 hasil-penilaian d-none">
        <div class="card shadow-base mb-3">
            <div class="card-header">
                <div class="card-title">
                    <h6 class="mg-b-0">
                        Penelitian DTPS 3 Tahun Terakhir
                    </h6>
                </div>
                <div class="ml-auto">
                    <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-penelitian-dtps"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
                </div>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <div class="row mg-b-20">
                    <div class="col-md-12">
                        <img class="w-100" src="{{ asset ('penilaian/img') }}/penelitian-1.jpg" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="internasional">Penelitian Internasional (NI)</label>
                            <input type="text" class="form-control" id="ni" disabled>
                            <small class="form-text text-muted">RI = NI/NDT = <span class="rata_inter">0</span></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Penelitian Nasional (NN)</label>
                            <input type="text" class="form-control" id="nn" disabled>
                            <small class="form-text text-muted">RN = NN/NDT = <span class="rata_nasional">0</span></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Penelitian Lokal/PT (NL)</label>
                            <input type="text" class="form-control" id="nl" disabled>
                            <small class="form-text text-muted">RL = NL/NDT = <span class="rata_lokal">0</span></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Jumlah Dosen Tetap (NDT)</label>
                            <input type="text" class="form-control" id="dtps" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Skor</label>
                            <input type="text" class="form-control" id="skor_penelitian" readonly>
                            <small class="form-text text-muted">Skor = <span class="rumus_penelitian"></span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('simulasi.penelitian.modal')
@endsection

@section('js')
<script src="{{asset('assets')}}/simulasi.js"></script>
@endsection

@section('custom-js')
<script type="text/javascript">

$(function(){

$('form#form_penilaian').submit(function(e){
    e.preventDefault();

    var kd_prodi  = $(this).find('select[name=kd_prodi]').val();
    var indikator = "penelitian_dtps";
    var button    = $(this).find('button[type=submit]');

    button.html('<i class="fa fa-circle-notch fa-spin"></i>');
    button.attr('disabled',true);

    penilaian_penelitian_dtps(kd_prodi);

    setTimeout(function() {
        button.attr('disabled', false);
        button.text('Tampilkan');
        $('.hasil-penilaian').addClass('d-none');
        $('#'+indikator).removeClass('d-none');
    }, 1000);

});

function penilaian_penelitian_dtps(kd_prodi) {

    $.ajax({
        url: '/assessment/research',
        type: 'POST',
        data: {
            kd_prodi:kd_prodi,
        },
        dataType: 'json',
        success: function (data) {

            $('#penelitian_dtps')
                .find('#ni').val(data.jumlah['ni']).end()
                .find('#nn').val(data.jumlah['nn']).end()
                .find('#nl').val(data.jumlah['nl']).end()
                .find('#dtps').val(data.jumlah['dtps']).end()
                .find('#skor_penelitian').val(data.skor.toFixed(2)).end()
                .find('span.rata_inter').text(data.rata['inter'].toFixed(2)).end()
                .find('span.rata_nasional').text(data.rata['nasional'].toFixed(2)).end()
                .find('span.rata_lokal').text(data.rata['lokal'].toFixed(2)).end()
                .find('span.rumus_penelitian').text(data.rumus);
        }
    });
}


});


</script>
@endsection
