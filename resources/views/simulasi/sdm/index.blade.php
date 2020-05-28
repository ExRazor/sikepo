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
        <form id="form_penilaian" method="POST" enctype="application/x-www-form-urlencoded">
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
                                            {{-- <option value="0">Semua</option> --}}
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

    $('form#form_penilaian').on('submit', function(e){
        e.preventDefault();

        var kd_prodi  = $(this).find('select[name=kd_prodi]').val();
        var indikator = $('select[name=indikator_penilaian]').val();
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        switch(indikator) {
            case 'penilaian_dosen':
                penilaian_kecukupan_dosen(kd_prodi);
                penilaian_persentase_dtps_s3(kd_prodi);
                penilaian_persentase_dtps_jabatan(kd_prodi);
                penilaian_persentase_dtps_sertifikat(kd_prodi);
                penilaian_persentase_dtps_dtt(kd_prodi);
                penilaian_rasio_mahasiswa_dtps(kd_prodi);
                break;
            case 'kinerja_dosen':
                penilaian_beban_bimbingan(kd_prodi);
                penilaian_waktu_mengajar(kd_prodi);
                penilaian_prestasi_dtps(kd_prodi);
                break;
            case 'pkm_dosen':
                penilaian_publikasi_jurnal(kd_prodi);
                penilaian_publikasi_seminar(kd_prodi);
                penilaian_publikasi_tersitasi(kd_prodi);
                penilaian_luaran_pkm(kd_prodi);
                break;
        }

        setTimeout(function() {
            button.attr('disabled', false);
            button.text('Tampilkan');
            $('.hasil-penilaian').addClass('d-none');
            $('#'+indikator).removeClass('d-none');
        }, 1000);

    });

    function penilaian_kecukupan_dosen(kd_prodi) {
        $.ajax({
            url: '/assessment/resource/kecukupan_dosen',
            type: 'POST',
            data: {
                kd_prodi:kd_prodi,
            },
            dataType: 'json',
            success: function (data) {

                $('#kecukupan_dosen')
                    .find('#dtps').val(data.jumlah['dtps']).end()
                    .find('#skor').val(data.skor.toFixed(2));
            }
        });
    }

    function penilaian_persentase_dtps_s3(kd_prodi) {
        $.ajax({
            url: '/assessment/resource/persentase_dtps_s3',
            type: 'POST',
            data: {
                kd_prodi:kd_prodi,
            },
            dataType: 'json',
            success: function (data) {
                $('#persentase_dtps_s3')
                    .find('#dtps').val(data.jumlah['dtps']).end()
                    .find('#dtps_s3').val(data.jumlah['dtps_s3']).end()
                    .find('#persentase').val(data.persentase.toFixed(2)+"%").end()
                    .find('#skor').val(data.skor.toFixed(2));
            }
        });
    }

    function penilaian_persentase_dtps_jabatan(kd_prodi) {
        $.ajax({
            url: '/assessment/resource/persentase_dtps_jabatan',
            type: 'POST',
            data: {
                kd_prodi:kd_prodi,
            },
            dataType: 'json',
            success: function (data) {
                $('#persentase_dtps_jabatan')
                    .find('#dtps').val(data.jumlah['dtps']).end()
                    .find('#dtps_gubes_lk').val(data.jumlah['dtps_gubes_lk']).end()
                    .find('#persentase').val(data.persentase.toFixed(2)+"%").end()
                    .find('#skor').val(data.skor.toFixed(2));
            }
        });
    }

    function penilaian_persentase_dtps_sertifikat(kd_prodi) {
        $.ajax({
            url: '/assessment/resource/persentase_dtps_sertifikat',
            type: 'POST',
            data: {
                kd_prodi:kd_prodi,
            },
            dataType: 'json',
            success: function (data) {
                $('#persentase_dtps_sertifikat')
                    .find('#dtps').val(data.jumlah['dtps']).end()
                    .find('#dtps_sertifikat').val(data.jumlah['dtps_sertifikat']).end()
                    .find('#persentase').val(data.persentase.toFixed(2)+"%").end()
                    .find('#skor').val(data.skor.toFixed(2));
            }
        });
    }

    function penilaian_persentase_dtps_dtt(kd_prodi) {
        $.ajax({
            url: '/assessment/resource/persentase_dtps_dtt',
            type: 'POST',
            data: {
                kd_prodi:kd_prodi,
            },
            dataType: 'json',
            success: function (data) {
                $('#persentase_dtps_dtt')
                    .find('#dosen').val(data.jumlah['dosen']).end()
                    .find('#dtps').val(data.jumlah['dtps']).end()
                    .find('#dtt').val(data.jumlah['dtt']).end()
                    .find('span.persentase_dtps').text(data.persentase['dtps'].toFixed(2)+"%").end()
                    .find('span.persentase_dtt').text(data.persentase['dtt'].toFixed(2)+"%").end()
                    .find('#skor').val(data.skor.toFixed(2));
            }
        });
    }

    function penilaian_rasio_mahasiswa_dtps(kd_prodi) {
        $.ajax({
            url: '/assessment/resource/rasio_mahasiswa_dtps',
            type: 'POST',
            data: {
                kd_prodi:kd_prodi,
            },
            dataType: 'json',
            success: function (data) {
                $('#rasio_mahasiswa_dtps')
                    .find('#dtps').val(data.jumlah['dtps']).end()
                    .find('#mahasiswa').val(data.jumlah['mahasiswa']).end()
                    .find('#rasio_dtps').val(data.rasio['dtps'].toFixed(0)).end()
                    .find('#rasio_mahasiswa').val(data.rasio['mahasiswa'].toFixed(0)).end()
                    .find('#skor').val(data.skor.toFixed(2));
            }
        });
    }

    function penilaian_kecukupan_dosenss(kd_prodi) {
        $.ajax({
            url: '/resource/kecukupan_dosen',
            type: 'POST',
            data: {
                kd_prodi:kd_prodi,
            },
            dataType: 'json',
            success: function (data) {

                $('#penelitian_dtps')
                    .find('#ni').val(data.jumlah['dtps']).end()
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


    function penilaian_beban_bimbingan(kd_prodi) {
        $.ajax({
            url: '/assessment/resource/beban_bimbingan',
            type: 'POST',
            data: {
                kd_prodi:kd_prodi,
            },
            dataType: 'json',
            success: function (data) {
                $('#beban_bimbingan')
                    .find('#pembimbing_utama').val(data.jumlah['pembimbing_utama']).end()
                    .find('#pembimbing_10').val(data.jumlah['pembimbing_10']).end()
                    .find('#persentase').val(data.persentase.toFixed(2)+"%").end()
                    .find('#skor').val(data.skor.toFixed(2));
            }
        });
    }

    function penilaian_waktu_mengajar(kd_prodi) {
        $.ajax({
            url: '/assessment/resource/waktu_mengajar',
            type: 'POST',
            data: {
                kd_prodi:kd_prodi,
            },
            dataType: 'json',
            success: function (data) {
                $('#waktu_mengajar')
                    .find('#dtps').val(data.jumlah['dtps']).end()
                    .find('#total_rata_sks').val(data.jumlah['rata_sks']).end()
                    .find('#rata_sks').val(data.rata_sks.toFixed(2)).end()
                    .find('#skor').val(data.skor.toFixed(2));
            }
        });
    }

    function penilaian_prestasi_dtps(kd_prodi) {
        $.ajax({
            url: '/assessment/resource/prestasi_dtps',
            type: 'POST',
            data: {
                kd_prodi:kd_prodi,
            },
            dataType: 'json',
            success: function (data) {
                $('#prestasi_dtps')
                    .find('#dtps').val(data.jumlah['dtps']).end()
                    .find('#dtps_berprestasi').val(data.jumlah['dtps_berprestasi']).end()
                    .find('#dtps_prestasi_inter').val(data.jumlah['dtps_prestasi_inter']).end()
                    .find('#rata').val(data.rata.toFixed(2)).end()
                    .find('#skor').val(data.skor.toFixed(2));
            }
        });
    }

    function penilaian_publikasi_jurnal(kd_prodi) {
        $.ajax({
            url: '/assessment/resource/publikasi_jurnal',
            type: 'POST',
            data: {
                kd_prodi:kd_prodi,
            },
            dataType: 'json',
            success: function (data) {
                $('#publikasi_jurnal')
                    .find('#dtps').val(data.jumlah['dtps']).end()
                    .find('#na1').val(data.jumlah['na1']).end()
                    .find('#na2').val(data.jumlah['na2']).end()
                    .find('#na3').val(data.jumlah['na3']).end()
                    .find('#na4').val(data.jumlah['na4']).end()
                    .find('span.faktor_a').text(data.faktor['a']).end()
                    .find('span.faktor_b').text(data.faktor['b']).end()
                    .find('span.faktor_c').text(data.faktor['c']).end()
                    .find('span.rata_rl').text(data.rata['rl'].toFixed(2)).end()
                    .find('span.rata_rn').text(data.rata['rn'].toFixed(2)).end()
                    .find('span.rata_ri').text(data.rata['ri'].toFixed(2)).end()
                    .find('span.rumus').text(data.rumus).end()
                    .find('#skor').val(data.skor.toFixed(2));
            }
        });
    }

    function penilaian_publikasi_seminar(kd_prodi) {
        $.ajax({
            url: '/assessment/resource/publikasi_seminar',
            type: 'POST',
            data: {
                kd_prodi:kd_prodi,
            },
            dataType: 'json',
            success: function (data) {
                $('#publikasi_seminar')
                    .find('#dtps').val(data.jumlah['dtps']).end()
                    .find('#nb1').val(data.jumlah['nb1']).end()
                    .find('#nb2').val(data.jumlah['nb2']).end()
                    .find('#nb3').val(data.jumlah['nb3']).end()
                    .find('#nc1').val(data.jumlah['nc1']).end()
                    .find('#nc2').val(data.jumlah['nc2']).end()
                    .find('span.faktor_a').text(data.faktor['a']).end()
                    .find('span.faktor_b').text(data.faktor['b']).end()
                    .find('span.faktor_c').text(data.faktor['c']).end()
                    .find('span.rata_rl').text(data.rata['rl'].toFixed(2)).end()
                    .find('span.rata_rn').text(data.rata['rn'].toFixed(2)).end()
                    .find('span.rata_ri').text(data.rata['ri'].toFixed(2)).end()
                    .find('span.rumus').text(data.rumus).end()
                    .find('#skor').val(data.skor.toFixed(2));
            }
        });
    }

    function penilaian_publikasi_tersitasi(kd_prodi) {
        $.ajax({
            url: '/assessment/resource/publikasi_tersitasi',
            type: 'POST',
            data: {
                kd_prodi:kd_prodi,
            },
            dataType: 'json',
            success: function (data) {
                $('#publikasi_tersitasi')
                    .find('#dtps').val(data.jumlah['dtps']).end()
                    .find('#nas').val(data.jumlah['nas']).end()
                    .find('span.rata_rs').text(data.rata['rs'].toFixed(2)).end()
                    .find('span.rumus').text(data.rumus).end()
                    .find('#skor').val(data.skor.toFixed(2));
            }
        });
    }

    function penilaian_luaran_pkm(kd_prodi) {
        $.ajax({
            url: '/assessment/resource/luaran_pkm',
            type: 'POST',
            data: {
                kd_prodi:kd_prodi,
            },
            dataType: 'json',
            success: function (data) {
                $('#luaran_pkm')
                    .find('#dtps').val(data.jumlah['dtps']).end()
                    .find('#na').val(data.jumlah['na']).end()
                    .find('#nb').val(data.jumlah['nb']).end()
                    .find('#nc').val(data.jumlah['nc']).end()
                    .find('#nd').val(data.jumlah['nd']).end()
                    .find('#rlp').val(data.rata.toFixed(2)).end()
                    .find('span.rumus_rlp').text(data.rumus['rlp']).end()
                    .find('span.rumus').text(data.rumus['skor']).end()
                    .find('#skor').val(data.skor.toFixed(2));
            }
        });
    }
});

</script>
@endsection
