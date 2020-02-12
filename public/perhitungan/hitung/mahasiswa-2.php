<div class="row">
	<div class="col-md-12 panduan">
	<img src="assets/images/mahasiswa-2.jpg" />
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<form>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="internasional">Jumlah Mahasiswa Aktif</label>
						<input type="text" class="form-control form-isi" id="mahasiswa" value="1242">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="exampleInputEmail1">Jumlah Mahasiswa Asing Full-time</label>
						<input type="text" class="form-control form-isi" id="mahasiswa_asing_full" value="0">
						<small class="form-text text-muted">Persentase = <span class="persentase_asing_full">0</span>%</small>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="exampleInputEmail1">Jumlah Mahasiswa Asing Part-time</label>
						<input type="text" class="form-control form-isi" id="mahasiswa_asing_part" value="0">
						<small class="form-text text-muted">Persentase = <span class="persentase_asing_part">0</span>%</small>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Persentase Mahasiswa Asing</label>
						<input type="text" class="form-control" id="persentase_asing" readonly>
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
		var mahasiswa = $('#mahasiswa').val();
		var asing_full = $('#mahasiswa_asing_full').val();
		var asing_part = $('#mahasiswa_asing_part').val();
		var skor;

		var persentase_asing_full = (asing_full/mahasiswa)*100;
		var persentase_asing_part = (asing_part/mahasiswa)*100;
		var persentase_asing 	  = persentase_asing_full+persentase_asing_part;

		if(persentase_asing>=1) {
			skor = 4;
		} else if (persentase_asing<1) {
			skor = 2+((200*persentase_asing)/100);
		} else {
			skor = null;
		}

		$('span.persentase_asing_full').text(persentase_asing_full.toFixed(2));
		$('span.persentase_asing_part').text(persentase_asing_part.toFixed(2));
		$('#persentase_asing').val(persentase_asing.toFixed(2)+"%");
		$('#skor').val(skor.toFixed(2));



	}

});


</script>