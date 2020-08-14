<div id="modal-master-department" class="modal fade effect-slide-in-right">
    <form method="POST" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold"><span class="title-action"></span> Jurusan</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    @include('layouts.alert')
                    <div class="form-group row mg-t-20">
                        <input type="hidden" name="_id">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Asal Fakultas:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="id_fakultas" data-placeholder="Pilih Prodi" required>
                                <option value="">- Pilih Fakultas -</option>
                                @foreach ($faculty as $f)
                                <option value="{{$f->id}}">{{$f->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Kode Jurusan:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="kd_jurusan" placeholder="Tuliskan kode jurusan" maxlength="5" required>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label"><span class="tx-danger">*</span> Nama Jurusan:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="nama" placeholder="Tuliskan nama jurusan" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-save" value="post" data-dest="{{route('master.department.store')}}">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
