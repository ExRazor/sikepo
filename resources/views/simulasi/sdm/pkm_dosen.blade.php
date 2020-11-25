<div id="penelitian_dtps" class="card shadow-base mb-3">
    <div class="card-header">
        <div class="card-title">
            <h6 class="mg-b-0">
                Penelitian DTPS 3 Tahun Terakhir
            </h6>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-penelitian-dtps"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
        </div>
    </div>
    <div class="card-body bd-color-gray-lighter">
        <div class="row mg-b-20">
            <div class="col-md-12">
                <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-3a.jpg" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-primary">
                  <span>
                      Faktor a = <span class="faktor_a"></span> || Faktor b = <span class="faktor_b"></span> || Faktor c = <span class="faktor_c"></span>
                  </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="internasional">Pembiayaan Luar Negeri (NI)</label>
                    <input type="text" class="form-control" id="ni" disabled>
                    <small class="form-text text-muted">RI = NI/3/NDT = <span class="rata_inter">0</span></small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="exampleInputEmail1">Pembiayaan Dalam Negeri (NN)</label>
                    <input type="text" class="form-control" id="nn" disabled>
                    <small class="form-text text-muted">RN = NN/3/NDT = <span class="rata_nasional">0</span></small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="exampleInputEmail1">Pembiayaan Perguruang Tinggi/Mandiri (NL)</label>
                    <input type="text" class="form-control" id="nl" disabled>
                    <small class="form-text text-muted">RL = NL/3/NDT = <span class="rata_lokal">0</span></small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Jumlah Dosen Tetap (NDT)</label>
                    <input type="text" class="form-control" id="dtps" disabled>
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
</div>
<div id="pengabdian_dtps" class="card shadow-base mb-3">
    <div class="card-header">
        <div class="card-title">
            <h6 class="mg-b-0">
                Pengabdian DTPS 3 Tahun Terakhir
            </h6>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-pengabdian-dtps"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
        </div>
    </div>
    <div class="card-body bd-color-gray-lighter">
        <div class="row mg-b-20">
            <div class="col-md-12">
                <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-3b.jpg" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-primary">
                  <span>
                      Faktor a = <span class="faktor_a"></span> || Faktor b = <span class="faktor_b"></span> || Faktor c = <span class="faktor_c"></span>
                  </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="internasional">Pembiayaan Luar Negeri (NI)</label>
                    <input type="text" class="form-control" id="ni" disabled>
                    <small class="form-text text-muted">RI = NI/3/NDT = <span class="rata_inter">0</span></small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="exampleInputEmail1">Pembiayaan Dalam Negeri (NN)</label>
                    <input type="text" class="form-control" id="nn" disabled>
                    <small class="form-text text-muted">RN = NN/3/NDT = <span class="rata_nasional">0</span></small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="exampleInputEmail1">Pembiayaan Perguruang Tinggi/Mandiri (NL)</label>
                    <input type="text" class="form-control" id="nl" disabled>
                    <small class="form-text text-muted">RL = NL/3/NDT = <span class="rata_lokal">0</span></small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Jumlah Dosen Tetap (NDT)</label>
                    <input type="text" class="form-control" id="dtps" disabled>
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
</div>
<div id="publikasi_dtps" class="card shadow-base mb-3">
    <div class="card-header">
        <div class="card-title">
            <h6 class="mg-b-0">
                Publikasi DTPS 3 Tahun Terakhir
            </h6>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-primary btn-add" data-toggle="modal" data-target="#simulasi-publikasi-dtps"><i class="fa fa-sync mg-r-10"></i> Simulasi</button>
        </div>
    </div>
    <div class="card-body bd-color-gray-lighter">
        <form class="form-perhitungan">
            <div class="row mg-b-20">
                <div class="col-md-12">
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-3c.jpg" />
                </div>
            </div>
            <div class="row">
				<div class="col-md-12">
					<div class="alert alert-primary">
					  <span>
					  	Faktor a = <span class="faktor_a"></span> || Faktor b = <span class="faktor_b"></span> || Faktor c = <span class="faktor_c"></span>
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
								<input type="text" class="form-control" id="na1" disabled>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Jurnal Nasional<br>(NA2)</label>
								<input type="text" class="form-control" id="na2" disabled>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Jurnal Internasional<br>(NA3)</label>
								<input type="text" class="form-control" id="na3" disabled>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Jurnal Internasional Bereputasi<br>(NA4)</label>
								<input type="text" class="form-control" id="na4" disabled>
							</div>
						</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="internasional">Seminar Wilayah/Lokal/PT<br>(NB1)</label>
                                <input type="text" class="form-control" id="nb1" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Seminar Penelitian Nasional<br>(NB2)</label>
                                <input type="text" class="form-control" id="nb2" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Seminar Penelitian Internasional<br>(NB3)</label>
                                <input type="text" class="form-control" id="nb3" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="internasional">Tulisan di Media Massa Lokal<br>(NC1)</label>
                                <input type="text" class="form-control" id="nc1" disabled>
                                <small class="form-text text-muted">RL = (NA1+NB1+NC1)/NDT = <span class="rata_rl">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="internasional">Tulisan di Media Massa Nasional<br>(NC2)</label>
                                <input type="text" class="form-control" id="nc2" disabled>
                                <small class="form-text text-muted">RN = (NA2+NA3+NB2+NC2)/NDT = <span class="rata_rn">0</span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tulisan di Media Massa Internasional<br>(NC3)</label>
                                <input type="text" class="form-control" id="nc3" disabled>
                                <small class="form-text text-muted">RI = (NA4+NB3+NC3)/NDT = <span class="rata_ri">0</span></small>
                            </div>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="internasional">Total DTPS<br>(NDTPS)</label>
                                <input type="text" class="form-control" id="dtps" disabled>
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
			</div>
        </form>
    </div>
</div>
<div id="publikasi_tersitasi" class="card shadow-base mb-3">
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
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-3d.jpg" />
                </div>
            </div>
            <div class="row">
				<div class="col-md-4">
                    <div class="form-group">
                        <label for="internasional">Total DTPS (NDT)</label>
                        <input type="text" class="form-control form-isi" id="dtps" disabled>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="internasional">Karya Ilmiah Tersitasi (NAS)</label>
                        <input type="text" class="form-control form-isi" id="nas" disabled>
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
        </form>
    </div>
</div>
<div id="luaran_pkm" class="card shadow-base mb-3">
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
                    <img class="w-100" src="{{ asset ('penilaian/img') }}/sdm-3e.jpg" />
                </div>
            </div>
            <div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="internasional">HKI A (Paten/Paten Sederhana)<br>(NA)</label>
						<input type="text" class="form-control form-pkm" id="na" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>HKI B (Hak Cipta, Desain Produk Industri, dll)<br>(NB)</label>
						<input type="text" class="form-control form-pkm" id="nb" disabled>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="internasional">Teknologi Tepat Guna, Produk, dll<br>(NC)</label>
						<input type="text" class="form-control form-pkm" id="nc" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Buku ber-ISBN<br>(ND)</label>
						<input type="text" class="form-control form-pkm" id="nd" disabled>
					</div>
				</div>
			</div>
			<div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="internasional">Total DTPS<br>(NDT)</label>
                        <input type="text" class="form-control form-isi" id="dtps" disabled>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="internasional">Rata-Rata</label>
                        <input type="text" class="form-control form-isi" id="rlp" disabled>
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
        </form>
    </div>
</div>
