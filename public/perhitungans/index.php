<!DOCTYPE html>
<html>
<head>
	<title>Simulasi Perhitungan Borang</title>
	<link rel="stylesheet" href="assets\css\bootstrap.min.css">
	<link rel="stylesheet" href="assets\style.css">
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	  <a class="navbar-brand" href="#">Simulasi Perhitungan</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>

	  <div class="collapse navbar-collapse" id="navbarSupportedContent">
	    <ul class="navbar-nav mr-auto">
	      <li class="nav-item">
	        <a class="nav-link" href="#">Beranda <span class="sr-only">(current)</span></a>
	      </li>
	      <li class="nav-item dropdown">
	        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Tata Pamong</a>
	        <div class="dropdown-menu">
	        	<a class="dropdown-item" href="index.php?menu=pamong-1">Tabel 1 LKPS</a>
	        </div>
	      </li>
	      <li class="nav-item dropdown">
	        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Mahasiswa</a>
	        <div class="dropdown-menu">
	        	<a class="dropdown-item" href="index.php?menu=mahasiswa-1">Tabel 2.a Seleksi Mahasiswa</a>
	        	<a class="dropdown-item" href="index.php?menu=mahasiswa-2">Tabel 2.b Mahasiswa Asing</a>
	        </div>
	      </li>
	      <li class="nav-item dropdown">
	        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Sumber Daya Manusia</a>
	        <div class="dropdown-menu">
	        	<a class="dropdown-item" href="index.php?menu=sdm-1">Tabel 3.a Profil Dosen</a>
	        	<a class="dropdown-item" href="index.php?menu=sdm-2">Tabel 3.b Dosen Pembimbing TA</a>
	        	<a class="dropdown-item" href="index.php?menu=sdm-3">Tabel 3.c Kinerja Dosen</a>
	        	<a class="dropdown-item" href="index.php?menu=sdm-4">Tabel 3.d Penelitian dan PKM</a>
	        </div>
	      </li>
	      <li class="nav-item dropdown">
	        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Penelitian</a>
	        <div class="dropdown-menu">
	        	<a class="dropdown-item" href="index.php?menu=penelitian-1">Tabel 7.a Penelitian Dosen Mahasiswa</a>
	        </div>
	      </li>
	      <li class="nav-item dropdown">
	        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Luaran & Capaian Tridharma</a>
	        <div class="dropdown-menu">
	        	<a class="dropdown-item" href="index.php?menu=tridharma-1">Tabel 9.a Rata-Rata IPK Lulusan</a>
	        	<a class="dropdown-item" href="index.php?menu=tridharma-2">Tabel 9.b Prestasi Akademik Mahasiswa</a>
	        	<a class="dropdown-item" href="index.php?menu=tridharma-3">Tabel 9.c Tingkat dan Ukuran Tempat Kerja Lulusan</a>
	        </div>
	      </li>
	    </ul>
	    <form class="form-inline my-2 my-lg-0">
	      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
	      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
	    </form>
	  </div>
	</nav>
	<main role="main" class="col-md-10 konten">
		<?php include "content.php" ?>
	</main>
</body>
</html>