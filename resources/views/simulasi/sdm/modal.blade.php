<div id="simulasi-kecukupan-dosen" class="modal fade effect-scale">
    <form id="form_simulasi_kecukupan_dosen" method="POST">
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Kecukupan Dosen</h6>
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
                                        <label>Jumlah Dosen Tetap Program Studi</label>
                                        <input type="number" class="form-control form-isi" id="dtps" name="dtps" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bobot Skor</label>
                                        <input type="text" class="form-control" id="skor" readonly>
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

    $('#form_simulasi_kecukupan_dosen').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/resource/kecukupan_dosen',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#skor').val(data.skor.toFixed(2));

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })
</script>
@endpush
<div id="simulasi-persentase-s3" class="modal fade effect-scale">
    <form id="form_simulasi_persentase_s3" method="POST">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Persentase DTPS S3</h6>
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
                                        <label>Jumlah DTPS</label>
                                        <input type="number" class="form-control form-isi" id="dtps" name="dtps" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jumlah DTPS S3</label>
                                        <input type="number" class="form-control form-isi" id="dtps_s3" name="dtps_s3" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Persentase</label>
                                        <input type="text" class="form-control" id="persentase_dtps_s3" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bobot Skor</label>
                                        <input type="text" class="form-control" id="skor_dtps_s3" readonly>
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

    $('#form_simulasi_persentase_s3').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/resource/persentase_dtps_s3',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#persentase_dtps_s3').val(data.persentase.toFixed(2)+"%").end()
                    .find('#skor_dtps_s3').val(data.skor.toFixed(2));

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })
</script>
@endpush
<div id="simulasi-persentase-gubes" class="modal fade effect-scale">
    <form id="form_simulasi_persentase_gubes" method="POST">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Persentase Jabatan Akademik</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Jumlah DTPS</label>
                                        <input type="number" class="form-control form-isi" id="dtps" name="dtps" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Guru Besar</label>
                                        <input type="number" class="form-control form-isi" id="dtps_gb" name="dtps_gb" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Lektor Kepala</label>
                                        <input type="number" class="form-control form-isi" id="dtps_lk" name="dtps_lk" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Lektor</label>
                                        <input type="number" class="form-control form-isi" id="dtps_lektor" name="dtps_lektor" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Persentase</label>
                                        <input type="text" class="form-control" id="persentase" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bobot Skor</label>
                                        <input type="text" class="form-control" id="skor" readonly>
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

    $('#form_simulasi_persentase_gubes').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/resource/persentase_dtps_jabatan',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#persentase').val(data.persentase.toFixed(2)+"%").end()
                    .find('#skor').val(data.skor.toFixed(2));

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })
</script>
@endpush
<div id="simulasi-rasio-mahasiswa" class="modal fade effect-scale">
    <form id="form_simulasi_rasio_mahasiswa" method="POST">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Rasio Mahasiswa Terhadap DTPS</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah DTPS</label>
                                <input type="number" class="form-control form-isi" id="dtps" name="dtps" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Mahasiswa</label>
                                <input type="number" class="form-control form-isi" id="mahasiswa" name="mahasiswa" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rasio Perbandingan</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="rasio_dtps" readonly>
                                        <small class="form-text text-muted">Rasio DTPS</small>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="rasio_mahasiswa" readonly>
                                        <small class="form-text text-muted">Rasio Mahasiswa</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bobot Skor</label>
                                <input type="text" class="form-control" id="skor_rasio_dtpm" readonly>
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
    $('#form_simulasi_rasio_mahasiswa').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/resource/rasio_mahasiswa_dtps',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#rasio_dtps').val(data.rasio['dtps'].toFixed(0)).end()
                    .find('#rasio_mahasiswa').val(data.rasio['mahasiswa'].toFixed(0)).end()
                    .find('#skor_rasio_dtpm').val(data.skor.toFixed(2));

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })
</script>
@endpush
<div id="simulasi-persentase-dtt" class="modal fade effect-scale">
    <form id="form_simulasi_persentase_dtt" method="POST">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Persentase Dosen Tidak Tetap</h6>
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
                                        <label>Jumlah DTPS</label>
                                        <input type="number" class="form-control form-isi" id="dtps" name="dtps" required>
                                        <small class="form-text text-muted">Persentase: <span class="persentase_dtps"></span></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jumlah Dosen Tidak Tetap</label>
                                        <input type="number" class="form-control form-isi" id="dtt" name="dtt" required>
                                        <small class="form-text text-muted">Persentase: <span class="persentase_dtt"></span></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jumlah Dosen PS</label>
                                        <input type="number" class="form-control" id="dosen" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bobot Skor</label>
                                        <input type="text" class="form-control" id="skor" readonly>
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
    $('#form_simulasi_persentase_dtt').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/resource/persentase_dtps_dtt',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('span.persentase_dtps').val(data.persentase['dtps'].toFixed(2)+"%").end()
                    .find('span.persentase_dtt').val(data.persentase['dtt'].toFixed(2)+"%").end()
                    .find('#dosen').val(data.jumlah['dosen']).end()
                    .find('#skor').val(data.skor.toFixed(2));

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })
</script>
@endpush
<div id="simulasi-beban-bimbingan" class="modal fade effect-scale">
    <form id="form_simulasi_beban_bimbingan" method="POST">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Beban Bimbingan DTPS</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rata-Rata Bimbingan Utama</label>
                                <input type="text" class="form-control" id="rata_bimbingan" name="rata_bimbingan" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bobot Skor</label>
                                <input type="text" class="form-control" id="skor" readonly>
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
    $('#form_simulasi_beban_bimbingan').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/resource/beban_bimbingan',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#skor').val(data.skor.toFixed(2));

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })
</script>
@endpush
<div id="simulasi-ewmp-dtps" class="modal fade effect-scale">
    <form id="form_simulasi_ewmp_dtps" method="POST">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Waktu Mengajar Penuh DTPS</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total DTPS</label>
                                <input type="number" class="form-control form-isi" id="total_dtps" name="total_dtps" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total Rata SKS</label>
                                <input type="text" class="form-control form-isi" id="total_rata_sks" name="total_rata_sks" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rata-Rata SKS per Dosen</label>
                                <input type="text" class="form-control" id="rata_sks" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bobot Skor</label>
                                <input type="text" class="form-control" id="skor" readonly>
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
    $('#form_simulasi_ewmp_dtps').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/resource/waktu_mengajar',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#rata_sks').val(data.rata_sks.toFixed(2)).end()
                    .find('#skor').val(data.skor.toFixed(2));

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })
</script>
@endpush
<div id="simulasi-prestasi-dtps" class="modal fade effect-scale">
    <form id="form_simulasi_prestasi_dtps" method="POST">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Prestasi DTPS</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Dosen Tetap Program Studi</label>
                                <input type="number" class="form-control form-isi" id="dtps" name="dtps" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Dosen Berprestasi</label>
                                <input type="number" class="form-control form-isi" id="dtps_berprestasi" name="dtps_berprestasi" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rata-Rata</label>
                                <input type="text" class="form-control" id="rata_prestasi_dtps" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Skor</label>
                                <input type="text" class="form-control" id="skor_prestasi_dtps" readonly>
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
    $('#form_simulasi_prestasi_dtps').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/resource/prestasi_dtps',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#rata_prestasi_dtps').val(data.rata.toFixed(2)).end()
                    .find('#skor_prestasi_dtps').val(data.skor.toFixed(2));

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })
</script>
@endpush
<div id="simulasi-penelitian-dtps" class="modal fade effect-scale">
    <form id="form_simulasi_penelitian_dtps" method="POST">
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
                                <label>Pembiayaan Luar Negeri (NI)</label>
                                <input type="text" class="form-control" id="ni" name="ni" required>
                                <small class="form-text text-muted">RI = NI/3/NDT = <span class="rata_inter">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Pembiayaan Dalam Negeri (NN)</label>
                                <input type="text" class="form-control" id="nn" name="nn" required>
                                <small class="form-text text-muted">RN = NN/3/NDT = <span class="rata_nasional">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Pembiayaan Perguruan Tinggi/Mandiri (NL)</label>
                                <input type="text" class="form-control" id="nl" name="nl" required>
                                <small class="form-text text-muted">RL = NL/3/NDT = <span class="rata_lokal">0</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Jumlah Dosen Tetap (NDT)</label>
                                <input type="text" class="form-control form-isi" id="dtps" name="dtps" required>
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
    $('#form_simulasi_penelitian_dtps').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/resource/penelitian',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('span.rata_inter').val(data.rata['inter']).end()
                    .find('span.rata_nasional').val(data.rata['nasional']).end()
                    .find('span.rata_lokal').val(data.rata['lokal']).end()
                    .find('#skor').val(data.skor.toFixed(2)).end()
                    .find('span.rumus').val(data.rumus);

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })
</script>
@endpush
<div id="simulasi-pengabdian-dtps" class="modal fade effect-scale">
    <form id="form_simulasi_pengabdian_dtps" method="POST">
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Pengabdian DTPS</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pembiayaan Luar Negeri (NI)</label>
                                <input type="text" class="form-control" id="ni" name="ni" required>
                                <small class="form-text text-muted">RI = NI/3/NDT = <span class="rata_inter">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Pembiayaan Dalam Negeri (NN)</label>
                                <input type="text" class="form-control" id="nn" name="nn" required>
                                <small class="form-text text-muted">RN = NN/3/NDT = <span class="rata_nasional">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Pembiayaan Perguruan Tinggi/Mandiri (NL)</label>
                                <input type="text" class="form-control" id="nl" name="nl" required>
                                <small class="form-text text-muted">RL = NL/3/NDT = <span class="rata_lokal">0</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Jumlah Dosen Tetap (NDT)</label>
                                <input type="text" class="form-control form-isi" id="dtps" name="dtps" required>
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
    $('#form_simulasi_pengabdian_dtps').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/resource/pengabdian',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('span.rata_inter').val(data.rata['inter']).end()
                    .find('span.rata_nasional').val(data.rata['nasional']).end()
                    .find('span.rata_lokal').val(data.rata['lokal']).end()
                    .find('#skor').val(data.skor.toFixed(2)).end()
                    .find('span.rumus').val(data.rumus);

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })
</script>
@endpush
<div id="simulasi-publikasi-dtps" class="modal fade effect-scale">
    <form id="form_simulasi_publikasi_dtps" method="POST">
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
                                <label>Total DTPS</label>
                                <input type="text" class="form-control" id="dtps" name="dtps" required>
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
@push('custom-js')
<script>
    $('#form_simulasi_publikasi_dtps').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/resource/publikasi',
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
</script>
@endpush
<div id="simulasi-karya-sitasi" class="modal fade effect-scale">
    <form id="form_simulasi_karya_sitasi" method="POST">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Karya Ilmiah yang Tersitasi</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Total DTPS (NDT)</label>
                                <input type="number" class="form-control form-isi" id="dtps" name="dtps" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Karya Ilmiah Tersitasi (NAS)</label>
                                <input type="number" class="form-control form-isi" id="karya_ilmiah" name="nas" required>
                                <small class="form-text text-muted">RS = NAS/NDT = <span class="rata_rs">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
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
    $('#form_simulasi_karya_sitasi').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/resource/publikasi_tersitasi',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('span.rata_rs').text(data.rata['rs'].toFixed(2)).end()
                    .find('span.rumus').text(data.rumus).end()
                    .find('#skor').val(data.skor.toFixed(2));

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })
</script>
@endpush
<div id="simulasi-luaran-pkm" class="modal fade effect-scale">
    <form id="form_simulasi_luaran_pkm">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Simulasi Luaran Penelitian dan PkM</h6>
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Total DTPS</label>
                                <input type="number" class="form-control form-isi" id="dtps" name="dtps" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Rata-Rata (RLP)</label>
                                <input type="number" class="form-control" id="rlp" disabled>
                                <small class="form-text text-muted">RLP = <span class="rumus_rlp"></span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
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
    $('#form_simulasi_luaran_pkm').submit(function(e){
        e.preventDefault();

        var data      = $(this).serialize() + "&simulasi=1";
        var form      = $(this);
        var button    = $(this).find('button[type=submit]');

        button.html('<i class="fa fa-circle-notch fa-spin"></i>');
        button.attr('disabled',true);

        $.ajax({
            url: '/assessment/resource/luaran_pkm',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {

                form
                    .find('#rlp').val(data.rata.toFixed(2)).end()
                    .find('span.rumus_rlp').text(data.rumus['rlp']).end()
                    .find('span.rumus').text(data.rumus['skor']).end()
                    .find('#skor').val(data.skor.toFixed(2));

                button.attr('disabled', false);
                button.text('Hitung');
            }
        });
    })
</script>
@endpush
