<div id="simulasi-seleksi-mahasiswa" class="modal fade effect-scale">
    <form id="form_sim_mhs_seleksi" method="POST">
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
                                        <input type="text" class="form-control form-isi" id="mhs_calon" name="mhs_calon" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mahasiswa_baru">Jumlah Mahasiswa Baru</label>
                                        <input type="text" class="form-control form-isi" id="mhs_baru" name="mhs_baru" required>
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
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Hitung</button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
<div id="simulasi-mahasiswa-asing" class="modal fade effect-scale">
    <form id="form_sim_mhs_asing" method="POST">
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
                                        <input type="text" class="form-control form-isi" id="mahasiswa_aktif" name="mahasiswa_aktif" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Mahasiswa Asing Full-time</label>
                                        <input type="text" class="form-control form-isi" id="mahasiswa_asing_full" name="mahasiswa_asing_full" required>
                                        <small class="form-text text-muted">Persentase = <span class="persentase_asing_full">0</span>%</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Mahasiswa Asing Part-time</label>
                                        <input type="text" class="form-control form-isi" id="mahasiswa_asing_part" name="mahasiswa_asing_part" required>
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
                                        <select class="form-control form-isi" id="skor_asing_a" name="skor_asing_a">
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
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Hitung</button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
<div id="simulasi-publikasi-mhs" class="modal fade effect-scale">
    <form id="form_simulasi_publikasi_mhs" method="POST">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Publikasi DTPS</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Jurnal Non Akreditasi<br>(NA1)</label>
								<input type="text" class="form-control" id="na1" name="na1" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Jurnal Nasional<br>(NA2)</label>
								<input type="text" class="form-control" id="na2" name="na2" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Jurnal Internasional<br>(NA3)</label>
								<input type="text" class="form-control" id="na3" name="na3" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Jurnal Internasional Bereputasi<br>(NA4)</label>
								<input type="text" class="form-control" id="na4" name="na4" required>
							</div>
						</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Seminar Wilayah/Lokal/PT<br>(NB1)</label>
                                <input type="text" class="form-control" id="nb1" name="nb1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Seminar Penelitian Nasional<br>(NB2)</label>
                                <input type="text" class="form-control" id="nb2" name="nb2" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Seminar Penelitian Internasional<br>(NB3)</label>
                                <input type="text" class="form-control" id="nb3" name="nb3" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tulisan di Media Massa Wilayah<br>(NC1)</label>
                                <input type="text" class="form-control" id="nc1" name="nc1" required>
                                <small class="form-text text-muted">RL = (NA1+NB1+NC1)/NDT = <span class="rata_rl">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tulisan di Media Massa Nasional<br>(NC2)</label>
                                <input type="text" class="form-control" id="nc2" name="nc2" required>
                                <small class="form-text text-muted">RN = (NA2+NA3+NB2+NC2)/NDT = <span class="rata_rn">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tulisan di Media Massa Internasional<br>(NC3)</label>
                                <input type="text" class="form-control" id="nc3" name="nc3" required>
                                <small class="form-text text-muted">RI = (NA4+NB3+NC3)/NDT = <span class="rata_ri">0</span></small>
                            </div>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total Mahasiswa</label>
                                <input type="text" class="form-control" id="mhs" name="mhs" required>
                            </div>
                        </div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Skor</label>
								<input type="text" class="form-control" id="skor" disabled>
								<small class="form-text text-muted">Skor = <span class="rumus"></span></small>
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
<div id="simulasi-luaran-mhs" class="modal fade effect-scale">
    <form id="form_simulasi_luaran_mhs">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Luaran Mahasiswa</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>HKI A (Paten/Paten Sederhana)<br>(NA)</label>
                                <input type="number" class="form-control form-isi" id="na" name="na" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>HKI B (Hak Cipta, Desain Produk Industri, dll)<br>(NB)</label>
                                <input type="number" class="form-control form-isi" id="nb" name="nb" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Teknologi Tepat Guna, Produk, dll<br>(NC)</label>
                                <input type="number" class="form-control form-isi" id="nc" name="nc" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Buku ber-ISBN<br>(ND)</label>
                                <input type="number" class="form-control form-isi" id="nd" name="nd" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hasil (RLP)</label>
                                <input type="number" class="form-control" id="nlp" disabled>
                                <small class="form-text text-muted">RLP = <span class="rumus_nlp"></span></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Skor</label>
                                <input type="text" class="form-control" id="skor" readonly>
                                <small class="form-text text-muted">Skor = <span class="rumus"></span></small>
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

    $('#form_sim_mhs_seleksi').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/student/seleksi',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#mhs_calon').val(data.jumlah['mhs_calon']).end()
                    .find('#mhs_baru').val(data.jumlah['mhs_baru']).end()
                    .find('#rasio_calon').val(data.rasio['calon']).end()
                    .find('#rasio_baru').val(data.rasio['baru']).end()
                    .find('#skor_seleksi').val(data.skor);

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })

    $('#form_sim_mhs_asing').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/student/asing',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#mahasiswa_aktif').val(data.jumlah['mahasiswa']).end()
                    .find('#mahasiswa_asing_full').val(data.jumlah['asing_full']).end()
                    .find('#mahasiswa_asing_part').val(data.jumlah['asing_part']).end()
                    .find('#persentase_asing').val(data.persentase['asing']).end()
                    .find('#skor_asing_b').val(data.skor['b']).end()
                    .find('#skor_asing').val(data.skor['total']).end()
                    .find('span.persentase_asing_full').text(data.persentase['asing_full']).end()
                    .find('span.persentase_asing_part').text(data.persentase['asing_part']);

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })

    $('#form_simulasi_publikasi_mhs').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/student/publikasi',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('span.rata_rl').text(data.rata['rl'].toFixed(2)).end()
                    .find('span.rata_rn').text(data.rata['rn'].toFixed(2)).end()
                    .find('span.rata_ri').text(data.rata['ri'].toFixed(2)).end()
                    .find('span.rumus').text(data.rumus).end()
                    .find('#skor').val(data.skor.toFixed(2));

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })

    $('#form_simulasi_luaran_mhs').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/student/luaran',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#nlp').val(data.hasil.toFixed(2)).end()
                    .find('span.rumus_nlp').text(data.rumus['nlp']).end()
                    .find('span.rumus').text(data.rumus['skor']).end()
                    .find('#skor').val(data.skor.toFixed(2));

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })
    </script>
@endpush
