<?php
	session_start();
	include"../lib/conn.php";

	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if ($_POST['type'] == "pixel") {
		// input is in format 1,2,3...|1,2,3...|...
		$im = imagecreatetruecolor(320, 240);

		foreach (explode("|", $_POST['image']) as $y => $csv) {
			foreach (explode(";", $csv) as $x => $color) {
				imagesetpixel($im, $x, $y, $color);
			}
		}

	} else {
		// input is in format: data:image/png;base64,...
		//$im = imagecreatefrompng($_POST['image']);
		//file_put_contents('tmp/image.png',  base64_decode($_POST['image']));

		//nama file
		$nm_file = $_POST['nim_mhs'].".png";

		$file = $_POST['image']; //your data in base64 'data:image/png....';
		$img = str_replace('data:image/png;base64,', '', $file);
		file_put_contents('tmp/'.$nm_file, base64_decode($img));

		//menyimpan ke database
		mysql_query("INSERT INTO mahasiswa_detail(nim, foto, ukuran_almamater) VALUES('$_POST[nim_mhs]', '$nm_file', '$_POST[ukuran_a]')");
	}

	// do something with $im
?>