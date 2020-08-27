<div id="modal-tridharma-print" class="modal fade effect-slide-in-right">
    <form method="POST" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Cetak Laporan Tridharma</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    @include('layouts.alert')
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">Jenis:<span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control" name="jenis" required>
                                <option value="">- Jenis Tridharma -</option>
                                <option value="penelitian">Penelitian</option>
                                <option value="pengabdian">Pengabdian</option>
                                <option value="publikasi">Publikasi</option>
                                {{-- <option value="luaran">Luaran</option> --}}
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">Periode:<span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="periode_awal" class="form-control" required>
                                        <option value="">- Periode Awal -</option>
                                        @foreach($periodeTahun as $pt)
                                        <option value="{{$pt->tahun_akademik}}">{{$pt->tahun_akademik}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select name="periode_akhir" class="form-control">
                                        <option value="">- Periode Akhir -</option>
                                        @foreach($periodeTahun as $pt)
                                        <option value="{{$pt->tahun_akademik}}">{{$pt->tahun_akademik}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">Tgl Disahkan: <span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="icon ion-calendar tx-16 lh-0 op-6"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control datepicker" name="disahkan" placeholder="YYYY-MM-DD" data-provide='datepicker' data-date-container='#modal-tridharma-print' value="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">Kelompok: <span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <div id="tampil_kelompok">
                                <label class="rdiobox rdiobox-inline mb-0 mg-r-20">
                                    <input name="tampil_kelompok" type="radio" value="tunggal" required>
                                    <span>Tunggal</span>
                                </label>
                                <label class="rdiobox rdiobox-inline mb-0 mg-r-20">
                                    <input name="tampil_kelompok" type="radio" value="kelompok" required>
                                    <span>Kelompok</span>
                                </label>
                                <label class="rdiobox rdiobox-inline mb-0 mg-r-20">
                                    <input name="tampil_kelompok" type="radio" value="semua" required>
                                    <span>Semua</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">Tipe: <span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <div id="tampil_tipe">
                                <label class="rdiobox rdiobox-inline mb-0 mg-r-20">
                                    <input name="tampil_tipe" type="radio" value="prodi" required>
                                    <span>Program Studi</span>
                                </label>
                                <label class="rdiobox rdiobox-inline mb-0 mg-r-20">
                                    <input name="tampil_tipe" type="radio" value="individu" required>
                                    <span>Individu</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="prodi_form" style="display:none;">
                        <div class="form-group row mg-t-20">
                            <label class="col-sm-3 form-control-label">Program Studi: <span class="tx-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="kd_prodi" disabled>
                                    @if(!Auth::user()->hasRole('kaprodi'))
                                    <option value="">- Semua -</option>
                                    @endif
                                    @foreach ($studyProgram as $sp)
                                        @if(Auth::user()->hasRole('kaprodi') && Auth::user()->kd_prodi == $sp->kd_prodi)
                                        <option value="{{$sp->kd_prodi}}" selected>{{$sp->nama}}</option>
                                        @else
                                        <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="individu_form" style="display:none;">
                        <div class="form-group row mg-t-20">
                            <label class="col-sm-3 form-control-label">NIDN: <span class="tx-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control select2-dosen" name="nidn" disabled>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">Anggota: <span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <div id="tampil_ketua_anggota">
                                <label class="ckbox ckbox-inline mg-r-20">
                                    <input type="checkbox" name="tampil_ketua" value="1">
                                    <span>Ketua</span>
                                </label>
                                <label class="ckbox ckbox-inline mg-r-20">
                                    <input type="checkbox" name="tampil_anggota" value="1">
                                    <span>Anggota</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-print" data-type="post" data-dest="{{route('ajax.report.tridharma.pdf')}}">
                        Cetak
                    </button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-cancel" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
@push('custom-js')
<script>
    select2_dosen($('.select2-dosen'));

    $('#modal-tridharma-print').on('change','input[name=tampil_tipe]',function(){
        var cont            = $('#modal-tridharma-print');
        var prodi_form      = cont.find('#prodi_form');
        var individu_form   = cont.find('#individu_form');

        if ($(this).is(':checked') && $(this).val() == 'prodi') {

            individu_form.hide();
            individu_form.find('select').val(null).trigger('change').prop('disabled',true).prop('required',false);
            individu_form.find('input').prop('disabled',true).prop('required',false);

            prodi_form.show();
            prodi_form.find('select').val(null).trigger('change').prop('disabled',false).prop('required',true);
            prodi_form.find('input').prop('disabled',false).prop('required',true);

            //Kode Prodi
            // prodi_form.find('select[name=kd_prodi]').val(null).trigger('change');
            // prodi_form.find('select[name=kd_prodi]').prop('disabled',false);
            // prodi_form.find('select[name=kd_prodi]').prop('required',true);

            // //Tampil Tipe
            // prodi_form.find('input[name=tampil_tipe]').val(null);
            // prodi_form.find('input[name=tampil_tipe]').prop('disabled',false);
            // prodi_form.find('input[name=tampil_tipe]').prop('required',true);
        } else {

            prodi_form.hide();
            prodi_form.find('select').val(null).trigger('change').prop('disabled',true).prop('required',false);
            prodi_form.find('input').prop('disabled',true).prop('required',false);

            individu_form.show();
            individu_form.find('select').val(null).trigger('change').prop('disabled',false).prop('required',true);
            individu_form.find('input').prop('disabled',false).prop('required',true);

            // //NIDN
            // individu_form.find('select[name=nidn]').prop('disabled',false);
            // individu_form.find('select[name=nidn]').prop('required',true);

            // //Tampil Anggota
            // prodi_form.find('input[name=anggota_exist]').val(null);
            // prodi_form.find('input[name=anggota_exist]').prop('disabled',false);
            // prodi_form.find('input[name=anggota_exist]').prop('required',true);
        }
    })

    $('.btn-print').on('click', function(e) {
        e.preventDefault();

        var modal   = $('#modal-tridharma-print');
        var form    = $('#modal-tridharma-print').find('form');
        var button  = $(this);
        var url     = $(this).data('dest');
        var type    = $(this).data('type');
        var teks    = $(this).text();

        $.ajax({
            url: url,
            data: form.serialize(),
            type: type,
            beforeSend: function() {
                button.prop('disabled',true);
                $('.btn-cancel').prop('disabled',true);
                button.html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (data) {
                var x=window.open(null,null,"width=1000,scrollbars=yes");
                x.document.open();
                x.document.write(data);
                x.document.close();

                button.prop('disabled',false);
                $('.btn-cancel').prop('disabled',false);
                button.html(teks);
                // console.log(data)

                $('.alert-danger').html('').hide();
                $('.is-invalid').removeClass('is-invalid');

            },
            error: function (request) {
                json = $.parseJSON(request.responseText);
                $('.alert-danger').html('');
                $('.is-invalid').removeClass('is-invalid');
                $.each(json.errors, function(key, value){
                    $('.alert-danger').show();
                    $('.alert-danger').append('<span>'+value+'</span><br>');
                    $('#'+key).addClass('is-invalid');
                    $('[name='+key+']').addClass('is-invalid');
                    $('[name='+key+']').parents('div.radio').addClass('is-invalid');
                    $('[aria-labelledby*=select2-'+key+']').addClass('is-invalid');
                });

                button.prop('disabled',false);
                $('.btn-cancel').prop('disabled',false);
                button.html(teks);
            },
        });
    });

    $('#modal-tridharma-print').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');

        var cont           = $(this);
        cont.find('#prodi_form').hide();
        cont.find('#individu_form').hide();
    })
</script>

@endpush
