<div id="simulasi-penelitian-dtps" class="modal fade effect-scale">
    <form>
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Penelitian DTPS</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="internasional">Penelitian Internasional (NI)</label>
                                <input type="text" class="form-control form-isi" id="ni">
                                <small class="form-text text-muted">RI = NI/NDT = <span class="rata_inter">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Penelitian Nasional (NN)</label>
                                <input type="text" class="form-control form-isi" id="nn">
                                <small class="form-text text-muted">RN = NN/NDT = <span class="rata_nasional">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Penelitian Lokal/PT (NL)</label>
                                <input type="text" class="form-control form-isi" id="nl">
                                <small class="form-text text-muted">RL = NL/NDT = <span class="rata_lokal">0</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Jumlah Dosen Tetap (NDT)</label>
                                <input type="text" class="form-control form-isi" id="ndt">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Skor</label>
                                <input type="text" class="form-control" id="skor_penelitian" readonly>
                                <small class="form-text text-muted">Skor = <span class="rumus_penelitian"></span></small>
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
