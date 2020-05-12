<div class="card shadow-base mb-3">
    <div class="card-header">
        <div class="card-title">
            <h6 class="mg-b-0">
                Publikasi di Jurnal 3 Tahun Terakhir
            </h6>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-publikasi-jurnal"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
        </div>
    </div>
    <div class="card-body bd-color-gray-lighter">
        <form class="form-perhitungan">
            <div class="row mg-b-20">
                <div class="col-md-12">
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-4a.jpg" />
                </div>
            </div>
            <div class="row">
				<div class="col-md-12">
					<div class="alert alert-primary">
					  <span>
					  	Faktor a = 0.1 || Faktor b = 1 || Faktor c = 2
					  </span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Jurnal Non Akreditasi<br>(NA1)</label>
								<input type="text" class="form-control" id="jurnal_nonakre" disabled>
								<small class="form-text text-muted">RL = NA1/NDT = <span class="rata_a1">0</span></small>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Jurnal Nasional<br>(NA2)</label>
								<input type="text" class="form-control" id="jurnal_nasional" disabled>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Jurnal Internasional<br>(NA3)</label>
								<input type="text" class="form-control" id="jurnal_inter" disabled>
								<small class="form-text text-muted">RN = (NA2+NA3)/NDT = <span class="rata_a3">0</span></small>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Jurnal Internasional Bereputasi<br>(NA4)</label>
								<input type="text" class="form-control" id="jurnal_inter_rep" disabled>
								<small class="form-text text-muted">RI = NA4/NDT = <span class="rata_a4">0</span></small>
							</div>
						</div>
					</div>
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="internasional">Total DTPS</label>
                                <input type="text" class="form-control" id="dtps" disabled>
                            </div>
                        </div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Skor</label>
								<input type="text" class="form-control" id="skor_publikasi_jurnal" disabled>
								<small class="form-text text-muted">Skor = <span class="rumus_jurnal"></span></small>
							</div>
						</div>
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
                Publikasi di Seminar 3 Tahun Terakhir
            </h6>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-publikasi-seminar"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
        </div>
    </div>
    <div class="card-body bd-color-gray-lighter">
        <form class="form-perhitungan">
            <div class="row mg-b-20">
                <div class="col-md-12">
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-4b.jpg" />
                </div>
            </div>
            <div class="row">
				<div class="col-md-12">
					<div class="alert alert-success">
					  <span>
					  	Faktor a = 0.1 || Faktor b = 1 || Faktor c = 2
					  </span>
					</div>
				</div>
			</div>
		    <div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="internasional">Seminar Wilayah/Lokal/PT<br>(NB1)</label>
						<input type="text" class="form-control" id="publikasi_lokal" disabled>
						<small class="form-text text-muted">RL = NB1/NDT = <span class="rata_b1">0</span></small>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Seminar Penelitian Nasional<br>(NB2)</label>
						<input type="text" class="form-control" id="publikasi_nasional" disabled>
						<small class="form-text text-muted">RL = NB2/NDT = <span class="rata_b2">0</span></small>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Seminar Penelitian Internasional<br>(NB3)</label>
						<input type="text" class="form-control" id="publikasi_inter" disabled>
						<small class="form-text text-muted">RL = NB3/NDT = <span class="rata_b3">0</span></small>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="internasional">Tulisan di Media Massa Nasional</label>
						<input type="text" class="form-control" id="media_nasional" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Tulisan di Media Massa Internasional</label>
						<input type="text" class="form-control" id="media_inter" disabled>
					</div>
				</div>
			</div>
			<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="internasional">Total DTPS</label>
                        <input type="text" class="form-control" id="dtps" disabled>
                    </div>
                </div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Skor</label>
						<input type="text" class="form-control" id="skor_publikasi_seminar" readonly>
						<small class="form-text text-muted">Skor = <span class="rumus_seminar"></span></small>
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
                Karya Ilmiah Tersitasi 3 Tahun Terakhir
            </h6>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-karya-sitasi"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
        </div>
    </div>
    <div class="card-body bd-color-gray-lighter">
        <form class="form-perhitungan">
            <div class="row mg-b-20">
                <div class="col-md-12">
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-4c.jpg" />
                </div>
            </div>
            <div class="row">
				<div class="col-md-4">
                    <div class="form-group">
                        <label for="internasional">Total DTPS</label>
                        <input type="text" class="form-control form-isi" id="dtps" disabled>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="internasional">Karya Ilmiah Tersitasi (NAS)</label>
                        <input type="text" class="form-control form-isi" id="karya_ilmiah" disabled>
                        <small class="form-text text-muted">RS = NAS/NDT = <span class="rata_rs">0</span></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Skor</label>
                        <input type="text" class="form-control" id="skor_karya_ilmiah" readonly>
                        <small class="form-text text-muted">Skor = <span class="rumus_karya_ilmiah"></span></small>
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
                Luaran Penelitian dan PKM 3 Tahun Terakhir
            </h6>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-luaran-pkm"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
        </div>
    </div>
    <div class="card-body bd-color-gray-lighter">
        <form class="form-perhitungan">
            <div class="row mg-b-20">
                <div class="col-md-12">
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-4d.jpg" />
                </div>
            </div>
            <div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="internasional">HKI A (Paten/Paten Sederhana)<br>(NA)</label>
						<input type="text" class="form-control form-pkm" id="pkm_paten" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>HKI B (Hak Cipta, Desain Produk Industri, dll)<br>(NB)</label>
						<input type="text" class="form-control form-pkm" id="pkm_cipta" disabled>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="internasional">Teknologi Tepat Guna, Produk, dll<br>(NC)</label>
						<input type="text" class="form-control form-pkm" id="pkm_produk" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Buku ber-ISBN<br>(ND)</label>
						<input type="text" class="form-control form-pkm" id="pkm_buku" disabled>
					</div>
				</div>
			</div>
			<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="internasional">Total DTPS</label>
                        <input type="text" class="form-control form-isi" id="dtps" disabled>
                    </div>
                </div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Skor</label>
						<input type="text" class="form-control" id="skor_pkm" readonly>
						<small class="form-text text-muted">Skor = <span class="rumus_pkm"></span></small>
					</div>
				</div>
			</div>
        </form>
    </div>
</div>
