@extends('layouts.master')

@section('title', 'Penilaian Data Mahasiswa')

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
        <h4>Mahasiswa</h4>
        <p class="mg-b-0">Penilaian Data Mahasiswa</p>
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
    <div id="penilaian_mahasiswa" class="widget-2 hasil-penilaian d-none">
        <div id="penilaian_mhs_seleksi" class="card shadow-base mb-3">
            <div class="card-header">
                <div class="card-title">
                    <h6 class="mg-b-0">
                        Seleksi Mahasiswa
                    </h6>
                </div>
                <div class="ml-auto">
                    <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-seleksi-mahasiswa"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
                </div>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <form class="form-perhitungan">
                    <div class="row mg-b-20">
                        <div class="col-md-12">
                            <img class="w-100" src="{{ asset ('penilaian/img') }}/mahasiswa-1.jpg" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mahasiswa_calon">Jumlah Calon Mahasiswa</label>
                                        <input type="text" class="form-control" id="mhs_calon" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mahasiswa_baru">Jumlah Mahasiswa Baru</label>
                                        <input type="text" class="form-control" id="mhs_baru" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Rasio Perbandingan</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="rasio_calon" disabled>
                                                <small class="form-text text-muted">Rasio Calon Mahasiswa</small>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="rasio_baru" disabled>
                                                <small class="form-text text-muted">Rasio Mahasiswa Baru</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bobot Skor</label>
                                        <input type="text" class="form-control" id="skor_seleksi" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
        <div id="penilaian_mhs_asing" class="card shadow-base mb-3">
            <div class="card-header">
                <div class="card-title">
                    <h6 class="mg-b-0">
                        Mahasiswa Asing
                    </h6>
                </div>
                <div class="ml-auto">
                    <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-mahasiswa-asing"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
                </div>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <div class="row mg-b-20">
                    <div class="col-md-12">
                        <img class="w-100" src="{{ asset ('penilaian/img') }}/mahasiswa-2.jpg" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="internasional">Mahasiswa Aktif</label>
                                    <input type="text" class="form-control" id="mahasiswa_aktif" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Mahasiswa Asing Full-time</label>
                                    <input type="text" class="form-control" id="mahasiswa_asing_full" disabled>
                                    <small class="form-text text-muted">Persentase = <span class="persentase_asing_full">0</span>%</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Mahasiswa Asing Part-time</label>
                                    <input type="text" class="form-control" id="mahasiswa_asing_part" disabled>
                                    <small class="form-text text-muted">Persentase = <span class="persentase_asing_part">0</span>%</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Persentase Mhs Asing</label>
                                    <input type="text" class="form-control" id="persentase_asing" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Skor A (Upaya)</label>
                                    <select class="form-control" id="skor_asing_a">
                                        @for($i=1;$i <= 4;$i++)
                                        <option>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Skor B</label>
                                    <input type="text" class="form-control" id="skor_asing_b" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Bobot Skor</label>
                                    <input type="text" class="form-control" id="skor_asing" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('simulasi.mahasiswa.modal')
@endsection

@section('js')
<script src="{{asset('assets')}}/simulasi.js"></script>
@endsection

@push('custom-js')
<script>

$(function(){

$('#form_penilaian').submit(function(e){
    e.preventDefault();

    var kd_prodi  = $(this).find('select[name=kd_prodi]').val();
    // var indikator = $(this).find('select[name=indikator]').val();
    var indikator = "penilaian_mahasiswa";
    var button    = $(this).find('button[type=submit]');

    button.html('<i class="fa fa-circle-notch fa-spin"></i>');
    button.attr('disabled',true);

    penilaian_mhs_seleksi(kd_prodi);
    penilaian_mhs_asing(kd_prodi);

    setTimeout(function() {
        button.attr('disabled', false);
        button.text('Tampilkan');
        $('.hasil-penilaian').addClass('d-none');
        $('#'+indikator).removeClass('d-none');
    }, 1000);
})

$('select#skor_asing_a').change(function(){
    var kd_prodi  = $('#form_penilaian').find('select[name=kd_prodi]').val();
    penilaian_mhs_asing(kd_prodi);
});

function penilaian_mhs_seleksi(kd_prodi) {
    $.ajax({
        url: '/assessment/student/seleksi',
        type: 'POST',
        data: {kd_prodi:kd_prodi},
        dataType: 'json',
        success: function (data) {

            $('#penilaian_mhs_seleksi')
                .find('#mhs_calon').val(data.jumlah['mhs_calon']).end()
                .find('#mhs_baru').val(data.jumlah['mhs_baru']).end()
                .find('#rasio_calon').val(data.rasio['calon']).end()
                .find('#rasio_baru').val(data.rasio['baru']).end()
                .find('#skor_seleksi').val(data.skor);
        }
    });
}

function penilaian_mhs_asing(kd_prodi) {
    var skor_asing_a = $('#skor_asing_a').val();

    $.ajax({
        url: '/assessment/student/asing',
        type: 'POST',
        data: {
            kd_prodi:kd_prodi,
            skor_asing_a:skor_asing_a
        },
        dataType: 'json',
        success: function (data) {

            $('#penilaian_mhs_asing')
                .find('#mahasiswa_aktif').val(data.jumlah['mahasiswa']).end()
                .find('#mahasiswa_asing_full').val(data.jumlah['asing_full']).end()
                .find('#mahasiswa_asing_part').val(data.jumlah['asing_part']).end()
                .find('#persentase_asing').val(data.persentase['asing']).end()
                .find('#skor_asing_b').val(data.skor['b']).end()
                .find('#skor_asing').val(data.skor['total']).end()
                .find('span.persentase_asing_full').text(data.persentase['asing_full']).end()
                .find('span.persentase_asing_part').text(data.persentase['asing_part'])
                ;
        }
    });
}

});

</script>
@endpush
