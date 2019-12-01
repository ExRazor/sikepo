<div id="modal-alumnus-suitable" class="modal fade effect-slide-in-right">
    <form method="POST" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold"><span class="title-action"></span> Waktu Tunggu Lulusan</h6>
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
                            <input type="hidden" name="id">
                            <input type="hidden" name="kd_prodi" value="{{encrypt($studyProgram->kd_prodi)}}">
                            <input type="text" class="form-control" name="tahun_lulus" style="display:none;" disabled>
                            <select class="form-control tahun_alumni" name="tahun_lulus">
                                <option value="">= Pilih Tahun =</option>
                                @foreach($tahun as $t)
                                <option value="{{$t->tahun_akademik}}">{{$t->tahun_akademik}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Jumlah Lulusan:</label>
                        <div class="col-sm-8">
                            <div class="row align-items-center">
                                <div class="col-6 pr-1">
                                    <input type="number" class="form-control" name="jumlah_lulusan" placeholder="Isikan jumlah Lulusan" readonly>
                                </div>
                                <div class="col-3 pl-1 ">
                                    <div class="checkbox">
                                        <label class="ckbox ckbox-inline mb-0 mr-4">
                                            <input id="manual" type="checkbox">
                                            <span class="pl-0">Manual?</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Lulusan Terlacak:</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="lulusan_terlacak" placeholder="Isikan jumlah lulusan yang terlacak">
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Kesesuaian Rendah:</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="sesuai_rendah" placeholder="Isikan jumlah lulusan dengan kesesuaian bidang kerja rendah">
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Kesesuaian Sedang:</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="sesuai_sedang" placeholder="Isikan jumlah lulusan dengan kesesuaian bidang kerja sedang" required>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                            <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Kesesuaian Tinggi:</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" name="sesuai_tinggi" placeholder="Isikan jumlah lulusan dengan kesesuaian bidang kerja tinggi" required>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-save" value="post" data-dest="{{route('alumnus.suitable.store')}}">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
