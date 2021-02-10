<div id="simulasi-kerjasama" class="modal fade effect-scale">
    <form id="form_sim_kerjasama" method="POST" enctype="multipart/form-data">
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Kerja Sama 3 Tahun Terakhir</h6>
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
                                        <input type="text" class="form-control form-isi" id="sim_dosen" name="sim_dosen" required>
                                    </div>
                                </div>
                                <div class="col-md-4 ml-auto">
                                    <div class="form-group">
                                        <label>Skor</label>
                                        <input type="text" class="form-control" id="skor" disabled>
                                        <small class="form-text text-muted">Skor = <span class="rumus"></span></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="pendidikan">Kerja Sama Pendidikan<br>(N1)</label>
                                        <input type="text" class="form-control form-isi" id="sim_pendidikan" name="sim_pendidikan" required>
                                        <small class="form-text text-muted">a * N1 = <span class="rata_pendidikan">0</span></small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="penelitian">Kerja Sama Penelitian<br>(N2)</label>
                                        <input type="text" class="form-control form-isi" id="sim_penelitian" name="sim_penelitian" required>
                                        <small class="form-text text-muted">b * N2 = <span class="rata_penelitian">0</span></small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="pkm">Kerja Sama PkM<br>(N3)</label>
                                        <input type="text" class="form-control form-isi" id="sim_pkm" name="sim_pkm" required>
                                        <small class="form-text text-muted">c * N3 = <span class="rata_pkm">0</span></small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="skor_a">&nbsp;<br>Skor A</label>
                                        <input type="text" class="form-control" id="skor_a" disabled>
                                        <small class="form-text text-muted">A = <span class="rumus_a"></span></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="internasional">Kerjasama Internasional<br>(NI)</label>
                                        <input type="text" class="form-control form-isi" id="sim_internasional" name="sim_internasional" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nasional">Kerjasama Nasional<br>(NN)</label>
                                        <input type="text" class="form-control form-isi" id="sim_nasional" name="sim_nasional" required>
                                        <!-- <small class="form-text text-muted">RN = NN/NDT = <span class="rata_nasional">0</span></small> -->
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="lokal">Kerjasama Lokal<br>(NL)</label>
                                        <input type="text" class="form-control form-isi" id="sim_lokal" name="sim_lokal" required>
                                        <!-- <small class="form-text text-muted">RL = NL/NDT = <span class="rata_lokal">0</span></small> -->
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="skor_b">&nbsp;<br>Skor B</label>
                                        <input type="text" class="form-control" id="skor_b" readonly>
                                        <small class="form-text text-muted">B = <span class="rumus_b"></span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Hitung</button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->

@push('custom-js')
<script>

$('#form_sim_kerjasama').submit(function(e){
    e.preventDefault();

    var data      = $(this).serialize() + "&simulasi=1";
    var form      = $(this);
    var button    = $(this).find('button[type=submit]');

    button.html('<i class="fa fa-circle-notch fa-spin"></i>');
    button.attr('disabled',true);

    $.ajax({
        url: '/assessment/collaboration',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function (data) {

            form
                .find('#sim_dosen').val(data.jumlah['dtps']).end()
                .find('#sim_pendidikan').val(data.jumlah['pendidikan']).end()
                .find('#sim_penelitian').val(data.jumlah['penelitian']).end()
                .find('#sim_pkm').val(data.jumlah['pengabdian']).end()
                .find('#sim_internasional').val(data.jumlah['internasional']).end()
                .find('#sim_nasional').val(data.jumlah['nasional']).end()
                .find('#sim_lokal').val(data.jumlah['lokal']).end()
                .find('#skor_a').val(data.skor['a']).end()
                .find('#skor_b').val(data.skor['b']).end()
                .find('#skor').val(data.skor['total']).end()
                .find('span.rumus').text(data.rumus['total']).end()
                .find('span.rata_pendidikan').text(data.rata['pendidikan']).end()
                .find('span.rata_penelitian').text(data.rata['penelitian']).end()
                .find('span.rata_pkm').text(data.rata['pengabdian']).end()
                .find('span.rumus_a').text(data.rumus['a']).end()
                .find('span.rumus_b').text(data.rumus['b']).end();

            button.attr('disabled', false);
            button.text('Hitung');
        }
    });
})
</script>
@endpush
