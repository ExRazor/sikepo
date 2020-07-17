<div id="modal-teach-status" class="modal fade effect-slide-in-right">
    <form method="POST" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold"><span class="title-action"></span> Status Jabatan</h6>
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
                        <label class="col-sm-3 form-control-label">Jabatan:<span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="hidden" name="_id">
                            <input type="hidden" name="_nidn" value="{{$data->nidn}}">
                            <select class="form-control" name="jabatan">
                                <option value="">= Pilih Jabatan =</option>
                                <option value="Dosen">Dosen</option>
                                <option value="Kajur">Kajur</option>
                                <option value="Kaprodi">Kaprodi</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">Periode:<span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control" name="id_ta">
                                <option value="">= Pilih Periode =</option>
                                @foreach ($tahun as $t)
                                    <option value="{{$t->id}}">{{$t->tahun_akademik}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">Klaster:<span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control" name="kd_prodi">
                                <option value="">= Pilih Klaster =</option>
                                @foreach ($studyProgram as $sp)
                                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-save" value="post" data-dest="{{route('teacher.status.store')}}">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
