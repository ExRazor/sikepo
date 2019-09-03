<div id="academicYear-form" class="modal fade effect-slide-in-right">
    <form id="form-academicYear" action="" method="POST" enctype="multipart/form-data">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold title-ay">Tambah Tahun Akademik</h6>
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
                        <input type="hidden" name="id">
                        <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span> Tahun Akademik:</label>
                        <div class="input-group col-sm-4">
                            <input type="text" class="form-control" id="tahunAkademik" name="tahun_akademik" placeholder="{{ now()->year }}" required>
                            <div class="input-group-append">
                                <span class="input-group-text ta_next">/ {{ (now()->year)+1 }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span> Semester:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="semester">
                                <option value="">= Pilih Semester =</option>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" id="btn-save-ay" value="post">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->