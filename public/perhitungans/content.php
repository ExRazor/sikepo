<?php

if(isset($_GET['menu'])) {
	$menu = $_GET['menu'];

	// switch($menu) {
	// 	case 'pamong-1':
	// 		include "hitung/pamong-1.php";
	// 		break;
	// 	case 'mahasiswa-1':
	// 		include "hitung/mahasiswa-1.php";
	// 		break;
	// }

	include "hitung/".$menu.".php";
} else {
	include "beranda.php";
}


?>