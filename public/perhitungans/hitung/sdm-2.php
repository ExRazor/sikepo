<div class="row">
	<div class="col-md-12 panduan">
	<img src="assets/images/sdm-2a.jpg" />
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Total Pembimbing Utama</label>
					<input type="text" class="form-control" id="total_pembimbing" value="10">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Total Mahasiswa</label>
					<input type="text" class="form-control" id="total_mahasiswa" readonly>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Persentase</label>
					<input type="text" class="form-control" id="persentase_bimbingan" readonly>
					<small class="form-text text-muted">Dosen yang Bimbingan > 10 </small>
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
	<div class="col-md-6" id="form_pembimbing">
	</div>
</div>




<script>

$(function(){

	load_pembimbing();

	$('#total_pembimbing').on('keyup', function(){
		load_pembimbing();
	})

	$('.mahasiswa_bimbingan').on('keyup', function(){
		persentase_bimbingan();
	})

});

function persentase_bimbingan()
{
	var skor;
	var tot_pembimbing = $("#total_pembimbing").val();
	var tot_mahasiswa = 0;

	var pembimbing_filter=0;

	$(".mahasiswa_bimbingan").each(function() {
	    mahasiswa = parseInt($(this).val());
		tot_mahasiswa = tot_mahasiswa+mahasiswa;
	});


	for(i=0;i<tot_pembimbing;i++) {
		var mahasiswa_bimbingan = $('input[name="mahasiswa_bimbingan['+i+']').val();

		if(mahasiswa_bimbingan <= 10) {
			pembimbing_filter=pembimbing_filter+1;
		}
	}

	//Persentase Pembimbing <= 10 Mahasiswa dengan Total Pembimbing
	var desimal 	= (pembimbing_filter/tot_pembimbing);
	var persentase 	= desimal*100;

	if(persentase>20) {
		skor = (5*desimal)-1
	} else if (persentase<=20) {
		skor = 0
	}

	$("#total_mahasiswa").val(tot_mahasiswa);
	$("#persentase_bimbingan").val(persentase.toFixed(2)+'%');
	$("#skor").val(skor.toFixed(2));

	console.log(persentase);

}

function load_pembimbing()
{
	var total = $("#total_pembimbing").val();
	$('#form_pembimbing').html('');

	for(i=0;i<total;i++) {
		var form = '<div class="row"><div class="col-md-6"><div class="form-group"><label for="dosen">Nama Dosen</label><input type="text" class="form-control" value="Dosen '+(i+1)+'" id="dosen" name="dosen_pembimbing['+i+']" readonly></div></div><div class="col-md-6"><div class="form-group"><label for="mahasiswa">Jumlah Mahasiswa Bimbingan</label><input type="text" class="form-control form-isi mahasiswa_bimbingan" onkeyup="persentase_bimbingan()" name="mahasiswa_bimbingan['+i+']" id="mahasiswa"></div></div></div>';

		$('#form_pembimbing').append(form);
	}
	random_number();
	persentase_bimbingan();
}

function random_number(){
	var bimbingan = $(".mahasiswa_bimbingan");
	
	$.each(bimbingan, function(i) {
		var mahasiswa = randomInt(1,20);
		$("input[name='mahasiswa_bimbingan["+i+"]").val(mahasiswa);
	});

	
}

function randomInt(min,max) // min and max included
{
    return Math.floor(Math.random()*(max-min+1)+min);
}


</script>