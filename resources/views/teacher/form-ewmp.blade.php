<div id="modal-teach-ewmp" class="modal fade effect-slide-in-right">
    <form method="POST" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold"><span class="title-action"></span> Waktu Mengajar</h6>
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
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Tahun Akademik:</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="_id">
                            <input type="hidden" name="nidn" value="{{encrypt($data->nidn)}}">
                            <select class="form-control" name="id_ta">
                                <option value="">= Pilih Tahun Akademik =</option>
                                @foreach ($academicYear as $ay)
                                <option value="{{$ay->id}}">{{$ay->tahun_akademik.' - '.$ay->semester}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> SKS Mengajar:</label>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-4 pr-1">
                                    <input type="text" class="form-control" name="ps_intra" placeholder="Dalam PS" required>
                                </div>
                                <div class="col-4 px-1">
                                    <input type="text" class="form-control" name="ps_lain" placeholder="PS Lain dalam PT" required>
                                </div>
                                <div class="col-4 pl-1">
                                    <input type="text" class="form-control" name="ps_luar" placeholder="PS Lain luar PT"required>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> SKS Penelitian:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="penelitian" placeholder="Jumlah SKS pada Bidang Penelitian" required>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> SKS Pengabdian:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="pkm" placeholder="Jumlah SKS pada Bidang Pengabdian/PKM" required>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> SKS Tugas Tambahan:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="tugas_tambahan" placeholder="Jumlah SKS Tugas Tambahan/Penunjang" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-save" value="post" data-dest="{{route('ewmp.store')}}">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
