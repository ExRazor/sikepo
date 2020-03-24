<div class="row">
	<div class="col-md-12 panduan">
	<img src="assets/images/mahasiswa-1.jpg" />
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<form>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="internasional">Jumlah Calon Mahasiswa</label>
						<input type="text" class="form-control form-isi" id="mahasiswa_calon" value="648">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="exampleInputEmail1">Jumlah Mahasiswa Baru</label>
						<input type="text" class="form-control form-isi" id="mahasiswa_baru" value="232">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Rasio Perbandingan</label>
						<div class="row">
							<div class="col-md-6">
								<input type="text" class="form-control" id="rasio_calon" readonly>
								<small class="form-text text-muted">Rasio Calon Mahasiswa</small>
							</div>
							<div class="col-md-6">
								<input type="text" class="form-control" id="rasio_baru" readonly>
								<small class="form-text text-muted">Rasio Mahasiswa Baru</small>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Bobot Skor</label>
						<input type="text" class="form-control" id="skor" readonly>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>


<script>

$(function(){

	hitung();
	$('.form-isi').on('keyup', function(){
		hitung();
	})

	function hitung(){
		var calon = $("#mahasiswa_calon").val();
		var baru  = $("#mahasiswa_baru").val();

		var rasio_baru  = parseFloat((baru/calon)*10).toFixed(0);
		var rasio_calon = 10-rasio_baru;

		if(rasio_calon>=5) {
			skor = 4;
		} else if(rasio_calon<5) {
			skor = (4*rasio_calon)/5;
		}

		$("#rasio_calon").val(rasio_calon);
		$("#rasio_baru").val(rasio_baru);
		$("#skor").val(skor);

	}

});


</script>