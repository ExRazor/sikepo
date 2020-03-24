<div class="row">
	<div class="col-md-12 panduan">
	<img src="assets/images/tridharma-1.jpg" />
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Jumlah Mahasiswa</label>
					<input type="text" class="form-control" id="total_mahasiswa" onkeyup="load_ipk()" value="5">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Rata-Rata IPK</label>
					<input type="text" class="form-control" id="rata_ipk" readonly>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label>Bobot Skor</label>
					<input type="text" class="form-control" id="skor_ipk" readonly>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6" id="form_mahasiswa">
	</div>
</div>




<script>

$(function(){
	load_ipk();
	hitung_skor_ipk();
});

function load_ipk(){
	var tot_mahasiswa = $("#total_mahasiswa").val();

	$("#form_mahasiswa").html("");

	for(i=0;i<tot_mahasiswa;i++) {
		var ipk  = randomIPK(2,4); 
		var form = '<div class=row><div class=col-md-6><div class=form-group><label>Nama Mahasiswa</label><input class="form-control nama_mahasiswa" id="mahasiswa['+i+']" value="Mahasiswa '+(i+1)+'" readonly></div></div><div class=col-md-6><div class=form-group><label>IPK</label> <input class="form-control ipk_mahasiswa" id="ipk['+i+']" value="'+ipk+'" onkeyup="hitung_skor_ipk()"></div></div></div>';

		$("#form_mahasiswa").append(form);
	}

	hitung_skor_ipk();
}

function hitung_skor_ipk()
{
	var total_ipk 		= 0;
	var total_mahasiswa = $("#total_mahasiswa").val();
	var skor 			= 0;

	$.each($(".ipk_mahasiswa"), function(){
		ipk = parseFloat($(this).val());
		total_ipk = total_ipk+ipk;
	});

	rata_ipk = parseFloat(total_ipk/total_mahasiswa).toFixed(2);


	if(rata_ipk>=3.25) {
		skor = 4;
		skor = skor.toFixed(2);
	} else if(rata_ipk>=2.00 && rata_ipk<3.25) {
		skor = ((8*rata_ipk)-6)/5;
		skor = skor.toFixed(2);
	} else {
		skor = "Tidak ada skor di bawah 2";
	}

	$("#rata_ipk").val(rata_ipk);
	$("#skor_ipk").val(skor);

}

function randomIPK(min,max) // min and max included
{
    return parseFloat(Math.min(min + (Math.random() * (max - min)),max).toFixed(2));
}


</script>