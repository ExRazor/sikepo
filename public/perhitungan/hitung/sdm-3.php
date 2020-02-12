<div class="container">
	<div class="card mb-4">
	  <div class="card-body">
	    <h5 class="card-title">Ekuivalen Waktu Mengajar DTPS</h5>
	    <div class="container">
		    <div class="row">
				<div class="col-md-12 panduan">
				<img src="assets/images/sdm-3a.jpg" />
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Total DTPS</label>
								<input type="text" class="form-control" id="total_dosen" value="5">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Total Beban SKS</label>
								<input type="text" class="form-control" id="total_sks" readonly>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Rata-Rata Beban SKS</label>
								<input type="text" class="form-control" id="rata_sks" readonly>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Bobot Skor</label>
								<input type="text" class="form-control" id="skor_mengajar" readonly>
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
	    <h5 class="card-title">Prestasi Kinerja DTPS</h5>
	    <div class="container">
		    <div class="row">
				<div class="col-md-12 panduan">
				<img src="assets/images/sdm-3b.jpg" />
				</div>
			</div>
		    <div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="internasional">Jumlah Dosen Tetap Program Studi</label>
						<input type="text" class="form-control form_prestasi" id="total_dtps" value="50">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Jumlah Dosen Berprestasi</label>
						<input type="text" class="form-control form_prestasi" id="dtps_prestasi" value="2">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Jumlah Dosen Prestasi Internasional</label>
						<input type="text" class="form-control form_prestasi" id="dtps_prestasi_inter" value="0">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="internasional">Rata-Rata</label>
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
	  </div>
	</div>
</div>





<script>

$(function(){
	load_pembimbing();
	hitung_skor_prestasi();

	$('#total_dosen').on('keyup', function(){
		load_pembimbing();
	})

	$('.form_prestasi').on('keyup', function(){
		hitung_skor_prestasi();
	})

});

function load_pembimbing()
{
	var total = $("#total_dosen").val();
	$('#form_kinerja').html('');

	for(i=0;i<total;i++) {
		var form = '<div class="row"><div class="col-md-6"><div class="form-group"><label>Nama Dosen</label><input type="text" class="form-control" value="Dosen '+(i+1)+'"name="dtps['+i+']" readonly></div></div><div class="col-md-6"><div class="form-group"><label>Jumlah Beban SKS</label><input type="text" class="form-control form-isi beban_sks" onkeyup="hitung_skor_mengajar()" name="beban_sks['+i+']"></div></div></div>';

		$('#form_kinerja').append(form);
	}
	random_number();
	hitung_skor_mengajar();
}

function hitung_skor_mengajar()
{
	var skor;
	var tot_dosen = $("#total_dosen").val();
	var tot_sks   = 0

	$(".beban_sks").each(function() {
	    sks = parseInt($(this).val());
	    tot_sks = tot_sks+sks;
	});
	
	// console.log(tot_sks);

	var rata_sks  = tot_sks/tot_dosen;

	if(rata_sks>=12 && rata_sks<=13) {
		skor = 4;
	} else if(rata_sks>=6 && rata_sks<12) {
		skor = ((4*rata_sks)-24)/5
	} else if(rata_sks>=13 && rata_sks<=18) {
		skor = (72-(4*rata_sks))/5
	} else if(rata_sks<6 || rata_sks>18) {
		skor = 0
	}

	$("#total_sks").val(tot_sks);
	$("#rata_sks").val(rata_sks);
	$("#skor_mengajar").val(skor.toFixed(2));
}

function random_number(){
	var beban_sks = $(".beban_sks");

	$.each(beban_sks, function(i) {
		var sks = randomInt(1,18);
		$("input[name='beban_sks["+i+"]").val(sks);
	});
}

function randomInt(min,max) // min and max included
{
    return Math.floor(Math.random()*(max-min+1)+min);
}


/*******************************************************************/

function hitung_skor_prestasi()
{
	var skor;
	var dtps 				= $('#total_dtps').val();
	var dtps_prestasi 		= $('#dtps_prestasi').val();
	var dtps_prestasi_inter = $('#dtps_prestasi_inter').val();

	var rata_prestasi = (dtps_prestasi+dtps_prestasi_inter)/dtps;

	if(rata_prestasi>=0.5 || dtps_prestasi_inter>=1) {
		skor = 4;
	} else if(rata_prestasi<=0.5) {
		skor = 2+(4*rata_prestasi);
	} else {
		skor = 0;
	}

	$('#rata_prestasi_dtps').val(rata_prestasi.toFixed(2));
	$('#skor_prestasi_dtps').val(skor.toFixed(2));

}


</script>