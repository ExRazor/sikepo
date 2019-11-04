<div id="modal-student-status" class="modal fade effect-slide-in-right">
        <form method="POST" enctype="multipart/form-data">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content bd-0 tx-14 modal-form">
                    <div class="modal-header pd-y-20 pd-x-25">
                        <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold"><span class="title-action"></span> Status Akademik</h6>
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
                                <input type="hidden" name="_nim" value="{{encrypt($data->nim)}}">
                                <select class="form-control" name="id_ta">
                                    <option value="">= Pilih Tahun Akademik =</option>
                                    @foreach ($academicYear as $ay)
                                        @if($ay->tahun_akademik > $data->angkatan)
                                        <option value="{{$ay->id}}">{{$ay->tahun_akademik.' - '.$ay->semester}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mg-t-20 status_mahasiswa">
                            <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Status Mahasiswa:</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="status">
                                    <option value="">= Pilih Status =</option>
                                    @if($status->status != 'Aktif')<option value="Aktif">Aktif</option>@endif
                                    @if($status->status != 'Nonaktif')<option value="Nonaktif">Nonaktif</option>@endif
                                    @if($status->status != 'Lulus')<option value="Lulus">Lulus</option>@endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mg-t-20">
                            <label class="col-sm-3 form-control-label">IPK Terakhir :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="ipk_terakhir" placeholder="IPK terakhir yang terhitung" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-save" value="post" data-dest="{{route('student.status.store')}}">
                            Simpan
                        </button>
                        <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div><!-- modal-dialog -->
        </form>
    </div><!-- modal -->
