<div id="simulasi-kerjasama" class="modal fade effect-scale">
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi</h6>
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
                                        <label>Jumlah Dosen Tetap Program Studi (NDTPS)</label>
                                        <input type="text" class="form-control form-isi" id="sim_dosen" value="0">
                                    </div>
                                </div>
                                <div class="col-md-4 ml-auto">
                                    <div class="form-group">
                                        <label>Skor</label>
                                        <input type="text" class="form-control" id="skor" disabled>
                                        <small class="form-text text-muted">Skor = <span id="rumus"></span></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="pendidikan">Kerja Sama Pendidikan<br>(N1)</label>
                                        <input type="text" class="form-control form-isi" id="sim_pendidikan" value="0">
                                        <small class="form-text text-muted">a * N1 = <span class="rata_pendidikan">0</span></small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="penelitian">Kerja Sama Penelitian<br>(N2)</label>
                                        <input type="text" class="form-control form-isi" id="sim_penelitian" value="0">
                                        <small class="form-text text-muted">b * N2 = <span class="rata_penelitian">0</span></small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="pkm">Kerja Sama PkM<br>(N3)</label>
                                        <input type="text" class="form-control form-isi" id="sim_pkm" value="0">
                                        <small class="form-text text-muted">c * N3 = <span class="rata_pkm">0</span></small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="skor_a">&nbsp;<br>Skor A</label>
                                        <input type="text" class="form-control" id="skor_a" value="0" disabled>
                                        <small class="form-text text-muted">A = <span id="rumus_a"></span></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="internasional">Kerjasama Internasional<br>(NI)</label>
                                        <input type="text" class="form-control form-isi" id="sim_internasional" value="0">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nasional">Kerjasama Nasional<br>(NN)</label>
                                        <input type="text" class="form-control form-isi" id="sim_nasional" value="0">
                                        <!-- <small class="form-text text-muted">RN = NN/NDT = <span class="rata_nasional">0</span></small> -->
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="lokal">Kerjasama Lokal<br>(NL)</label>
                                        <input type="text" class="form-control form-isi" id="sim_lokal" value="0">
                                        <!-- <small class="form-text text-muted">RL = NL/NDT = <span class="rata_lokal">0</span></small> -->
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="skor_b">&nbsp;<br>Skor B</label>
                                        <input type="text" class="form-control" id="skor_b" readonly>
                                        <small class="form-text text-muted">B = <span id="rumus_b"></span></small>
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
