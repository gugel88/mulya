<?php
	
	// Include file koneksi
	include"lib/conn.php";
	
	session_start();// Memulai Session
	// Menyimpan Session
	$user_check = $_SESSION['login_user'];
	// Ambil nama karyawan berdasarkan username karyawan dengan mysql_fetch_assoc
	$ses_sql=mysql_query("select nama_lengkap from user where usernm = '$user_check'", $conn);
	$row = mysql_fetch_assoc($ses_sql);
	$login_session = $row['nama_lengkap'];

	//data header
	$HEAD = array();
	$sqlhead = mysql_query("SELECT * FROM tb_pengaturan");
	while ($p = mysql_fetch_assoc($sqlhead)) {
		$HEAD[$p['nama_peng']] = $p['val_peng'];
	}

	if(!isset($login_session)){
		mysql_close($conn); // Menutup koneksi
		header('location: login.php'); // Mengarahkan ke Home Page
	}
?>