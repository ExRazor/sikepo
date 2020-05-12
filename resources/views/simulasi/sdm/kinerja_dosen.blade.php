<div class="card shadow-base mb-3">
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
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-2a.jpg" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Total Pembimbing Utama</label>
                        <input type="text" class="form-control" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pembimbing dengan Bimbingan <= 10</label>
                        <input type="text" class="form-control" disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Persentase</label>
                        <input type="text" class="form-control" disabled>
                        <small class="form-text text-muted">Dosen Bimbingan <= 10 terhadap Total Pembimbing </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Bobot Skor</label>
                        <input type="text" class="form-control" disabled>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card shadow-base mb-3">
    <div class="card-header">
        <div class="card-title">
            <h6 class="mg-b-0">
                Ekuivalen Waktu Mengajar DTPS
            </h6>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-ewmp-dtps"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
        </div>
    </div>
    <div class="card-body bd-color-gray-lighter">
        <form class="form-perhitungan">
            <div class="row mg-b-20">
                <div class="col-md-12">
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-3a.jpg" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Total DTPS</label>
                        <input type="text" class="form-control" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Total Beban SKS</label>
                        <input type="text" class="form-control" disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Rata-Rata Beban SKS</label>
                        <input type="text" class="form-control" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Bobot Skor</label>
                        <input type="text" class="form-control" disabled>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card shadow-base mb-3">
    <div class="card-header">
        <div class="card-title">
            <h6 class="mg-b-0">
                Prestasi DTPS 3 Tahun Terakhir
            </h6>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-prestasi-dtps"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
        </div>
    </div>
    <div class="card-body bd-color-gray-lighter">
        <form class="form-perhitungan">
            <div class="row mg-b-20">
                <div class="col-md-12">
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-3b.jpg" />
                </div>
            </div>
            <div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="internasional">Jumlah Dosen Tetap Program Studi</label>
						<input type="text" class="form-control" disabled>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Jumlah Dosen Berprestasi</label>
						<input type="text" class="form-control" disabled>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Jumlah Dosen Berprestasi Internasional</label>
						<input type="text" class="form-control" disabled>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="internasional">Rata-Rata</label>
						<input type="text" class="form-control" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Skor</label>
						<input type="text" class="form-control" disabled>
					</div>
				</div>
			</div>
        </form>
    </div>
</div>
