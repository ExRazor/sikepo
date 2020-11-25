<div id="kecukupan_dosen" class="card shadow-base mb-3">
    <div class="card-header">
        <div class="card-title">
            <h6 class="mg-b-0">
                Kecukupan Dosen
            </h6>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-kecukupan-dosen"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
        </div>
    </div>
    <div class="card-body bd-color-gray-lighter">
        <div class="row mg-b-20">
            <div class="col-md-12">
                <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-1a.jpg" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Jumlah Dosen Tetap Program Studi</label>
                    <input type="text" class="form-control" id="dtps" disabled>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Bobot Skor</label>
                    <input type="text" class="form-control" id="skor" disabled>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="persentase_dtps_s3" class="card shadow-base mb-3">
    <div class="card-header">
        <div class="card-title">
            <h6 class="mg-b-0">
                Persentase DTPS S3 (Kualifikasi Akademik)
            </h6>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-persentase-s3"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
        </div>
    </div>
    <div class="card-body bd-color-gray-lighter">
        <form class="form-perhitungan">
            <div class="row mg-b-20">
                <div class="col-md-12">
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-1b.jpg" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jumlah DTPS</label>
                        <input type="text" class="form-control" id="dtps" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jumlah DTPS S3</label>
                        <input type="text" class="form-control" id="dtps_s3" disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Persentase</label>
                        <input type="text" class="form-control" id="persentase" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Bobot Skor</label>
                        <input type="text" class="form-control" id="skor" disabled>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="persentase_dtps_jabatan" class="card shadow-base mb-3">
    <div class="card-header">
        <div class="card-title">
            <h6 class="mg-b-0">
                Persentase Jabatan Akademik
            </h6>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-persentase-gubes"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
        </div>
    </div>
    <div class="card-body bd-color-gray-lighter">
        <form class="form-perhitungan">
            <div class="row mg-b-20">
                <div class="col-md-12">
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-1c.jpg" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Jumlah DTPS</label>
                        <input type="number" class="form-control" id="dtps" name="dtps" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Guru Besar</label>
                        <input type="number" class="form-control" id="dtps_gb" name="dtps_gb" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Lektor Kepala</label>
                        <input type="number" class="form-control" id="dtps_lk" name="dtps_lk" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Lektor</label>
                        <input type="number" class="form-control" id="dtps_lektor" name="dtps_lektor" disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Persentase</label>
                        <input type="text" class="form-control" id="persentase" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Bobot Skor</label>
                        <input type="text" class="form-control" id="skor" disabled>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="rasio_mahasiswa_dtps" class="card shadow-base mb-3">
    <div class="card-header">
        <div class="card-title">
            <h6 class="mg-b-0">
                Rasio Mahasiswa dan DTPS
            </h6>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-rasio-mahasiswa"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
        </div>
    </div>
    <div class="card-body bd-color-gray-lighter">
        <form class="form-perhitungan">
            <div class="row mg-b-20">
                <div class="col-md-12">
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-1d.jpg" />
                </div>
            </div>
            <div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Jumlah DTPS</label>
						<input type="text" class="form-control" id="dtps" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Jumlah Mahasiswa</label>
						<input type="text" class="form-control" id="mahasiswa" disabled>
					</div>
				</div>
			</div>
            <div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Rasio Perbandingan</label>
						<div class="row">
							<div class="col-md-6">
								<input type="text" class="form-control" id="rasio_dtps" disabled>
								<small class="form-text text-muted">Rasio DTPS</small>
							</div>
							<div class="col-md-6">
								<input type="text" class="form-control" id="rasio_mahasiswa" disabled>
								<small class="form-text text-muted">Rasio Mahasiswa</small>
							</div>
						</div>

					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Bobot Skor</label>
						<input type="text" class="form-control" id="skor" disabled>
					</div>
				</div>
			</div>
        </form>
    </div>
</div>
<div id="persentase_dtps_dtt" class="card shadow-base mb-3">
    <div class="card-header">
        <div class="card-title">
            <h6 class="mg-b-0">
                Persentase Dosen Tidak Tetap terhadap DTPS
            </h6>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-persentase-dtt"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
        </div>
    </div>
    <div class="card-body bd-color-gray-lighter">
        <form class="form-perhitungan">
            <div class="row mg-b-20">
                <div class="col-md-12">
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-1e.jpg" />
                </div>
            </div>
            <div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Jumlah DTPS</label>
                        <input type="text" class="form-control" id="dtps" disabled>
						<small class="form-text text-muted">Persentase: <span class="persentase_dtps"></span></small>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Jumlah Dosen Tidak Tetap</label>
                        <input type="text" class="form-control" id="dtt" disabled>
						<small class="form-text text-muted">Persentase: <span class="persentase_dtt"></span></small>
					</div>
				</div>
			</div>
            <div class="row">
                <div class="col-md-6">
					<div class="form-group">
						<label>Jumlah Dosen PS</label>
						<input type="text" class="form-control" id="dosen" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Bobot Skor</label>
						<input type="text" class="form-control" id="skor" disabled>
					</div>
				</div>
			</div>
        </form>
    </div>
</div>
<div id="beban_bimbingan" class="card shadow-base mb-3">
    <div class="card-header">
        <div class="card-title">
            <h6 class="mg-b-0">
                Beban Bimbingan Dosen
            </h6>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-beban-bimbingan"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
        </div>
    </div>
    <div class="card-body bd-color-gray-lighter">
        <form class="form-perhitungan">
            <div class="row mg-b-20">
                <div class="col-md-12">
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-1f.jpg" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Rata-Rata Bimbingan</label>
                        <input type="text" class="form-control" id="rata_bimbingan" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Bobot Skor</label>
                        <input type="text" class="form-control" id="skor" disabled>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
