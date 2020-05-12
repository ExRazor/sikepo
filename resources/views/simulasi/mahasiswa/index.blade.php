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
    <div class="card shadow-base mb-3 d-none">
        <form id="form_penilaian_mahasiswa" method="POST" enctype="application/x-www-form-urlencoded">
            <div class="card-body bd-color-gray-lighter">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mahasiswa_baru">Indikator Penilaian</label>
                                    <select class="form-control" name="indikator_penilaian" data-placeholder="Pilih Prodi">
                                        <option value="">- Pilih Indikator -</option>
                                        <option value="seleksi">Seleksi Mahasiswa</option>
                                        <option value="asing">Mahasiswa Asing</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bd-color-gray-lighter">
                <div class="row">
                    <div class="col-6 mx-auto">
                        <div class="text-center">
                            <button type="submit" class="btn btn-info btn-submit">Hitung</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="seleksi_mahasiswa" class="widget-2">
        <div class="card shadow-base mb-3">
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
                                        <input type="text" class="form-control" name="mhs_calon" value="0" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mahasiswa_baru">Jumlah Mahasiswa Baru</label>
                                        <input type="text" class="form-control" name="mhs_baru" value="0" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Rasio Perbandingan</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="rasio_calon" value="0" disabled>
                                                <small class="form-text text-muted">Rasio Calon Mahasiswa</small>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="rasio_baru" value="0" disabled>
                                                <small class="form-text text-muted">Rasio Mahasiswa Baru</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bobot Skor</label>
                                        <input type="text" class="form-control" name="skor_seleksi" value="0" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div id="mahasiswa_asing" class="widget-2">
        <div class="card shadow-base mb-3">
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
                <form class="form-perhitungan">
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
                                        <input type="text" class="form-control form-isi" name="mahasiswa_aktif" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Mahasiswa Asing Full-time</label>
                                        <input type="text" class="form-control form-isi" name="mahasiswa_asing_full" disabled>
                                        <small class="form-text text-muted">Persentase = <span class="persentase_asing_full">0</span>%</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Mahasiswa Asing Part-time</label>
                                        <input type="text" class="form-control form-isi" name="mahasiswa_asing_part" disabled>
                                        <small class="form-text text-muted">Persentase = <span class="persentase_asing_part">0</span>%</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Persentase Mhs Asing</label>
                                        <input type="text" class="form-control" name="persentase_asing" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Skor A (Upaya)</label>
                                        <select class="form-control form-isi" name="skor_asing_a">
                                            @for($i=1;$i <= 4;$i++)
                                            <option>{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Skor B</label>
                                        <input type="text" class="form-control" name="skor_asing_b" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Bobot Skor</label>
                                        <input type="text" class="form-control" name="skor_asing" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('simulasi.mahasiswa.modal')
@endsection

@section('js')
<script src="{{asset('assets')}}/simulasi.js"></script>
@endsection

@section('custom-js')
<script>

$(function(){

    $('#simulasi-seleksi-mahasiswa .form-isi').bind('keyup change', function(){
        hitung_seleksi();
    })

    $('#simulasi-mahasiswa-asing .form-isi').bind('keyup change', function(){
		hitung_asing();
    })

    function hitung_seleksi(){
        var calon = $("#mhs_calon").val();
        var baru  = $("#mhs_baru").val();

        var rasio_baru  = parseFloat((baru/calon)*10).toFixed(0);
        var rasio_calon = 10-rasio_baru;

        if(rasio_calon>=5) {
            skor = 4;
        } else if(rasio_calon<5) {
            skor = (4*rasio_calon)/5;
        }

        $("#rasio_calon").val(rasio_calon);
        $("#rasio_baru").val(rasio_baru);
        $("#skor_seleksi").val(skor);
    }

	function hitung_asing(){
		var mahasiswa   = $('#mahasiswa_aktif').val();
		var asing_full  = $('#mahasiswa_asing_full').val();
		var asing_part  = $('#mahasiswa_asing_part').val();
		var skor_a      = $('#skor_asing_a').val();
		var skor;

		var persentase_asing_full = (asing_full/mahasiswa)*100;
		var persentase_asing_part = (asing_part/mahasiswa)*100;
		var persentase_asing 	  = persentase_asing_full+persentase_asing_part;

		if(persentase_asing>=1) {
			skor_b = 4;
		} else if (persentase_asing<1) {
			skor_b = 2+((200*persentase_asing)/100);
		} else {
			skor_b = 0;
        }

        skor = ((2*skor_a) + skor_b) / 3;

		$('span.persentase_asing_full').text(persentase_asing_full.toFixed(2));
		$('span.persentase_asing_part').text(persentase_asing_part.toFixed(2));
		$('#persentase_asing').val(persentase_asing.toFixed(2)+"%");
		$('#skor_asing_b').val(skor_b.toFixed(2));
		$('#skor_asing').val(skor.toFixed(2));

	}

});

</script>
@endsection
