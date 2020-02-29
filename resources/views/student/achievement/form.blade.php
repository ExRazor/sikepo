<div id="modal-student-acv" class="modal fade effect-slide-in-right">
    <form method="POST" enctype="multipart/form-data" data-parsley-validate>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold"><span class="title-action"></span> Prestasi Mahasiswa</h6>
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
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Program Studi:</label>
                        <div class="col-sm-8">
                            <select id="selectProdi" class="form-control" {{Auth::user()->role=='kaprodi' ? 'disabled' : 'required'}}>
                                <option value="">- Pilih Program Studi -</option>
                                @foreach ($studyProgram as $sp)
                                <option value="{{$sp->kd_prodi}}" {{Auth::user()->kd_prodi==$sp->kd_prodi ? 'selected' : ''}}>{{$sp->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Nama Mahasiswa:</label>
                        <div class="col-sm-8">
                            <select class="form-control select-mhs-prodi" name="nim" data-placeholder="Pilih Mahasiswa" {{Auth::user()->role=='kaprodi' ? '' : 'disabled'}}>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Nama Kegiatan:</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="_id">
                            <input type="text" class="form-control" name="kegiatan_nama" placeholder="Nama kegiatan yang diikuti" required>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Tingkat Kegiatan:</label>
                        <div class="col-sm-8">
                            <div id="prestasi_jenis" class="radio">
                                <label class="rdiobox rdiobox-inline mb-0">
                                    <input name="kegiatan_tingkat" type="radio" value="Wilayah" required>
                                    <span>Wilayah</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </label>
                                <label class="rdiobox rdiobox-inline mb-0">
                                    <input name="kegiatan_tingkat" type="radio" value="Nasional">
                                    <span>Nasional</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </label>
                                <label class="rdiobox rdiobox-inline mb-0">
                                    <input name="kegiatan_tingkat" type="radio" value="Internasional">
                                    <span>Internasional</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Tahun Diperoleh:</label>
                        <div class="col-sm-8">
                            <select class="form-control select-academicYear" name="id_ta" placeholder="Tuliskan tanggal prestasi dicapai" required>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Prestasi:</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="_id">
                            <input type="text" class="form-control" name="prestasi" placeholder="Prestasi yang diraih" required>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Jenis Prestasi:</label>
                        <div class="col-sm-8">
                            <div id="prestasi_jenis" class="radio">
                                <label class="rdiobox rdiobox-inline mb-0">
                                    <input name="prestasi_jenis" type="radio" value="Akademik" required>
                                    <span>Akademik</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </label>
                                <label class="rdiobox rdiobox-inline mb-0">
                                    <input name="prestasi_jenis" type="radio" value="Non Akademik">
                                    <span>Non Akademik</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-save" value="post" data-dest="{{route('student.achievement.store')}}">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
