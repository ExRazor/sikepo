@extends('layouts.master')

@section('title', 'Penilaian Sumber Daya Manusia')

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
        <h4>Sumber Daya Manusia</h4>
        <p class="mg-b-0">Penilaian Kinerja Sumber Daya Manusia pada Program Studi</p>
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
        <form id="form_penilaian_sdm" method="POST" enctype="application/x-www-form-urlencoded">
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
                                        <option value="penilaian_dosen">Penilaian Dosen</option>
                                        <option value="kinerja_dosen">Kinerja Dosen</option>
                                        <option value="pkm_dosen">Penelitian & PkM Dosen</option>
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
                            <button type="submit" class="btn btn-info btn-submit">Tampilkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="penilaian_dosen" class="widget-2 hasil-penilaian d-none">
        @include('simulasi.sdm.penilaian_dosen')
    </div>
    <div id="kinerja_dosen" class="widget-2 hasil-penilaian d-none">
        @include('simulasi.sdm.kinerja_dosen')
    </div>
    <div id="pkm_dosen" class="widget-2 hasil-penilaian d-none">
        @include('simulasi.sdm.pkm_dosen')
    </div>
</div>
@include('simulasi.sdm.modal')
@endsection

@section('js')
<script src="{{asset('assets')}}/simulasi.js"></script>
@endsection

@section('custom-js')
<script type="text/javascript">

$(function(){

$('form#form_penilaian_sdm').on('submit', function(e){
    e.preventDefault();

    var indikator = $('select[name=indikator_penilaian]').val();
    var button    = $(this).find('button[type=submit]');

    button.html('<i class="fa fa-circle-notch fa-spin"></i>');
    button.attr('disabled',true);

    setTimeout(function() {
        button.attr('disabled', false);
        button.text('Tampilkan');
        $('.hasil-penilaian').addClass('d-none');
        $('#'+indikator).removeClass('d-none');
    }, 1000);

});

function simulasi_dosen() {
    hitung_dtps();
    persentase_dtps_s3();
    persentase_dtps_gblk();
    persentase_dtps_sp();
    persentase_dtps_ttp();
    persentase_dtps_ttp();
    rasio_mahasiswa_dtps();
}

function simulasi_kinerja()
{
    persentase_bimbingan();
    skor_ewmp();
    skor_prestasi();
}

function simulasi_pkm()
{
    publikasi_jurnal();
    publikasi_seminar();
    karya_ilmiah();
    luaran_pkm();
}

$('.form-isi').bind('keyup change', function(){
    simulasi_dosen();
    simulasi_kinerja();
    simulasi_pkm();
});

var skor;

/*************************************************************************/
/**************************** Penilaian Dosen ****************************/
/*************************************************************************/

function hitung_dtps()
{
    var cont = $("#simulasi-kecukupan-dosen");
    var dtps = cont.find("#dtps").val();

    if(dtps>=12) {
        skor = 4;
    } else if(dtps>=6 && dtps<12) {
        skor = dtps/3;
    } else {
        skor = 0;
    }

    cont.find("#skor_dtps").val(skor.toFixed(2));
}

function persentase_dtps_s3()
{
    var cont    = $("#simulasi-persentase-s3");
    var dtps    = cont.find("#dtps").val();
    var dtps_s3 = cont.find("#dtps_s3").val();
    var persentase = (dtps_s3/dtps)*100;

    if(persentase>=50) {
        skor = 4;
    } else if (persentase<50) {
        skor = 2 + ((4*persentase)/100);
    } else {
        skor = 0;
    }

    cont.find("#persentase_dtps_s3").val(persentase.toFixed(2)+"%");
    cont.find("#skor_dtps_s3").val(skor.toFixed(2));
}

function persentase_dtps_gblk()
{
    var cont      = $("#simulasi-persentase-gubes");
    var dtps      = cont.find("#dtps").val();
    var dtps_gblk = cont.find("#dtps_gblk").val();
    var persentase = (dtps_gblk/dtps)*100;

    if(persentase>=40) {
        skor = 4;
    } else if (persentase<40) {
        skor = 2 + ((4*persentase)/100);
    } else {
        skor = 0;
    }

    cont.find("#persentase_dtps_gblk").val(persentase.toFixed(2)+"%");
    cont.find("#skor_dtps_gblk").val(skor.toFixed(2));
}

function persentase_dtps_sp()
{
    var cont    = $("#simulasi-dtps-bersertifikat");
    var dtps    = cont.find("#dtps").val();
    var dtps_sp = cont.find("#dtps_sp").val();
    var persentase = (dtps_sp/dtps)*100;

    if(persentase>=80) {
        skor = 4;
    } else if (persentase<80) {
        skor = 1 + (((15*persentase)/100)/4);
    } else {
        skor = 0;
    }

    cont.find("#persentase_dtps_sp").val(persentase.toFixed(2)+"%");
    cont.find("#skor_dtps_sp").val(skor.toFixed(2));
}

function persentase_dtps_ttp()
{
    var cont        = $("#simulasi-persentase-dtt");
    var dtps        = cont.find("#dtps").val();
    var dtps_ttp 	= cont.find("#dtps_ttp").val();
    var desimal 	= (dtps_ttp/dtps);
    var persentase 	= desimal*100;

    if(persentase<=10) {
        skor = 4;
    } else if (persentase>10 && persentase<=40) {
        skor = (16 - (40*desimal))/3;
    } else if (persentase > 40) {
        skor = 0;
    }

    cont.find("#persentase_dtps_ttp").val(persentase.toFixed(2)+"%");
    cont.find("#skor_dtps_ttp").val(skor.toFixed(2));
}

function rasio_mahasiswa_dtps()
{
    var cont        = $("#simulasi-rasio-mahasiswa");
    var dtps        = cont.find("#dtps").val();
    var mahasiswa   = cont.find("#mahasiswa").val();

    var rasio_dosen 	= parseFloat((dtps/mahasiswa)*100).toFixed(0);
    var rasio_mahasiswa = 100-rasio_dosen;

    if(rasio_dosen>=15 && rasio_dosen <= 25) {
        skor=4;
    } else if(rasio_dosen<15) {
        skor = (4*rasio_dosen)/15
    } else if(rasio_dosen>25 && rasio_dosen<=35) {
        skor = (70-(2*rasio_dosen))/5;
    } else if (rasio_dosen>35) {
        skor=0;
    }

    cont.find("#rasio_mahasiswa").val(rasio_mahasiswa);
    cont.find("#rasio_dtps").val(rasio_dosen);
    cont.find("#skor_rasio_dtpm").val(skor.toFixed(2));

}

/*********************************************************************/
/*************************** Kinerja Dosen ***************************/
/*********************************************************************/

function persentase_bimbingan()
{
    var cont           = $("#simulasi-beban-bimbingan");
	var tot_pembimbing = cont.find("#total_pembimbing").val();
	var tot_bimbingan  = cont.find("#total_bimbingan").val();

	//Persentase Pembimbing <= 10 Mahasiswa dengan Total Pembimbing
    var desimal    = (tot_bimbingan/tot_pembimbing);
    var persentase = desimal*100;

	if(persentase>20) {
		skor = (5*desimal)-1
	} else if (persentase<=20) {
		skor = 0
	}

	cont.find("#persentase_bimbingan").val(persentase.toFixed(2)+'%');
	cont.find("#skor_bimbingan").val(skor.toFixed(2));
}

function skor_ewmp()
{
    var cont      = $("#simulasi-ewmp-dtps");
	var tot_dosen = cont.find("#total_dtps").val();
	var tot_sks   = cont.find("#total_sks").val();

	var rata_sks  = tot_sks/tot_dosen;

	if(rata_sks>=12 && rata_sks<=13) {
		skor = 4;
	} else if(rata_sks>=6 && rata_sks<12) {
		skor = ((4*rata_sks)-24)/5
	} else if(rata_sks>=13 && rata_sks<=18) {
		skor = (72-(4*rata_sks))/5
	} else if(rata_sks<6 || rata_sks>18) {
		skor = 0
	}

	cont.find("#total_sks").val(tot_sks);
	cont.find("#rata_sks").val(rata_sks);
	cont.find("#skor_ewmp").val(skor.toFixed(2));
}

function skor_prestasi()
{
    var cont                = $("#simulasi-prestasi-dtps");
	var dtps 				= parseInt(cont.find('#total_dtps').val());
	var dtps_prestasi 		= parseInt(cont.find('#dtps_prestasi').val());
	var dtps_prestasi_inter = parseInt(cont.find('#dtps_prestasi_inter').val());

	var rata_prestasi = (dtps_prestasi+dtps_prestasi_inter)/dtps;

	if(rata_prestasi>=0.5 || dtps_prestasi_inter>=1) {
		skor = 4;
	} else if(rata_prestasi<=0.5) {
		skor = 2+(4*rata_prestasi);
	} else {
		skor = 0;
	}

	cont.find('#rata_prestasi_dtps').val(rata_prestasi.toFixed(2));
	cont.find('#skor_prestasi_dtps').val(skor.toFixed(2));

}

/*****************************************************************/
/*************************** PkM Dosen ***************************/
/*****************************************************************/

function publikasi_jurnal()
{
    var cont    = $("#simulasi-publikasi-jurnal");
	var dt      = parseInt(cont.find("#dtps").val());
	var a1      = parseInt(cont.find("#jurnal_nonakre").val());
	var a2      = parseInt(cont.find("#jurnal_nasional").val());
	var a3      = parseInt(cont.find("#jurnal_inter").val());
	var a4      = parseInt(cont.find("#jurnal_inter_rep").val());

	var faktor_a = 0.1;
	var faktor_b = 1;
	var faktor_c = 2;
	var skor = 0;

	rl = a1/dt;
	rn = (a2+a3)/dt;
	ri = a4/dt;

	cont.find("span.rata_a1").text(rl.toFixed(2));
	cont.find("span.rata_a3").text(rn.toFixed(2));
	cont.find("span.rata_a4").text(ri.toFixed(2));

	if(ri >= faktor_a) {
		skor = 4;
		Rumus = "Skor = 4";
	} else if(ri < faktor_a && rn >= faktor_b) {
		skor = 3+(ri/faktor_a);
		rumus = "3 + (RI / faktor a)";
	} else if((ri > 0 && ri < faktor_a) || (rn > 0 && rn < faktor_b)) {
		skor = 2+(2*(ri/faktor_a)) + (rn/faktor_b) - ((ri*rn) / (faktor_a*faktor_b));
		rumus = "2 + (2 * (RI / a)) + (RN / b) 0 ((RI * RN) / (faktor a * faktor b))";
	} else if(ri==0 && rn==0 && rl>=faktor_c) {
		skor = 2;
		rumus = "Skor = 2";
	} else if(ri==0 && rn==0 && rl<faktor_c) {
		skor = (2*rl)/faktor_c;
		rumus = "Skor = (2*RL)/faktor c";
	} else {
        skor = 0;
        rumus = 0;
    }

	cont.find("#skor_publikasi_jurnal").val(skor.toFixed(2));
	cont.find("span.rumus_jurnal").text(rumus);
}

function publikasi_seminar()
{
    var cont    = $("#simulasi-publikasi-seminar");
	var dt      = parseInt(cont.find("#dtps").val());
	var b1      = parseInt(cont.find("#publikasi_lokal").val());
	var b2      = parseInt(cont.find("#publikasi_nasional").val());
	var b3      = parseInt(cont.find("#publikasi_inter").val());

	var faktor_a = 0.1;
	var faktor_b = 1;
	var faktor_c = 2;
	var skor = 0;

	rl = b1/dt;
	rn = b2/dt;
	ri = b3/dt;

	cont.find("span.rata_b1").text(rl.toFixed(2));
	cont.find("span.rata_b2").text(rn.toFixed(2));
	cont.find("span.rata_b3").text(ri.toFixed(2));

	if(ri >= faktor_a) {
		skor = 4;
		Rumus = "4";
	} else if(ri < faktor_a && rn >= faktor_b) {
		skor = 3+(ri/faktor_a);
		rumus = "3 + (RI / faktor a)";
	} else if((ri > 0 && ri < faktor_a) || (rn > 0 && rn < faktor_b)) {
		skor = 2+(2*(ri/faktor_a)) + (rn/faktor_b) - ((ri*rn) / (faktor_a*faktor_b));
		rumus = "2 + (2 * (RI / a)) + (RN / b) 0 ((RI * RN) / (faktor a * faktor b))";
	} else if(ri==0 && rn==0 && rl>=faktor_c) {
		skor = 2;
		rumus = "Skor = 2";
	} else if(ri==0 && rn==0 && rl<faktor_c) {
		skor = (2*rl)/faktor_c;
		rumus = "Skor = (2*RL)/faktor c";
	}

	cont.find("#skor_publikasi_seminar").val(skor.toFixed(2));
	cont.find("span.rumus_seminar").text(rumus);
}

function karya_ilmiah()
{
    var cont    = $("#simulasi-karya-sitasi");
	var dt      = parseInt(cont.find("#dtps").val());
	var as      = parseInt(cont.find("#karya_ilmiah").val());

	rs = as/dt;

	cont.find("span.rata_rs").text(rs.toFixed(2));

	if(rs>=0.5) {
		skor = 4;
		rumus = "4";
	} else if(rs<0.5) {
		skor = 2+(4*rs);
		rumus = "2 + (4 * RS)";
	} else {
		skor = 0;
		rumus = "Tidak ada Skor kurang dari 2";
	}

	cont.find("#skor_karya_ilmiah").val(skor.toFixed(2));
	cont.find("span.rumus_karya_ilmiah").text(rumus);
}

function luaran_pkm()
{
    var cont    = $("#simulasi-luaran-pkm");
	var dt      = parseInt(cont.find("#dtps").val());
	var na      = parseInt(cont.find("#pkm_paten").val());
	var nb      = parseInt(cont.find("#pkm_cipta").val());
	var nc      = parseInt(cont.find("#pkm_produk").val());
	var nd      = parseInt(cont.find("#pkm_buku").val());

	rlp   = ((4 * na) + (2 * (nb + nc)) + nd) / dt;
	rumus = "(4 * NA + 2 * (NB + NC) + ND) / NDT";

	cont.find("#skor_pkm").val(rlp.toFixed(2));
	cont.find("span.rumus_pkm").text(rumus);

}


});


</script>
@endsection
