<div id="simulasi-capaian-ipk" class="modal fade effect-scale">
    <form>
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Capaian IPK Lulusan/h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Mahasiswa</label>
                                <input type="text" class="form-control form-isi" id="total_mahasiswa">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rata-Rata IPK</label>
                                <input type="text" class="form-control form-isi" id="rata_ipk">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Bobot Skor</label>
                                <input type="text" class="form-control" id="skor_ipk" readonly>
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
<div id="simulasi-prestasi-mhs" class="modal fade effect-scale">
    <form>
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Prestasi Akademik Mahasiswa</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="internasional">Prestasi Internasional (NI)</label>
                                <input type="text" class="form-control form-isi" id="ni">
                                <small class="form-text text-muted">RI = NI/NM = <span class="rata_inter">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Prestasi Nasional (NN)</label>
                                <input type="text" class="form-control form-isi" id="nn">
                                <small class="form-text text-muted">RN = NN/NM = <span class="rata_nasional">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Prestasi Wilayah (NW)</label>
                                <input type="text" class="form-control form-isi" id="nw">
                                <small class="form-text text-muted">RW = NW/NM = <span class="rata_lokal">0</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Jumlah Mahasiswa Aktif (NM)</label>
                                <input type="text" class="form-control form-isi" id="nm">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Skor</label>
                                <input type="text" class="form-control" id="skor_prestasi" readonly>
                                <small class="form-text text-muted">Skor = <span class="rumus_prestasi"></span></small>
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
<div id="simulasi-lembaga-lulusan" class="modal fade effect-scale">
    <form>
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Tingkat Tempat Kerja Lulusan</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="internasional">Lembaga Internasional (NI)</label>
                                <input type="text" class="form-control form-isi" id="ni">
                                <small class="form-text text-muted">RI = NI/NA = <span class="rata_inter">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Lembaga Nasional (NN)</label>
                                <input type="text" class="form-control form-isi" id="nn">
                                <small class="form-text text-muted">RN = NN/NA = <span class="rata_nasional">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Lembaga Wilayah (NL)</label>
                                <input type="text" class="form-control form-isi" id="nl">
                                <small class="form-text text-muted">RL = NL/NA = <span class="rata_lokal">0</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Jumlah Lulusan (NA)</label>
                                <input type="text" class="form-control form-isi" id="na">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Skor</label>
                                <input type="text" class="form-control" id="skor_lembaga" readonly>
                                <small class="form-text text-muted">Skor = <span class="rumus_lembaga"></span></small>
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
