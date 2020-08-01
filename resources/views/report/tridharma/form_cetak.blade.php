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
                    <div class="alert alert-danger" style="display:none">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">Jenis:<span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control" name="jenis" required>
                                <option value="">- Jenis Tridharma -</option>
                                <option value="Penelitian">Penelitian</option>
                                <option value="Pengabdian">Pengabdian</option>
                                <option value="Publikasi">Publikasi</option>
                                <option value="Luaran">Luaran</option>
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
                        <label class="col-sm-3 form-control-label">Program Studi:<span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control" name="kd_prodi" required>
                                <option value="">- Program Studi -</option>
                                @foreach ($studyProgram as $sp)
                                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-print" data-type="post" data-dest="{{route('ajax.report.tridharma.pdf')}}">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-cancel" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
@push('custom-js')
<script>
    $('.btn-print').on('click', function(e) {
        e.preventDefault();

        var button  = $(this);
        var url     = $(this).data('dest');
        var type    = $(this).data('type');
        var teks    = $(this).text();

        $.ajax({
            url: url,
            type: type,
            dataType: 'json',
            beforeSend: function() {
                button.prop('disabled',true);
                $('.btn-cancel').prop('disabled',true);
                button.html('<i class="fa fa-spinner fa-spin"></i>');
                $('body iframe').remove();
            },
            success: function (data) {
                // $('#iframe_cetak').append('<iframe id="printf" style="display:none;" name="printf"></iframe>');
                // $('#printf').html(data);
                // window.frames["printf"].focus();
                // window.frames["printf"].print();

                $("#iframe_cetak").load(data);
                document.getElementById('iframe_cetak').focus();
                document.getElementById('iframe_cetak').print();
                // console.log(data)
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
</script>

@endpush
