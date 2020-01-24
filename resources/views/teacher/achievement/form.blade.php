<div id="modal-teach-acv" class="modal fade effect-slide-in-right">
    <form method="POST" enctype="multipart/form-data" data-parsley-validate>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold"><span class="title-action"></span> Prestasi/Pengakuan Dosen</h6>
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
                            <select id="selectProdi" class="form-control" required>
                                <option value="">- Pilih Program Studi -</option>
                                @foreach ($studyProgram as $sp)
                                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Nama Dosen:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="nidn" data-placeholder="Pilih Dosen" disabled>
                                <option value="">- Pilih Dosen -</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Prestasi/Pengakuan:</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="_id">
                            <input type="text" class="form-control" name="prestasi" placeholder="Tuliskan prestasi yang diraih" required>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Tingkat Prestasi:</label>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-lg-4 mg-t-15">
                                    <label class="rdiobox">
                                        <input name="tingkat_prestasi" type="radio" value="Wilayah" required>
                                        <span>Wilayah</span>
                                    </label>
                                </div>
                                <div class="col-lg-4 mg-t-15">
                                    <label class="rdiobox">
                                        <input name="tingkat_prestasi" type="radio" value="Nasional" required>
                                        <span>Nasional</span>
                                    </label>
                                </div>
                                <div class="col-lg-4 mg-t-15">
                                    <label class="rdiobox">
                                        <input name="tingkat_prestasi" type="radio" value="Internasional" required>
                                        <span>Internasional</span>
                                    </label>
                                </div>
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
                        <label class="col-sm-3 form-control-label align-items-start pd-t-12"><span class="tx-danger">*</span> Bukti Prestasi:</label>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="bukti_nama" placeholder="Tuliskan jenis bukti prestasi" required>
                                </div>
                                <div class="col-sm-6">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="bukti_file" id="bukti_file" {{ isset($data) ? '' : 'required'}}>
                                        <label class="custom-file-label custom-file-label-primary" for="bukti_file">Pilih fail</label>
                                    </div>
                                    <small class="w-100">
                                        Berkas harap dikemas dalam 1 PDF.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-save" value="post" data-dest="{{route('teacher.achievement.store')}}">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
