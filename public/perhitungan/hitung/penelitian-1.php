<div class="row">
	<div class="col-md-12 panduan">
	<img src="assets/images/penelitian-1.jpg" />
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-success">
		  <span>
		  	Faktor a = 0.05 || Faktor b = 0.3 || Faktor c = 1
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
						<label for="internasional">Penelitian Internasional (NI)</label>
						<input type="text" class="form-control form-isi" id="ni" value="1">
						<small class="form-text text-muted">RI = NI/NDT = <span class="rata_inter">0</span></small>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="exampleInputEmail1">Penelitian Nasional (NN)</label>
						<input type="text" class="form-control form-isi" id="nn" value="3">
						<small class="form-text text-muted">RN = NN/NDT = <span class="rata_nasional">0</span></small>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="exampleInputEmail1">Penelitian Lokal/PT (NL)</label>
						<input type="text" class="form-control form-isi" id="nl" value="5">
						<small class="form-text text-muted">RL = NL/NDT = <span class="rata_lokal">0</span></small>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="exampleInputEmail1">Jumlah Dosen Tetap (NDT)</label>
						<input type="text" class="form-control form-isi" id="ndt" value="37">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Skor</label>
						<input type="text" class="form-control" id="skor_penelitian" readonly>
						<small class="form-text text-muted">Skor = <span class="rumus_penelitian"></span></small>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>


<script>

$(function(){

	hitung_penelitian();

	$('.form-isi').on('keyup', function(){
		hitung_penelitian();
	})
});

function hitung_penelitian()
{
	var ndt = $("#ndt").val();
	var ni  = $("#ni").val();
	var nn  = $("#nn").val();
	var nl  = $("#nl").val();

	var skor = 0;
	var a = 0.05;
	var b = 0.3;
	var c = 1

	ri = ni/3/ndt;
	rn = nn/3/ndt;
	rl = nl/3/ndt;

	$('span.rata_inter').text(ri.toFixed(2));
	$('span.rata_nasional').text(rn.toFixed(2));
	$('span.rata_lokal').text(rl.toFixed(2));

	if(ri>=a) {
		skor = 4;
		rumus = "4"
	} else if(ri<a && rn>=b) {
		skor = 3+(ri/a);
		rumus = "3 + (RI / a)"
	} else if((ri > 0 && ri < a) || (rn < 0 && rn > b)) {
		skor = 2 + (2*(ri/a))+(rn/b)-((ri*rn)/(a*b));
		rumus = "2 + (2 * (RI / a)) + (RN / b) - ((RI * RN) / (a * b))";
	} else if(ri==0 && rn==0 && rl>=c) {
		skor = 2;
		rumus = "2";
	} else if(ri==0 && rn==0 && rl<c) {
		skor = (2*rl)/c;
		rumus = "(2 * RL) / c"
	}

	$("#skor_penelitian").val(skor.toFixed(2));
	$(".rumus_penelitian").text(rumus);
}

</script>