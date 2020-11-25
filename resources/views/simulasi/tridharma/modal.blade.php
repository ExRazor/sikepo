<div id="simulasi-capaian-ipk" class="modal fade effect-scale">
    <form id="form_simulasi_capaian_ipk" method="post">
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Capaian IPK Lulusan</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Mahasiswa</label>
                                <input type="text" class="form-control form-isi" id="total_mahasiswa" name="total_mahasiswa" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rata-Rata IPK</label>
                                <input type="text" class="form-control form-isi" id="rata_ipk" name="rata_ipk" required>
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
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Hitung</button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
<div id="simulasi-prestasi-akademik-mhs" class="modal fade effect-scale">
    <form id="form_simulasi_prestasi_akademik_mhs" method="POST">
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
                                <input type="text" class="form-control form-isi" id="ni" name="ni" required>
                                <small class="form-text text-muted">RI = NI/NM = <span class="rata_inter">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Prestasi Nasional (NN)</label>
                                <input type="text" class="form-control form-isi" id="nn" name="nn" required>
                                <small class="form-text text-muted">RN = NN/NM = <span class="rata_nasional">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Prestasi Wilayah (NW)</label>
                                <input type="text" class="form-control form-isi" id="nw" name="nw" required>
                                <small class="form-text text-muted">RW = NW/NM = <span class="rata_lokal">0</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Jumlah Mahasiswa Aktif (NM)</label>
                                <input type="text" class="form-control form-isi" id="nm" name="nm" required>
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
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Hitung</button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
<div id="simulasi-prestasi-nonakademik-mhs" class="modal fade effect-scale">
    <form id="form_simulasi_prestasi_nonakademik_mhs" method="POST">
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Prestasi Non Akademik Mahasiswa</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="internasional">Prestasi Internasional (NI)</label>
                                <input type="text" class="form-control form-isi" id="ni" name="ni" required>
                                <small class="form-text text-muted">RI = NI/NM = <span class="rata_inter">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Prestasi Nasional (NN)</label>
                                <input type="text" class="form-control form-isi" id="nn" name="nn" required>
                                <small class="form-text text-muted">RN = NN/NM = <span class="rata_nasional">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Prestasi Wilayah (NW)</label>
                                <input type="text" class="form-control form-isi" id="nw" name="nw" required>
                                <small class="form-text text-muted">RW = NW/NM = <span class="rata_lokal">0</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Jumlah Mahasiswa Aktif (NM)</label>
                                <input type="text" class="form-control form-isi" id="nm" name="nm" required>
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
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Hitung</button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
<div id="simulasi-masa-studi-lulusan" class="modal fade effect-scale">
    <form id="form_simulasi_masa_studi_lulusan" method="post">
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Masa Studi Lulusan</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rata-Rata Masa Studi</label>
                                <input type="text" class="form-control form-isi" id="ms" name="ms" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bobot Skor</label>
                                <input type="text" class="form-control" id="skor" readonly>
                                <small class="form-text text-muted">Skor = <span class="rumus_skor"></span></small>
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
<div id="simulasi-lembaga-lulusan" class="modal fade effect-scale">
    <form id="form_simulasi_lembaga_lulusan" method="POST">
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
                                <input type="text" class="form-control form-isi" id="ni" name="ni" required>
                                <small class="form-text text-muted">RI = NI/NA = <span class="rata_inter">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Lembaga Nasional (NN)</label>
                                <input type="text" class="form-control form-isi" id="nn" name="nn" required>
                                <small class="form-text text-muted">RN = NN/NA = <span class="rata_nasional">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Lembaga Wilayah (NL)</label>
                                <input type="text" class="form-control form-isi" id="nl" name="nl" required>
                                <small class="form-text text-muted">RL = NL/NA = <span class="rata_lokal">0</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Jumlah Lulusan (NA)</label>
                                <input type="text" class="form-control form-isi" id="na" name="na" required>
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
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Hitung</button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
@push('custom-js')
<script>

    $('#form_simulasi_capaian_ipk').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/tridharma/ipk',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#skor_ipk').val(data.skor);

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })

    $('#form_simulasi_prestasi_akademik_mhs').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/tridharma/prestasi_mahasiswa/akademik',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#skor_prestasi').val(data.skor.toFixed(2)).end()
                    .find('span.rata_inter').text(data.rata['inter'].toFixed(2)).end()
                    .find('span.rata_nasional').text(data.rata['nasional'].toFixed(2)).end()
                    .find('span.rata_lokal').text(data.rata['lokal'].toFixed(2)).end()
                    .find('span.rumus_prestasi').text(data.rumus);

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })

    $('#form_simulasi_prestasi_nonakademik_mhs').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/tridharma/prestasi_mahasiswa/nonakademik',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#skor_prestasi').val(data.skor.toFixed(2)).end()
                    .find('span.rata_inter').text(data.rata['inter'].toFixed(2)).end()
                    .find('span.rata_nasional').text(data.rata['nasional'].toFixed(2)).end()
                    .find('span.rata_lokal').text(data.rata['lokal'].toFixed(2)).end()
                    .find('span.rumus_prestasi').text(data.rumus);

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })

    $('#form_simulasi_masa_studi_lulusan').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/tridharma/masa_studi_lulusan',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#skor').val(data.skor).end()
                    .find('span.rumus_skor').text(data.rumus);

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })

    $('#form_simulasi_lembaga_lulusan').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/tridharma/tempat_lulusan',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#skor_lembaga').val(data.skor.toFixed(2)).end()
                    .find('span.rata_inter').text(data.rata['inter'].toFixed(2)).end()
                    .find('span.rata_nasional').text(data.rata['nasional'].toFixed(2)).end()
                    .find('span.rata_lokal').text(data.rata['lokal'].toFixed(2)).end()
                    .find('span.rumus_lembaga').text(data.rumus);

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })
    </script>
@endpush
