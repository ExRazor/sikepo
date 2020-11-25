<div id="waktu_mengajar" class="card shadow-base mb-3">
    <div class="card-header">
        <div class="card-title">
            <h6 class="mg-b-0">
                Waktu Mengajar Penuh DTPS
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
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-2a.jpg" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Total DTPS</label>
                        <input type="text" class="form-control" id="dtps" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Total Rata SKS</label>
                        <input type="text" class="form-control" id="total_rata_sks" disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Rata-Rata SKS per Dosen</label>
                        <input type="text" class="form-control" id="rata_sks" disabled>
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
<div id="prestasi_dtps" class="card shadow-base mb-3">
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
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-3a.jpg" />
                </div>
            </div>
            <div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="internasional">Jumlah Dosen Tetap Program Studi</label>
						<input type="text" class="form-control" id="dtps" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Jumlah Dosen Berprestasi</label>
						<input type="text" class="form-control" id="dtps_berprestasi" disabled>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="internasional">Rata-Rata</label>
						<input type="text" class="form-control" id="rata" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Skor</label>
						<input type="text" class="form-control" id="skor" disabled>
					</div>
				</div>
			</div>
        </form>
    </div>
</div>
