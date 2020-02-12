<div class="row">
	<div class="col-md-12 panduan">
	<img src="assets/images/tridharma-3.jpg" />
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-success">
		  <span>
		  	Faktor a = 5% || Faktor b = 20% || Faktor c = 90%
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
						<label for="internasional">Lembaga Internasional (NI)</label>
						<input type="text" class="form-control form-isi" id="ni" value="1">
						<small class="form-text text-muted">RI = NI/NA = <span class="rata_inter">0</span></small>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="exampleInputEmail1">Lembaga Nasional (NN)</label>
						<input type="text" class="form-control form-isi" id="nn" value="10">
						<small class="form-text text-muted">RN = NN/NA = <span class="rata_nasional">0</span></small>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="exampleInputEmail1">Lembaga Wilayah (NL)</label>
						<input type="text" class="form-control form-isi" id="nl" value="26">
						<small class="form-text text-muted">RL = NL/NA = <span class="rata_lokal">0</span></small>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="exampleInputEmail1">Jumlah Lulusan (NA)</label>
						<input type="text" class="form-control form-isi" id="na" value="137">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Skor</label>
						<input type="text" class="form-control" id="skor_lembaga" readonly>
						<small class="form-text text-muted">Skor = <span class="rumus_lembaga"></span></small>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>


<script>

$(function(){

	hitung_lembaga();

	$('.form-isi').on('keyup', function(){
		hitung_lembaga();
	})
});

function hitung_lembaga()
{
	var na  = $("#na").val();
	var ni  = $("#ni").val();
	var nn  = $("#nn").val();
	var nl  = $("#nl").val();

	var skor = 0;
	var a 	 = 5;
	var b 	 = 20;
	var c 	 = 90;

	ri = (ni/na)*100;
	rn = (nn/na)*100;
	rl = (nl/na)*100;

	$('span.rata_inter').text(ri.toFixed(2)+'%');
	$('span.rata_nasional').text(rn.toFixed(2)+'%');
	$('span.rata_lokal').text(rl.toFixed(2)+'%');

	if(ri>=a) {
		skor = 4;
		skor = skor.toFixed(2)
		rumus = "4"
	} else if(ri<a && rn>=b) {
		skor = 3+(ri/a);
		skor = skor.toFixed(2)
		rumus = "3 + (RI / a)"
	} else if((ri>0 && ri<a) || (rn>0 && rn<b)) {
		skor = 2 + (2*(ri/a))+(rn/b)-((ri*rn)/(a*b));
		skor = skor.toFixed(2)
		rumus = "2 + (2 * (RI / a)) + (RN / b) - ((RI * RN) / (a * b))";
	} else if(ri==0 && rn==0 && rl>=c) {
		skor = 2;
		skor = skor.toFixed(2)
		rumus = "2";
	} else if(ri==0 && rn==0 && rl<c) {
		skor = (2*rl)/c;
		skor = skor.toFixed(2)
		rumus = "(2 * RL) / c"
	}

	$("#skor_lembaga").val(skor);
	$(".rumus_lembaga").text(rumus);
}

</script>