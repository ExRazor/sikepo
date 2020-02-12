<div class="row">
	<div class="col-md-12 panduan">
	<img src="assets/images/pamong-1.jpg" />
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-success">
		  <span>
		  	Faktor a = 0.02; Faktor b= 0.2; Faktor c = 0.5
		  </span>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<form>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label for="internasional">Kerjasama Internasional (NI)</label>
						<input type="text" class="form-control form-isi" id="internasional" value="0">
						<small class="form-text text-muted">RI = NI/NDT = <span class="rata_inter">0</span></small>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="exampleInputEmail1">Kerjasama Nasional (NN)</label>
						<input type="text" class="form-control form-isi" id="nasional" value="0">
						<small class="form-text text-muted">RN = NN/NDT = <span class="rata_nasional">0</span></small>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="exampleInputEmail1">Kerjasama Lokal (NL)</label>
						<input type="text" class="form-control form-isi" id="lokal" value="0">
						<small class="form-text text-muted">RL = NL/NDT = <span class="rata_lokal">0</span></small>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="exampleInputEmail1">Jumlah Dosen Tetap (NDT)</label>
						<input type="text" class="form-control form-isi" id="dosen" value="23">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Skor</label>
						<input type="text" class="form-control" id="skor" readonly>
						<small class="form-text text-muted">Skor = <span class="rumus"></span></small>
					</div>
				</div><!-- 
				<div class="col-md-6">
					<div class="form-group">
						<label>Bobot Nilai</label>
						<input type="text" class="form-control" id="bobot" readonly>
					</div>
				</div> -->
			</div>
		</form>
	</div>
</div>


<script>
$(document).ready(hitung);

$('.form-isi').on('keyup', function(){
	hitung();
})

function hitung(){
	var inter = $('#internasional').val();
	var nas   = $('#nasional').val();
	var lokal = $('#lokal').val();
	var dosen = $('#dosen').val();
	var faktor_a = 0.02;
	var faktor_b = 0.2;
	var faktor_c = 0.5;
	var skor;

	var RI = (inter/dosen);
	var RN = (nas/dosen);
	var RL = (lokal/dosen);

	$('span.rata_inter').text(RI.toFixed(2));
	$('span.rata_nasional').text(RN.toFixed(2));
	$('span.rata_lokal').text(RL.toFixed(2));

	if(RI >= faktor_a) {
		skor = 4;
		bobot = 4;
		rumus = 4;
	} else if(RI < faktor_a && RN >= faktor_b ) {
		skor = 3 + (RI/faktor_a);
		bobot = 3;
		rumus = "3 + (RI/a)";
	} else if ((RI > 0 && RI < faktor_a) || (RN > 0 && RN < faktor_b)) {
		skor = 2 + (2 * (RI/faktor_a)) + (RN/faktor_b) - ((RI*RN)/(faktor_a*faktor_b));
		bobot = 2;
		rumus = "2 + (2 * (RI/a)) + (RN/b) - ((RI*RN) / (a*b))";
	} else if (RI == 0 && RN == 0 && RL >= faktor_c) {
		skor = 2;
		bobot = 1;
		rumus = "2"
	} else if (RI == 0 && RN == 0 && RL < faktor_c) {
		skor = (2*RL)/faktor_c;
		bobot = 0;
		rumus = "(2*RL) / c";
	} else {
		skor = 0;
		bobot = 0;
		rumus = null;
	}

	$('.rumus').text(rumus);
	$('#skor').val(skor.toFixed(2));
	// $('#bobot').val(bobot);

}
</script>