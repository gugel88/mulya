<?php session_start();
	include"../../lib/conn.php";
	include"../../lib/all_function.php";


	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if(isset($_SESSION['supplier']) AND $_SESSION['supplier'] <> 'TRUE')
	{
		echo"<div class='w3-container w3-red'><p>Dilarang mengakses file ini.</p></div>";
		die();
	}

	if(isset($_GET['mulya']) && isset($_GET['act']))
	{
		$mulya = $_GET['mulya'];
		$act = $_GET['act'];
	}
	else
	{
		$mulya = "";
		$act = "";
	}

	if($mulya == "supplier" AND $act == "simpan")
	{
		//variable input
		$kode_supplier = trim($_POST['id']);
		$nama_toko= anti_inject($_POST['nama_toko']);
		$alamat= anti_inject($_POST['alamat']);
		$telepon= anti_inject($_POST['telepon']);
		$email= anti_inject($_POST['email']);

		mysql_query("INSERT INTO tb_supplier(kode_supplier, 
										nama_toko, 
										alamat, 
										telepon, 
										email)
									VALUES ('$kode_supplier', 
										'$nama_toko', 
										'$alamat', 
										'$telepon', 
										'$email')") or die(mysql_error());
		flash('example_message', '<p>Berhasil menambah data biaya.</p>' );

		echo"<script>
			window.history.go(-2);
		</script>";
	}

	elseif ($mulya == "supplier" AND $act == "edit") 
	{
		//variable input
		$kode_supplier = trim($_POST['id']);
		$nama_toko= anti_inject($_POST['nama_toko']);
		$alamat= anti_inject($_POST['alamat']);
		$telepon= anti_inject($_POST['telepon']);
		$email= anti_inject($_POST['email']);

		mysql_query("UPDATE tb_supplier SET 
										nama_toko= '$nama_toko', 
										alamat= '$alamat', 
										telepon= '$telepon', 
										email= '$email' 
					WHERE kode_supplier = '$_POST[id]'") or die(mysql_error());

		flash('example_message', '<p>Berhasil mengubah data biaya.</p>');

		echo"<script>
			window.history.go(-2);
		</script>";
	}

	elseif ($mulya == "supplier" AND $act == "hapus") 
	{
		mysql_query("DELETE FROM tb_supplier WHERE kode_supplier = '$_GET[id]'") or die(mysql_error());
		flash('example_message', '<p>Berhasil menghapus data biaya kuliah.</p>' );
		echo"<script>
			window.history.back();
		</script>";	
	}

?>