<div id="modal-publication-author" class="modal fade effect-slide-in-right">
    <form method="POST" enctype="multipart/form-data" data-parsley-validate>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold"><span class="title-action"></span> Penulis Publikasi</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    @include('layouts.alert')
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Status Penulis:</label>
                        <div class="col-sm-8">
                            <div class="row radio">
                                <input type="hidden" name="_id" value={{$data->id}}>
                                <div class="col-lg-4 mg-t-5">
                                    <label class="rdiobox">
                                        <input name="status_penulis" type="radio" value="Dosen" required>
                                        <span>Dosen</span>
                                    </label>
                                </div>
                                <div class="col-lg-4 mg-t-5">
                                    <label class="rdiobox">
                                        <input name="status_penulis" type="radio" value="Mahasiswa" required>
                                        <span>Mahasiswa</span>
                                    </label>
                                </div>
                                <div class="col-lg-4 mg-t-5">
                                    <label class="rdiobox">
                                        <input name="status_penulis" type="radio" value="Lainnya" required>
                                        <span>Luar Jurusan</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20 tipe-dosen" style="display:none;">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> NIDN:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="penulis_nidn">
                        </div>
                    </div>
                    <div class="form-group row mg-t-20 tipe-mahasiswa" style="display:none;">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> NIM:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="penulis_nim">
                        </div>
                    </div>
                    <div class="form-group row mg-t-20 tipe-lainnya" style="display:none;">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Nama Penulis:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="penulis_nama" placeholder="Tuliskan nama Penulis">
                        </div>
                    </div>
                    <div class="form-group row mg-t-20 tipe-lainnya" style="display:none;">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Asal Penulis:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="penulis_asal" placeholder="Tuliskan asal penulis">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-save" value="post" data-dest="{{route('publication.member.store')}}">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
@push('custom-js')
<script>
    $('#modal-publication-author').on('change', 'input[name=status_penulis]', function() {
        var val = $(this).val();
        form_penulis(val);
    });

    function form_penulis(val) {

        var cont             = $('#modal-publication-author');
        var dosen_form       = cont.find('.tipe-dosen');
        var mahasiswa_form   = cont.find('.tipe-mahasiswa');
        var lainnya_form     = cont.find('.tipe-lainnya');

        switch(val) {
            case 'Dosen':
                dosen_form.show();
                dosen_form.find('input').prop('disabled',false).prop('required',true);

                mahasiswa_form.hide();
                mahasiswa_form.find('input').prop('disabled',true).prop('required',false);
                lainnya_form.hide();
                lainnya_form.find('input').prop('disabled',true).prop('required',false);
            break;
            case 'Mahasiswa':
                mahasiswa_form.show();
                mahasiswa_form.find('input').prop('disabled',false).prop('required',true);

                dosen_form.hide();
                dosen_form.find('input').prop('disabled',true).prop('required',false);
                lainnya_form.hide();
                lainnya_form.find('input').prop('disabled',true).prop('required',false);
            break;
            case 'Lainnya':
                lainnya_form.show();
                lainnya_form.find('input').prop('disabled',false).prop('required',true);

                dosen_form.hide();
                dosen_form.find('input').prop('disabled',true).prop('required',false);
                mahasiswa_form.hide();
                mahasiswa_form.find('input').prop('disabled',true).prop('required',false);
            break;
            default:
                dosen_form.hide();
                dosen_form.find('input').prop('disabled',true).prop('required',false);
                mahasiswa_form.hide();
                mahasiswa_form.find('input').prop('disabled',true).prop('required',false);
                lainnya_form.hide();
                lainnya_form.find('input').prop('disabled',true).prop('required',false);
            break;
        }
    }

    $('#modal-publication-author').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');

        var cont           = $(this);
        cont.find('.tipe-lainnya').hide();
        cont.find('.tipe-non-lainnya').hide();
    })
</script>
@endpush
