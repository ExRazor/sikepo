<div id="modal-teach-schedule" class="modal fade effect-slide-in-right">
        <form method="POST" enctype="multipart/form-data">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content bd-0 tx-14 modal-form">
                    <div class="modal-header pd-y-20 pd-x-25">
                        <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold"><span class="title-action"></span> Jadwal Kurikulum</h6>
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
                        <div class="form-group row">
                            <input type="hidden" name="id">
                            <input type="hidden" name="nidn" value="{{$data->nidn}}">
                            <label class="col-3 form-control-label">Tahun Akademik: <span class="tx-danger">*</span></label>
                            <div class="col-8">
                                <div id="academicYear" class="parsley-select">
                                    <select class="form-control select-academicYear" name="id_ta" data-parsley-class-handler="#academicYear" data-parsley-errors-container="#errorsAcademicYear" required>
                                        <option value="">- Pilih Tahun Akademik -</option>
                                    </select>
                                    <div id="errorsAcademicYear"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mg-t-20">
                            <label class="col-3 form-control-label">Mata Kuliah: <span class="tx-danger">*</span></label>
                            <div class="col-8">
                                <div class="row align-items-center">
                                    <div class="col-7">
                                        <div id="curriculum" class="parsley-select">
                                            <select class="form-control select-curriculum" name="kd_matkul" data-parsley-class-handler="#curriculum" data-parsley-errors-container="#errorsCurriculum" required>
                                                <option value="">- Pilih Mata Kuliah -</option>
                                            </select>
                                            <div id="errorsCurriculum"></div>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div id="sesuai_bidang" class="checkbox">
                                            <label class="ckbox ckbox-inline mb-0 mr-4">
                                                <input name="sesuai_bidang" type="checkbox" value="1" {{ (isset($data) && isset($data->sesuai_bidang)) || Request::old('sesuai_bidang')=='1' ? 'checked' : ''}}>
                                                <span class="pl-0">Sesuai Bidang Ilmu?</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-save" value="post" data-dest="{{route('academic.schedule.store')}}">
                            Simpan
                        </button>
                        <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div><!-- modal-dialog -->
        </form>
    </div><!-- modal -->
