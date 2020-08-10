<div id="modal-master-faculty" class="modal fade effect-slide-in-right">
    <form method="POST" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold"><span class="title-action"></span> Fakultas</h6>
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
                        <input type="hidden" name="_id">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Nama Fakultas:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="nama" placeholder="Tuliskan nama fakultas" required>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Singkatan:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="singkatan" placeholder="Tuliskan singkatan dari nama fakultas" required>
                        </div>
                    </div>
                    {{-- <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">NIP Dekan:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="nip_dekan" placeholder="Tuliskan NIP dari Dekan saat ini">
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">Nama Dekan:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="nm_dekan" placeholder="Tuliskan nama dari Dekan saat ini">
                        </div>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-save" value="post" data-dest="{{route('master.faculty.store')}}">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
