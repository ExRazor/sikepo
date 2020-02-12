<div class="row">
	<div class="col-md-12 panduan">
	<img src="assets/images/tridharma-2.jpg" />
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-success">
		  <span>
		  	Faktor a = 0.05 || Faktor b = 0.5 || Faktor c = 2
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
						<label for="internasional">Prestasi Internasional (NI)</label>
						<input type="text" class="form-control form-isi" id="ni" value="1">
						<small class="form-text text-muted">RI = NI/NM = <span class="rata_inter">0</span></small>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="exampleInputEmail1">Prestasi Nasional (NN)</label>
						<input type="text" class="form-control form-isi" id="nn" value="25">
						<small class="form-text text-muted">RN = NN/NM = <span class="rata_nasional">0</span></small>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="exampleInputEmail1">Prestasi Wilayah (NW)</label>
						<input type="text" class="form-control form-isi" id="nw" value="35">
						<small class="form-text text-muted">RW = NW/NM = <span class="rata_lokal">0</span></small>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="exampleInputEmail1">Jumlah Mahasiswa Aktif (NM)</label>
						<input type="text" class="form-control form-isi" id="nm" value="40">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Skor</label>
						<input type="text" class="form-control" id="skor_prestasi" readonly>
						<small class="form-text text-muted">Skor = <span class="rumus_prestasi"></span></small>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>


<script>

$(function(){

	hitung_prestasi();

	$('.form-isi').on('keyup', function(){
		hitung_prestasi();
	})
});

function hitung_prestasi()
{
	var nm = $("#nm").val();
	var ni  = $("#ni").val();
	var nn  = $("#nn").val();
	var nw  = $("#nw").val();

	var skor = 0;
	var a = 0.05;
	var b = 0.5;
	var c = 2;

	ri = ni/nm;
	rn = nn/nm;
	rw = nw/nm;

	$('span.rata_inter').text(ri.toFixed(2));
	$('span.rata_nasional').text(rn.toFixed(2));
	$('span.rata_lokal').text(rw.toFixed(2));

	if(ri>=a) {
		skor = 4;
		skor = skor.toFixed(2)
		rumus = "4"
	} else if(ri<a && rn>=b) {
		skor = 3+(ri/a);
		skor = skor.toFixed(2)
		rumus = "3 + (RI / a)"
	} else if(ri<a && ri>=b) {
		skor = 2 + (2*(ri/a))+(rn/b)-((ri*rn)/(a*b));
		skor = skor.toFixed(2)
		rumus = "2 + (2 * (RI / a)) + (RN / b) - ((RI * RN) / (a * b))";
	} else if(ri==0 && rn==0 && (rw<=c && rw>=0)) {
		skor = 1+(rw/c);
		skor = skor.toFixed(2)
		rumus = "1 + (RW / c)";
	} else if(ri==0 && rn==0 && rw>c) {
		skor = 2;
		skor = skor.toFixed(2)
		rumus = "2"
	}

	$("#skor_prestasi").val(skor);
	$(".rumus_prestasi").text(rumus);
}

</script>