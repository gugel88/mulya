<?php
	session_start();
	include"../../lib/conn.php";
	include"../../lib/all_function.php";


	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if(isset($_SESSION['pengaturan']) AND $_SESSION['pengaturan'] <> 'TRUE')
	{
		echo"<div class='w3-container w3-red'><p>Dilarang mengakses file ini.</p></div>";
		die();
	}

	if(isset($_GET['mod']) && isset($_GET['act']))
	{
		$mod = $_GET['mod'];
		$act = $_GET['act'];
	}
	else
	{
		$mod = "";
		$act = "";
	}

	if($mod == "pengaturan" AND $act == "simpan")
	{
		//variable input
		//echo print_r($_POST['peng']);exit;
		
		if (count($_POST['peng']) > 0) {
			foreach ($_POST['peng'] as $key => $value) {
				mysql_query("UPDATE tb_pengaturan SET val_peng = '$value' WHERE nama_peng = '$key'") or die(mysql_error());
			}
		}
	

		flash('example_message', '<p>Berhasil menyimpan pengaturan.</p>' );

		echo"<script>
			window.history.back();
		</script>";
	}

?>