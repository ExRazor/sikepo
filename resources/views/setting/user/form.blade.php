<div id="modal-setting-user" class="modal fade effect-slide-in-right">
    <form method="POST" enctype="multipart/form-data" data-parsley-validate>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold"><span class="title-action"></span> User</h6>
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
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Nama Lengkap:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="name" placeholder="Masukkan nama lengkap" required>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Username:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="username" placeholder="Masukkan username" required>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Password:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="password" placeholder="Masukkan password" required readonly>
                        </div>
                        <div class="col-sm-2">
                            <button id="generatePassword" class="btn btn-primary btn-block" required>
                                Generate
                            </button>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Hak Akses:</label>
                        <div class="col-sm-8">
                            <div id="role">
                                <label class="rdiobox rdiobox-inline mb-0 mg-r-20">
                                    <input name="role" type="radio" value="Admin" required>
                                    <span>Admin</span>
                                </label>
                                <label class="rdiobox rdiobox-inline mb-0 mg-r-20">
                                    <input name="role" type="radio" value="Kajur" required>
                                    <span>Kajur</span>
                                </label>
                                <label class="rdiobox rdiobox-inline mb-0 mg-r-20">
                                    <input name="role" type="radio" value="Kaprodi" required>
                                    <span>Kaprodi</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Program Studi:</label>
                        <div class="col-sm-8">
                            <select id="kd_prodi" class="form-control" name="kd_prodi" disabled>
                                <option value="">- Pilih Program Studi -</option>
                                @foreach ($studyProgram as $sp)
                                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-save" value="post" data-dest="{{route('setting.user.store')}}">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
