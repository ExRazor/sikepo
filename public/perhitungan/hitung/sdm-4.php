<div class="container">
	<div class="card mb-4">
	  <div class="card-body">
	    <div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Jumlah Dosen Tetap (NDT)</label>
						<input type="text" class="form-control form-dosen" id="total_dosen" value="27">
					</div>
				</div>
			</div>
		</div>
	  </div>
	</div>
	<div class="card mb-4">
	  <div class="card-body">
	    <h5 class="card-title">Publikasi di Jurnal 3 Tahun Terakhir</h5>
	    <div class="container">
		    <div class="row">
				<div class="col-md-12 panduan">
					<img src="assets/images/sdm-4a.jpg" />
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
								<input type="text" class="form-control form-jurnal" id="jurnal_nonakre" value="6">
								<small class="form-text text-muted">RL = NA1/NDT = <span class="rata_a1">0</span></small>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Jurnal Nasional<br>(NA2)</label>
								<input type="text" class="form-control form-jurnal" id="jurnal_nasional" value="2">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Jurnal Internasional<br>(NA3)</label>
								<input type="text" class="form-control form-jurnal" id="jurnal_inter" value="1">
								<small class="form-text text-muted">RN = (NA2+NA3)/NDT = <span class="rata_a3">0</span></small>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Jurnal Internasional Bereputasi<br>(NA4)</label>
								<input type="text" class="form-control form-jurnal" id="jurnal_inter_rep" value="1">
								<small class="form-text text-muted">RI = NA4/NDT = <span class="rata_a4">0</span></small>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Skor</label>
								<input type="text" class="form-control" id="skor_publikasi_jurnal" readonly>
								<small class="form-text text-muted">Skor = <span class="rumus_jurnal"></span></small>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6" id="form_kinerja">
				</div>
			</div>
		</div>
	  </div>
	</div>
	<div class="card mb-4">
	  <div class="card-body">
	    <h5 class="card-title">Publikasi di Seminar 3 Tahun Terakhir</h5>
	    <div class="container">
		    <div class="row">
				<div class="col-md-12 panduan">
				<img src="assets/images/sdm-4b.jpg" />
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
						<input type="text" class="form-control form-seminar" id="publikasi_lokal" value="12">
						<small class="form-text text-muted">RL = NB1/NDT = <span class="rata_b1">0</span></small>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Seminar Penelitian Nasional<br>(NB2)</label>
						<input type="text" class="form-control form-seminar" id="publikasi_nasional" value="5">
						<small class="form-text text-muted">RL = NB2/NDT = <span class="rata_b2">0</span></small>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Seminar Penelitian Internasional<br>(NB3)</label>
						<input type="text" class="form-control form-seminar" id="publikasi_inter" value="2">
						<small class="form-text text-muted">RL = NB3/NDT = <span class="rata_b3">0</span></small>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="internasional">Tulisan di Media Massa Nasional</label>
						<input type="text" class="form-control form-seminar" id="media_nasional" value="3">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Tulisan di Media Massa Internasional</label>
						<input type="text" class="form-control form-seminar" id="media_inter" value="1">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Skor</label>
						<input type="text" class="form-control" id="skor_publikasi_seminar" readonly>
						<small class="form-text text-muted">Skor = <span class="rumus_seminar"></span></small>
					</div>
				</div>
			</div>
		</div>
	  </div>
	</div>
	<div class="card mb-4">
	  <div class="card-body">
	    <h5 class="card-title">Karya Ilmiah Tersitasi 3 Tahun Terakhir</h5>
	    <div class="container">
		    <div class="row">
				<div class="col-md-12 panduan">
				<img src="assets/images/sdm-4c.jpg" />
				</div>
			</div>
		    <div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="internasional">Karya Ilmiah Tersitasi (NAS)</label>
						<input type="text" class="form-control form-karya-ilmiah" id="karya_ilmiah" value="5">
						<small class="form-text text-muted">RS = NAS/NDT = <span class="rata_rs">0</span></small>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Skor</label>
						<input type="text" class="form-control" id="skor_karya_ilmiah" readonly>
						<small class="form-text text-muted">Skor = <span class="rumus_karya_ilmiah"></span></small>
					</div>
				</div>
			</div>
		</div>
	  </div>
	</div>
	<div class="card mb-4">
	  <div class="card-body">
	    <h5 class="card-title">Luaran Penelitian dan PKM 3 Tahun Terakhir</h5>
	    <div class="container">
		    <div class="row">
				<div class="col-md-12 panduan">
				<img src="assets/images/sdm-4d.jpg" />
				</div>
			</div>
		    <div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="internasional">Dengan Pengakuan HKI (Paten/Paten Sederhana)<br>(NA)</label>
						<input type="text" class="form-control form-pkm" id="pkm_paten" value="3">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Dengan Pengakuan HKI (Hak Cipta, Desain Produk Industri, dll)<br>(NB)</label>
						<input type="text" class="form-control form-pkm" id="pkm_cipta" value="6">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="internasional">Dalam bentuk Teknologi Tepat Guna, Produk, dll<br>(NC)</label>
						<input type="text" class="form-control form-pkm" id="pkm_produk" value="4">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Diterbitkan dalam bentuk Buku ber-ISBN<br>(ND)</label>
						<input type="text" class="form-control form-pkm" id="pkm_buku" value="2">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Skor</label>
						<input type="text" class="form-control" id="skor_pkm" readonly>
						<small class="form-text text-muted">Skor = <span class="rumus_pkm"></span></small>
					</div>
				</div>
			</div>
		</div>
	  </div>
	</div>
</div>


<script>

$(function(){
	
	hitung_jurnal();
	hitung_seminar();
	hitung_karya_ilmiah();
	hitung_pkm();

	$(".form-jurnal").on('keyup', function(){
		hitung_jurnal();
	});

	$(".form-seminar").on('keyup', function(){
		hitung_seminar();
	});

	$(".form-karya-ilmiah").on('keyup', function(){
		hitung_karya_ilmiah();
	});

	$(".form-pkm").on('keyup', function(){
		hitung_pkm();
	});

	$(".form-dosen").on('keyup', function(){
		hitung_jurnal();
		hitung_seminar();
		hitung_karya_ilmiah();
		hitung_pkm();
	});

});

function hitung_jurnal()
{
	var dt = $("#total_dosen").val();
	var a1 = $("#jurnal_nonakre").val();
	var a2 = $("#jurnal_nasional").val();
	var a3 = $("#jurnal_inter").val();
	var a4 = $("#jurnal_inter_rep").val();

	var faktor_a = 0.1;
	var faktor_b = 1;
	var faktor_c = 2;
	var skor = 0;

	rl = a1/dt;
	rn = (a2+a3)/dt;
	ri = a4/dt;
	
	$("span.rata_a1").text(rl.toFixed(2));
	$("span.rata_a3").text(rn.toFixed(2));
	$("span.rata_a4").text(ri.toFixed(2));

	if(ri >= faktor_a) {
		skor = 4;
		Rumus = "Skor = 4";
	} else if(ri < faktor_a && rn >= faktor_b) {
		skor = 3+(ri/faktor_a);
		rumus = "3 + (RI / faktor a)";
	} else if((ri > 0 && ri < faktor_a) || (rn > 0 && rn < faktor_b)) {
		skor = 2+(2*(ri/faktor_a)) + (rn/faktor_b) - ((ri*rn) / (faktor_a*faktor_b));
		rumus = "2 + (2 * (RI / a)) + (RN / b) 0 ((RI * RN) / (faktor a * faktor b))";
	} else if(ri==0 && rn==0 && rl>=faktor_c) {
		skor = 2;
		rumus = "Skor = 2";
	} else if(ri==0 && rn==0 && rl<faktor_c) {
		skor = (2*rl)/faktor_c;
		rumus = "Skor = (2*RL)/faktor c";
	}

	$("#skor_publikasi_jurnal").val(skor.toFixed(2));
	$("span.rumus_jurnal").text(rumus);
}

function hitung_seminar()
{
	var dt = $("#total_dosen").val();
	var b1 = $("#publikasi_lokal").val();
	var b2 = $("#publikasi_nasional").val();
	var b3 = $("#publikasi_inter").val();

	var faktor_a = 0.1;
	var faktor_b = 1;
	var faktor_c = 2;
	var skor = 0;

	rl = b1/dt;
	rn = b2/dt;
	ri = b3/dt;
	
	$("span.rata_b1").text(rl.toFixed(2));
	$("span.rata_b2").text(rn.toFixed(2));
	$("span.rata_b3").text(ri.toFixed(2));

	if(ri >= faktor_a) {
		skor = 4;
		Rumus = "4";
	} else if(ri < faktor_a && rn >= faktor_b) {
		skor = 3+(ri/faktor_a);
		rumus = "3 + (RI / faktor a)";
	} else if((ri > 0 && ri < faktor_a) || (rn > 0 && rn < faktor_b)) {
		skor = 2+(2*(ri/faktor_a)) + (rn/faktor_b) - ((ri*rn) / (faktor_a*faktor_b));
		rumus = "2 + (2 * (RI / a)) + (RN / b) 0 ((RI * RN) / (faktor a * faktor b))";
	} else if(ri==0 && rn==0 && rl>=faktor_c) {
		skor = 2;
		rumus = "Skor = 2";
	} else if(ri==0 && rn==0 && rl<faktor_c) {
		skor = (2*rl)/faktor_c;
		rumus = "Skor = (2*RL)/faktor c";
	}

	$("#skor_publikasi_seminar").val(skor.toFixed(2));
	$("span.rumus_seminar").text(rumus);
}

function hitung_karya_ilmiah()
{
	var dt = $("#total_dosen").val();
	var as = $("#karya_ilmiah").val();
	var skor = 0;

	rs = as/dt;

	$("span.rata_rs").text(rs.toFixed(2));

	if(rs>=0.5) {
		skor = 4;
		rumus = "4";
	} else if(rs<0.5) {
		skor = 2+(4*rs);
		rumus = "2 + (4 * RS)";
	} else {
		skor = 0;
		rumus = "Tidak ada Skor kurang dari 2";
	}

	$("#skor_karya_ilmiah").val(skor.toFixed(2));
	$("span.rumus_karya_ilmiah").text(rumus);
}

function hitung_pkm()
{
	var dt = parseInt($("#total_dosen").val());
	var na = parseInt($("#pkm_paten").val());
	var nb = parseInt($("#pkm_cipta").val());
	var nc = parseInt($("#pkm_produk").val());
	var nd = parseInt($("#pkm_buku").val());

	rlp = ((4 * na) + (2 * (nb + nc)) + nd) / dt;
	rumus = "(4 * NA + 2 * (NB + NC) + ND) / NDT";

	$("#skor_pkm").val(rlp.toFixed(2));
	$("span.rumus_pkm").text(rumus);

}



</script>