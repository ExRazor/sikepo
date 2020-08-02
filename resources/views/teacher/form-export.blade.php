<div id="modal-export-teacher" class="modal fade effect-slide-in-right">
    <form action="{{route('teacher.list.export')}}" method="GET" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Ekspor Data Dosen</h6>
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
                        <label class="col-sm-3 form-control-label">Program Studi: <span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control" name="kd_prodi">
                                <option value="">- Semua -</option>
                                @foreach ($studyProgram as $sp)
                                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">
                        Ekspor
                    </button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
@push('custom-js')
<script>
    $('.btn-export').on('click',function(e) {
        e.preventDefault();

        var cont    = $(this);
        var modal   = cont.closest('.modal');
        var url     = cont.data('dest');
        var data    = new FormData(cont.closest('form')[0]);

        $.ajax({
            url: url,
            data: data,
            method: 'get',
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function() {
                cont.prop('disabled',true);
                $('.btn-cancel').prop('disabled',true);
                cont.html('<i class="fa fa-spinner fa-spin"></i>');
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

                cont.prop('disabled',false);
                $('.btn-cancel').prop('disabled',false);
                cont.html('Simpan');
            },
        });

    });
</script>

@endpush
