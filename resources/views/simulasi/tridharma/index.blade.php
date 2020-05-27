@extends('layouts.master')

@section('title', 'Penilaian Luaran dan Capaian Tridharma')

@section('style')
@endsection

@section('content')
<div class="br-pagetitle">
    <i class="icon fa fa-sort-amount-down"></i>
    <div>
        <h4>Luaran dan Capaian Tridharma</h4>
        <p class="mg-b-0">Penilaian Kinerja Luaran dan Capaian Tridharma pada Program Studi</p>
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
                                @if(Auth::user()->hasRole('kaprodi'))
                                <input type="hidden" name="kd_prodi" value="{{Auth::user()->kd_prodi}}">
                                @endif
                                <select class="form-control" name="kd_prodi" data-placeholder="Pilih Prodi" {{Auth::user()->role=='kaprodi' ? 'disabled' : 'required'}}>
                                    <option value="0">Semua</option>
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
    <div id="penilaian_capaian" class="widget-2 hasil-penilaian d-none">
        <div id="penilaian_ipk_lulusan" class="card shadow-base mb-3">
            <div class="card-header">
                <div class="card-title">
                    <h6 class="mg-b-0">
                        Capaian IPK Lulusan
                    </h6>
                </div>
                <div class="ml-auto">
                    <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-capaian-ipk"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
                </div>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <form class="form-perhitungan">
                    <div class="row mg-b-20">
                        <div class="col-md-12">
                            <img class="w-100" src="{{ asset ('penilaian/img') }}/tridharma-1.jpg" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Lulusan</label>
                                <input type="text" class="form-control" id="total_mahasiswa" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rata-Rata IPK</label>
                                <input type="text" class="form-control" id="rata_ipk" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Bobot Skor</label>
                                <input type="text" class="form-control" id="skor_ipk" readonly>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="penilaian_prestasi_mhs" class="card shadow-base mb-3">
            <div class="card-header">
                <div class="card-title">
                    <h6 class="mg-b-0">
                        Prestasi Akademik Mahasiswa 3 Tahun Terakhir
                    </h6>
                </div>
                <div class="ml-auto">
                    <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-prestasi-mhs"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
                </div>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <div class="row mg-b-20">
                    <div class="col-md-12">
                        <img class="w-100" src="{{ asset ('penilaian/img') }}/tridharma-2.jpg" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            <span>
                                Faktor a = 0.05 || Faktor b = 0.5 || Faktor c = 2
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="internasional">Prestasi Internasional (NI)</label>
                            <input type="text" class="form-control" id="inter" disabled>
                            <small class="form-text text-muted">RI = NI/NM = <span class="rata_inter">0</span></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Prestasi Nasional (NN)</label>
                            <input type="text" class="form-control" id="nasional" disabled>
                            <small class="form-text text-muted">RN = NN/NM = <span class="rata_nasional">0</span></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Prestasi Wilayah (NW)</label>
                            <input type="text" class="form-control" id="lokal" disabled>
                            <small class="form-text text-muted">RW = NW/NM = <span class="rata_lokal">0</span></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Jumlah Mahasiswa Aktif (NM)</label>
                            <input type="text" class="form-control" id="mahasiswa" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Skor</label>
                            <input type="text" class="form-control" id="skor_prestasi" disabled>
                            <small class="form-text text-muted">Skor = <span class="rumus_prestasi"></span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="penilaian_tempat_lulusan" class="card shadow-base mb-3">
            <div class="card-header">
                <div class="card-title">
                    <h6 class="mg-b-0">
                        Tingkat Tempat Kerja Lulusan
                    </h6>
                </div>
                <div class="ml-auto">
                    <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-lembaga-lulusan"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
                </div>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <form class="form-perhitungan">
                    <div class="row mg-b-20">
                        <div class="col-md-12">
                            <img class="w-100" src="{{ asset ('penilaian/img') }}/tridharma-3.jpg" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <span>
                                    Faktor a = 5% || Faktor b = 20% || Faktor c = 90%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="internasional">Lembaga Internasional (NI)</label>
                                <input type="text" class="form-control" id="inter" disabled>
                                <small class="form-text text-muted">RI = NI/NA = <span class="rata_inter">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Lembaga Nasional (NN)</label>
                                <input type="text" class="form-control" id="nasional" disabled>
                                <small class="form-text text-muted">RN = NN/NA = <span class="rata_nasional">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Lembaga Wilayah (NL)</label>
                                <input type="text" class="form-control" id="lokal" disabled>
                                <small class="form-text text-muted">RL = NL/NA = <span class="rata_lokal">0</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Jumlah Lulusan (NA)</label>
                                <input type="text" class="form-control" id="lulusan" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Skor</label>
                                <input type="text" class="form-control" id="skor_lembaga" readonly>
                                <small class="form-text text-muted">Skor = <span class="rumus_lembaga"></span></small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('simulasi.tridharma.modal')
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
    var indikator = "penilaian_capaian";
    var button    = $(this).find('button[type=submit]');

    button.html('<i class="fa fa-circle-notch fa-spin"></i>');
    button.attr('disabled',true);

    penilaian_skor_ipk(kd_prodi);
    penilaian_prestasi_mhs(kd_prodi);
    penilaian_tempat_lulusan(kd_prodi);

    setTimeout(function() {
        button.attr('disabled', false);
        button.text('Tampilkan');
        $('.hasil-penilaian').addClass('d-none');
        $('#'+indikator).removeClass('d-none');
    }, 1000);

});

function penilaian_skor_ipk(kd_prodi)
{
    $.ajax({
        url: '/assessment/tridharma/ipk',
        type: 'POST',
        data: {kd_prodi:kd_prodi},
        dataType: 'json',
        success: function (data) {

            $('#penilaian_ipk_lulusan')
                .find('#total_mahasiswa').val(data.lulusan).end()
                .find('#rata_ipk').val(data.rata_ipk).end()
                .find('#skor_ipk').val(data.skor);
        }
    });
}

function penilaian_prestasi_mhs(kd_prodi)
{
    $.ajax({
        url: '/assessment/tridharma/prestasi',
        type: 'POST',
        data: {kd_prodi:kd_prodi},
        dataType: 'json',
        success: function (data) {

            $('#penilaian_prestasi_mhs')
                .find('#inter').val(data.jumlah['inter']).end()
                .find('#nasional').val(data.jumlah['nasional']).end()
                .find('#lokal').val(data.jumlah['lokal']).end()
                .find('#mahasiswa').val(data.jumlah['mahasiswa']).end()
                .find('#skor_prestasi').val(data.skor.toFixed(2)).end()
                .find('span.rata_inter').text(data.rata['inter'].toFixed(2)).end()
                .find('span.rata_nasional').text(data.rata['nasional'].toFixed(2)).end()
                .find('span.rata_lokal').text(data.rata['lokal'].toFixed(2)).end()
                .find('span.rumus_prestasi').text(data.rumus);
        }
    });
}

function penilaian_tempat_lulusan(kd_prodi)
{
    $.ajax({
        url: '/assessment/tridharma/tempat_lulusan',
        type: 'POST',
        data: {kd_prodi:kd_prodi},
        dataType: 'json',
        success: function (data) {

            $('#penilaian_tempat_lulusan')
                .find('#inter').val(data.jumlah['inter']).end()
                .find('#nasional').val(data.jumlah['nasional']).end()
                .find('#lokal').val(data.jumlah['lokal']).end()
                .find('#lulusan').val(data.jumlah['lulusan']).end()
                .find('#skor_lembaga').val(data.skor.toFixed(2)).end()
                .find('span.rata_inter').text(data.rata['inter'].toFixed(2)).end()
                .find('span.rata_nasional').text(data.rata['nasional'].toFixed(2)).end()
                .find('span.rata_lokal').text(data.rata['lokal'].toFixed(2)).end()
                .find('span.rumus_lembaga').text(data.rumus);
        }
    });
}


});


</script>
@endsection
