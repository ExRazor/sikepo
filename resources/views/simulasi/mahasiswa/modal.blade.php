<div id="simulasi-seleksi-mahasiswa" class="modal fade effect-scale">
    <form>
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Seleksi Mahasiswa</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mahasiswa_calon">Jumlah Calon Mahasiswa</label>
                                        <input type="text" class="form-control form-isi" id="mhs_calon">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mahasiswa_baru">Jumlah Mahasiswa Baru</label>
                                        <input type="text" class="form-control form-isi" id="mhs_baru">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Rasio Perbandingan</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="rasio_calon" disabled>
                                                <small class="form-text text-muted">Rasio Calon Mahasiswa</small>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="rasio_baru" disabled>
                                                <small class="form-text text-muted">Rasio Mahasiswa Baru</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bobot Skor</label>
                                        <input type="text" class="form-control" id="skor_seleksi" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
<div id="simulasi-mahasiswa-asing" class="modal fade effect-scale">
    <form>
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Mahasiswa Asing</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="internasional">Mahasiswa Aktif</label>
                                        <input type="text" class="form-control form-isi" id="mahasiswa_aktif">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Mahasiswa Asing Full-time</label>
                                        <input type="text" class="form-control form-isi" id="mahasiswa_asing_full">
                                        <small class="form-text text-muted">Persentase = <span class="persentase_asing_full">0</span>%</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Mahasiswa Asing Part-time</label>
                                        <input type="text" class="form-control form-isi" id="mahasiswa_asing_part">
                                        <small class="form-text text-muted">Persentase = <span class="persentase_asing_part">0</span>%</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Persentase Mhs Asing</label>
                                        <input type="text" class="form-control" id="persentase_asing" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Skor A (Upaya)</label>
                                        <select class="form-control form-isi" id="skor_asing_a">
                                            @for($i=1;$i <= 4;$i++)
                                            <option>{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Skor B</label>
                                        <input type="text" class="form-control" id="skor_asing_b" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Bobot Skor</label>
                                        <input type="text" class="form-control" id="skor_asing" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
