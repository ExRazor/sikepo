@extends('layouts.master')

@section('title', 'Penilaian Kerja Sama')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
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
    <i class="icon fa fa-handshake"></i>
    <div>
        <h4>Penilaian Kerja Sama</h4>
        <p class="mg-b-0">Cek hasil perhitungan penilaian kerja sama program studi</p>
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
    <div id="penilaian_kerjasama" class="widget-2 hasil-penilaian d-none">
        <div class="card shadow-base mb-3">
            <div class="card-header">
                <div class="card-title">
                    <h6 class="mg-b-0">
                        Kerja Sama 3 Tahun Terakhir
                    </h6>
                </div>
                <div class="ml-auto">
                    <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-kerjasama"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
                </div>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <div class="row mg-b-20">
                    <div class="col-md-12">
                        <img class="w-100" src="{{ asset ('penilaian/img') }}/kerjasama.png" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Jumlah Dosen Tetap Program Studi (NDTPS)</label>
                                    <input type="text" class="form-control" id="dtps" disabled>
                                </div>
                            </div>
                            <div class="col-md-4 ml-auto">
                                <div class="form-group">
                                    <label>Skor</label>
                                        <input type="text" class="form-control" id="skor" disabled>
                                    <small class="form-text text-muted">Skor = <span id="rumus"></span></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pendidikan">Kerja Sama Pendidikan (N1)</label>
                                    <input type="text" class="form-control" id="pendidikan" disabled>
                                    <small class="form-text text-muted">a * N1 = <span class="rata_pendidikan">0</span></small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="penelitian">Kerja Sama Penelitian (N2)</label>
                                    <input type="text" class="form-control" id="penelitian" disabled>
                                    <small class="form-text text-muted">b * N2 = <span class="rata_penelitian">0</span></small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pkm">Kerja Sama PkM (N3)</label>
                                    <input type="text" class="form-control" id="pkm" disabled>
                                    <small class="form-text text-muted">c * N3 = <span class="rata_pkm">0</span></small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="skor_a">Skor A (RK)</label>
                                    <input type="text" class="form-control" id="skor_a" disabled>
                                    <small class="form-text text-muted">A = <span id="rumus_a"></span></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="internasional">Kerjasama Internasional (NI)</label>
                                    <input type="text" class="form-control" id="internasional" disabled>
                                    <!-- <small class="form-text text-muted">RI = NI/NDT = <span class="rata_inter">0</span></small> -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nasional">Kerjasama Nasional (NN)</label>
                                    <input type="text" class="form-control" id="nasional" disabled>
                                    <!-- <small class="form-text text-muted">RN = NN/NDT = <span class="rata_nasional">0</span></small> -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="lokal">Kerjasama Lokal (NL)</label>
                                    <input type="text" class="form-control" id="lokal" disabled>
                                    <!-- <small class="form-text text-muted">RL = NL/NDT = <span class="rata_lokal">0</span></small> -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="skor_b">Skor B</label>
                                    <input type="text" class="form-control" id="skor_b" readonly>
                                    <small class="form-text text-muted">B = <span id="rumus_b"></span></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-success">
                            <strong>FAKTOR JENIS KERJA SAMA</strong><br>
                            <span>
                                a = 3; b= 2; c = 1
                            </span>
                            <hr>
                            <strong>FAKTOR TINGKAT KERJA SAMA</strong><br>
                            <span>
                                a = 2; b= 6; c = 9
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('simulasi.kerjasama.modal')
@endsection


@section('js')
<script src="{{asset('assets')}}/simulasi.js"></script>
@endsection

@push('custom-js')
<script>

$('#form_penilaian').submit(function(e){
    e.preventDefault();

    var data      = $(this).serialize();
    // var indikator = $(this).find('select[name=indikator]').val();
    var indikator = "penilaian_kerjasama";
    var button    = $(this).find('button[type=submit]');

    button.html('<i class="fa fa-circle-notch fa-spin"></i>');
    button.attr('disabled',true);

    $.ajax({
        url: '/assessment/collaboration',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function (data) {

            $('#penilaian_kerjasama')
                .find('#dtps').val(data.jumlah['dtps']).end()
                .find('#skor').val(data.skor['total']).end()
                .find('#pendidikan').val(data.jumlah['pendidikan']).end()
                .find('#penelitian').val(data.jumlah['penelitian']).end()
                .find('#pkm').val(data.jumlah['pengabdian']).end()
                .find('#skor_a').val(data.skor['a']).end()
                .find('#internasional').val(data.jumlah['internasional']).end()
                .find('#nasional').val(data.jumlah['nasional']).end()
                .find('#lokal').val(data.jumlah['lokal']).end()
                .find('#skor_b').val(data.skor['b']).end()
                .find('span.rumus').val(data.rumus['total']).end()
                .find('span.rata_pendidikan').val(data.rata['pendidikan']).end()
                .find('span.rata_penelitian').val(data.rata['penelitian']).end()
                .find('span.rata_pkm').val(data.rata['pengabdian']).end()
                .find('span.rumus_a').val(data.rumus['a']).end()
                .find('span.rumus_b').val(data.rumus['b']).end();

            button.attr('disabled', false);
            button.text('Tampilkan');
            $('.hasil-penilaian').addClass('d-none');
            $('#'+indikator).removeClass('d-none');
        }
    });
})


</script>
@endpush
